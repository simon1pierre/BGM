<?php

namespace App\Models\Concerns;

use App\Models\ContentTranslation;

trait HasTranslations
{
    protected array $translationCache = [];

    public function translations()
    {
        return $this->morphMany(ContentTranslation::class, 'content');
    }

    public function translationFor(?string $locale = null): ?ContentTranslation
    {
        $locale = $locale ?: app()->getLocale();
        if (isset($this->translationCache[$locale])) {
            return $this->translationCache[$locale];
        }

        $translation = $this->translations()
            ->where('locale', $locale)
            ->first();

        $this->translationCache[$locale] = $translation;
        return $translation;
    }

    protected function translatedValue(string $field, $fallback)
    {
        $translation = $this->translationFor();
        if ($translation && filled($translation->{$field})) {
            return $translation->{$field};
        }

        return $fallback;
    }
}








