<?php

namespace App\Services\Translation;

class TranslationQualityScorer
{
    public function score(
        string $sourceText,
        string $translatedText,
        float $providerConfidence,
        float $glossaryCompliance
    ): float {
        $sourceLen = mb_strlen(trim($sourceText));
        $targetLen = max(1, mb_strlen(trim($translatedText)));
        $ratio = $sourceLen > 0 ? min($targetLen, $sourceLen) / max($targetLen, $sourceLen) : 0.0;

        // Weighted quality score.
        $score = (0.40 * $providerConfidence) + (0.35 * $glossaryCompliance) + (0.25 * $ratio);

        return max(0.0, min(1.0, round($score, 4)));
    }
}









