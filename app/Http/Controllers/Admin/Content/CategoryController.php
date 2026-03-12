<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use App\Models\UserActivityLog;
use App\Models\Audio;
use App\Models\Audiobook;
use App\Models\Book;
use App\Models\Video;
use App\Http\Controllers\Concerns\HandlesTranslations;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use HandlesTranslations;

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        $query = ContentCategory::query()
            ->select('content_categories.*')
            ->selectSub(
                Video::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('category_id', 'content_categories.id'),
                'video_count'
            )
            ->selectSub(
                Audio::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('category_id', 'content_categories.id'),
                'audio_count'
            )
            ->selectSub(
                Audiobook::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('category_id', 'content_categories.id'),
                'audiobook_count'
            )
            ->selectSub(
                Book::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('category_id', 'content_categories.id'),
                'document_count'
            );

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status') === 'active');
        }

        if ($request->string('deleted') === 'with') {
            $query->withTrashed();
        } elseif ($request->string('deleted') === 'only') {
            $query->onlyTrashed();
        }

        $categories = $query->orderBy('name')->paginate($perPage)->withQueryString();

        return view('Admin.Content.Categories.index', compact('categories'));
    }

    public function create()
    {
        return view('Admin.Content.Categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'name_fr' => ['required', 'string', 'max:255'],
            'name_rw' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:content_categories,slug'],
            'type' => ['required', 'in:video,audio,document,all'],
            'description' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'description_fr' => ['nullable', 'string'],
            'description_rw' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category = ContentCategory::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'category_created',
            'meta' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
        ]);

        $this->syncTranslationsMapped($category, $request, [
            'name' => 'title',
            'description' => 'description',
        ]);

        return redirect()->route('admin.categories.index')->with('status', 'Category created.');
    }

    public function edit(ContentCategory $category)
    {
        return view('Admin.Content.Categories.edit', compact('category'));
    }

    public function show(Request $request, ContentCategory $category)
    {
        $tab = $request->string('tab')->toString() ?: 'videos';
        $tab = in_array($tab, ['videos', 'audios', 'documents'], true) ? $tab : 'videos';

        $videoQuery = Video::query()->where('category_id', $category->id);
        $audioQuery = Audio::query()->where('category_id', $category->id);
        $documentQuery = Book::query()->where('category_id', $category->id);

        $stats = [
            'videos' => [
                'total' => (clone $videoQuery)->count(),
                'published' => (clone $videoQuery)->where('is_published', true)->count(),
                'views' => (clone $videoQuery)->sum('view_count'),
            ],
            'audios' => [
                'total' => (clone $audioQuery)->count(),
                'published' => (clone $audioQuery)->where('is_published', true)->count(),
                'downloads' => (clone $audioQuery)->sum('download_count'),
            ],
            'documents' => [
                'total' => (clone $documentQuery)->count(),
                'published' => (clone $documentQuery)->where('is_published', true)->count(),
                'downloads' => (clone $documentQuery)->sum('download_count'),
            ],
        ];

        $items = match ($tab) {
            'audios' => $audioQuery->orderByDesc('created_at')->paginate(5)->withQueryString(),
            'documents' => $documentQuery->orderByDesc('created_at')->paginate(5)->withQueryString(),
            default => $videoQuery->orderByDesc('created_at')->paginate(5)->withQueryString(),
        };

        return view('Admin.Content.Categories.show', compact('category', 'tab', 'stats', 'items'));
    }

    public function update(Request $request, ContentCategory $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'name_fr' => ['required', 'string', 'max:255'],
            'name_rw' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:content_categories,slug,'.$category->id],
            'type' => ['required', 'in:video,audio,document,all'],
            'description' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'description_fr' => ['nullable', 'string'],
            'description_rw' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'category_updated',
            'meta' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
        ]);

        $this->syncTranslationsMapped($category, $request, [
            'name' => 'title',
            'description' => 'description',
        ]);

        return redirect()->route('admin.categories.index')->with('status', 'Category updated.');
    }

    public function destroy(Request $request, ContentCategory $category)
    {
        $category->delete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'category_deleted',
            'meta' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
        ]);

        return redirect()->back()->with('status', 'Category deleted.');
    }

    public function restore(Request $request, int $category)
    {
        $record = ContentCategory::withTrashed()->findOrFail($category);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'category_restored',
            'meta' => [
                'id' => $record->id,
                'name' => $record->name,
            ],
        ]);

        return redirect()->back()->with('status', 'Category restored.');
    }

    public function forceDelete(Request $request, int $category)
    {
        $record = ContentCategory::withTrashed()->findOrFail($category);
        $name = $record->name;
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'category_force_deleted',
            'meta' => [
                'id' => $category,
                'name' => $name,
            ],
        ]);

        return redirect()->back()->with('status', 'Category permanently deleted.');
    }
}










