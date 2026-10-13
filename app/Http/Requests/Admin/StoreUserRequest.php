<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class StoreUserRequest extends FormRequest
{
    /**
     * Lapisan kedua: rute sudah dijaga `can:users.manage`,
     * namun diperiksa ulang di sini agar request ini aman dipakai di tempat lain.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('users.manage') ?? false;
    }

    /**
     * Aturan validasi untuk pengguna baru buatan admin.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],

            // Tanpa `@` (hanya huruf, angka, strip, underscore) dan harus unik
            'username' => [
                'required', 'string', 'min:3', 'max:30', 'alpha_dash:ascii',
                Rule::unique(User::class),
            ],

            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],

            // Password awal ditetapkan admin
            'password' => ['required', 'string', Password::default(), 'confirmed'],

            // Peran harus salah satu peran yang ada di database
            'role' => ['required', Rule::in(Role::pluck('name')->all())],
        ];
    }
}
