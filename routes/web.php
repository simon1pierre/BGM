<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\EmailCampaigns\EmailCampaignController;
use App\Http\Controllers\Admin\Notifications\AdminNotificationController;
use App\Http\Controllers\Admin\Subscribers\SubscriberController;
use App\Http\Controllers\Admin\Settings\SettingsController;
use App\Http\Controllers\Admin\Users\ManageController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\Auth\TwoFactorController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function(){
    Route::get('/', 'index')->name('home');
    Route::post('/subscribe', 'subscribe')->name('subscribe');
});
Route::controller(VerificationController::class)->group(function () {
    Route::get('/verify', 'show')->name('verify.show');
    Route::post('/verify', 'verify')->name('verify.check');
    Route::post('/verify/resend', 'resend')->name('verify.resend');
});
Route::controller(AdminController::class)->group(function(){
    Route::get('/beacons/dashboard','index')->name('admin.dashboard')->middleware('auth');
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
