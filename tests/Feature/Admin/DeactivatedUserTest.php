<?php

use App\Models\User;

// Akun nonaktif tidak boleh login walau password benar
test('pengguna nonaktif tidak dapat login', function () {
    $user = User::factory()->deactivated()->create();

    $this->post('/login', [
        'login' => $user->email,
        'password' => 'password',
    ])->assertSessionHasErrors('login');

    $this->assertGuest();
});

// Sesi yang sedang berjalan harus berakhir saat akun dinonaktifkan
test('pengguna yang dinonaktifkan saat sedang login langsung dikeluarkan', function () {
    $user = User::factory()->deactivated()->create();

    $this->actingAs($user)->get('/dashboard')->assertRedirect('/login');

    $this->assertGuest();
});

// Akun aktif tidak terpengaruh
test('pengguna aktif tetap dapat membuka dashboard', function () {
    $this->actingAs(User::factory()->create())->get('/dashboard')->assertOk();
});
