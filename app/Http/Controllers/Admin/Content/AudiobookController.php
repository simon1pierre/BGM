<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesTranslations;
use App\Models\ContentCategory;
use App\Models\UserActivityLog;
use App\Models\audiobook;
use App\Models\book;
use Illuminate\Http\Request;
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
            'audio_file' => ['required', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'duration' => ['nullable', 'string', 'max:50'],
            'category_id' => ['nullable', Rule::exists('content_categories', 'id')->whereIn('type', ['audio', 'all'])],
            'book_id' => ['nullable', 'exists:books,id'],
            'narrator' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'recommended' => ['nullable', 'boolean'],
            'is_prayer_audio' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $audioPath = $request->file('audio_file')->store('content/audiobooks', 'public');
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
            'book_id' => ['nullable', 'exists:books,id'],
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

        return redirect()->route('admin.audiobooks.index')->with('status', 'Audiobook deleted.');
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

        return redirect()->route('admin.audiobooks.index')->with('status', 'Audiobook restored.');
    }
}
