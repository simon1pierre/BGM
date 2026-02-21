<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\ContentEvent;
use App\Models\audio;
use App\Models\book;
use App\Services\GeoIpService;
use Illuminate\Http\Request;

class PublicContentEngagementController extends Controller
{
    public function trackAudio(Request $request, audio $audio)
    {
        $event = $request->input('event');
        if (!in_array($event, ['view', 'play', 'watch', 'share', 'download'], true)) {
            return response()->json(['message' => 'Invalid event'], 422);
        }

        $validated = $request->validate([
            'visitor_id' => ['nullable', 'string', 'max:80'],
            'page_url' => ['nullable', 'string', 'max:500'],
            'timezone' => ['nullable', 'string', 'max:60'],
            'language' => ['nullable', 'string', 'max:20'],
            'platform' => ['nullable', 'string', 'max:60'],
            'device_type' => ['nullable', 'in:mobile,desktop,tablet,unknown'],
            'screen_width' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'screen_height' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'watch_seconds' => ['nullable', 'integer', 'min:0', 'max:86400'],
            'share_channel' => ['nullable', 'string', 'max:30'],
        ]);

        $deviceHash = $this->deviceHash($request);

        if ($event === 'play') {
            $audio->increment('play_count');
        }

        $watchSeconds = $validated['watch_seconds'] ?? null;

        $geo = $this->geoPayload($request);

        ContentEvent::create(array_merge([
            'content_type' => $audio->getMorphClass(),
            'content_id' => $audio->id,
            'event_type' => $event,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'page_url' => $validated['page_url'] ?? null,
            'session_id' => $request->session()->getId(),
            'visitor_id' => $validated['visitor_id'] ?? null,
            'device_type' => $validated['device_type'] ?? $this->deviceType($request),
            'screen_width' => $validated['screen_width'] ?? null,
            'screen_height' => $validated['screen_height'] ?? null,
            'timezone' => $validated['timezone'] ?? null,
            'language' => $validated['language'] ?? null,
            'platform' => $validated['platform'] ?? null,
            'device_hash' => $deviceHash,
            'watch_seconds' => $watchSeconds,
            'share_channel' => $validated['share_channel'] ?? null,
        ], $geo));

        return response()->noContent();
    }

    public function trackBook(Request $request, book $book)
    {
        $event = $request->input('event');
        if (!in_array($event, ['view', 'read', 'share', 'download', 'open_reader', 'read_aloud', 'read_progress'], true)) {
            return response()->json(['message' => 'Invalid event'], 422);
        }

        $validated = $request->validate([
            'visitor_id' => ['nullable', 'string', 'max:80'],
            'reader_session_id' => ['nullable', 'string', 'max:120'],
            'page_url' => ['nullable', 'string', 'max:500'],
            'timezone' => ['nullable', 'string', 'max:60'],
            'language' => ['nullable', 'string', 'max:20'],
            'platform' => ['nullable', 'string', 'max:60'],
            'device_type' => ['nullable', 'in:mobile,desktop,tablet,unknown'],
            'screen_width' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'screen_height' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'watch_seconds' => ['nullable', 'integer', 'min:0', 'max:86400'],
            'share_channel' => ['nullable', 'string', 'max:30'],
            'page_number' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'total_pages' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'progress_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $deviceHash = $this->deviceHash($request);
        $watchSeconds = $validated['watch_seconds'] ?? null;

        $geo = $this->geoPayload($request);

        ContentEvent::create(array_merge([
            'content_type' => $book->getMorphClass(),
            'content_id' => $book->id,
            'event_type' => $event,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'page_url' => $validated['page_url'] ?? null,
            'session_id' => $request->session()->getId(),
            'visitor_id' => $validated['visitor_id'] ?? null,
            'reader_session_id' => $validated['reader_session_id'] ?? null,
            'device_type' => $validated['device_type'] ?? $this->deviceType($request),
            'screen_width' => $validated['screen_width'] ?? null,
            'screen_height' => $validated['screen_height'] ?? null,
            'timezone' => $validated['timezone'] ?? null,
            'language' => $validated['language'] ?? null,
            'platform' => $validated['platform'] ?? null,
            'device_hash' => $deviceHash,
            'watch_seconds' => $watchSeconds,
            'page_number' => $validated['page_number'] ?? null,
            'total_pages' => $validated['total_pages'] ?? null,
            'progress_percent' => isset($validated['progress_percent']) ? round((float) $validated['progress_percent'], 2) : null,
            'share_channel' => $validated['share_channel'] ?? null,
        ], $geo));

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

    private function deviceType(Request $request): string
    {
        $deviceType = $request->input('device_type');
        if (!in_array($deviceType, ['mobile', 'desktop', 'tablet', 'unknown'], true)) {
            $deviceType = 'unknown';
        }

        return $deviceType;
    }

    private function geoPayload(Request $request): array
    {
        return app(GeoIpService::class)->lookup($request->ip());
    }
}
