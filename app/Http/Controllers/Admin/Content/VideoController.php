<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use App\Models\UserActivityLog;
use App\Models\MinistryLeader;
use App\Models\Video;
use App\Jobs\SendContentNotificationJob;
use App\Models\ContentNotification;
use App\Models\VideoSeries;
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

        $videoSeries = VideoSeries::query()
            ->with('translations')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $preachers = MinistryLeader::query()
            ->where('role_type', 'preacher')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('Admin.Content.Videos.create', compact('categories', 'videoSeries', 'preachers'));
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
            'speaker' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists('ministry_leaders', 'name')->where(function ($query) {
                    $query->where('role_type', 'preacher')->where('is_active', true);
                }),
            ],
            'video_series_id' => ['nullable', Rule::exists('video_series', 'id')],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'notify_subscribers' => ['nullable', 'boolean'],
            'notify_target' => ['nullable', 'in:all,custom'],
            'notify_emails' => ['nullable', 'string'],
            'notify_message' => ['nullable', 'string'],
        ]);

        $youtubeId = $this->extractYoutubeId($validated['youtube_url']);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'Enter a valid YouTube URL.'])->withInput();
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('content/videos/thumbnails', 'public');
        }

        $selectedSeries = null;
        if (!empty($validated['video_series_id'])) {
            $selectedSeries = VideoSeries::query()->find($validated['video_series_id']);
        }

        $video = Video::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'youtube_url' => $validated['youtube_url'],
            'youtube_id' => $youtubeId,
            'thumbnail' => $thumbnailPath,
            'category_id' => $validated['category_id'],
            'speaker' => $validated['speaker'] ?? null,
            'series' => $selectedSeries?->title,
            'video_series_id' => $selectedSeries?->id,
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

        $this->maybeNotifySubscribers($request, [
            'type' => 'video',
            'title' => $video->title,
            'description' => $video->description,
            'category' => $video->category?->name,
            'thumbnail' => $video->thumbnail_url,
            'cta_url' => $video->youtube_url,
            'cta_text' => 'Watch on YouTube',
            'subject' => 'new Video: '.$video->title,
            'extra_text' => $request->input('notify_message'),
        ]);

        return redirect()->route('admin.videos.index')->with('status', 'Video created.');
    }

    public function edit(Video $video)
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['video', 'all'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $videoSeries = VideoSeries::query()
            ->with('translations')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $preachers = MinistryLeader::query()
            ->where('role_type', 'preacher')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('Admin.Content.Videos.edit', compact('video', 'categories', 'videoSeries', 'preachers'));
    }

    public function preview(Video $video)
    {
        return view('Admin.Content.Videos.preview', compact('video'));
    }

    public function update(Request $request, Video $video)
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
            'speaker' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists('ministry_leaders', 'name')->where(function ($query) {
                    $query->where('role_type', 'preacher')->where('is_active', true);
                }),
            ],
            'video_series_id' => ['nullable', Rule::exists('video_series', 'id')],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'notify_subscribers' => ['nullable', 'boolean'],
            'notify_target' => ['nullable', 'in:all,custom'],
            'notify_emails' => ['nullable', 'string'],
            'notify_message' => ['nullable', 'string'],
        ]);

        $youtubeId = $this->extractYoutubeId($validated['youtube_url']);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'Enter a valid YouTube URL.'])->withInput();
        }

        $thumbnailPath = $video->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('content/videos/thumbnails', 'public');
        }

        $selectedSeries = null;
        if (!empty($validated['video_series_id'])) {
            $selectedSeries = VideoSeries::query()->find($validated['video_series_id']);
        }

        $video->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'youtube_url' => $validated['youtube_url'],
            'youtube_id' => $youtubeId,
            'thumbnail' => $thumbnailPath,
            'category_id' => $validated['category_id'],
            'speaker' => $validated['speaker'] ?? null,
            'series' => $selectedSeries?->title,
            'video_series_id' => $selectedSeries?->id,
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

    public function destroy(Request $request, Video $video)
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

        return redirect()->back()->with('status', 'Video deleted.');
    }

    public function restore(Request $request, int $video)
    {
        $record = Video::withTrashed()->findOrFail($video);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'video_restored',
            'meta' => [
                'id' => $record->id,
                'title' => $record->title,
            ],
        ]);

        return redirect()->back()->with('status', 'Video restored.');
    }

    public function forceDelete(Request $request, int $video)
    {
        $record = Video::withTrashed()->findOrFail($video);
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

        return redirect()->back()->with('status', 'Video permanently deleted.');
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

}










