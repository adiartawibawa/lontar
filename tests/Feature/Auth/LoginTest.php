<?php

use App\Models\User;

// Halaman login dapat dibuka tamu
test('halaman login dapat ditampilkan', function () {
    $this->get('/login')->assertOk();
});

// Login memakai email
test('pengguna dapat login dengan email', function () {
    $user = User::factory()->create();

    // Password bawaan factory adalah "password"
    $this->post('/login', [
        'login' => $user->email,
        'password' => 'password',
    ])->assertRedirect(config('fortify.home'));

    $this->assertAuthenticatedAs($user);
});

// Login memakai username
test('pengguna dapat login dengan username', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'login' => $user->username,
        'password' => 'password',
    ])->assertRedirect(config('fortify.home'));

    $this->assertAuthenticatedAs($user);
});

// Password salah tidak boleh membuat pengguna masuk
test('pengguna tidak dapat login dengan password salah', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'login' => $user->email,
        'password' => 'salah',
    ])->assertSessionHasErrors('login');

    $this->assertGuest();
});

// Identitas yang tidak terdaftar juga ditolak
test('pengguna tidak dapat login dengan identitas yang tidak terdaftar', function () {
    $this->post('/login', [
        'login' => 'tidak-ada',
        'password' => 'password',
    ])->assertSessionHasErrors('login');

    $this->assertGuest();
});
