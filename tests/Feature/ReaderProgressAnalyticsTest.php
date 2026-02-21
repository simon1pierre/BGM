<?php

use App\Models\ContentEvent;
use App\Models\User;
use App\Models\book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function makeAdminForAnalytics(): User
{
    return User::create([
        'first_name' => 'Admin',
        'last_name' => 'Analytics',
        'user_name' => 'admin_'.uniqid(),
        'email' => 'admin_'.uniqid().'@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'is_active' => true,
    ]);
}

function makeTrackableBook(array $overrides = []): book
{
    return book::create(array_merge([
        'title' => 'Tracked Book',
        'description' => 'Tracked description',
        'file_path' => 'content/books/tracked-book.pdf',
        'is_published' => true,
    ], $overrides));
}

test('book read progress tracking stores visitor and progress fields', function () {
    $book = makeTrackableBook();

    $this->postJson(route('content.book.track', $book), [
        'event' => 'read_progress',
        'visitor_id' => 'visitor_123',
        'reader_session_id' => 'session_book_1',
        'page_number' => 4,
        'total_pages' => 20,
        'progress_percent' => 20.0,
        'device_type' => 'desktop',
    ])->assertNoContent();

    $this->assertDatabaseHas('content_events', [
        'content_type' => $book->getMorphClass(),
        'content_id' => $book->id,
        'event_type' => 'read_progress',
        'visitor_id' => 'visitor_123',
        'reader_session_id' => 'session_book_1',
        'page_number' => 4,
        'total_pages' => 20,
        'progress_percent' => 20.00,
    ]);
});

test('admin audiences analytics shows book reading progress table data', function () {
    $admin = makeAdminForAnalytics();
    $book = makeTrackableBook(['title' => 'Prayer Guide']);

    ContentEvent::create([
        'content_type' => $book->getMorphClass(),
        'content_id' => $book->id,
        'event_type' => 'read_progress',
        'visitor_id' => 'visitor_demo',
        'reader_session_id' => 'reader_session_demo',
        'device_hash' => 'hash_demo',
        'progress_percent' => 65.5,
        'page_number' => 13,
        'total_pages' => 20,
        'device_type' => 'desktop',
        'created_at' => now(),
    ]);

    $this->actingAs($admin)
        ->get(route('admin.analytics.audiences'))
        ->assertOk()
        ->assertSeeText('Book Reading Progress by Visitor')
        ->assertSeeText('Prayer Guide')
        ->assertSeeText('65.5%');
});
