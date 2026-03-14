<?php

namespace App\Http\Controllers\Admin\Translations;

use App\Http\Controllers\Controller;
use App\Models\ContentTranslation;
use App\Models\SettingTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TranslationSearchController extends Controller
{
    private const LOCALES = ['rw', 'en', 'fr'];

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $locale = (string) $request->query('locale', 'all');
        $source = (string) $request->query('source', 'all');
        $contentFields = ['title', 'description', 'excerpt', 'body'];

        $contentRows = ContentTranslation::query()->whereRaw('1=0');
        if ($q !== '' && in_array($source, ['all', 'content'], true)) {
            $contentRows = ContentTranslation::query()
                ->select('id', 'content_type', 'content_id', 'locale', 'title', 'description', 'excerpt', 'body', 'updated_at')
                ->when($locale !== 'all', fn ($query) => $query->where('locale', $locale))
                ->where(function ($query) use ($q, $contentFields) {
                    foreach ($contentFields as $field) {
                        $query->orWhere($field, 'like', "%{$q}%");
                    }
                })
                ->orderByDesc('updated_at');
        }

        $contentResults = $contentRows->paginate(20, ['*'], 'content_page')->withQueryString();

        $settingMatches = collect();
        if ($q !== '' && in_array($source, ['all', 'settings'], true)) {
            $settingColumns = collect(Schema::getColumnListing('setting_translations'))
                ->reject(fn ($column) => in_array($column, ['id', 'setting_id', 'locale', 'created_at', 'updated_at'], true))
                ->values()
                ->all();

            $settings = SettingTranslation::query()
                ->when($locale !== 'all', fn ($query) => $query->where('locale', $locale))
                ->where(function ($query) use ($q, $settingColumns) {
                    foreach ($settingColumns as $column) {
                        $query->orWhere($column, 'like', "%{$q}%");
                    }
                })
                ->get();

            foreach ($settings as $row) {
                foreach ($settingColumns as $column) {
                    $value = (string) ($row->{$column} ?? '');
                    if ($value === '') {
                        continue;
                    }
                    if (Str::contains(Str::lower($value), Str::lower($q))) {
                        $settingMatches->push([
                            'id' => $row->id,
                            'locale' => $row->locale,
                            'field' => $column,
                            'value' => $value,
                        ]);
                    }
                }
            }
        }

        $settingsResults = $this->paginateCollection($settingMatches, 20, $request, 'settings_page');

        return view('Admin.Translations.search', [
            'q' => $q,
            'locale' => $locale,
            'source' => $source,
            'contentResults' => $contentResults,
            'settingsResults' => $settingsResults,
        ]);
    }

    public function updateContent(Request $request, ContentTranslation $translation): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
        ]);

        if (blank($validated['title'] ?? null)
            && blank($validated['description'] ?? null)
            && blank($validated['excerpt'] ?? null)
            && blank($validated['body'] ?? null)
        ) {
            return back()->with('status_error', 'Provide at least one field to save.');
        }

        $updates = array_filter($validated, fn ($value) => $value !== null);

        if ($this->hasQualityColumns()) {
            $updates['translation_status'] = 'approved';
            $updates['translated_by'] = 'manual';
            $updates['quality_score'] = 100.0;
            $updates['reviewed_by'] = $request->user()?->id;
            $updates['reviewed_at'] = now();
        }

        $translation->update($updates);

        return back()->with('status', 'Translation updated.');
    }

    public function updateSetting(Request $request, SettingTranslation $translation): RedirectResponse
    {
        $validated = $request->validate([
            'field' => ['required', 'string'],
            'value' => ['nullable', 'string'],
        ]);

        $allowedFields = collect(Schema::getColumnListing('setting_translations'))
            ->reject(fn ($column) => in_array($column, ['id', 'setting_id', 'locale', 'created_at', 'updated_at'], true))
            ->values()
            ->all();

        if (!in_array($validated['field'], $allowedFields, true)) {
            return back()->with('status_error', 'Invalid setting field selected.');
        }

        $translation->update([
            $validated['field'] => $validated['value'],
        ]);

        return back()->with('status', 'Setting translation updated.');
    }

    private function paginateCollection($items, int $perPage, Request $request, string $pageName): LengthAwarePaginator
    {
        $page = (int) $request->query($pageName, 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : collect($items);
        $results = $items->forPage($page, $perPage)->values();

        return new LengthAwarePaginator(
            $results,
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'pageName' => $pageName, 'query' => $request->query()]
        );
    }

    private function hasQualityColumns(): bool
    {
        return Schema::hasTable('content_translations')
            && Schema::hasColumn('content_translations', 'translation_status')
            && Schema::hasColumn('content_translations', 'translated_by')
            && Schema::hasColumn('content_translations', 'quality_score');
    }
}
