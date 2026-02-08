<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use App\Models\UserActivityLog;
use App\Models\audio;
use App\Jobs\SendContentNotificationJob;
use App\Models\ContentNotification;
use App\Models\Playlist;
use App\Models\PlaylistItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AudioController extends Controller
{
    public function index()
    {
        return view('Admin.Content.Audios.index');
    }

    public function create()
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['audio', 'all'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $playlists = Playlist::query()
            ->where('type', 'audio')
            ->where('is_published', true)
            ->orderBy('title')
            ->get();

        return view('Admin.Content.Audios.create', compact('categories', 'playlists'));
    }

    public function store(Request $request)
    {
        if (!$request->hasFile('audio_file')) {
            return back()
                ->withErrors(['audio_file' => 'No file received. Check PHP upload_max_filesize/post_max_size and try again.'])
                ->withInput();
        }

        if (!$request->file('audio_file')->isValid()) {
            return back()
                ->withErrors(['audio_file' => 'The audio file failed to upload. Check PHP upload_max_filesize/post_max_size and try again.'])
                ->withInput();
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'audio_file' => ['required', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'duration' => ['nullable', 'string', 'max:50'],
            'category_id' => [
                'required',
                Rule::exists('content_categories', 'id')->whereIn('type', ['audio', 'all']),
            ],
            'speaker' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'recommended' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'notify_subscribers' => ['nullable', 'boolean'],
            'notify_target' => ['nullable', 'in:all,custom'],
            'notify_emails' => ['nullable', 'string'],
            'notify_message' => ['nullable', 'string'],
            'playlist_ids' => ['nullable', 'array'],
            'playlist_ids.*' => ['integer', Rule::exists('playlists', 'id')->where('type', 'audio')],
            'new_playlist_title' => ['nullable', 'string', 'max:255'],
        ], [
            'audio_file.uploaded' => 'The audio file failed to upload. Check PHP upload_max_filesize/post_max_size and try again.',
        ]);

        $audioPath = $request->file('audio_file')->store('content/audios', 'public');
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('content/audios/thumbnails', 'public');
        }

        $audio = audio::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'audio_file' => $audioPath,
            'thumbnail' => $thumbnailPath,
            'duration' => $validated['duration'] ?? null,
            'category_id' => $validated['category_id'],
            'speaker' => $validated['speaker'] ?? null,
            'series' => $validated['series'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'recommended' => $request->boolean('recommended'),
            'is_published' => $request->boolean('is_published'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audio_created',
            'meta' => [
                'id' => $audio->id,
                'title' => $audio->title,
            ],
        ]);

        $this->syncPlaylists($request, $audio->id, 'audio');

        $this->maybeNotifySubscribers($request, [
            'type' => 'audio',
            'title' => $audio->title,
            'description' => $audio->description,
            'category' => $audio->category?->name,
            'thumbnail' => $audio->thumbnail_url,
            'cta_url' => asset('storage/'.$audio->audio_file),
            'cta_text' => 'Listen Now',
            'subject' => 'New Audio: '.$audio->title,
            'extra_text' => $request->input('notify_message'),
        ]);

        return redirect()->route('admin.audios.index')->with('status', 'Audio created.');
    }

    public function edit(audio $audio)
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['audio', 'all'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $playlists = Playlist::query()
            ->where('type', 'audio')
            ->where('is_published', true)
            ->orderBy('title')
            ->get();

        $selectedPlaylists = PlaylistItem::query()
            ->where('item_type', 'audio')
            ->where('item_id', $audio->id)
            ->pluck('playlist_id')
            ->all();

        return view('Admin.Content.Audios.edit', compact('audio', 'categories', 'playlists', 'selectedPlaylists'));
    }

    public function preview(audio $audio)
    {
        return view('Admin.Content.Audios.preview', compact('audio'));
    }

    public function update(Request $request, audio $audio)
    {
        if ($request->hasFile('audio_file') && !$request->file('audio_file')->isValid()) {
            return back()
                ->withErrors(['audio_file' => 'The audio file failed to upload. Check PHP upload_max_filesize/post_max_size and try again.'])
                ->withInput();
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'audio_file' => ['nullable', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'duration' => ['nullable', 'string', 'max:50'],
            'category_id' => [
                'required',
                Rule::exists('content_categories', 'id')->whereIn('type', ['audio', 'all']),
            ],
            'speaker' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'recommended' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'notify_subscribers' => ['nullable', 'boolean'],
            'notify_target' => ['nullable', 'in:all,custom'],
            'notify_emails' => ['nullable', 'string'],
            'notify_message' => ['nullable', 'string'],
            'playlist_ids' => ['nullable', 'array'],
            'playlist_ids.*' => ['integer', Rule::exists('playlists', 'id')->where('type', 'audio')],
            'new_playlist_title' => ['nullable', 'string', 'max:255'],
        ], [
            'audio_file.uploaded' => 'The audio file failed to upload. Check PHP upload_max_filesize/post_max_size and try again.',
        ]);

        $audioPath = $audio->audio_file;
        if ($request->hasFile('audio_file')) {
            $audioPath = $request->file('audio_file')->store('content/audios', 'public');
        }

        $thumbnailPath = $audio->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('content/audios/thumbnails', 'public');
        }

        $audio->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'audio_file' => $audioPath,
            'thumbnail' => $thumbnailPath,
            'duration' => $validated['duration'] ?? null,
            'category_id' => $validated['category_id'],
            'speaker' => $validated['speaker'] ?? null,
            'series' => $validated['series'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'recommended' => $request->boolean('recommended'),
            'is_published' => $request->boolean('is_published'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audio_updated',
            'meta' => [
                'id' => $audio->id,
                'title' => $audio->title,
            ],
        ]);

        $this->syncPlaylists($request, $audio->id, 'audio');

        $this->maybeNotifySubscribers($request, [
            'type' => 'audio',
            'title' => $audio->title,
            'description' => $audio->description,
            'category' => $audio->category?->name,
            'thumbnail' => $audio->thumbnail_url,
            'cta_url' => asset('storage/'.$audio->audio_file),
            'cta_text' => 'Listen Now',
            'subject' => 'Updated Audio: '.$audio->title,
            'extra_text' => $request->input('notify_message'),
        ]);

        return redirect()->route('admin.audios.index')->with('status', 'Audio updated.');
    }

    public function destroy(Request $request, audio $audio)
    {
        $audio->delete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audio_deleted',
            'meta' => [
                'id' => $audio->id,
                'title' => $audio->title,
            ],
        ]);

        return redirect()->route('admin.audios.index')->with('status', 'Audio deleted.');
    }

    public function restore(Request $request, int $audio)
    {
        $record = audio::withTrashed()->findOrFail($audio);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audio_restored',
            'meta' => [
                'id' => $record->id,
                'title' => $record->title,
            ],
        ]);

        return redirect()->route('admin.audios.index')->with('status', 'Audio restored.');
    }

    private function maybeNotifySubscribers(Request $request, array $payload): void
    {
        if (!$request->boolean('notify_subscribers')) {
            return;
        }

        $target = $request->input('notify_target', 'all');
        if ($target === 'custom') {
            $emails = $this->parseEmails((string) $request->input('notify_emails'));

            if (count($emails) === 0) {
                throw ValidationException::withMessages([
                    'notify_emails' => 'Please provide at least one valid email address.',
                ]);
            }

            ContentNotification::create([
                'payload' => $payload,
                'target_type' => 'custom',
                'target_emails' => $emails,
                'status' => 'sent',
                'sent_at' => now(),
                'created_by' => $request->user()?->id,
            ]);
            SendContentNotificationJob::dispatchSync($payload, $emails, false);
            return;
        }

        ContentNotification::create([
            'payload' => $payload,
            'target_type' => 'all',
            'target_emails' => null,
            'status' => 'sent',
            'sent_at' => now(),
            'created_by' => $request->user()?->id,
        ]);
        SendContentNotificationJob::dispatchSync($payload, [], true);
    }

    private function parseEmails(string $raw): array
    {
        return collect(explode(',', $raw))
            ->map(fn ($email) => trim($email))
            ->filter(fn ($email) => $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }

    private function syncPlaylists(Request $request, int $itemId, string $type): void
    {
        $playlistIds = array_map('intval', $request->input('playlist_ids', []));
        $newTitle = trim((string) $request->input('new_playlist_title'));

        if ($newTitle !== '') {
            $playlist = Playlist::create([
                'title' => $newTitle,
                'type' => $type,
                'is_published' => true,
                'featured' => false,
                'created_by' => $request->user()?->id,
            ]);
            $playlistIds[] = $playlist->id;
        }

        $playlistIds = array_values(array_unique(array_filter($playlistIds)));

        PlaylistItem::query()
            ->where('item_type', $type)
            ->where('item_id', $itemId)
            ->delete();

        $sort = 1;
        foreach ($playlistIds as $playlistId) {
            PlaylistItem::create([
                'playlist_id' => $playlistId,
                'item_type' => $type,
                'item_id' => $itemId,
                'sort_order' => $sort,
            ]);
            $sort++;
        }
    }
}
