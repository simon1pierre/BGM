<?php

namespace App\Services\Translation;

class NullTranslator implements TranslatorInterface
{
    public function translate(string $text, string $sourceLocale, string $targetLocale): TranslationResult
    {
        // No external provider configured: keep text and force human review.
        return new TranslationResult($text, 0.25);
    }
}









