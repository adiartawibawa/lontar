<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Membuat peran dan permission dasar aplikasi.
     * Aman dijalankan berulang kali (findOrCreate tidak membuat duplikat),
     * jadi bisa dipakai juga saat deploy untuk menambah permission baru.
     */
    public function run(): void
    {
        // Kosongkan cache package agar perubahan langsung terbaca
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar permission aplikasi. Tambahkan di sini setiap ada fitur baru.
        $permissions = [
            'users.manage', // mengelola pengguna (halaman admin)
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate($name);
        }

        // Peran admin: memegang SEMUA permission yang ada
        $admin = Role::findOrCreate('admin');
        $admin->syncPermissions(Permission::all());

        // Peran user: pengguna biasa, belum punya permission khusus
        Role::findOrCreate('user');
    }
}
