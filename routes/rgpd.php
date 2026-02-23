<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    // RGPD Contacts Management
    Route::get('/contacts', function () {
        return view('rgpd.contacts');
    })->name('contacts.index');

    // Mail Accounts Management
    Route::get('/mail-accounts', function () {
        return view('rgpd.mail-accounts');
    })->name('mail-accounts.index');
});
