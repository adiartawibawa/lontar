<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

// Kedua halaman reset password dapat dibuka tamu
test('halaman lupa password dapat ditampilkan', function () {
    $this->get('/forgot-password')->assertOk();
});

// Meminta tautan reset harus mengirim notifikasi ke email pengguna.
// Notification::fake() mencegah email sungguhan terkirim saat test.
test('tautan reset password dikirim ke email pengguna', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email])
        ->assertSessionHasNoErrors();

    Notification::assertSentTo($user, ResetPassword::class);
});

// Alur lengkap: minta tautan, lalu pakai token-nya untuk mengganti password
test('password dapat direset dengan token yang valid', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        // Token diambil dari notifikasi, sama seperti yang ada di tautan email
        $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ])->assertSessionHasNoErrors();

        // Password di database harus sudah berganti
        expect(Hash::check('password-baru-123', $user->fresh()->password))->toBeTrue();

        return true;
    });
});

// Token palsu harus ditolak
test('password tidak dapat direset dengan token yang salah', function () {
    $user = User::factory()->create();

    $this->post('/reset-password', [
        'token' => 'token-palsu',
        'email' => $user->email,
        'password' => 'password-baru-123',
        'password_confirmation' => 'password-baru-123',
    ])->assertSessionHasErrors('email');
});
