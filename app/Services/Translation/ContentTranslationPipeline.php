<?php

namespace App\Services\Translation;

use App\Models\ContentTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class ContentTranslationPipeline
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly TranslationQualityScorer $scorer
    ) {
    }

    /**
     * Auto-fill missing translations while preserving manual translations.
     */
    public function autoFillMissingTranslations(Model $model, array $fields, ?string $preferredSourceLocale = null): void
    {
        if (!config('translation_pipeline.auto_fill_enabled', true)) {
            return;
        }

        $supported = (array) config('translation_pipeline.supported_locales', ['rw', 'en', 'fr']);
        $preferredSourceLocale = $preferredSourceLocale ?: (string) config('translation_pipeline.primary_locale', 'rw');
        $sourceLocale = $this->resolveSourceLocale($model, $supported, $preferredSourceLocale);

        if (!$sourceLocale) {
            return;
        }

        $source = ContentTranslation::query()
            ->where('content_type', $model->getMorphClass())
            ->where('content_id', $model->id)
            ->where('locale', $sourceLocale)
            ->first();

        if (!$source) {
            return;
        }

        foreach ($supported as $targetLocale) {
            if ($targetLocale === $sourceLocale) {
                continue;
            }

            $existing = ContentTranslation::query()
                ->where('content_type', $model->getMorphClass())
                ->where('content_id', $model->id)
                ->where('locale', $targetLocale)
                ->first();

            if ($existing && $existing->translated_by === 'manual') {
                continue;
            }

            $payload = [];
            foreach ($fields as $field) {
                $sourceText = (string) ($source->{$field} ?? '');
                if ($sourceText === '') {
                    continue;
                }

                if ($this->isBibleLockedText($sourceText)) {
                    // Bible-like text must be manually verified from approved source.
                    continue;
                }

                $glossaryAppliedSource = $this->applyGlossary($sourceText, $sourceLocale, $targetLocale, true);
                $result = $this->translator->translate($glossaryAppliedSource, $sourceLocale, $targetLocale);
                $translated = $this->applyGlossary($result->text, $sourceLocale, $targetLocale, false);
                $glossaryCompliance = $this->glossaryCompliance($glossaryAppliedSource, $translated, $sourceLocale, $targetLocale);
                $score = $this->scorer->score($glossaryAppliedSource, $translated, $result->confidence, $glossaryCompliance);

                $payload[$field] = $translated;
                $payload['_quality_score'] = max($payload['_quality_score'] ?? 0.0, $score);
            }

            if (!$payload) {
                continue;
            }

            $qualityScore = (float) ($payload['_quality_score'] ?? 0.0);
            unset($payload['_quality_score']);
            $threshold = (float) Arr::get(config('translation_pipeline.quality_thresholds'), $targetLocale, 0.85);
            $status = $qualityScore >= $threshold ? 'approved' : 'needs_review';

            ContentTranslation::updateOrCreate(
                [
                    'content_type' => $model->getMorphClass(),
                    'content_id' => $model->id,
                    'locale' => $targetLocale,
                ],
                array_merge($payload, [
                    'source_locale' => $sourceLocale,
                    'translation_status' => $status,
                    'translated_by' => 'system',
                    'quality_score' => round($qualityScore * 100, 2),
                    'is_bible_locked' => false,
                    'reviewed_by' => null,
                    'reviewed_at' => $status === 'approved' ? Carbon::now() : null,
                ])
            );
        }
    }

    private function resolveSourceLocale(Model $model, array $supported, string $preferred): ?string
    {
        $ordered = array_values(array_unique(array_merge([$preferred], $supported)));
        foreach ($ordered as $locale) {
            $translation = ContentTranslation::query()
                ->where('content_type', $model->getMorphClass())
                ->where('content_id', $model->id)
                ->where('locale', $locale)
                ->first();

            if ($translation && (filled($translation->title) || filled($translation->description))) {
                return $locale;
            }
        }

        return null;
    }

    private function isBibleLockedText(string $text): bool
    {
        // Simple scripture reference detector: e.g. John 3:16 / Yohana 3:16 / 1 Petero 2:9
        return (bool) preg_match('/\b(?:[1-3]\s*)?[A-Za-zÀ-ÿ]+\s+\d{1,3}:\d{1,3}\b/u', $text);
    }

    private function applyGlossary(string $text, string $source, string $target, bool $sourceSide): string
    {
        $pairs = (array) Arr::get(config('translation_pipeline.glossary'), "{$source}.{$target}", []);
        if (!$pairs) {
            return $text;
        }

        $result = $text;
        foreach ($pairs as $src => $dst) {
            $search = $sourceSide ? $src : $dst;
            $replace = $sourceSide ? $src : $dst;
            $result = str_ireplace((string) $search, (string) $replace, $result);
        }

        return $result;
    }

    private function glossaryCompliance(string $sourceText, string $translatedText, string $source, string $target): float
    {
        $pairs = (array) Arr::get(config('translation_pipeline.glossary'), "{$source}.{$target}", []);
        if (!$pairs) {
            return 1.0;
        }

        $required = 0;
        $hits = 0;
        foreach ($pairs as $src => $dst) {
            if (stripos($sourceText, (string) $src) === false) {
                continue;
            }
            $required++;
            if (stripos($translatedText, (string) $dst) !== false) {
                $hits++;
            }
        }

        if ($required === 0) {
            return 1.0;
        }

        return $hits / $required;
    }
}









