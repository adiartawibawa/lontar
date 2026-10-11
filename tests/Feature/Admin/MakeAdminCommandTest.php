<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Spatie\Permission\Models\Role;

// Perintah berhasil menjadikan pengguna sebagai admin (dicari lewat email)
test('perintah user:make-admin mempromosikan pengguna dengan email', function () {
    $user = User::factory()->create();

    $this->artisan('user:make-admin', ['login' => $user->email])->assertSuccessful();

    expect($user->fresh()->hasRole('admin'))->toBeTrue();
});

// Pencarian lewat username juga berfungsi
test('perintah user:make-admin mempromosikan pengguna dengan username', function () {
    $user = User::factory()->create();

    $this->artisan('user:make-admin', ['login' => $user->username])->assertSuccessful();

    expect($user->fresh()->hasRole('admin'))->toBeTrue();
});

// Pengguna yang tidak ada harus menghasilkan kegagalan, bukan error
test('perintah user:make-admin gagal untuk pengguna yang tidak ada', function () {
    $this->artisan('user:make-admin', ['login' => 'tidak-ada'])->assertFailed();
});

// Seeder aman dijalankan ulang (dipakai saat deploy)
test('seeder peran tidak membuat duplikat saat dijalankan ulang', function () {
    $this->seed(RolePermissionSeeder::class);

    expect(Role::count())->toBe(2);
});
