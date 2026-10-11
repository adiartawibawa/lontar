<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

// Pengguna yang emailnya belum diverifikasi harus dialihkan dari dashboard
test('pengguna belum terverifikasi diarahkan ke halaman verifikasi', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->get('/dashboard')->assertRedirect('/email/verify');
});

// Halaman pemberitahuan verifikasi dapat dibuka
test('halaman verifikasi email dapat ditampilkan', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->get('/email/verify')->assertOk();
});

// Tautan bertanda tangan (signed URL) yang valid memverifikasi akun
test('email dapat diverifikasi dengan tautan yang valid', function () {
    $user = User::factory()->unverified()->create();

    // Membuat tautan yang sama dengan yang dikirim lewat email
    $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
        'id' => $user->getKey(),
        'hash' => sha1($user->getEmailForVerification()),
    ]);

    $this->actingAs($user)->get($url)->assertRedirectContains(config('fortify.home'));

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

// Tautan dengan hash yang salah tidak boleh memverifikasi akun
test('email tidak terverifikasi dengan hash yang salah', function () {
    $user = User::factory()->unverified()->create();

    $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
        'id' => $user->getKey(),
        'hash' => sha1('email-lain@contoh.test'),
    ]);

    $this->actingAs($user)->get($url)->assertForbidden();

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

// Kirim ulang tautan verifikasi
test('tautan verifikasi dapat dikirim ulang', function () {
    Notification::fake();
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->post('/email/verification-notification')
        ->assertSessionHas('status', 'verification-link-sent');

    Notification::assertSentTo($user, VerifyEmail::class);
});

// Mengganti email harus mereset status verifikasi dan mengirim tautan baru
test('mengganti email meminta verifikasi ulang', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->actingAs($user)->put('/user/profile-information', [
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'username' => $user->username,
        'email' => 'email-baru@contoh.test',
    ])->assertSessionHasNoErrors();

    expect($user->fresh()->email_verified_at)->toBeNull();
    Notification::assertSentTo($user->fresh(), VerifyEmail::class);
});
