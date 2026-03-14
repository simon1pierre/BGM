<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Analytics\AnalyticsController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\EmailCampaigns\EmailCampaignController;
use App\Http\Controllers\Admin\Notifications\AdminNotificationController;
use App\Http\Controllers\Admin\Subscribers\SubscriberController;
use App\Http\Controllers\Admin\Contacts\ContactMessageController;
use App\Http\Controllers\Admin\Events\EventController as AdminEventController;
use App\Http\Controllers\Admin\Settings\SettingsController;
use App\Http\Controllers\Admin\Ministry\MinistryLeaderController;
use App\Http\Controllers\Admin\Trash\TrashController;
use App\Http\Controllers\Admin\Translations\TranslationReviewController;
use App\Http\Controllers\Admin\Users\ManageController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\Content\VideoController;
use App\Http\Controllers\Admin\Content\AudioController;
use App\Http\Controllers\Admin\Content\DocumentController;
use App\Http\Controllers\Admin\Content\AudiobookController;
use App\Http\Controllers\Admin\Content\CategoryController;
use App\Http\Controllers\Admin\Content\ContentNotificationController;
use App\Http\Controllers\Admin\Content\PlaylistController;
use App\Http\Controllers\Admin\Content\VideoSeriesController;
use App\Http\Controllers\Admin\Content\DevotionalController;
use App\Http\Controllers\Admin\Auth\TwoFactorController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Content\ContentDownloadController;
use App\Http\Controllers\Content\ContentEngagementController;
use App\Http\Controllers\Content\PublicContentEngagementController;
use App\Http\Controllers\Content\AudienceAnalyticsController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function(){
    Route::get('/', 'index')->name('home');
    Route::post('/subscribe', 'subscribe')->name('subscribe');
    Route::get('/videos', 'videos')->name('videos.index');
    Route::get('/books', 'books')->name('books.index');
    Route::get('/books/{book}', 'bookShow')->name('books.show');
    Route::get('/books/{book}/reader', 'bookReader')->name('books.reader');
    Route::get('/audios', 'audios')->name('audios.index');
    Route::get('/audios/{audio}', 'audioShow')->name('audios.show');
    Route::get('/audiobooks', 'audiobooks')->name('audiobooks.index');
    Route::get('/audiobooks/{audiobook}', 'audiobookShow')->name('audiobooks.show');
    Route::get('/devotionals', 'devotionals')->name('devotionals.index');
    Route::get('/devotionals/{devotional}', 'devotionalShow')->name('devotionals.show');
    Route::get('/about', 'about')->name('about');
    Route::get('/resources', 'resources')->name('resources');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit')->name('contact.submit');
    Route::get('/events', 'events')->name('events');
    Route::get('/events/{event}', 'eventShow')->name('events.show');
    Route::get('/give', 'give')->name('give');
    Route::get('/privacy', 'privacy')->name('privacy');
    Route::get('/terms', 'terms')->name('terms');
});
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');
Route::controller(VerificationController::class)->group(function () {
    Route::get('/verify', 'show')->name('verify.show');
    Route::post('/verify', 'verify')->name('verify.check');
    Route::post('/verify/resend', 'resend')->name('verify.resend');
});
Route::controller(AdminController::class)->group(function(){
    Route::get('/beacons/dashboard','index')->name('admin.dashboard')->middleware(['auth', 'admin']);
    Route::get('/beacons/admin/stats','stats')->name('admin.stats')->middleware(['auth', 'admin']);
});
Route::prefix('beacons/admin')->middleware(['auth', 'admin'])->name('admin.analytics.')->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('index');
    Route::get('/analytics/events', [AnalyticsController::class, 'events'])->name('events');
    Route::get('/analytics/audiences', [AnalyticsController::class, 'audiences'])->name('audiences');
    Route::get('/analytics/content', [AnalyticsController::class, 'content'])->name('content');
});
Route::prefix('beacons/admin')->middleware(['auth', 'admin'])->name('admin.trash.')->group(function () {
    Route::post('/trash/{module}/{id}/restore', [TrashController::class, 'restore'])->name('restore');
    Route::get('/trash', [TrashController::class, 'index'])->name('index');
    Route::delete('/trash/{module}/{id}/force-delete', [TrashController::class, 'forceDelete'])->name('force-delete');
    Route::post('/trash/bulk/restore', [TrashController::class, 'bulkRestore'])->name('bulk-restore');
    Route::delete('/trash/bulk/force-delete', [TrashController::class, 'bulkForceDelete'])->name('bulk-force-delete');
});
Route::prefix('beacons/admin')->middleware(['auth', 'admin'])->name('admin.translations.')->group(function () {
    Route::get('/translations/review', [TranslationReviewController::class, 'index'])->name('review');
    Route::post('/translations/{translation}/approve', [TranslationReviewController::class, 'approve'])->name('approve');
    Route::post('/translations/{translation}/reject', [TranslationReviewController::class, 'reject'])->name('reject');
    Route::post('/translations/{translation}/manual-save', [TranslationReviewController::class, 'saveManual'])->name('manual-save');
    Route::get('/translations/search', [\App\Http\Controllers\Admin\Translations\TranslationSearchController::class, 'index'])->name('search');
    Route::post('/translations/search/content/{translation}', [\App\Http\Controllers\Admin\Translations\TranslationSearchController::class, 'updateContent'])->name('search.content.update');
    Route::post('/translations/search/settings/{translation}', [\App\Http\Controllers\Admin\Translations\TranslationSearchController::class, 'updateSetting'])->name('search.setting.update');
});
Route::controller(ManageController::class)->group(function(){
    Route::get('/beacons/admin/register','index')->name('admin.register')->middleware(['auth', 'admin']);
    Route::post('/beacons/admin/register','store')->name('admin.register.store')->middleware(['auth', 'admin']);
});
Route::controller(SettingsController::class)->group(function(){
    Route::get('/beacons/admin/settings','edit')->name('admin.settings.edit')->middleware(['auth', 'admin']);
    Route::post('/beacons/admin/settings','update')->name('admin.settings.update')->middleware(['auth', 'admin']);
    Route::post('/beacons/admin/settings/test-email','testEmail')->name('admin.settings.test-email')->middleware(['auth', 'admin']);
});
Route::prefix('beacons/admin')->middleware(['auth', 'admin'])->name('admin.campaigns.')->group(function () {
    Route::get('/campaigns', [EmailCampaignController::class, 'index'])->name('index');
    Route::get('/campaigns/create', [EmailCampaignController::class, 'create'])->name('create');
    Route::post('/campaigns', [EmailCampaignController::class, 'store'])->name('store');
    Route::get('/campaigns/{campaign}/edit', [EmailCampaignController::class, 'edit'])->name('edit');
    Route::put('/campaigns/{campaign}', [EmailCampaignController::class, 'update'])->name('update');
    Route::delete('/campaigns/{campaign}', [EmailCampaignController::class, 'destroy'])->name('destroy');
    Route::post('/campaigns/{campaign}/restore', [EmailCampaignController::class, 'restore'])->name('restore');
    Route::delete('/campaigns/{campaign}/force-delete', [EmailCampaignController::class, 'forceDelete'])->name('force-delete');
    Route::get('/campaigns/{campaign}/preview', [EmailCampaignController::class, 'preview'])->name('preview');
    Route::get('/campaigns/{campaign}/preview/raw', [EmailCampaignController::class, 'previewRaw'])->name('preview.raw');
});
Route::prefix('beacons/admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('videos', VideoController::class)->except(['show'])->names('admin.videos');
    Route::get('videos/{video}/preview', [VideoController::class, 'preview'])->name('admin.videos.preview');
    Route::post('videos/{video}/restore', [VideoController::class, 'restore'])->name('admin.videos.restore');
    Route::delete('videos/{video}/force-delete', [VideoController::class, 'forceDelete'])->name('admin.videos.force-delete');

    Route::resource('audios', AudioController::class)->except(['show'])->names('admin.audios');
    Route::get('audios/{audio}/preview', [AudioController::class, 'preview'])->name('admin.audios.preview');
    Route::post('audios/{audio}/restore', [AudioController::class, 'restore'])->name('admin.audios.restore');
    Route::delete('audios/{audio}/force-delete', [AudioController::class, 'forceDelete'])->name('admin.audios.force-delete');

    Route::resource('audiobooks', AudiobookController::class)->except(['show'])->names('admin.audiobooks');
    Route::get('audiobooks/{audiobook}/preview', [AudiobookController::class, 'preview'])->name('admin.audiobooks.preview');
    Route::get('audiobooks/{audiobook}/parts', [AudiobookController::class, 'parts'])->name('admin.audiobooks.parts');
    Route::post('audiobooks/{audiobook}/parts', [AudiobookController::class, 'addPart'])->name('admin.audiobooks.parts.store');
    Route::put('audiobooks/{audiobook}/parts/{part}', [AudiobookController::class, 'updatePart'])->name('admin.audiobooks.parts.update');
    Route::post('audiobooks/{audiobook}/parts/reorder', [AudiobookController::class, 'reorderParts'])->name('admin.audiobooks.parts.reorder');
    Route::delete('audiobooks/{audiobook}/parts', [AudiobookController::class, 'destroyManyParts'])->name('admin.audiobooks.parts.destroy-many');
    Route::delete('audiobooks/{audiobook}/parts/{part}', [AudiobookController::class, 'destroyPart'])->name('admin.audiobooks.parts.destroy');
    Route::post('audiobooks/{audiobook}/restore', [AudiobookController::class, 'restore'])->name('admin.audiobooks.restore');
    Route::delete('audiobooks/{audiobook}/force-delete', [AudiobookController::class, 'forceDelete'])->name('admin.audiobooks.force-delete');

    Route::resource('documents', DocumentController::class)->except(['show'])->names('admin.documents');
    Route::get('documents/{document}/preview', [DocumentController::class, 'preview'])->name('admin.documents.preview');
    Route::post('documents/{document}/audiobooks', [AudiobookController::class, 'storeForBook'])->name('admin.documents.audiobooks.store');
    Route::get('documents/{document}/audiobook-parts', [AudiobookController::class, 'showPartsForBook'])->name('admin.documents.audiobook-parts');
    Route::post('documents/{document}/audiobook-parts', [AudiobookController::class, 'storePartsForBook'])->name('admin.documents.audiobook-parts.store');
    Route::post('documents/{document}/restore', [DocumentController::class, 'restore'])->name('admin.documents.restore');
    Route::delete('documents/{document}/force-delete', [DocumentController::class, 'forceDelete'])->name('admin.documents.force-delete');

    Route::resource('categories', CategoryController::class)->names('admin.categories');
    Route::post('categories/{category}/restore', [CategoryController::class, 'restore'])->name('admin.categories.restore');
    Route::delete('categories/{category}/force-delete', [CategoryController::class, 'forceDelete'])->name('admin.categories.force-delete');

    Route::get('content-notifications', [ContentNotificationController::class, 'index'])->name('admin.content-notifications.index');
    Route::post('content-notifications/{notification}/resend', [ContentNotificationController::class, 'resend'])->name('admin.content-notifications.resend');

    Route::resource('playlists', PlaylistController::class)->names('admin.playlists');
    Route::post('playlists/{playlist}/restore', [PlaylistController::class, 'restore'])->name('admin.playlists.restore');
    Route::delete('playlists/{playlist}/force-delete', [PlaylistController::class, 'forceDelete'])->name('admin.playlists.force-delete');

    Route::resource('video-series', VideoSeriesController::class)->except(['show'])->names('admin.video-series');
    Route::post('video-series/{videoSeries}/restore', [VideoSeriesController::class, 'restore'])->name('admin.video-series.restore');
    Route::delete('video-series/{videoSeries}/force-delete', [VideoSeriesController::class, 'forceDelete'])->name('admin.video-series.force-delete');

    Route::resource('devotionals', DevotionalController::class)->names('admin.devotionals');
    Route::post('devotionals/{devotional}/toggle-featured', [DevotionalController::class, 'toggleFeatured'])->name('admin.devotionals.toggle-featured');
    Route::post('devotionals/{devotional}/toggle-published', [DevotionalController::class, 'togglePublished'])->name('admin.devotionals.toggle-published');
    Route::post('devotionals/{devotional}/restore', [DevotionalController::class, 'restore'])->name('admin.devotionals.restore');
    Route::delete('devotionals/{devotional}/force-delete', [DevotionalController::class, 'forceDelete'])->name('admin.devotionals.force-delete');

    Route::resource('ministry-leaders', MinistryLeaderController::class)->except(['show'])->names('admin.ministry-leaders');
    Route::post('ministry-leaders/{ministry_leader}/restore', [MinistryLeaderController::class, 'restore'])->name('admin.ministry-leaders.restore');
    Route::post('ministry-leaders/{ministry_leader}/toggle-active', [MinistryLeaderController::class, 'toggleActive'])->name('admin.ministry-leaders.toggle-active');
    Route::delete('ministry-leaders/{ministry_leader}/force-delete', [MinistryLeaderController::class, 'forceDelete'])->name('admin.ministry-leaders.force-delete');
});
Route::prefix('beacons/admin')->middleware(['auth', 'admin'])->name('admin.subscribers.')->group(function () {
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('index');
    Route::post('/subscribers/{subscriber}/toggle', [SubscriberController::class, 'toggle'])->name('toggle');
    Route::delete('/subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('destroy');
    Route::post('/subscribers/{subscriber}/restore', [SubscriberController::class, 'restore'])->name('restore');
    Route::delete('/subscribers/{subscriber}/force-delete', [SubscriberController::class, 'forceDelete'])->name('force-delete');
});
Route::prefix('beacons/admin')->middleware(['auth', 'admin'])->name('admin.contacts.')->group(function () {
    Route::get('/contacts', [ContactMessageController::class, 'index'])->name('index');
    Route::get('/contacts/{contactMessage}', [ContactMessageController::class, 'show'])->name('show');
    Route::post('/contacts/{contactMessage}/reply', [ContactMessageController::class, 'reply'])->name('reply');
    Route::delete('/contacts/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('destroy');
    Route::post('/contacts/{contactMessage}/restore', [ContactMessageController::class, 'restore'])->name('restore');
    Route::delete('/contacts/{contactMessage}/force-delete', [ContactMessageController::class, 'forceDelete'])->name('force-delete');
});
Route::prefix('beacons/admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('events', AdminEventController::class)->except(['show'])->names('admin.events');
    Route::post('events/{event}/restore', [AdminEventController::class, 'restore'])->name('admin.events.restore');
    Route::post('events/{event}/toggle-published', [AdminEventController::class, 'togglePublished'])->name('admin.events.toggle-published');
    Route::post('events/{event}/toggle-featured', [AdminEventController::class, 'toggleFeatured'])->name('admin.events.toggle-featured');
    Route::delete('events/{event}/force-delete', [AdminEventController::class, 'forceDelete'])->name('admin.events.force-delete');
});
Route::prefix('beacons/admin')->middleware(['auth', 'admin'])->name('admin.users.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('update');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
    Route::post('/users/{user}/force-logout', [UserController::class, 'forceLogout'])->name('force-logout');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('destroy');
    Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('restore');
    Route::delete('/users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('force-delete');
});
Route::controller(LoginController::class)->group(function () {
    Route::get('/beacons/admin/login', 'create')->name('admin.login')->middleware('guest');
    Route::post('/beacons/admin/login', 'store')->name('admin.login.store')->middleware('guest');
    Route::post('/beacons/admin/logout', 'destroy')->name('admin.logout')->middleware(['auth', 'admin']);
});
Route::controller(TwoFactorController::class)->group(function () {
    Route::get('/beacons/admin/login/verify', 'show')->name('admin.login.verify')->middleware('guest');
    Route::post('/beacons/admin/login/verify', 'verify')->name('admin.login.verify.post')->middleware('guest');
    Route::post('/beacons/admin/login/verify/resend', 'resend')->name('admin.login.verify.resend')->middleware('guest');
});
Route::post('/beacons/admin/notifications/read-all', [AdminNotificationController::class, 'readAll'])
    ->name('admin.notifications.read-all')
    ->middleware(['auth', 'admin']);
Route::get('/beacons/admin/notifications/{notification}', [AdminNotificationController::class, 'show'])
    ->name('admin.notifications.show')
    ->middleware(['auth', 'admin']);

Route::get('/downloads/audio/{audio}', [ContentDownloadController::class, 'audio'])
    ->name('content.download.audio');
Route::get('/downloads/document/{document}', [ContentDownloadController::class, 'document'])
    ->name('content.download.document');
Route::get('/downloads/audiobook-part/{part}', [ContentDownloadController::class, 'audiobookPart'])
    ->name('content.download.audiobook-part');
Route::post('/videos/{video}/view', [ContentDownloadController::class, 'videoView'])
    ->name('content.video.view');
Route::post('/videos/{video}/track', [ContentDownloadController::class, 'trackVideo'])
    ->name('content.video.track');
Route::post('/videos/{video}/like', [ContentEngagementController::class, 'likeVideo'])
    ->name('content.video.like');
Route::post('/videos/{video}/comment', [ContentEngagementController::class, 'commentVideo'])
    ->name('content.video.comment');
Route::post('/books/{book}/like', [ContentEngagementController::class, 'likeBook'])
    ->name('content.book.like');
Route::post('/books/{book}/comment', [ContentEngagementController::class, 'commentBook'])
    ->name('content.book.comment');
Route::post('/audios/{audio}/like', [ContentEngagementController::class, 'likeAudio'])
    ->name('content.audio.like');
Route::post('/audios/{audio}/comment', [ContentEngagementController::class, 'commentAudio'])
    ->name('content.audio.comment');
Route::post('/audios/{audio}/track', [PublicContentEngagementController::class, 'trackAudio'])
    ->name('content.audio.track');
Route::post('/books/{book}/track', [PublicContentEngagementController::class, 'trackBook'])
    ->name('content.book.track');
Route::post('/analytics/audience/track', [AudienceAnalyticsController::class, 'track'])
    ->name('content.audience.track');






