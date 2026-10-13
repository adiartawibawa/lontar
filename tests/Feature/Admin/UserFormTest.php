<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Data valid yang dipakai ulang; `$override` mengubah sebagian isinya
function dataPengguna(array $override = []): array
{
    return array_merge([
        'first_name' => 'Rina',
        'last_name' => 'Wijaya',
        'username' => 'rina_wijaya',
        'email' => 'baru@contoh.test',
        'role' => 'user',
        'password' => 'password-kuat-123',
        'password_confirmation' => 'password-kuat-123',
    ], $override);
}

// Data tanpa penggantian password, untuk test ubah pengguna
function tanpaPassword(array $override = []): array
{
    return dataPengguna(array_merge(['password' => '', 'password_confirmation' => ''], $override));
}

// ----- Tambah pengguna -----

test('halaman tambah pengguna dapat dibuka admin', function () {
    $this->actingAs(buatAdmin())->get('/admin/users/create')->assertOk();
});

// Jalur utama: pengguna dibuat, terverifikasi, dan berperan sesuai pilihan
test('admin dapat membuat pengguna baru', function () {
    $this->actingAs(buatAdmin())->post('/admin/users', dataPengguna())
        ->assertRedirect('/admin/users');

    $user = User::where('email', 'baru@contoh.test')->first();

    expect($user)->not->toBeNull();
    expect($user->hasVerifiedEmail())->toBeTrue();
    expect($user->hasRole('user'))->toBeTrue();
    expect(Hash::check('password-kuat-123', $user->password))->toBeTrue();
});

test('pengguna biasa tidak dapat membuat pengguna', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)->post('/admin/users', dataPengguna())->assertForbidden();

    expect(User::where('email', 'baru@contoh.test')->exists())->toBeFalse();
});

test('pembuatan pengguna menolak username dengan @', function () {
    $this->actingAs(buatAdmin())
        ->post('/admin/users', dataPengguna(['username' => 'rina@wijaya']))
        ->assertSessionHasErrors('username');
});

test('pembuatan pengguna menolak email yang sudah terdaftar', function () {
    $ada = User::factory()->create();

    $this->actingAs(buatAdmin())
        ->post('/admin/users', dataPengguna(['email' => $ada->email]))
        ->assertSessionHasErrors('email');
});

test('pembuatan pengguna menolak peran yang tidak ada', function () {
    $this->actingAs(buatAdmin())
        ->post('/admin/users', dataPengguna(['role' => 'superuser']))
        ->assertSessionHasErrors('role');
});

// ----- Ubah pengguna -----

test('halaman ubah pengguna dapat dibuka admin', function () {
    $target = User::factory()->create()->assignRole('user');

    $this->actingAs(buatAdmin())->get("/admin/users/{$target->id}/edit")->assertOk();
});

test('admin dapat mengubah data dan peran pengguna', function () {
    $target = User::factory()->create()->assignRole('user');

    $this->actingAs(buatAdmin())
        ->put("/admin/users/{$target->id}", tanpaPassword(['role' => 'admin']))
        ->assertSessionHasNoErrors()
        ->assertRedirect('/admin/users');

    $target = $target->fresh();

    expect($target->username)->toBe('rina_wijaya');
    expect($target->hasRole('admin'))->toBeTrue();
    expect($target->hasRole('user'))->toBeFalse();
});

// Password dikosongkan = tidak berubah
test('password tidak berubah jika dikosongkan saat ubah pengguna', function () {
    $target = User::factory()->create()->assignRole('user');
    $passwordLama = $target->password;

    $this->actingAs(buatAdmin())->put("/admin/users/{$target->id}", tanpaPassword());

    expect($target->fresh()->password)->toBe($passwordLama);
});

test('admin dapat mengganti password pengguna', function () {
    $target = User::factory()->create()->assignRole('user');

    $this->actingAs(buatAdmin())->put("/admin/users/{$target->id}", dataPengguna())
        ->assertSessionHasNoErrors();

    expect(Hash::check('password-kuat-123', $target->fresh()->password))->toBeTrue();
});

// Username milik sendiri tidak dianggap duplikat
test('pengguna dapat disimpan dengan username dan email miliknya sendiri', function () {
    $target = User::factory()->create()->assignRole('user');

    $this->actingAs(buatAdmin())
        ->put("/admin/users/{$target->id}", tanpaPassword([
            'username' => $target->username,
            'email' => $target->email,
        ]))
        ->assertSessionHasNoErrors();
});

test('ubah pengguna menolak username milik pengguna lain', function () {
    $lain = User::factory()->create();
    $target = User::factory()->create()->assignRole('user');

    $this->actingAs(buatAdmin())
        ->put("/admin/users/{$target->id}", tanpaPassword(['username' => $lain->username]))
        ->assertSessionHasErrors('username');
});

// ----- Aturan admin terakhir -----

test('admin aktif terakhir tidak dapat diturunkan perannya', function () {
    $admin = buatAdmin();

    $this->actingAs($admin)
        ->put("/admin/users/{$admin->id}", tanpaPassword(['role' => 'user']))
        ->assertSessionHasErrors('role');

    expect($admin->fresh()->hasRole('admin'))->toBeTrue();
});

test('admin dapat menurunkan admin lain', function () {
    $admin = buatAdmin();
    $lain = buatAdmin();

    $this->actingAs($admin)
        ->put("/admin/users/{$lain->id}", tanpaPassword(['role' => 'user']))
        ->assertSessionHasNoErrors();

    expect($lain->fresh()->hasRole('user'))->toBeTrue();
});

// Admin yang sudah nonaktif tidak dihitung sebagai admin aktif
test('admin nonaktif tidak dihitung sebagai admin aktif', function () {
    $admin = buatAdmin();
    $nonaktif = User::factory()->deactivated()->create()->assignRole('admin');

    expect($admin->isLastActiveAdmin())->toBeTrue();
    expect($nonaktif->isLastActiveAdmin())->toBeFalse();
});
