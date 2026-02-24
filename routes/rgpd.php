<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    // RGPD Contacts Management
    Route::get('/contacts', function () {
        return view('rgpd.contacts');
    })->name('contacts.index');

    // RGPD Templates Management
    Route::get('/plantillas', function () {
        return view('rgpd.plantillas');
    })->name('plantillas.index');

    // Mail Accounts Management
    Route::get('/mail-accounts', function () {
        return view('rgpd.mail-accounts');
    })->name('mail-accounts.index');

    // Inbox
    Route::get('/inbox', function () {
        return view('rgpd.inbox');
    })->name('inbox');

    // RGPD Firmas Management
    Route::get('/firmas', function () {
        return view('rgpd.firmas');
    })->name('firmas.index');
});
