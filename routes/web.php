<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth'/* , 'verified' */])
    ->name('dashboard');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(base_path('routes/admin.php'));

// RGPD Routes
Route::prefix('rgpd')->name('rgpd.')->group(base_path('routes/rgpd.php'));

require __DIR__.'/settings.php';
