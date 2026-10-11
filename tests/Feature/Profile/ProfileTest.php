<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Tamu tidak boleh membuka halaman profil
test('tamu diarahkan ke login saat membuka profil', function () {
    $this->get('/profile')->assertRedirect('/login');
});

// Pengguna login dapat membuka halaman profil
test('halaman profil dapat ditampilkan', function () {
    $this->actingAs(User::factory()->create())->get('/profile')->assertOk();
});

// Jalur utama: nama dan username berubah di database
test('profil dapat diperbarui', function () {
    $user = User::factory()->create();

    dump(User::pluck('username', 'id')->all());

    $this->actingAs($user)->put('/user/profile-information', [
        'first_name' => 'Budi',
        'last_name' => 'Santoso',
        'username' => 'budi_santoso',
        'email' => $user->email,
    ])->assertSessionHasNoErrors();

    expect($user->fresh()->first_name)->toBe('Budi');
    expect($user->fresh()->username)->toBe('budi_santoso');
});

// Username dengan `@` ditolak agar tidak bentrok dengan email saat login
test('username tidak boleh mengandung karakter @', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->put('/user/profile-information', [
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'username' => 'budi@contoh',
        'email' => $user->email,
    ])->assertSessionHasErrors('username', null, 'updateProfileInformation');
});

// Username yang sudah dipakai pengguna lain ditolak
test('username harus unik', function () {
    $lain = User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user)->put('/user/profile-information', [
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'username' => $lain->username,
        'email' => $user->email,
    ])->assertSessionHasErrors('username', null, 'updateProfileInformation');
});

// Jalur utama ganti password
test('password dapat diubah', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->put('/user/password', [
        'current_password' => 'password',
        'password' => 'password-baru-123',
        'password_confirmation' => 'password-baru-123',
    ])->assertSessionHasNoErrors();

    expect(Hash::check('password-baru-123', $user->fresh()->password))->toBeTrue();
});

// Password lama yang salah harus ditolak
test('password tidak berubah jika password saat ini salah', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->put('/user/password', [
        'current_password' => 'salah',
        'password' => 'password-baru-123',
        'password_confirmation' => 'password-baru-123',
    ])->assertSessionHasErrors('current_password', null, 'updatePassword');
});
