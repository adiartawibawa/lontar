<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Halaman depan publik
Route::inertia('/', 'Landing')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('/profile', 'Profile/Edit')->name('profile.edit');

    // Area admin: hanya untuk pengguna dengan permission `users.manage`
    Route::middleware('can:users.manage')->group(function () {
        // Halaman /admin diarahkan ke daftar pengguna
        Route::redirect('/admin', '/admin/users');

        Route::prefix('admin')->name('admin.')->group(function () {
            // index, create, store, edit, update (tanpa show dan destroy)
            Route::resource('users', UserController::class)->except(['show', 'destroy']);

            // Aksi nonaktifkan dan aktifkan kembali akun
            Route::patch('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
            Route::patch('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        });
    });
});
