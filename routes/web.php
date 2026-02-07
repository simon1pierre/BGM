<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function(){
    Route::get('/', 'index')->name('home');
});
Route::controller(AdminController::class)->group(function(){
    route::get('/beacons/dashboard','index')->name('admin.dashboard');
});