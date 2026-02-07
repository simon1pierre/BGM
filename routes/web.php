<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Users\ManageController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function(){
    Route::get('/', 'index')->name('home');
});
Route::controller(AdminController::class)->group(function(){
    Route::get('/beacons/dashboard','index')->name('admin.dashboard')->middleware('auth');
});
Route::controller(ManageController::class)->group(function(){
    Route::get('/beacons/admin/register','index')->name('admin.register')->middleware('auth');
    Route::post('/beacons/admin/register','store')->name('admin.register.store')->middleware('auth');
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
