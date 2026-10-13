<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

// Daftar menampilkan semua pengguna (admin sendiri + 2 pengguna lain = 3)
test('admin dapat melihat daftar pengguna', function () {
    $admin = buatAdmin();
    User::factory()->count(2)->create();

    $this->actingAs($admin)->get('/admin/users')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Users/Index')
            ->has('users.data', 3));
});

// Pencarian menyaring hasil berdasarkan username
test('daftar pengguna dapat dicari', function () {
    $admin = buatAdmin();
    User::factory()->create(['username' => 'cari_saya']);
    User::factory()->create(['username' => 'yang_lain']);

    $this->actingAs($admin)->get('/admin/users?search=CARI_saya')
        ->assertInertia(fn (Assert $page) => $page->has('users.data', 1));
});

// Jalur utama nonaktif dan aktif
test('admin dapat menonaktifkan dan mengaktifkan kembali pengguna', function () {
    $admin = buatAdmin();
    $target = User::factory()->create();

    $this->actingAs($admin)->patch("/admin/users/{$target->id}/deactivate")
        ->assertSessionHasNoErrors();
    expect($target->fresh()->isDeactivated())->toBeTrue();

    $this->actingAs($admin)->patch("/admin/users/{$target->id}/activate")
        ->assertSessionHasNoErrors();
    expect($target->fresh()->isDeactivated())->toBeFalse();
});

// Aturan pengaman: tidak boleh menonaktifkan diri sendiri
test('admin tidak dapat menonaktifkan akunnya sendiri', function () {
    $admin = buatAdmin();

    $this->actingAs($admin)->patch("/admin/users/{$admin->id}/deactivate")
        ->assertSessionHasErrors('user');

    expect($admin->fresh()->isDeactivated())->toBeFalse();
});

// Pengguna biasa tidak boleh menjalankan aksi admin
test('pengguna biasa tidak dapat menonaktifkan pengguna lain', function () {
    $user = User::factory()->create()->assignRole('user');
    $target = User::factory()->create();

    $this->actingAs($user)->patch("/admin/users/{$target->id}/deactivate")->assertForbidden();

    expect($target->fresh()->isDeactivated())->toBeFalse();
});
