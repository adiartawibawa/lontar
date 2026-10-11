<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('/profile', 'Profile/Edit')->name('profile.edit');
});
