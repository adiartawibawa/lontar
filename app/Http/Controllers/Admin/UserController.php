<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Daftar pengguna dengan pencarian dan halaman (15 per halaman).
     */
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));

        $users = User::query()
            ->with('roles') // muat peran sekaligus agar tidak N+1
            ->when($search !== '', function (Builder $query) use ($search) {
                // LOWER(...) LIKE dipilih agar pencarian tidak peka huruf besar-kecil
                // di semua jenis database (SQLite, MySQL, PostgreSQL).
                $term = '%'.mb_strtolower($search).'%';

                $query->where(function (Builder $q) use ($term) {
                    $q->whereRaw('LOWER(username) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(first_name) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(last_name) LIKE ?', [$term]);
                });
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(15)
            ->withQueryString() // pencarian ikut terbawa saat pindah halaman
            ->through(fn (User $user) => $this->toRow($user));

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => ['search' => $search],
        ]);
    }

    /**
     * Menonaktifkan akun. Admin tidak boleh menonaktifkan akunnya sendiri.
     */
    public function deactivate(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            throw ValidationException::withMessages([
                'user' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.',
            ]);
        }

        $user->forceFill(['deactivated_at' => now()])->save();

        return back()->with('success', "Akun {$user->username} dinonaktifkan.");
    }

    /**
     * Mengaktifkan kembali akun yang sebelumnya dinonaktifkan.
     */
    public function activate(User $user): RedirectResponse
    {
        $user->forceFill(['deactivated_at' => null])->save();

        return back()->with('success', "Akun {$user->username} diaktifkan kembali.");
    }

    /**
     * Mengubah model User menjadi data ringkas untuk dikirim ke halaman Vue.
     * Hanya kolom yang dibutuhkan UI (tidak pernah mengirim password dan sejenisnya).
     *
     * @return array<string, mixed>
     */
    private function toRow(User $user): array
    {
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->roles->first()?->name,
            'is_active' => ! $user->isDeactivated(),
            'email_verified' => $user->hasVerifiedEmail(),
            'created_at' => $user->created_at?->toDateString(),
        ];
    }
}
