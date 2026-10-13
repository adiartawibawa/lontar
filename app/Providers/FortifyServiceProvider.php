<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // Halaman pendaftaran
        Fortify::registerView(fn () => Inertia::render('Auth/Register'));

        // Fortify memanggil closure ini saat pengguna membuka /login,
        // lalu kita render halaman Vue "Auth/Login" lewat Inertia.
        Fortify::loginView(fn () => Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'canRegister' => Route::has('register'),
            // Status flash (misalnya pesan "password berhasil direset")
            'status' => session('status'),
        ]));

        /**
         * Logika autentikasi: field `login` dicocokkan dengan kolom email
         * ATAU username. Jika pengguna ditemukan dan password cocok,
         * kembalikan pengguna tersebut; jika tidak, kembalikan null (login gagal).
         */
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->input('login'))
                ->orWhere('username', $request->input('login'))
                ->first();

            if ($user && Hash::check($request->input('password'), $user->password)) {
                // Pesan khusus hanya diberikan setelah password benar,
                // sehingga tidak membocorkan status akun kepada orang asing.
                if ($user->isDeactivated()) {
                    throw ValidationException::withMessages([
                        Fortify::username() => 'Akun Anda dinonaktifkan. Hubungi admin.',
                    ]);
                }

                return $user;
            }

            return null;
        });

        // Halaman untuk meminta tautan reset password
        Fortify::requestPasswordResetLinkView(fn () => Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]));

        // Halaman untuk memasukkan password baru (token dan email datang dari tautan di email)
        Fortify::resetPasswordView(fn (Request $request) => Inertia::render('Auth/ResetPassword', [
            'token' => $request->route('token'),
            'email' => $request->query('email'),
        ]));

        // Halaman pemberitahuan: "cek email Anda untuk verifikasi"
        Fortify::verifyEmailView(fn () => Inertia::render('Auth/VerifyEmail', [
            'status' => session('status'),
        ]));

        // Batasi 5 percobaan login per menit untuk kombinasi email + IP,
        // supaya tidak mudah ditebak dengan brute force.
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())).'|'.$request->ip()
            );

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId()).'|'.$request->ip()
            );
        });
    }
}
