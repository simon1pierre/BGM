<?php

use App\Models\audiobook;
use App\Models\book;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

function makeAdminUser(): User
{
    return User::create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'user_name' => 'admin_'.uniqid(),
        'email' => 'admin_'.uniqid().'@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'is_active' => true,
    ]);
}

function makeBook(array $overrides = []): book
{
    return book::create(array_merge([
        'title' => 'Prayer Book',
        'description' => 'Book description',
        'file_path' => 'content/books/prayer-book.pdf',
        'is_published' => true,
    ], $overrides));
}

function makeAudiobook(array $overrides = []): audiobook
{
    return audiobook::create(array_merge([
        'title' => 'Linked Audio',
        'description' => 'Audio description',
        'audio_file' => 'content/audiobooks/sample.mp3',
        'is_published' => true,
        'featured' => false,
        'recommended' => false,
        'is_prayer_audio' => false,
    ], $overrides));
}

test('admin can create and update audiobook prayer audio field', function () {
    $admin = makeAdminUser();
    $book = makeBook();

    $this->actingAs($admin);

    $storeResponse = $this->post(route('admin.audiobooks.store'), [
        'title' => 'Morning Prayers',
        'description' => 'Prayer audio',
        'title_en' => 'Morning Prayers',
        'title_fr' => 'Prieres du Matin',
        'title_rw' => 'Amasengesho yigitondo',
        'description_en' => 'Prayer audio',
        'description_fr' => 'Audio de priere',
        'description_rw' => 'Audio yo gusenga',
        'audio_file' => UploadedFile::fake()->create('morning.mp3', 100, 'audio/mpeg'),
        'book_id' => $book->id,
        'is_published' => '1',
        'is_prayer_audio' => '1',
    ]);

    $storeResponse->assertRedirect(route('admin.audiobooks.index'));

    $created = audiobook::query()->latest('id')->firstOrFail();
    expect($created->is_prayer_audio)->toBeTrue();

    $updateResponse = $this->put(route('admin.audiobooks.update', $created), [
        'title' => 'Morning Prayers Updated',
        'description' => 'Updated prayer audio',
        'title_en' => 'Morning Prayers Updated',
        'title_fr' => 'Prieres du Matin MAJ',
        'title_rw' => 'Amasengesho yigitondo yavuguruwe',
        'description_en' => 'Updated prayer audio',
        'description_fr' => 'Audio de priere mis a jour',
        'description_rw' => 'Audio yo gusenga yavuguruwe',
        'is_published' => '1',
        'is_prayer_audio' => '0',
    ]);

    $updateResponse->assertRedirect(route('admin.audiobooks.index'));

    expect($created->fresh()->is_prayer_audio)->toBeFalse();
});

test('audiobooks index filters by prayer query', function () {
    makeAudiobook(['title' => 'Prayer Track', 'is_prayer_audio' => true]);
    makeAudiobook(['title' => 'Study Track', 'is_prayer_audio' => false]);

    $this->get(route('audiobooks.index', ['prayer' => '1']))
        ->assertOk()
        ->assertSeeText('Prayer Track')
        ->assertDontSeeText('Study Track');

    $this->get(route('audiobooks.index', ['prayer' => '0']))
        ->assertOk()
        ->assertSeeText('Study Track')
        ->assertDontSeeText('Prayer Track');
});

test('book reader loads only published linked audiobooks and keeps tts controls', function () {
    $book = makeBook(['title' => 'Reader Book']);

    makeAudiobook([
        'title' => 'Published Linked Audio',
        'book_id' => $book->id,
        'is_published' => true,
    ]);

    makeAudiobook([
        'title' => 'Draft Linked Audio',
        'book_id' => $book->id,
        'is_published' => false,
    ]);

    $this->get(route('books.reader', $book))
        ->assertOk()
        ->assertSeeText(__('messages.books.audiobook_while_reading'))
        ->assertSeeText('Published Linked Audio')
        ->assertDontSeeText('Draft Linked Audio')
        ->assertSeeText(__('messages.common.fullscreen'));
});

test('book reader hides audiobook panel when there are no linked audiobooks', function () {
    $book = makeBook(['title' => 'No Audio Book']);

    $this->get(route('books.reader', $book))
        ->assertOk()
        ->assertDontSeeText(__('messages.books.audiobook_while_reading'));
});

test('book details linked audiobook block respects prayer filter', function () {
    $book = makeBook(['title' => 'Detail Book']);

    makeAudiobook([
        'title' => 'Prayer Only Linked',
        'book_id' => $book->id,
        'is_prayer_audio' => true,
    ]);

    makeAudiobook([
        'title' => 'Study Session Linked',
        'book_id' => $book->id,
        'is_prayer_audio' => false,
    ]);

    $this->get(route('books.show', ['book' => $book, 'prayer' => '1']))
        ->assertOk()
        ->assertSeeText('Prayer Only Linked')
        ->assertDontSeeText('Study Session Linked');

    $this->get(route('books.show', ['book' => $book, 'prayer' => '0']))
        ->assertOk()
        ->assertSeeText('Study Session Linked')
        ->assertDontSeeText('Prayer Only Linked');
});
