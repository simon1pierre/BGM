<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\ContentEvent;
use App\Models\audio;
use App\Models\book;
use Illuminate\Http\Request;

class PublicContentEngagementController extends Controller
{
    public function trackAudio(Request $request, audio $audio)
    {
        $event = $request->input('event');
        if (!in_array($event, ['view', 'play', 'watch', 'share', 'download'], true)) {
            return response()->json(['message' => 'Invalid event'], 422);
        }

        $deviceHash = $this->deviceHash($request);

        if ($event === 'play') {
            $audio->increment('play_count');
        }

        $watchSeconds = $request->input('watch_seconds');
        $watchSeconds = is_numeric($watchSeconds) ? (int) $watchSeconds : null;
        if ($watchSeconds !== null && $watchSeconds < 0) {
            $watchSeconds = null;
        }

        ContentEvent::create([
            'content_type' => $audio->getMorphClass(),
            'content_id' => $audio->id,
            'event_type' => $event,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'page_url' => $request->input('page_url'),
            'session_id' => $request->session()->getId(),
            'device_type' => $this->deviceType($request),
            'screen_width' => $this->intOrNull($request->input('screen_width')),
            'screen_height' => $this->intOrNull($request->input('screen_height')),
            'timezone' => $request->input('timezone'),
            'language' => $request->input('language'),
            'platform' => $request->input('platform'),
            'device_hash' => $deviceHash,
            'watch_seconds' => $watchSeconds,
            'share_channel' => $request->input('share_channel'),
        ]);

        return response()->noContent();
    }

    public function trackBook(Request $request, book $book)
    {
        $event = $request->input('event');
        if (!in_array($event, ['view', 'read', 'share', 'download'], true)) {
            return response()->json(['message' => 'Invalid event'], 422);
        }

        $deviceHash = $this->deviceHash($request);

        ContentEvent::create([
            'content_type' => $book->getMorphClass(),
            'content_id' => $book->id,
            'event_type' => $event,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'page_url' => $request->input('page_url'),
            'session_id' => $request->session()->getId(),
            'device_type' => $this->deviceType($request),
            'screen_width' => $this->intOrNull($request->input('screen_width')),
            'screen_height' => $this->intOrNull($request->input('screen_height')),
            'timezone' => $request->input('timezone'),
            'language' => $request->input('language'),
            'platform' => $request->input('platform'),
            'device_hash' => $deviceHash,
            'share_channel' => $request->input('share_channel'),
        ]);

        return response()->noContent();
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

    private function intOrNull($value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }

    private function deviceType(Request $request): string
    {
        $deviceType = $request->input('device_type');
        if (!in_array($deviceType, ['mobile', 'desktop', 'tablet', 'unknown'], true)) {
            $deviceType = 'unknown';
        }

        return $deviceType;
    }
}
