<?php

use App\Models\User;

test('tamu diarahkan ke login saat membuka area admin', function () {
    $this->get('/admin/users')->assertRedirect('/login');
});

test('pengguna biasa tidak dapat membuka area admin', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)->get('/admin/users')->assertForbidden();
});

test('pengguna tanpa peran tidak dapat membuka area admin', function () {
    $this->actingAs(User::factory()->create())->get('/admin/users')->assertForbidden();
});

test('admin dapat membuka daftar pengguna', function () {
    $admin = User::factory()->create()->assignRole('admin');

    $this->actingAs($admin)->get('/admin/users')->assertOk();
});

// /admin hanya pintu masuk yang diarahkan ke daftar pengguna
test('halaman /admin diarahkan ke daftar pengguna', function () {
    $admin = User::factory()->create()->assignRole('admin');

    $this->actingAs($admin)->get('/admin')->assertRedirect('/admin/users');
});

test('admin belum terverifikasi diarahkan ke halaman verifikasi', function () {
    $admin = User::factory()->unverified()->create()->assignRole('admin');

    $this->actingAs($admin)->get('/admin/users')->assertRedirect('/email/verify');
});
