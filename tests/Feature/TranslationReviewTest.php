<?php

use App\Models\ContentTranslation;
use App\Models\User;
use App\\Models\\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function makeAdminReviewer(): User
{
    return User::create([
        'first_name' => 'Admin',
        'last_name' => 'Reviewer',
        'user_name' => 'reviewer_'.uniqid(),
        'email' => 'reviewer_'.uniqid().'@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'is_active' => true,
    ]);
}

function makeTranslationBookRecord(): book
{
    return Book::create([
        'title' => 'Translation Book',
        'description' => 'Translation description',
        'file_path' => 'content/books/translation-book.pdf',
        'is_published' => true,
    ]);
}

test('admin can open translation validation review page', function () {
    $admin = makeAdminReviewer();
    $book = makeTranslationBookRecord();

    ContentTranslation::create([
        'content_type' => $book->getMorphClass(),
        'content_id' => $book->id,
        'locale' => 'en',
        'source_locale' => 'rw',
        'title' => 'Auto title',
        'description' => 'Auto description',
        'translation_status' => 'needs_review',
        'translated_by' => 'system',
        'quality_score' => 72.5,
        'is_bible_locked' => false,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.translations.review', ['locale' => 'all']))
        ->assertOk()
        ->assertSeeText('Translations')
        ->assertSeeText('Auto title');
});

test('admin can approve translation from review queue', function () {
    $admin = makeAdminReviewer();
    $book = makeTranslationBookRecord();

    $translation = ContentTranslation::create([
        'content_type' => $book->getMorphClass(),
        'content_id' => $book->id,
        'locale' => 'fr',
        'source_locale' => 'rw',
        'title' => 'Titre auto',
        'description' => 'Description auto',
        'translation_status' => 'needs_review',
        'translated_by' => 'system',
        'quality_score' => 84.0,
        'is_bible_locked' => false,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.translations.approve', $translation))
        ->assertRedirect();

    $this->assertDatabaseHas('content_translations', [
        'id' => $translation->id,
        'translation_status' => 'approved',
        'reviewed_by' => $admin->id,
    ]);
});

