<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Users Management
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');

    // Roles Management
    Route::get('/roles', function () {
        return view('admin.roles.index');
    })->name('roles.index');

    // Permissions Management
    Route::get('/permissions', function () {
        return view('admin.permissions.index');
    })->name('permissions.index');

    // Mail Accounts Management
    Route::get('/mail-accounts', function () {
        return view('admin.mail-accounts.index');
    })->name('mail-accounts.index');
});
