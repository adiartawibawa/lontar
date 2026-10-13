<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

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
     * Halaman formulir pengguna baru.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create', [
            'roles' => Role::orderBy('name')->pluck('name'),
        ]);
    }

    /**
     * Menyimpan pengguna baru buatan admin.
     * Email langsung dianggap terverifikasi karena admin yang menjamin alamatnya.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = new User(Arr::only($data, ['first_name', 'last_name', 'username', 'email']));
        $user->password = Hash::make($data['password']);
        $user->email_verified_at = now();
        $user->save();

        $user->assignRole($data['role']);

        return to_route('admin.users.index')->with('success', "Pengguna {$user->username} dibuat.");
    }

    /**
     * Halaman formulir ubah pengguna.
     */
    public function edit(User $user): Response
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $this->toRow($user),
            'roles' => Role::orderBy('name')->pluck('name'),
        ]);
    }

    /**
     * Menyimpan perubahan data, peran, dan (opsional) password pengguna.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Aturan pengaman: admin aktif terakhir tidak boleh kehilangan peran admin,
        // karena area admin akan terkunci untuk semua orang.
        if ($data['role'] !== 'admin' && $user->isLastActiveAdmin()) {
            throw ValidationException::withMessages([
                'role' => 'Peran tidak dapat diubah karena ini satu-satunya admin aktif.',
            ]);
        }

        $user->fill(Arr::only($data, ['first_name', 'last_name', 'username', 'email']));

        // Password hanya diganti jika kolomnya diisi
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();
        $user->syncRoles([$data['role']]); // mengganti peran lama dengan yang baru

        return to_route('admin.users.index')->with('success', "Pengguna {$user->username} diperbarui.");
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
