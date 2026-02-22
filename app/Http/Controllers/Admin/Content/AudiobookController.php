<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Concerns\HandlesTranslations;
use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use App\Models\UserActivityLog;
use App\Models\audiobook;
use App\Models\book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AudiobookController extends Controller
{
    use HandlesTranslations;

    public function index()
    {
        return view('Admin.Content.Audiobooks.index');
    }

    public function create()
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['audio', 'all'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $books = book::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();

        return view('Admin.Content.Audiobooks.create', compact('categories', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'title_en' => ['required', 'string', 'max:255'],
            'title_fr' => ['required', 'string', 'max:255'],
            'title_rw' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_fr' => ['nullable', 'string'],
            'description_rw' => ['nullable', 'string'],
            'audio_file' => ['nullable', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'duration' => ['nullable', 'string', 'max:50'],
            'category_id' => ['nullable', Rule::exists('content_categories', 'id')->whereIn('type', ['audio', 'all'])],
            'book_id' => ['required', 'exists:books,id'],
            'narrator' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'recommended' => ['nullable', 'boolean'],
            'is_prayer_audio' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'part_files' => ['nullable', 'array'],
            'part_files.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'part_title_prefix' => ['nullable', 'string', 'max:80'],
            'part_order_start' => ['nullable', 'integer', 'min:1'],
            'part_duration' => ['nullable', 'string', 'max:50'],
        ]);

        if (!$request->hasFile('audio_file') && !$request->hasFile('part_files')) {
            return back()
                ->withErrors(['audio_file' => 'Upload a main audiobook file or at least one audiobook part.'])
                ->withInput();
        }

        $uploadedParts = $this->storeUploadedParts(
            $request,
            (string) ($validated['part_title_prefix'] ?? 'Part'),
            (int) ($validated['part_order_start'] ?? 1),
            (string) ($validated['part_duration'] ?? '')
        );

        $audioPath = null;
        if ($request->hasFile('audio_file')) {
            $audioPath = $request->file('audio_file')->store('content/audiobooks', 'public');
        } elseif (count($uploadedParts) > 0) {
            $audioPath = $uploadedParts[0]['audio_file'];
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('content/audiobooks/thumbnails', 'public');
        }

        $audiobook = audiobook::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'audio_file' => $audioPath,
            'thumbnail' => $thumbnailPath,
            'duration' => $validated['duration'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'book_id' => $validated['book_id'] ?? null,
            'narrator' => $validated['narrator'] ?? null,
            'series' => $validated['series'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'recommended' => $request->boolean('recommended'),
            'is_prayer_audio' => $request->boolean('is_prayer_audio'),
            'is_published' => $request->boolean('is_published'),
        ]);

        if (count($uploadedParts) > 0) {
            $audiobook->parts()->createMany($uploadedParts);
        }

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audiobook_created',
            'meta' => [
                'id' => $audiobook->id,
                'title' => $audiobook->title,
            ],
        ]);

        $this->syncTranslations($audiobook, $request, ['title', 'description']);

        return redirect()->route('admin.audiobooks.index')->with('status', 'Audiobook created.');
    }

    public function edit(audiobook $audiobook)
    {
        $audiobook->load(['parts' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        }]);

        $categories = ContentCategory::query()
            ->whereIn('type', ['audio', 'all'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $books = book::query()
            ->where('is_published', true)
            ->orWhere('id', $audiobook->book_id)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();

        return view('Admin.Content.Audiobooks.edit', compact('audiobook', 'categories', 'books'));
    }

    public function preview(audiobook $audiobook)
    {
        return view('Admin.Content.Audiobooks.preview', compact('audiobook'));
    }

    public function update(Request $request, audiobook $audiobook)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'title_en' => ['required', 'string', 'max:255'],
            'title_fr' => ['required', 'string', 'max:255'],
            'title_rw' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_fr' => ['nullable', 'string'],
            'description_rw' => ['nullable', 'string'],
            'audio_file' => ['nullable', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'duration' => ['nullable', 'string', 'max:50'],
            'category_id' => ['nullable', Rule::exists('content_categories', 'id')->whereIn('type', ['audio', 'all'])],
            'book_id' => ['required', 'exists:books,id'],
            'narrator' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'recommended' => ['nullable', 'boolean'],
            'is_prayer_audio' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $audioPath = $audiobook->audio_file;
        if ($request->hasFile('audio_file')) {
            $audioPath = $request->file('audio_file')->store('content/audiobooks', 'public');
        }

        $thumbnailPath = $audiobook->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('content/audiobooks/thumbnails', 'public');
        }

        $audiobook->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'audio_file' => $audioPath,
            'thumbnail' => $thumbnailPath,
            'duration' => $validated['duration'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'book_id' => $validated['book_id'] ?? null,
            'narrator' => $validated['narrator'] ?? null,
            'series' => $validated['series'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'recommended' => $request->boolean('recommended'),
            'is_prayer_audio' => $request->boolean('is_prayer_audio'),
            'is_published' => $request->boolean('is_published'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audiobook_updated',
            'meta' => [
                'id' => $audiobook->id,
                'title' => $audiobook->title,
            ],
        ]);

        $this->syncTranslations($audiobook, $request, ['title', 'description']);

        return redirect()->route('admin.audiobooks.index')->with('status', 'Audiobook updated.');
    }

    public function addPart(Request $request, audiobook $audiobook)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'audio_file' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'part_files' => ['nullable', 'array'],
            'part_files.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'part_title_prefix' => ['nullable', 'string', 'max:80'],
            'part_order_start' => ['nullable', 'integer', 'min:1'],
            'duration' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if (!$request->hasFile('audio_file') && !$request->hasFile('part_files')) {
            return back()->withErrors(['audio_file' => 'Select one part file or upload many part files.'])->withInput();
        }

        if ($request->hasFile('part_files')) {
            $startOrder = (int) ($validated['part_order_start'] ?? (($audiobook->parts()->max('sort_order') ?? 0) + 1));
            $rows = $this->storeUploadedParts(
                $request,
                (string) ($validated['part_title_prefix'] ?? 'Part'),
                $startOrder,
                (string) ($validated['duration'] ?? '')
            );

            if (count($rows) > 0) {
                $audiobook->parts()->createMany($rows);
                if (empty($audiobook->audio_file)) {
                    $audiobook->update(['audio_file' => $rows[0]['audio_file']]);
                }
            }

            return redirect()->back()->with('status', count($rows).' audiobook part(s) added.');
        }

        $nextOrder = (int) ($validated['sort_order'] ?? (($audiobook->parts()->max('sort_order') ?? 0) + 1));
        $filePath = $request->file('audio_file')->store('content/audiobooks/parts', 'public');

        $part = $audiobook->parts()->create([
            'title' => trim((string) ($validated['title'] ?? '')) ?: 'Part '.$nextOrder,
            'audio_file' => $filePath,
            'duration' => $validated['duration'] ?? null,
            'sort_order' => $nextOrder,
            'is_published' => $request->boolean('is_published', true),
        ]);

        if (empty($audiobook->audio_file)) {
            $audiobook->update(['audio_file' => $part->audio_file]);
        }

        return redirect()->back()->with('status', 'Audiobook part added.');
    }

    public function storeForBook(Request $request, book $document)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'title_fr' => ['nullable', 'string', 'max:255'],
            'title_rw' => ['nullable', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_fr' => ['nullable', 'string'],
            'description_rw' => ['nullable', 'string'],
            'audio_file' => ['nullable', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'duration' => ['nullable', 'string', 'max:50'],
            'category_id' => ['nullable', Rule::exists('content_categories', 'id')->whereIn('type', ['audio', 'all'])],
            'narrator' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'recommended' => ['nullable', 'boolean'],
            'is_prayer_audio' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'part_files' => ['nullable', 'array'],
            'part_files.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'part_title_prefix' => ['nullable', 'string', 'max:80'],
            'part_order_start' => ['nullable', 'integer', 'min:1'],
            'part_duration' => ['nullable', 'string', 'max:50'],
        ]);

        if (!$request->hasFile('audio_file') && !$request->hasFile('part_files')) {
            return back()
                ->withErrors(['audio_file' => 'Upload a main audiobook file or at least one audiobook part.'])
                ->withInput();
        }

        $uploadedParts = $this->storeUploadedParts(
            $request,
            (string) ($validated['part_title_prefix'] ?? 'Part'),
            (int) ($validated['part_order_start'] ?? 1),
            (string) ($validated['part_duration'] ?? '')
        );

        $audioPath = null;
        if ($request->hasFile('audio_file')) {
            $audioPath = $request->file('audio_file')->store('content/audiobooks', 'public');
        } elseif (count($uploadedParts) > 0) {
            $audioPath = $uploadedParts[0]['audio_file'];
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('content/audiobooks/thumbnails', 'public');
        }

        $audiobook = audiobook::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'audio_file' => $audioPath,
            'thumbnail' => $thumbnailPath,
            'duration' => $validated['duration'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'book_id' => $document->id,
            'narrator' => $validated['narrator'] ?? null,
            'series' => $validated['series'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'recommended' => $request->boolean('recommended'),
            'is_prayer_audio' => $request->boolean('is_prayer_audio'),
            'is_published' => $request->boolean('is_published', true),
        ]);

        if (count($uploadedParts) > 0) {
            $audiobook->parts()->createMany($uploadedParts);
        }

        $request->merge([
            'title_en' => $validated['title_en'] ?? $validated['title'],
            'title_fr' => $validated['title_fr'] ?? $validated['title'],
            'title_rw' => $validated['title_rw'] ?? $validated['title'],
            'description_en' => $validated['description_en'] ?? ($validated['description'] ?? null),
            'description_fr' => $validated['description_fr'] ?? ($validated['description'] ?? null),
            'description_rw' => $validated['description_rw'] ?? ($validated['description'] ?? null),
        ]);
        $this->syncTranslations($audiobook, $request, ['title', 'description']);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audiobook_created_for_book',
            'meta' => [
                'book_id' => $document->id,
                'audiobook_id' => $audiobook->id,
                'title' => $audiobook->title,
            ],
        ]);

        return redirect()->route('admin.documents.edit', $document)->with('status', 'Audiobook added to this book.');
    }

    public function storePartsForBook(Request $request, book $document)
    {
        $validated = $request->validate([
            'audiobook_id' => ['nullable', 'integer', 'exists:audiobooks,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', Rule::exists('content_categories', 'id')->whereIn('type', ['audio', 'all'])],
            'narrator' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
            'is_prayer_audio' => ['nullable', 'boolean'],
            'part_files' => ['nullable', 'array'],
            'part_files.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'single_part_file' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'part_title_prefix' => ['nullable', 'string', 'max:80'],
            'part_order_start' => ['nullable', 'integer', 'min:1'],
            'part_duration' => ['nullable', 'string', 'max:50'],
        ]);

        $hasMultiParts = $request->hasFile('part_files') && count((array) $request->file('part_files')) > 0;
        $hasSinglePart = $request->hasFile('single_part_file');
        if (!$hasMultiParts && !$hasSinglePart) {
            return back()->withErrors(['part_files' => 'Upload at least one audiobook part.'])->withInput();
        }

        $targetAudiobook = null;
        if (!empty($validated['audiobook_id'])) {
            $targetAudiobook = audiobook::query()
                ->where('book_id', $document->id)
                ->findOrFail((int) $validated['audiobook_id']);
        } else {
            $title = trim((string) ($validated['title'] ?? ''));
            if ($title === '') {
                return back()->withErrors(['title' => 'Provide audiobook title or choose existing audiobook.'])->withInput();
            }

            $targetAudiobook = audiobook::create([
                'title' => $title,
                'description' => null,
                'audio_file' => null,
                'thumbnail' => null,
                'duration' => $validated['part_duration'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'book_id' => $document->id,
                'narrator' => $validated['narrator'] ?? null,
                'series' => $validated['series'] ?? null,
                'published_at' => $validated['published_at'] ?? null,
                'featured' => false,
                'recommended' => false,
                'is_prayer_audio' => $request->boolean('is_prayer_audio'),
                'is_published' => $request->boolean('is_published', true),
            ]);

            $request->merge([
                'title_en' => $title,
                'title_fr' => $title,
                'title_rw' => $title,
            ]);
            $this->syncTranslations($targetAudiobook, $request, ['title', 'description']);
        }

        $startOrder = (int) (
            $validated['part_order_start']
            ?? (($targetAudiobook->parts()->max('sort_order') ?? 0) + 1)
        );

        $partUploadRequest = $request->duplicate();
        $files = [];
        foreach ((array) $request->file('part_files', []) as $file) {
            if ($file) {
                $files[] = $file;
            }
        }
        if ($request->hasFile('single_part_file')) {
            $files[] = $request->file('single_part_file');
        }
        $partUploadRequest->files->set('part_files', $files);

        $parts = $this->storeUploadedParts(
            $partUploadRequest,
            (string) ($validated['part_title_prefix'] ?? 'Part'),
            $startOrder,
            (string) ($validated['part_duration'] ?? '')
        );

        if (count($parts) > 0) {
            $targetAudiobook->parts()->createMany($parts);
            if (empty($targetAudiobook->audio_file)) {
                $targetAudiobook->update(['audio_file' => $parts[0]['audio_file']]);
            }
        }

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audiobook_parts_uploaded_for_book',
            'meta' => [
                'book_id' => $document->id,
                'audiobook_id' => $targetAudiobook->id,
                'parts_count' => count($parts),
            ],
        ]);

        return redirect()->route('admin.documents.edit', $document)->with('status', count($parts).' part(s) uploaded for this book.');
    }

    public function destroyPart(Request $request, audiobook $audiobook, int $part)
    {
        $partModel = $audiobook->parts()->findOrFail($part);
        $partModel->delete();

        return redirect()->back()->with('status', 'Audiobook part removed.');
    }

    public function reorderParts(Request $request, audiobook $audiobook)
    {
        $validated = $request->validate([
            'ordered_ids' => ['required', 'array', 'min:1'],
            'ordered_ids.*' => ['required', 'integer'],
        ]);

        $ids = array_values(array_unique(array_map('intval', $validated['ordered_ids'])));
        $existingIds = $audiobook->parts()->pluck('id')->map(fn ($id) => (int) $id)->all();
        $existingIdSet = array_flip($existingIds);

        foreach ($ids as $id) {
            if (!isset($existingIdSet[$id])) {
                return back()->withErrors(['ordered_ids' => 'Invalid part order payload.']);
            }
        }

        $remainingIds = array_values(array_diff($existingIds, $ids));
        $finalOrder = array_merge($ids, $remainingIds);

        DB::transaction(function () use ($audiobook, $finalOrder): void {
            foreach ($finalOrder as $index => $partId) {
                $audiobook->parts()->where('id', $partId)->update([
                    'sort_order' => $index + 1,
                ]);
            }
        });

        return redirect()->back()->with('status', 'Audiobook parts reordered.');
    }

    public function destroy(Request $request, audiobook $audiobook)
    {
        $audiobook->delete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audiobook_deleted',
            'meta' => [
                'id' => $audiobook->id,
                'title' => $audiobook->title,
            ],
        ]);

        return redirect()->back()->with('status', 'Audiobook deleted.');
    }

    public function restore(Request $request, int $audiobook)
    {
        $record = audiobook::withTrashed()->findOrFail($audiobook);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audiobook_restored',
            'meta' => [
                'id' => $record->id,
                'title' => $record->title,
            ],
        ]);

        return redirect()->back()->with('status', 'Audiobook restored.');
    }

    public function forceDelete(Request $request, int $audiobook)
    {
        $record = audiobook::withTrashed()->findOrFail($audiobook);
        $title = $record->title;
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audiobook_force_deleted',
            'meta' => [
                'id' => $audiobook,
                'title' => $title,
            ],
        ]);

        return redirect()->back()->with('status', 'Audiobook permanently deleted.');
    }

    private function storeUploadedParts(Request $request, string $titlePrefix, int $startOrder, string $duration): array
    {
        $files = $request->file('part_files', []);
        if (!is_array($files) || count($files) === 0) {
            return [];
        }

        $rows = [];
        $order = max(1, $startOrder);
        foreach ($files as $file) {
            if (!$file) {
                continue;
            }

            $audioPath = $file->store('content/audiobooks/parts', 'public');
            $rows[] = [
                'title' => trim($titlePrefix).' '.$order,
                'audio_file' => $audioPath,
                'duration' => $duration !== '' ? $duration : null,
                'sort_order' => $order,
                'is_published' => true,
            ];
            $order++;
        }

        return $rows;
    }
}
