<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('/profile', 'Profile/Edit')->name('profile.edit');

    // Area admin: hanya untuk pengguna dengan permission `users.manage`.
    // Pengguna lain mendapat respons 403 (dilarang).
    Route::middleware('can:users.manage')->group(function () {
        Route::inertia('/admin', 'Admin/Index')->name('admin.index');
    });
});
