<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeoIpService
{
    public function lookup(?string $ip): array
    {
        if (!$ip || $this->isPrivateIp($ip)) {
            return [];
        }

        $cacheKey = 'geoip:' . $ip;
        return Cache::remember($cacheKey, now()->addDay(), function () use ($ip) {
            try {
                $response = Http::timeout(3)->get("https://ipapi.co/{$ip}/json/");
                if (!$response->successful()) {
                    return [];
                }
                $data = $response->json();
                if (!is_array($data) || isset($data['error'])) {
                    return [];
                }

                return [
                    'geo_country' => $data['country_name'] ?? null,
                    'geo_country_code' => $data['country_code'] ?? null,
                    'geo_region' => $data['region'] ?? null,
                    'geo_city' => $data['city'] ?? null,
                    'geo_continent_code' => $data['continent_code'] ?? null,
                    'geo_latitude' => isset($data['latitude']) ? (float) $data['latitude'] : null,
                    'geo_longitude' => isset($data['longitude']) ? (float) $data['longitude'] : null,
                    'geo_timezone' => $data['timezone'] ?? null,
                    'geo_org' => $data['org'] ?? null,
                    'geo_asn' => $data['asn'] ?? null,
                ];
            } catch (\Throwable $e) {
                return [];
            }
        });
    }

    private function isPrivateIp(string $ip): bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }
}


