<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use App\Models\UserActivityLog;
use App\Models\video;
use App\Jobs\SendContentNotificationJob;
use App\Models\ContentNotification;
use App\Models\Playlist;
use App\Models\PlaylistItem;
use App\Http\Controllers\Concerns\HandlesTranslations;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VideoController extends Controller
{
    use HandlesTranslations;

    public function index()
    {
        return view('Admin.Content.Videos.index');
    }

    public function create()
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['video', 'all'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $playlists = Playlist::query()
            ->where('type', 'video')
            ->where('is_published', true)
            ->orderBy('title')
            ->get();

        return view('Admin.Content.Videos.create', compact('categories', 'playlists'));
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
            'youtube_url' => ['required', 'url', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'category_id' => [
                'required',
                Rule::exists('content_categories', 'id')->whereIn('type', ['video', 'all']),
            ],
            'speaker' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'notify_subscribers' => ['nullable', 'boolean'],
            'notify_target' => ['nullable', 'in:all,custom'],
            'notify_emails' => ['nullable', 'string'],
            'notify_message' => ['nullable', 'string'],
            'playlist_ids' => ['nullable', 'array'],
            'playlist_ids.*' => ['integer', Rule::exists('playlists', 'id')->where('type', 'video')],
            'new_playlist_title' => ['nullable', 'string', 'max:255'],
        ]);

        $youtubeId = $this->extractYoutubeId($validated['youtube_url']);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'Enter a valid YouTube URL.'])->withInput();
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('content/videos/thumbnails', 'public');
        }

        $video = video::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'youtube_url' => $validated['youtube_url'],
            'youtube_id' => $youtubeId,
            'thumbnail' => $thumbnailPath,
            'category_id' => $validated['category_id'],
            'speaker' => $validated['speaker'] ?? null,
            'series' => $validated['series'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'is_published' => $request->boolean('is_published'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'video_created',
            'meta' => [
                'id' => $video->id,
                'title' => $video->title,
            ],
        ]);

        $this->syncTranslations($video, $request, ['title', 'description']);

        $this->syncPlaylists($request, $video->id, 'video');

        $this->maybeNotifySubscribers($request, [
            'type' => 'video',
            'title' => $video->title,
            'description' => $video->description,
            'category' => $video->category?->name,
            'thumbnail' => $video->thumbnail_url,
            'cta_url' => $video->youtube_url,
            'cta_text' => 'Watch on YouTube',
            'subject' => 'New Video: '.$video->title,
            'extra_text' => $request->input('notify_message'),
        ]);

        return redirect()->route('admin.videos.index')->with('status', 'Video created.');
    }

    public function edit(video $video)
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['video', 'all'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $playlists = Playlist::query()
            ->where('type', 'video')
            ->where('is_published', true)
            ->orderBy('title')
            ->get();

        $selectedPlaylists = PlaylistItem::query()
            ->where('item_type', 'video')
            ->where('item_id', $video->id)
            ->pluck('playlist_id')
            ->all();

        return view('Admin.Content.Videos.edit', compact('video', 'categories', 'playlists', 'selectedPlaylists'));
    }

    public function preview(video $video)
    {
        return view('Admin.Content.Videos.preview', compact('video'));
    }

    public function update(Request $request, video $video)
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
            'youtube_url' => ['required', 'url', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'category_id' => [
                'required',
                Rule::exists('content_categories', 'id')->whereIn('type', ['video', 'all']),
            ],
            'speaker' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'notify_subscribers' => ['nullable', 'boolean'],
            'notify_target' => ['nullable', 'in:all,custom'],
            'notify_emails' => ['nullable', 'string'],
            'notify_message' => ['nullable', 'string'],
            'playlist_ids' => ['nullable', 'array'],
            'playlist_ids.*' => ['integer', Rule::exists('playlists', 'id')->where('type', 'video')],
            'new_playlist_title' => ['nullable', 'string', 'max:255'],
        ]);

        $youtubeId = $this->extractYoutubeId($validated['youtube_url']);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'Enter a valid YouTube URL.'])->withInput();
        }

        $thumbnailPath = $video->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('content/videos/thumbnails', 'public');
        }

        $video->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'youtube_url' => $validated['youtube_url'],
            'youtube_id' => $youtubeId,
            'thumbnail' => $thumbnailPath,
            'category_id' => $validated['category_id'],
            'speaker' => $validated['speaker'] ?? null,
            'series' => $validated['series'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'is_published' => $request->boolean('is_published'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'video_updated',
            'meta' => [
                'id' => $video->id,
                'title' => $video->title,
            ],
        ]);

        $this->syncTranslations($video, $request, ['title', 'description']);

        $this->syncPlaylists($request, $video->id, 'video');

        $this->maybeNotifySubscribers($request, [
            'type' => 'video',
            'title' => $video->title,
            'description' => $video->description,
            'category' => $video->category?->name,
            'thumbnail' => $video->thumbnail_url,
            'cta_url' => $video->youtube_url,
            'cta_text' => 'Watch on YouTube',
            'subject' => 'Updated Video: '.$video->title,
            'extra_text' => $request->input('notify_message'),
        ]);

        return redirect()->route('admin.videos.index')->with('status', 'Video updated.');
    }

    public function destroy(Request $request, video $video)
    {
        $video->delete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'video_deleted',
            'meta' => [
                'id' => $video->id,
                'title' => $video->title,
            ],
        ]);

        return redirect()->route('admin.videos.index')->with('status', 'Video deleted.');
    }

    public function restore(Request $request, int $video)
    {
        $record = video::withTrashed()->findOrFail($video);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'video_restored',
            'meta' => [
                'id' => $record->id,
                'title' => $record->title,
            ],
        ]);

        return redirect()->route('admin.videos.index')->with('status', 'Video restored.');
    }

    public function forceDelete(Request $request, int $video)
    {
        $record = video::withTrashed()->findOrFail($video);
        $title = $record->title;
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'video_force_deleted',
            'meta' => [
                'id' => $video,
                'title' => $title,
            ],
        ]);

        return redirect()->route('admin.videos.index')->with('status', 'Video permanently deleted.');
    }

    private function extractYoutubeId(string $url): ?string
    {
        $parts = parse_url($url);
        if (!$parts) {
            return null;
        }

        if (!empty($parts['host']) && str_contains($parts['host'], 'youtu.be')) {
            return ltrim($parts['path'] ?? '', '/');
        }

        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
            if (!empty($query['v'])) {
                return $query['v'];
            }
        }

        if (!empty($parts['path'])) {
            $segments = array_values(array_filter(explode('/', $parts['path'])));
            $key = array_search('embed', $segments, true);
            if ($key !== false && isset($segments[$key + 1])) {
                return $segments[$key + 1];
            }
        }

        return null;
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
        $emails = collect(explode(',', $raw))
            ->map(fn ($email) => trim($email))
            ->filter(fn ($email) => $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();

        return $emails;
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
