<?php

namespace App\Http\Controllers\Concerns;

use App\Models\ContentTranslation;
use App\Services\Translation\ContentTranslationPipeline;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait HandlesTranslations
{
    protected array $supportedLocales = ['en', 'fr', 'rw'];

    protected function syncTranslations(Model $model, Request $request, array $fields): void
    {
        foreach ($this->supportedLocales as $locale) {
            $payload = [];
            foreach ($fields as $field) {
                $value = $request->input("{$field}_{$locale}");
                $payload[$field] = $value;
            }

            $hasAny = collect($payload)->filter(fn ($value) => filled($value))->isNotEmpty();

            if (!$hasAny) {
                ContentTranslation::query()
                    ->where('content_type', $model->getMorphClass())
                    ->where('content_id', $model->id)
                    ->where('locale', $locale)
                    ->delete();
                continue;
            }

            ContentTranslation::updateOrCreate(
                [
                    'content_type' => $model->getMorphClass(),
                    'content_id' => $model->id,
                    'locale' => $locale,
                ],
                array_merge($payload, [
                    'source_locale' => $locale,
                    'translation_status' => 'approved',
                    'translated_by' => 'manual',
                    'quality_score' => 100.0,
                    'is_bible_locked' => false,
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                ])
            );
        }

        app(ContentTranslationPipeline::class)
            ->autoFillMissingTranslations($model, $fields, config('translation_pipeline.primary_locale', 'rw'));
    }

    protected function syncTranslationsMapped(Model $model, Request $request, array $fieldMap): void
    {
        foreach ($this->supportedLocales as $locale) {
            $payload = [];
            foreach ($fieldMap as $inputField => $translationField) {
                $value = $request->input("{$inputField}_{$locale}");
                $payload[$translationField] = $value;
            }

            $hasAny = collect($payload)->filter(fn ($value) => filled($value))->isNotEmpty();

            if (!$hasAny) {
                ContentTranslation::query()
                    ->where('content_type', $model->getMorphClass())
                    ->where('content_id', $model->id)
                    ->where('locale', $locale)
                    ->delete();
                continue;
            }

            ContentTranslation::updateOrCreate(
                [
                    'content_type' => $model->getMorphClass(),
                    'content_id' => $model->id,
                    'locale' => $locale,
                ],
                array_merge($payload, [
                    'source_locale' => $locale,
                    'translation_status' => 'approved',
                    'translated_by' => 'manual',
                    'quality_score' => 100.0,
                    'is_bible_locked' => false,
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                ])
            );
        }

        app(ContentTranslationPipeline::class)
            ->autoFillMissingTranslations($model, array_values($fieldMap), config('translation_pipeline.primary_locale', 'rw'));
    }
}


