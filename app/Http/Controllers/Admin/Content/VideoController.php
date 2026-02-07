<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\UserActivityLog;
use App\Models\video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        return view('Admin.Content.Videos.index');
    }

    public function create()
    {
        return view('Admin.Content.Videos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'youtube_url' => ['required', 'url', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'speaker' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
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

        return redirect()->route('admin.videos.index')->with('status', 'Video created.');
    }

    public function edit(video $video)
    {
        return view('Admin.Content.Videos.edit', compact('video'));
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
            'youtube_url' => ['required', 'url', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'speaker' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
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
}
