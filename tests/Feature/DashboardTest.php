<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

// Tamu tidak boleh melihat dashboard: harus dialihkan ke halaman login
test('tamu diarahkan ke halaman login saat membuka dashboard', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

// Pengguna yang sudah login dapat melihat halaman Dashboard
test('pengguna yang login dapat membuka dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('Dashboard'));
});
