<?php

namespace App\Services\Translation;

interface TranslatorInterface
{
    public function translate(string $text, string $sourceLocale, string $targetLocale): TranslationResult;
}

