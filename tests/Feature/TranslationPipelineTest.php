<?php

use App\Models\ContentTranslation;
use App\Models\book;
use App\Services\Translation\ContentTranslationPipeline;
use App\Services\Translation\TranslationResult;
use App\Services\Translation\TranslatorInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeTranslationBook(array $overrides = []): book
{
    return book::create(array_merge([
        'title' => 'Igitabo',
        'description' => 'Ibisobanuro',
        'file_path' => 'content/books/sample.pdf',
        'is_published' => true,
    ], $overrides));
}

test('translation pipeline auto-fills missing locales from rw source', function () {
    config()->set('translation_pipeline.auto_fill_enabled', true);

    $this->app->bind(TranslatorInterface::class, fn () => new class implements TranslatorInterface {
        public function translate(string $text, string $sourceLocale, string $targetLocale): TranslationResult
        {
            return new TranslationResult(strtoupper($targetLocale).': '.$text, 0.95);
        }
    });

    $book = makeTranslationBook();

    ContentTranslation::create([
        'content_type' => $book->getMorphClass(),
        'content_id' => $book->id,
        'locale' => 'rw',
        'source_locale' => 'rw',
        'title' => 'Ubuntu bw Imana',
        'description' => 'Ubutumwa bwiza',
        'translation_status' => 'approved',
        'translated_by' => 'manual',
        'quality_score' => 100,
    ]);

    app(ContentTranslationPipeline::class)->autoFillMissingTranslations($book, ['title', 'description'], 'rw');

    $this->assertDatabaseHas('content_translations', [
        'content_type' => $book->getMorphClass(),
        'content_id' => $book->id,
        'locale' => 'en',
        'translated_by' => 'system',
    ]);

    $this->assertDatabaseHas('content_translations', [
        'content_type' => $book->getMorphClass(),
        'content_id' => $book->id,
        'locale' => 'fr',
        'translated_by' => 'system',
    ]);
});

test('translation pipeline skips bible-like text for machine translation', function () {
    config()->set('translation_pipeline.auto_fill_enabled', true);

    $this->app->bind(TranslatorInterface::class, fn () => new class implements TranslatorInterface {
        public function translate(string $text, string $sourceLocale, string $targetLocale): TranslationResult
        {
            return new TranslationResult('SHOULD_NOT_RUN', 0.95);
        }
    });

    $book = makeTranslationBook(['title' => 'Yohana']);

    ContentTranslation::create([
        'content_type' => $book->getMorphClass(),
        'content_id' => $book->id,
        'locale' => 'rw',
        'source_locale' => 'rw',
        'title' => 'Yohana 3:16',
        'description' => 'Yohana 3:16',
        'translation_status' => 'approved',
        'translated_by' => 'manual',
        'quality_score' => 100,
    ]);

    app(ContentTranslationPipeline::class)->autoFillMissingTranslations($book, ['title', 'description'], 'rw');

    $this->assertDatabaseMissing('content_translations', [
        'content_type' => $book->getMorphClass(),
        'content_id' => $book->id,
        'locale' => 'en',
    ]);
});

