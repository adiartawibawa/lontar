<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

// Data pendaftaran yang valid, dipakai ulang di beberapa test
function dataDaftar(array $override = []): array
{
    return array_merge([
        'first_name' => 'Siti',
        'last_name' => 'Aminah',
        'username' => 'siti_aminah',
        'email' => 'siti@contoh.test',
        'password' => 'password-kuat-123',
        'password_confirmation' => 'password-kuat-123',
    ], $override);
}

test('halaman registrasi dapat ditampilkan', function () {
    $this->get('/register')->assertOk();
});

// Jalur utama: akun dibuat, login otomatis, dan belum terverifikasi
test('pengguna baru dapat mendaftar', function () {
    Notification::fake();

    $this->post('/register', dataDaftar())->assertRedirect(config('fortify.home'));

    $user = User::where('email', 'siti@contoh.test')->first();

    expect($user)->not->toBeNull();
    expect($user->username)->toBe('siti_aminah');
    expect($user->hasVerifiedEmail())->toBeFalse();
    $this->assertAuthenticatedAs($user);

    // Tautan aktivasi harus terkirim ke email pendaftar
    Notification::assertSentTo($user, VerifyEmail::class);
});

// Setelah mendaftar, pengguna belum bisa masuk dashboard sebelum verifikasi.
// Request kedua tidak otomatis membawa sesi dari request pertama,
// jadi pengguna baru diambil dari database lalu dipakai lewat actingAs().
test('pengguna baru diarahkan ke verifikasi saat membuka dashboard', function () {
    Notification::fake();

    $this->post('/register', dataDaftar());

    $user = User::where('email', 'siti@contoh.test')->first();

    $this->actingAs($user)->get('/dashboard')->assertRedirect('/email/verify');
});

test('registrasi menolak username yang mengandung @', function () {
    $this->post('/register', dataDaftar(['username' => 'siti@contoh']))
        ->assertSessionHasErrors('username');

    $this->assertGuest();
});

test('registrasi menolak email yang sudah terdaftar', function () {
    $ada = User::factory()->create();

    $this->post('/register', dataDaftar(['email' => $ada->email]))
        ->assertSessionHasErrors('email');
});

test('registrasi menolak username yang sudah terdaftar', function () {
    $ada = User::factory()->create();

    $this->post('/register', dataDaftar(['username' => $ada->username]))
        ->assertSessionHasErrors('username');
});

test('registrasi menolak konfirmasi password yang tidak cocok', function () {
    $this->post('/register', dataDaftar(['password_confirmation' => 'beda']))
        ->assertSessionHasErrors('password');
});
