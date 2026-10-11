<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('user:make-admin {login : Email atau username pengguna}')]
#[Description('Menjadikan pengguna yang sudah terdaftar sebagai admin')]
class MakeAdmin extends Command
{
    /**
     * Mencari pengguna lewat email atau username, lalu mengganti perannya menjadi admin.
     */
    public function handle(): int
    {
        $login = $this->argument('login');

        $user = User::where('email', $login)
            ->orWhere('username', $login)
            ->first();

        if (! $user) {
            $this->error("Pengguna '{$login}' tidak ditemukan.");

            return self::FAILURE;
        }

        // syncRoles mengganti peran lama (`user`) dengan `admin`
        $user->syncRoles(['admin']);

        $this->info("{$user->username} sekarang berperan sebagai admin.");

        return self::SUCCESS;
    }
}
