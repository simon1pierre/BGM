<?php

namespace App\Http\Controllers\Admin\Translations;

use App\Http\Controllers\Controller;
use App\Models\ContentTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TranslationReviewController extends Controller
{
    public function index(Request $request): View
    {
        if (!$this->hasQualityColumns()) {
            abort(500, 'Translation validation columns are missing. Run migrations first.');
        }

        $locale = (string) $request->query('locale', 'rw');
        $status = (string) $request->query('status', 'needs_review');
        $type = (string) $request->query('type', 'all');
        $translatedBy = (string) $request->query('translated_by', 'all');
        $q = trim((string) $request->query('q', ''));

        $base = ContentTranslation::query()
            ->select(
                'id',
                'content_type',
                'content_id',
                'locale',
                'source_locale',
                'title',
                'description',
                'translation_status',
                'translated_by',
                'quality_score',
                'is_bible_locked',
                'reviewed_by',
                'reviewed_at',
                'updated_at',
                DB::raw("
                    CASE
                        WHEN LOWER(content_type) LIKE '%audiobook' THEN 'audiobook'
                        WHEN LOWER(content_type) LIKE '%audio' THEN 'audio'
                        WHEN LOWER(content_type) LIKE '%book' THEN 'book'
                        WHEN LOWER(content_type) LIKE '%video' THEN 'video'
                        ELSE LOWER(content_type)
                    END AS content_kind
                ")
            );

        $rows = DB::query()->fromSub($base, 'translations')
            ->when($locale !== 'all', fn ($query) => $query->where('locale', $locale))
            ->when($status !== 'all', fn ($query) => $query->where('translation_status', $status))
            ->when($type !== 'all', fn ($query) => $query->where('content_kind', $type))
            ->when($translatedBy !== 'all', fn ($query) => $query->where('translated_by', $translatedBy))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('content_id', 'like', "%{$q}%");
                });
            })
            ->orderByRaw("CASE WHEN translation_status = 'needs_review' THEN 0 ELSE 1 END")
            ->orderBy('quality_score')
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'needs_review' => ContentTranslation::query()->where('translation_status', 'needs_review')->count(),
            'approved' => ContentTranslation::query()->where('translation_status', 'approved')->count(),
            'manual' => ContentTranslation::query()->where('translated_by', 'manual')->count(),
            'system' => ContentTranslation::query()->where('translated_by', 'system')->count(),
        ];

        return view('Admin.Translations.review', compact('rows', 'summary', 'locale', 'status', 'type', 'translatedBy', 'q'));
    }

    public function approve(Request $request, ContentTranslation $translation): RedirectResponse
    {
        if (!$this->hasQualityColumns()) {
            return back()->with('status_error', 'Run migrations to enable translation validation.');
        }

        if ($translation->is_bible_locked && $translation->translated_by !== 'manual') {
            return back()->with('status_error', 'Bible-locked text requires manual verified translation before approval.');
        }

        $translation->update([
            'translation_status' => 'approved',
            'reviewed_by' => $request->user()?->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'Translation approved.');
    }

    public function reject(Request $request, ContentTranslation $translation): RedirectResponse
    {
        if (!$this->hasQualityColumns()) {
            return back()->with('status_error', 'Run migrations to enable translation validation.');
        }

        $translation->update([
            'translation_status' => 'needs_review',
            'reviewed_by' => $request->user()?->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'Translation sent back for review.');
    }

    public function saveManual(Request $request, ContentTranslation $translation): RedirectResponse
    {
        if (!$this->hasQualityColumns()) {
            return back()->with('status_error', 'Run migrations to enable translation validation.');
        }

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        if (blank($validated['title'] ?? null) && blank($validated['description'] ?? null)) {
            return back()->with('status_error', 'Provide at least title or description to save manual translation.');
        }

        $translation->update([
            'title' => $validated['title'] ?? $translation->title,
            'description' => $validated['description'] ?? $translation->description,
            'translation_status' => 'approved',
            'translated_by' => 'manual',
            'quality_score' => 100.0,
            'reviewed_by' => $request->user()?->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'Manual translation saved and approved.');
    }

    private function hasQualityColumns(): bool
    {
        return Schema::hasTable('content_translations')
            && Schema::hasColumn('content_translations', 'translation_status')
            && Schema::hasColumn('content_translations', 'translated_by')
            && Schema::hasColumn('content_translations', 'quality_score');
    }
}








