<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Concerns\HandlesTranslations;
use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use App\Models\UserActivityLog;
use App\\Models\\Audiobook;
use App\\Models\\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AudiobookController extends Controller
{
    use HandlesTranslations;

    public function index()
    {
        return redirect()
            ->route('admin.documents.index')
            ->with('warning', 'Audiobooks are managed from the related book. Open a book to manage its audiobook parts.');
    }

    public function create()
    {
        return redirect()
            ->route('admin.documents.index')
            ->with('warning', 'Create audiobooks from the related book page.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'title_fr' => ['required', 'string', 'max:255'],
            'title_rw' => ['required', 'string', 'max:255'],
            'audio_file' => ['nullable', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'duration' => ['nullable', 'string', 'max:50'],
            'book_id' => ['required', 'exists:books,id'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'is_prayer_audio' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'part_files' => ['nullable', 'array'],
            'part_files.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files_rw' => ['nullable', 'array'],
            'part_files_rw.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files_en' => ['nullable', 'array'],
            'part_files_en.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files_fr' => ['nullable', 'array'],
            'part_files_fr.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_order_start' => ['nullable', 'integer', 'min:1'],
            'part_duration' => ['nullable', 'string', 'max:50'],
        ]);

        $languageFileMap = $this->collectPartFilesByLanguage($request);
        $hasAnyPartFiles = collect($languageFileMap)->flatten(1)->isNotEmpty();

        if (!$request->hasFile('audio_file') && !$hasAnyPartFiles) {
            return back()
                ->withErrors(['audio_file' => 'Upload a main audiobook file or at least one audiobook part.'])
                ->withInput();
        }

        $uploadedParts = [];
        $nextOrder = (int) ($validated['part_order_start'] ?? 1);
        foreach ($languageFileMap as $lang => $files) {
            $rows = $this->storeUploadedParts(
                $files,
                $nextOrder,
                (string) ($validated['part_duration'] ?? ''),
                $lang
            );
            if (count($rows) > 0) {
                $uploadedParts = array_merge($uploadedParts, $rows);
                $nextOrder += count($rows);
            }
        }

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

        $linkedBook = Book::query()->find($validated['book_id']);

        $audiobook = Audiobook::create([
            'title' => $validated['title'],
            'description' => null,
            'audio_file' => $audioPath,
            'thumbnail' => $thumbnailPath,
            'duration' => $validated['duration'] ?? null,
            'category_id' => $linkedBook?->category_id,
            'book_id' => $validated['book_id'] ?? null,
            'narrator' => null,
            'series' => null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'recommended' => false,
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

        $this->syncTranslations($audiobook, $request, ['title']);

        return redirect()
            ->route('admin.documents.edit', $audiobook->book_id)
            ->with('status', 'Audiobook created.');
    }

    public function edit(Audiobook $audiobook)
    {
        $audiobook->load(['parts' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        }]);

        $categories = ContentCategory::query()
            ->whereIn('type', ['audio', 'all'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $books = Book::query()
            ->where('is_published', true)
            ->orWhere('id', $audiobook->book_id)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();

        return view('Admin.Content.Audiobooks.edit', compact('audiobook', 'categories', 'books'));
    }

    public function preview(Audiobook $audiobook)
    {
        return view('Admin.Content.Audiobooks.preview', compact('audiobook'));
    }

    public function parts(Audiobook $audiobook)
    {
        return redirect()
            ->route('admin.audiobooks.edit', $audiobook)
            ->with('warning', 'Use the form on the edit page to upload audiobook parts.');
    }

    public function update(Request $request, Audiobook $audiobook)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'title_fr' => ['required', 'string', 'max:255'],
            'title_rw' => ['required', 'string', 'max:255'],
            'audio_file' => ['nullable', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'duration' => ['nullable', 'string', 'max:50'],
            'book_id' => ['required', 'exists:books,id'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
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

        $linkedBook = Book::query()->find($validated['book_id']);

        $audiobook->update([
            'title' => $validated['title'],
            'description' => null,
            'audio_file' => $audioPath,
            'thumbnail' => $thumbnailPath,
            'duration' => $validated['duration'] ?? null,
            'category_id' => $linkedBook?->category_id,
            'book_id' => $validated['book_id'] ?? null,
            'narrator' => null,
            'series' => null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'recommended' => false,
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

        $this->syncTranslations($audiobook, $request, ['title']);

        return $this->redirectToBookEdit($audiobook, 'Audiobook updated.');
    }

    public function addPart(Request $request, Audiobook $audiobook)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'audio_file' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files' => ['nullable', 'array'],
            'part_files.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files_rw' => ['nullable', 'array'],
            'part_files_rw.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files_en' => ['nullable', 'array'],
            'part_files_en.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files_fr' => ['nullable', 'array'],
            'part_files_fr.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_language' => ['nullable', 'in:rw,en,fr'],
            'part_order_start' => ['nullable', 'integer', 'min:1'],
            'duration' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $languageFileMap = $this->collectPartFilesByLanguage($request);
        $hasAnyPartFiles = collect($languageFileMap)->flatten(1)->isNotEmpty();
        if (!$request->hasFile('audio_file') && !$hasAnyPartFiles) {
            return back()->withErrors(['audio_file' => 'Select one part file or upload many part files.'])->withInput();
        }

        if ($hasAnyPartFiles) {
            $startOrder = (int) ($validated['part_order_start'] ?? (($audiobook->parts()->max('sort_order') ?? 0) + 1));
            $rows = [];
            $nextOrder = $startOrder;
            foreach ($languageFileMap as $lang => $files) {
                $currentRows = $this->storeUploadedParts(
                    $files,
                    $nextOrder,
                    (string) ($validated['duration'] ?? ''),
                    $lang
                );
                if (count($currentRows) > 0) {
                    $rows = array_merge($rows, $currentRows);
                    $nextOrder += count($currentRows);
                }
            }

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

        $originalName = pathinfo((string) $request->file('audio_file')->getClientOriginalName(), PATHINFO_FILENAME);
        $defaultTitle = trim($originalName) !== '' ? trim($originalName) : 'untitled';

        $part = $audiobook->parts()->create([
            'title' => trim((string) ($validated['title'] ?? '')) ?: $defaultTitle,
            'audio_file' => $filePath,
            'language' => $this->normalizePartLanguage((string) ($validated['part_language'] ?? 'rw')),
            'duration' => $validated['duration'] ?? null,
            'sort_order' => $nextOrder,
            'is_published' => $request->boolean('is_published', true),
        ]);

        if (empty($audiobook->audio_file)) {
            $audiobook->update(['audio_file' => $part->audio_file]);
        }

        return redirect()->back()->with('status', 'Audiobook part added.');
    }

    public function storeForBook(Request $request, Book $document)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'title_fr' => ['nullable', 'string', 'max:255'],
            'title_rw' => ['nullable', 'string', 'max:255'],
            'audio_file' => ['nullable', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'duration' => ['nullable', 'string', 'max:50'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'is_prayer_audio' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'part_files' => ['nullable', 'array'],
            'part_files.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files_rw' => ['nullable', 'array'],
            'part_files_rw.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files_en' => ['nullable', 'array'],
            'part_files_en.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files_fr' => ['nullable', 'array'],
            'part_files_fr.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_order_start' => ['nullable', 'integer', 'min:1'],
            'part_duration' => ['nullable', 'string', 'max:50'],
        ]);

        $languageFileMap = $this->collectPartFilesByLanguage($request);
        $hasAnyPartFiles = collect($languageFileMap)->flatten(1)->isNotEmpty();

        if (!$request->hasFile('audio_file') && !$hasAnyPartFiles) {
            return back()
                ->withErrors(['audio_file' => 'Upload a main audiobook file or at least one audiobook part.'])
                ->withInput();
        }

        $uploadedParts = [];
        $nextOrder = (int) ($validated['part_order_start'] ?? 1);
        foreach ($languageFileMap as $lang => $files) {
            $rows = $this->storeUploadedParts(
                $files,
                $nextOrder,
                (string) ($validated['part_duration'] ?? ''),
                $lang
            );
            if (count($rows) > 0) {
                $uploadedParts = array_merge($uploadedParts, $rows);
                $nextOrder += count($rows);
            }
        }

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

        $audiobook = Audiobook::create([
            'title' => $validated['title'],
            'description' => null,
            'audio_file' => $audioPath,
            'thumbnail' => $thumbnailPath,
            'duration' => $validated['duration'] ?? null,
            'category_id' => $document->category_id,
            'book_id' => $document->id,
            'narrator' => null,
            'series' => null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'recommended' => false,
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
        ]);
        $this->syncTranslations($audiobook, $request, ['title']);

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

    public function storePartsForBook(Request $request, Book $document)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
            'is_prayer_audio' => ['nullable', 'boolean'],
            'part_files' => ['nullable', 'array'],
            'part_files.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files_rw' => ['nullable', 'array'],
            'part_files_rw.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files_en' => ['nullable', 'array'],
            'part_files_en.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'part_files_fr' => ['nullable', 'array'],
            'part_files_fr.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'single_part_file' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'single_part_language' => ['nullable', 'in:rw,en,fr'],
            'part_order_start' => ['nullable', 'integer', 'min:1'],
            'part_duration' => ['nullable', 'string', 'max:50'],
        ]);

        $languageFileMap = $this->collectPartFilesByLanguage($request);
        $hasMultiParts = collect($languageFileMap)->flatten(1)->isNotEmpty();
        $hasSinglePart = $request->hasFile('single_part_file');
        if (!$hasMultiParts && !$hasSinglePart) {
            return back()->withErrors(['part_files' => 'Upload at least one audiobook part.'])->withInput();
        }

        // One flat audiobook-parts list per book (no nested/main parts grouping).
        $targetAudiobook = Audiobook::query()
            ->where('book_id', $document->id)
            ->latest('id')
            ->first();

        if (!$targetAudiobook) {
            $title = trim((string) ($validated['title'] ?? ''));
            if ($title === '') {
                return back()
                    ->withErrors(['title' => 'Enter audiobook title when creating a new Audiobook.'])
                    ->withInput();
            }

            $targetAudiobook = Audiobook::create([
                'title' => $title,
                'description' => null,
                'audio_file' => null,
                'thumbnail' => null,
                'duration' => $validated['part_duration'] ?? null,
                'category_id' => $document->category_id,
                'book_id' => $document->id,
                'narrator' => null,
                'series' => null,
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
            $this->syncTranslations($targetAudiobook, $request, ['title']);
        }

        $startOrder = (int) (
            $validated['part_order_start']
            ?? (($targetAudiobook->parts()->max('sort_order') ?? 0) + 1)
        );

        $parts = [];
        $nextOrder = $startOrder;
        foreach ($languageFileMap as $lang => $files) {
            $rows = $this->storeUploadedParts(
                $files,
                $nextOrder,
                (string) ($validated['part_duration'] ?? ''),
                $lang
            );
            if (count($rows) > 0) {
                $parts = array_merge($parts, $rows);
                $nextOrder += count($rows);
            }
        }
        if ($request->hasFile('single_part_file')) {
            $singleFile = $request->file('single_part_file');
            $language = $this->normalizePartLanguage((string) ($validated['single_part_language'] ?? 'rw'));
            $singleRows = $this->storeUploadedParts(
                [$singleFile],
                $nextOrder,
                (string) ($validated['part_duration'] ?? ''),
                $language
            );
            if (count($singleRows) > 0) {
                $parts = array_merge($parts, $singleRows);
            }
        }

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

    public function showPartsForBook(Book $document)
    {
        return redirect()
            ->route('admin.documents.edit', $document)
            ->with('warning', 'Use the upload form on this page to add audiobook parts.');
    }

    public function updatePart(Request $request, Audiobook $audiobook, int $part)
    {
        $partModel = $audiobook->parts()->findOrFail($part);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'audio_file' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'language' => ['required', 'in:rw,en,fr'],
            'duration' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $oldFile = $partModel->audio_file;
        $filePath = $oldFile;
        if ($request->hasFile('audio_file')) {
            $filePath = $request->file('audio_file')->store('content/audiobooks/parts', 'public');
        }

        $partModel->update([
            'title' => $validated['title'],
            'audio_file' => $filePath,
            'language' => $this->normalizePartLanguage($validated['language']),
            'duration' => $validated['duration'] ?? null,
            'sort_order' => (int) $validated['sort_order'],
            'is_published' => $request->boolean('is_published', true),
        ]);

        if (!empty($audiobook->audio_file) && $audiobook->audio_file === $oldFile && $filePath !== $oldFile) {
            $audiobook->update(['audio_file' => $filePath]);
        }

        return redirect()->back()->with('status', 'Audiobook part updated.');
    }

    public function destroyPart(Request $request, Audiobook $audiobook, int $part)
    {
        $partModel = $audiobook->parts()->findOrFail($part);
        $partModel->delete();

        return redirect()->back()->with('status', 'Audiobook part removed.');
    }

    public function destroyManyParts(Request $request, Audiobook $audiobook)
    {
        $validated = $request->validate([
            'part_ids' => ['required', 'array', 'min:1'],
            'part_ids.*' => ['required', 'integer'],
        ]);

        $ids = array_values(array_unique(array_map('intval', $validated['part_ids'])));
        $ownedIds = $audiobook->parts()->whereIn('id', $ids)->pluck('id')->map(fn ($id) => (int) $id)->all();

        if (count($ownedIds) === 0) {
            return redirect()->back()->withErrors(['part_ids' => 'No valid parts selected.']);
        }

        $audiobook->parts()->whereIn('id', $ownedIds)->delete();

        return redirect()->back()->with('status', count($ownedIds).' audiobook part(s) removed.');
    }

    public function reorderParts(Request $request, Audiobook $audiobook)
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

    public function destroy(Request $request, Audiobook $audiobook)
    {
        $bookId = $audiobook->book_id;
        $audiobook->delete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audiobook_deleted',
            'meta' => [
                'id' => $audiobook->id,
                'title' => $audiobook->title,
            ],
        ]);

        if ($bookId) {
            return redirect()->route('admin.documents.edit', $bookId)->with('status', 'Audiobook deleted.');
        }

        return redirect()->route('admin.documents.index')->with('status', 'Audiobook deleted.');
    }

    public function restore(Request $request, int $audiobook)
    {
        $record = Audiobook::withTrashed()->findOrFail($audiobook);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audiobook_restored',
            'meta' => [
                'id' => $record->id,
                'title' => $record->title,
            ],
        ]);

        if ($record->book_id) {
            return redirect()->route('admin.documents.edit', $record->book_id)->with('status', 'Audiobook restored.');
        }

        return redirect()->route('admin.documents.index')->with('status', 'Audiobook restored.');
    }

    public function forceDelete(Request $request, int $audiobook)
    {
        $record = Audiobook::withTrashed()->findOrFail($audiobook);
        $title = $record->title;
        $bookId = $record->book_id;
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audiobook_force_deleted',
            'meta' => [
                'id' => $audiobook,
                'title' => $title,
            ],
        ]);

        if ($bookId) {
            return redirect()->route('admin.documents.edit', $bookId)->with('status', 'Audiobook permanently deleted.');
        }

        return redirect()->route('admin.documents.index')->with('status', 'Audiobook permanently deleted.');
    }

    private function storeUploadedParts(array $files, int $startOrder, string $duration, string $language): array
    {
        if (count($files) === 0) {
            return [];
        }

        $rows = [];
        $order = max(1, $startOrder);
        $normalizedLanguage = $this->normalizePartLanguage($language);
        foreach ($files as $file) {
            if (!$file) {
                continue;
            }

            $audioPath = $file->store('content/audiobooks/parts', 'public');
            $originalName = pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME);
            $defaultTitle = trim($originalName) !== '' ? trim($originalName) : 'untitled';

            $rows[] = [
                'title' => $defaultTitle,
                'audio_file' => $audioPath,
                'language' => $normalizedLanguage,
                'duration' => $duration !== '' ? $duration : null,
                'sort_order' => $order,
                'is_published' => true,
            ];
            $order++;
        }

        return $rows;
    }

    private function redirectToBookEdit(Audiobook $audiobook, string $statusMessage)
    {
        if ($audiobook->book_id) {
            return redirect()->route('admin.documents.edit', $audiobook->book_id)->with('status', $statusMessage);
        }

        return redirect()->route('admin.documents.index')->with('status', $statusMessage);
    }

    private function normalizePartLanguage(string $language): string
    {
        $language = strtolower(trim($language));
        return in_array($language, ['rw', 'en', 'fr'], true) ? $language : 'rw';
    }

    private function collectPartFilesByLanguage(Request $request): array
    {
        $result = [
            'rw' => [],
            'en' => [],
            'fr' => [],
        ];

        foreach ((array) $request->file('part_files_rw', []) as $file) {
            if ($file) {
                $result['rw'][] = $file;
            }
        }
        foreach ((array) $request->file('part_files_en', []) as $file) {
            if ($file) {
                $result['en'][] = $file;
            }
        }
        foreach ((array) $request->file('part_files_fr', []) as $file) {
            if ($file) {
                $result['fr'][] = $file;
            }
        }

        // Backward compatibility for existing "part_files[]" forms.
        foreach ((array) $request->file('part_files', []) as $file) {
            if ($file) {
                $result['rw'][] = $file;
            }
        }

        return $result;
    }
}


