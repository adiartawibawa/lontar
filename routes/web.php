<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome');

Route::inertia('/dashboard', 'Dashboard')
    ->middleware('auth')
    ->name('dashboard');
