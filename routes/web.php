<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Analytics\AnalyticsController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\EmailCampaigns\EmailCampaignController;
use App\Http\Controllers\Admin\Notifications\AdminNotificationController;
use App\Http\Controllers\Admin\Subscribers\SubscriberController;
use App\Http\Controllers\Admin\Settings\SettingsController;
use App\Http\Controllers\Admin\Users\ManageController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\Content\VideoController;
use App\Http\Controllers\Admin\Content\AudioController;
use App\Http\Controllers\Admin\Content\DocumentController;
use App\Http\Controllers\Admin\Content\CategoryController;
use App\Http\Controllers\Admin\Content\ContentNotificationController;
use App\Http\Controllers\Admin\Content\PlaylistController;
use App\Http\Controllers\Admin\Auth\TwoFactorController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Content\ContentDownloadController;
use App\Http\Controllers\Content\ContentEngagementController;
use App\Http\Controllers\Content\PublicContentEngagementController;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function(){
    Route::get('/', 'index')->name('home');
    Route::post('/subscribe', 'subscribe')->name('subscribe');
    Route::get('/videos', 'videos')->name('videos.index');
    Route::get('/books', 'books')->name('books.index');
    Route::get('/books/{book}', 'bookShow')->name('books.show');
    Route::get('/audios', 'audios')->name('audios.index');
    Route::get('/audios/{audio}', 'audioShow')->name('audios.show');
});
Route::controller(VerificationController::class)->group(function () {
    Route::get('/verify', 'show')->name('verify.show');
    Route::post('/verify', 'verify')->name('verify.check');
    Route::post('/verify/resend', 'resend')->name('verify.resend');
});
Route::controller(AdminController::class)->group(function(){
    Route::get('/beacons/dashboard','index')->name('admin.dashboard')->middleware('auth');
    Route::get('/beacons/admin/stats','stats')->name('admin.stats')->middleware('auth');
});
Route::prefix('beacons/admin')->middleware('auth')->name('admin.analytics.')->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('index');
    Route::get('/analytics/events', [AnalyticsController::class, 'events'])->name('events');
    Route::get('/analytics/audiences', [AnalyticsController::class, 'audiences'])->name('audiences');
    Route::get('/analytics/content', [AnalyticsController::class, 'content'])->name('content');
});
Route::controller(ManageController::class)->group(function(){
    Route::get('/beacons/admin/register','index')->name('admin.register')->middleware('auth');
    Route::post('/beacons/admin/register','store')->name('admin.register.store')->middleware('auth');
});
Route::controller(SettingsController::class)->group(function(){
    Route::get('/beacons/admin/settings','edit')->name('admin.settings.edit')->middleware('auth');
    Route::post('/beacons/admin/settings','update')->name('admin.settings.update')->middleware('auth');
    Route::post('/beacons/admin/settings/test-email','testEmail')->name('admin.settings.test-email')->middleware('auth');
});
Route::prefix('beacons/admin')->middleware('auth')->name('admin.campaigns.')->group(function () {
    Route::get('/campaigns', [EmailCampaignController::class, 'index'])->name('index');
    Route::get('/campaigns/create', [EmailCampaignController::class, 'create'])->name('create');
    Route::post('/campaigns', [EmailCampaignController::class, 'store'])->name('store');
    Route::get('/campaigns/{campaign}/edit', [EmailCampaignController::class, 'edit'])->name('edit');
    Route::put('/campaigns/{campaign}', [EmailCampaignController::class, 'update'])->name('update');
    Route::get('/campaigns/{campaign}/preview', [EmailCampaignController::class, 'preview'])->name('preview');
    Route::get('/campaigns/{campaign}/preview/raw', [EmailCampaignController::class, 'previewRaw'])->name('preview.raw');
});
Route::prefix('beacons/admin')->middleware('auth')->group(function () {
    Route::resource('videos', VideoController::class)->except(['show'])->names('admin.videos');
    Route::get('videos/{video}/preview', [VideoController::class, 'preview'])->name('admin.videos.preview');
    Route::post('videos/{video}/restore', [VideoController::class, 'restore'])->name('admin.videos.restore');

    Route::resource('audios', AudioController::class)->except(['show'])->names('admin.audios');
    Route::get('audios/{audio}/preview', [AudioController::class, 'preview'])->name('admin.audios.preview');
    Route::post('audios/{audio}/restore', [AudioController::class, 'restore'])->name('admin.audios.restore');

    Route::resource('documents', DocumentController::class)->except(['show'])->names('admin.documents');
    Route::get('documents/{document}/preview', [DocumentController::class, 'preview'])->name('admin.documents.preview');
    Route::post('documents/{document}/restore', [DocumentController::class, 'restore'])->name('admin.documents.restore');

    Route::resource('categories', CategoryController::class)->names('admin.categories');
    Route::post('categories/{category}/restore', [CategoryController::class, 'restore'])->name('admin.categories.restore');

    Route::get('content-notifications', [ContentNotificationController::class, 'index'])->name('admin.content-notifications.index');
    Route::post('content-notifications/{notification}/resend', [ContentNotificationController::class, 'resend'])->name('admin.content-notifications.resend');

    Route::resource('playlists', PlaylistController::class)->names('admin.playlists');
    Route::post('playlists/{playlist}/restore', [PlaylistController::class, 'restore'])->name('admin.playlists.restore');
});
Route::prefix('beacons/admin')->middleware('auth')->name('admin.subscribers.')->group(function () {
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('index');
    Route::post('/subscribers/{subscriber}/toggle', [SubscriberController::class, 'toggle'])->name('toggle');
    Route::delete('/subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('destroy');
    Route::post('/subscribers/{subscriber}/restore', [SubscriberController::class, 'restore'])->name('restore');
});
Route::prefix('beacons/admin')->middleware('auth')->name('admin.users.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('update');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
    Route::post('/users/{user}/force-logout', [UserController::class, 'forceLogout'])->name('force-logout');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('destroy');
    Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('restore');
});
Route::controller(LoginController::class)->group(function () {
    Route::get('/beacons/admin/login', 'create')->name('admin.login')->middleware('guest');
    Route::post('/beacons/admin/login', 'store')->name('admin.login.store')->middleware('guest');
    Route::post('/beacons/admin/logout', 'destroy')->name('admin.logout')->middleware('auth');
});
Route::controller(TwoFactorController::class)->group(function () {
    Route::get('/beacons/admin/login/verify', 'show')->name('admin.login.verify')->middleware('guest');
    Route::post('/beacons/admin/login/verify', 'verify')->name('admin.login.verify.post')->middleware('guest');
    Route::post('/beacons/admin/login/verify/resend', 'resend')->name('admin.login.verify.resend')->middleware('guest');
});
Route::post('/beacons/admin/notifications/read-all', [AdminNotificationController::class, 'readAll'])
    ->name('admin.notifications.read-all')
    ->middleware('auth');
Route::get('/beacons/admin/notifications/{notification}', [AdminNotificationController::class, 'show'])
    ->name('admin.notifications.show')
    ->middleware('auth');

Route::get('/downloads/audio/{audio}', [ContentDownloadController::class, 'audio'])
    ->name('content.download.audio');
Route::get('/downloads/document/{document}', [ContentDownloadController::class, 'document'])
    ->name('content.download.document');
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
