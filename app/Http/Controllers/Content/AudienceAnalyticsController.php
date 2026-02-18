<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\AudiencePageEvent;
use App\Services\GeoIpService;
use Illuminate\Http\Request;

class AudienceAnalyticsController extends Controller
{
    public function track(Request $request)
    {
        $validated = $request->validate([
            'event_type' => ['required', 'in:page_view,session_start,session_end,scroll_depth,cta_click'],
            'visitor_id' => ['nullable', 'string', 'max:80'],
            'session_id' => ['nullable', 'string', 'max:120'],
            'page_url' => ['nullable', 'string', 'max:500'],
            'route_name' => ['nullable', 'string', 'max:120'],
            'referrer' => ['nullable', 'string', 'max:500'],
            'cta_label' => ['nullable', 'string', 'max:180'],
            'cta_target' => ['nullable', 'string', 'max:500'],
            'scroll_depth' => ['nullable', 'integer', 'min:0', 'max:100'],
            'engaged_seconds' => ['nullable', 'integer', 'min:0', 'max:86400'],
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_medium' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:120'],
            'utm_term' => ['nullable', 'string', 'max:120'],
            'utm_content' => ['nullable', 'string', 'max:120'],
            'device_type' => ['nullable', 'in:mobile,desktop,tablet,unknown'],
            'screen_width' => ['nullable', 'integer', 'min:0'],
            'screen_height' => ['nullable', 'integer', 'min:0'],
            'timezone' => ['nullable', 'string', 'max:60'],
            'language' => ['nullable', 'string', 'max:20'],
            'platform' => ['nullable', 'string', 'max:60'],
        ]);

        $geo = app(GeoIpService::class)->lookup($request->ip());

        AudiencePageEvent::create(array_merge([
            'event_type' => $validated['event_type'],
            'visitor_id' => $validated['visitor_id'] ?? null,
            'session_id' => $validated['session_id'] ?? $request->session()->getId(),
            'device_hash' => $this->deviceHash($request, $validated),
            'route_name' => $validated['route_name'] ?? null,
            'page_url' => $validated['page_url'] ?? $request->fullUrl(),
            'referrer' => $validated['referrer'] ?? $request->headers->get('referer'),
            'cta_label' => $validated['cta_label'] ?? null,
            'cta_target' => $validated['cta_target'] ?? null,
            'scroll_depth' => $validated['scroll_depth'] ?? null,
            'engaged_seconds' => $validated['engaged_seconds'] ?? null,
            'utm_source' => $validated['utm_source'] ?? null,
            'utm_medium' => $validated['utm_medium'] ?? null,
            'utm_campaign' => $validated['utm_campaign'] ?? null,
            'utm_term' => $validated['utm_term'] ?? null,
            'utm_content' => $validated['utm_content'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_type' => $validated['device_type'] ?? 'unknown',
            'screen_width' => $validated['screen_width'] ?? null,
            'screen_height' => $validated['screen_height'] ?? null,
            'timezone' => $validated['timezone'] ?? null,
            'language' => $validated['language'] ?? null,
            'platform' => $validated['platform'] ?? null,
        ], $geo));

        return response()->noContent();
    }

    private function deviceHash(Request $request, array $validated): string
    {
        $parts = [
            (string) ($validated['visitor_id'] ?? ''),
            (string) $request->ip(),
            (string) $request->userAgent(),
            (string) ($validated['platform'] ?? ''),
            (string) ($validated['screen_width'] ?? ''),
            (string) ($validated['screen_height'] ?? ''),
            (string) ($validated['language'] ?? ''),
            (string) ($validated['timezone'] ?? ''),
        ];

        return hash('sha256', implode('|', $parts));
    }
}
