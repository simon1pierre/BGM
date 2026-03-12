<?php

namespace App\Services\Translation;

use Illuminate\Support\Facades\Http;

class LibreTranslateTranslator implements TranslatorInterface
{
    public function translate(string $text, string $sourceLocale, string $targetLocale): TranslationResult
    {
        $url = (string) config('translation_pipeline.providers.libretranslate.url');
        if ($url === '') {
            return new TranslationResult($text, 0.25);
        }

        $payload = [
            'q' => $text,
            'source' => $sourceLocale,
            'target' => $targetLocale,
            'format' => 'text',
        ];

        $apiKey = (string) config('translation_pipeline.providers.libretranslate.api_key');
        if ($apiKey !== '') {
            $payload['api_key'] = $apiKey;
        }

        $timeout = (int) config('translation_pipeline.providers.libretranslate.timeout', 15);

        $response = Http::timeout($timeout)->asForm()->post(rtrim($url, '/').'/translate', $payload);
        if (!$response->ok()) {
            return new TranslationResult($text, 0.25);
        }

        $translatedText = (string) ($response->json('translatedText') ?? '');
        if ($translatedText === '') {
            return new TranslationResult($text, 0.25);
        }

        // LibreTranslate does not return confidence; use a conservative baseline.
        return new TranslationResult($translatedText, 0.78);
    }
}









