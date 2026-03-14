<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\DownloadsLog;
use App\Models\AudiobookPart;
use App\Models\Audio;
use App\Models\Book;
use App\Models\Video;
use App\Models\VideoEvent;
use App\Services\GeoIpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ContentDownloadController extends Controller
{
    public function audio(Request $request, Audio $audio): Response
    {
        $storagePath = (string) $audio->audio_file;
        $publicPath = public_path('storage/'.$storagePath);

        if ($storagePath === '' || (!Storage::disk('public')->exists($storagePath) && !is_file($publicPath))) {
            abort(404);
        }

        $this->recordDownload('audio', $audio->id, $request->ip());
        $this->recordContentEvent($request, $audio->getMorphClass(), $audio->id, 'download');
        $audio->increment('download_count');

        $filename = $this->sanitizeDownloadName($audio->title, 'mp3');

        if (Storage::disk('public')->exists($storagePath)) {
            return Storage::disk('public')->download($storagePath, $filename);
        }

        return response()->download($publicPath, $filename);
    }

    public function document(Request $request, Book $document): Response
    {
        $storagePath = (string) $document->file_path;
        $publicPath = public_path('storage/'.$storagePath);

        if ($storagePath === '' || (!Storage::disk('public')->exists($storagePath) && !is_file($publicPath))) {
            abort(404);
        }

        $this->recordDownload('book', $document->id, $request->ip());
        $this->recordContentEvent($request, $document->getMorphClass(), $document->id, 'download');
        $document->increment('download_count');

        $filename = $this->sanitizeDownloadName($document->title, 'pdf');

        if (Storage::disk('public')->exists($storagePath)) {
            return Storage::disk('public')->download($storagePath, $filename);
        }

        return response()->download($publicPath, $filename);
    }

    public function audiobookPart(Request $request, AudiobookPart $part): Response
    {
        $storagePath = (string) $part->audio_file;
        $publicPath = public_path('storage/'.$storagePath);

        if (!$part->is_published || $storagePath === '' || (!Storage::disk('public')->exists($storagePath) && !is_file($publicPath))) {
            abort(404);
        }

        $extension = pathinfo((string) $part->audio_file, PATHINFO_EXTENSION);
        if ($extension === '') {
            $extension = 'mp3';
        }

        $this->recordDownload('audiobook_part', $part->id, $request->ip());
        $this->recordContentEvent($request, $part->getMorphClass(), $part->id, 'download');

        if ($part->audiobook) {
            $part->audiobook->increment('download_count');
        }

        $filename = $this->sanitizeDownloadName($part->title, $extension);

        if (Storage::disk('public')->exists($storagePath)) {
            return Storage::disk('public')->download($storagePath, $filename);
        }

        return response()->download($publicPath, $filename);
    }

    public function videoView(Video $video)
    {
        $video->increment('view_count');

        return response()->noContent();
    }

    public function trackVideo(Request $request, Video $video)
    {
        $event = $request->input('event');
        if (!in_array($event, ['play', 'youtube_click', 'impression', 'watch', 'share'], true)) {
            return response()->json(['message' => 'Invalid event'], 422);
        }

        $deviceType = $request->input('device_type');
        if (!in_array($deviceType, ['mobile', 'desktop', 'tablet', 'unknown'], true)) {
            $deviceType = 'unknown';
        }

        $screenWidth = $request->input('screen_width');
        $screenWidth = is_numeric($screenWidth) ? (int) $screenWidth : null;

        $screenHeight = $request->input('screen_height');
        $screenHeight = is_numeric($screenHeight) ? (int) $screenHeight : null;

        $deviceHash = $this->deviceHash($request);

        if ($event === 'play') {
            $alreadyViewed = VideoEvent::query()
                ->where('video_id', $video->id)
                ->where('event_type', 'play')
                ->where('device_hash', $deviceHash)
                ->exists();

            if (!$alreadyViewed) {
                $video->increment('view_count');
            }
        }

        $watchSeconds = $request->input('watch_seconds');
        $watchSeconds = is_numeric($watchSeconds) ? (int) $watchSeconds : null;
        if ($watchSeconds !== null && $watchSeconds < 0) {
            $watchSeconds = null;
        }

        $geo = $this->geoPayload($request);

        VideoEvent::create(array_merge([
            'video_id' => $video->id,
            'event_type' => $event,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'page_url' => $request->input('page_url'),
            'session_id' => $request->session()->getId(),
            'visitor_id' => $request->input('visitor_id'),
            'device_type' => $deviceType,
            'screen_width' => $screenWidth,
            'screen_height' => $screenHeight,
            'timezone' => $request->input('timezone'),
            'language' => $request->input('language'),
            'platform' => $request->input('platform'),
            'device_hash' => $deviceHash,
            'watch_seconds' => $watchSeconds,
            'share_channel' => $request->input('share_channel'),
        ], $geo));

        return response()->noContent();
    }

    private function recordDownload(string $type, int $id, ?string $ip): void
    {
        DownloadsLog::create([
            'item_type' => $type,
            'item_id' => $id,
            'ip_address' => $ip,
            'downloaded_at' => now(),
        ]);
    }

    private function sanitizeDownloadName(string $title, string $extension): string
    {
        $name = preg_replace('/[^A-Za-z0-9\-\s]/', '', $title);
        $name = trim(preg_replace('/\s+/', ' ', $name));
        $name = $name !== '' ? $name : 'download';

        return $name.'.'.$extension;
    }

    private function recordContentEvent(Request $request, string $contentType, int $contentId, string $eventType): void
    {
        $geo = $this->geoPayload($request);

        \App\Models\ContentEvent::create(array_merge([
            'content_type' => $contentType,
            'content_id' => $contentId,
            'event_type' => $eventType,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'page_url' => $request->fullUrl(),
            'session_id' => $request->session()->getId(),
            'visitor_id' => $request->input('visitor_id'),
            'device_hash' => $this->deviceHash($request),
        ], $geo));
    }

    private function deviceHash(Request $request): string
    {
        $parts = [
            (string) $request->ip(),
            (string) $request->userAgent(),
            (string) $request->input('platform'),
            (string) $request->input('screen_width'),
            (string) $request->input('screen_height'),
            (string) $request->input('language'),
            (string) $request->input('timezone'),
        ];

        return hash('sha256', implode('|', $parts));
    }

    private function geoPayload(Request $request): array
    {
        return app(GeoIpService::class)->lookup($request->ip());
    }
}








