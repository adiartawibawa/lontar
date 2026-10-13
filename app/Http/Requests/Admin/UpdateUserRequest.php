<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UpdateUserRequest extends FormRequest
{
    /**
     * Lapisan kedua pemeriksaan hak akses (lihat StoreUserRequest).
     */
    public function authorize(): bool
    {
        return $this->user()?->can('users.manage') ?? false;
    }

    /**
     * Aturan validasi untuk mengubah pengguna.
     * Username dan email unik, kecuali milik pengguna yang sedang diubah.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],

            'username' => [
                'required', 'string', 'min:3', 'max:30', 'alpha_dash:ascii',
                Rule::unique(User::class)->ignore($user),
            ],

            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique(User::class)->ignore($user),
            ],

            // Password hanya diganti jika diisi
            'password' => ['nullable', 'string', Password::default(), 'confirmed'],

            'role' => ['required', Rule::in(Role::pluck('name')->all())],
        ];
    }
}
