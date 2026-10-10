<?php

use App\Models\User;

// Memastikan halaman login dapat dibuka tamu
test('halaman login dapat ditampilkan', function () {
    $this->get('/login')->assertOk();
});

// Jalur utama: kredensial benar -> masuk dan diarahkan ke beranda
test('pengguna dapat login dengan kredensial yang benar', function () {
    $user = User::factory()->create();

    // Password bawaan factory adalah "password"
    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(config('fortify.home'));

    $this->assertAuthenticatedAs($user);
});

// Jalur gagal: password salah tidak boleh membuat pengguna masuk
test('pengguna tidak dapat login dengan password salah', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'salah',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});
