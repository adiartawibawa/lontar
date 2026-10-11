<?php

use App\Models\User;

// Tamu diarahkan ke halaman login
test('tamu diarahkan ke login saat membuka area admin', function () {
    $this->get('/admin')->assertRedirect('/login');
});

// Pengguna biasa (peran `user`) tidak boleh masuk
test('pengguna biasa tidak dapat membuka area admin', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)->get('/admin')->assertForbidden();
});

// Pengguna tanpa peran apa pun juga ditolak
test('pengguna tanpa peran tidak dapat membuka area admin', function () {
    $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
});

// Admin boleh masuk
test('admin dapat membuka area admin', function () {
    $admin = User::factory()->create()->assignRole('admin');

    $this->actingAs($admin)->get('/admin')->assertOk();
});

// Admin yang emailnya belum terverifikasi tetap diarahkan ke verifikasi
test('admin belum terverifikasi diarahkan ke halaman verifikasi', function () {
    $admin = User::factory()->unverified()->create()->assignRole('admin');

    $this->actingAs($admin)->get('/admin')->assertRedirect('/email/verify');
});
