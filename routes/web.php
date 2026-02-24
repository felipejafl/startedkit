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

// Compatibility: respond to accidental GET requests from browser to Boost logs endpoint.
// The Boost package expects POST for browser-logs; some clients may perform a GET
// (e.g., manual navigation). Provide a silent 204 response for GET to avoid MethodNotAllowed.
Route::get('/_boost/browser-logs', function () {
    return response('', 204);
});

require __DIR__.'/settings.php';
