<?php

namespace App\Services\Translation;

class TranslationResult
{
    public function __construct(
        public readonly string $text,
        public readonly float $confidence
    ) {
    }
}

