<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\DownloadsLog;
use App\Models\audio;
use App\Models\book;
use App\Models\video;
use App\Models\VideoEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContentDownloadController extends Controller
{
    public function audio(Request $request, audio $audio): StreamedResponse
    {
        if (!Storage::disk('public')->exists($audio->audio_file)) {
            abort(404);
        }

        $this->recordDownload('audio', $audio->id, $request->ip());
        $this->recordContentEvent($request, $audio->getMorphClass(), $audio->id, 'download');
        $audio->increment('download_count');

        return Storage::disk('public')->download(
            $audio->audio_file,
            $this->sanitizeDownloadName($audio->title, 'mp3')
        );
    }

    public function document(Request $request, book $document): StreamedResponse
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404);
        }

        $this->recordDownload('book', $document->id, $request->ip());
        $this->recordContentEvent($request, $document->getMorphClass(), $document->id, 'download');
        $document->increment('download_count');

        return Storage::disk('public')->download(
            $document->file_path,
            $this->sanitizeDownloadName($document->title, 'pdf')
        );
    }

    public function videoView(video $video)
    {
        $video->increment('view_count');

        return response()->noContent();
    }

    public function trackVideo(Request $request, video $video)
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

        VideoEvent::create([
            'video_id' => $video->id,
            'event_type' => $event,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'page_url' => $request->input('page_url'),
            'session_id' => $request->session()->getId(),
            'device_type' => $deviceType,
            'screen_width' => $screenWidth,
            'screen_height' => $screenHeight,
            'timezone' => $request->input('timezone'),
            'language' => $request->input('language'),
            'platform' => $request->input('platform'),
            'device_hash' => $deviceHash,
            'watch_seconds' => $watchSeconds,
            'share_channel' => $request->input('share_channel'),
        ]);

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
        \App\Models\ContentEvent::create([
            'content_type' => $contentType,
            'content_id' => $contentId,
            'event_type' => $eventType,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'page_url' => $request->fullUrl(),
            'session_id' => $request->session()->getId(),
            'device_hash' => $this->deviceHash($request),
        ]);
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
}
