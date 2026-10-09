<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Menampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses autentikasi pengguna
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Cek status aktif jika akun adalah member
            if ($user->role === 'member' && $user->status !== 'aktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi petugas.',
                ]);
            }

            // Arahkan ke dashboard utama
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Menampilkan form register
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Memproses pendaftaran member baru
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'noTelepon' => ['required', 'string', 'max:15'],
            'alamat' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Generate nomor anggota berformat AG-2026-xxxxx
        $nextNumber = User::count() + 1;
        $nomorAnggota = 'AG-2026-'.str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'noTelepon' => $validated['noTelepon'],
            'alamat' => $validated['alamat'],
            'password' => Hash::make($validated['password']),
            'role' => 'member',
            'status' => 'aktif',
            'qr_token' => $nomorAnggota,
        ]);

        // Simpan id user ke flash session untuk ditampilkan pada kartu register-success
        return redirect()->route('register.success')->with('registered_user_id', $user->id);
    }

    /**
     * Menampilkan kartu ucapan selamat & nomor anggota setelah registrasi berhasil
     */
    public function registerSuccess()
    {
        $userId = session('registered_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        return view('auth.register-success', compact('user'));
    }

    /**
     * Menampilkan form lupa password
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Menampilkan form untuk menyelesaikan reset password dengan token.
     */
    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('auth.forgot-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Meminta tautan reset melalui Laravel Password Broker.
     */
    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $statusMessage = 'Jika alamat email terdaftar, tautan reset password akan dikirim.';

        if ($this->passwordResetMailIsEnabled()) {
            try {
                Password::sendResetLink(['email' => $validated['email']]);
            } catch (\Throwable $exception) {
                report($exception);
            }
        } else {
            $statusMessage = 'Reset password sementara dinonaktifkan karena pengiriman email belum dikonfigurasi.';
        }

        return redirect()->route('password.request')
            ->with('status', $statusMessage);
    }

    /**
     * Menyimpan password baru hanya setelah broker memverifikasi token reset.
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $status = Password::reset(
            $validated,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password berhasil diperbarui. Silakan masuk.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Tautan reset tidak valid atau telah kedaluwarsa.']);
    }

    private function passwordResetMailIsEnabled(): bool
    {
        $hasUnsafeMailer = function (string $mailerName) use (&$hasUnsafeMailer): bool {
            $mailer = config("mail.mailers.{$mailerName}", []);
            $transport = $mailer['transport'] ?? $mailerName;

            if (in_array($transport, ['log', 'array'], true)) {
                return true;
            }

            foreach ($mailer['mailers'] ?? [] as $fallbackMailer) {
                if ($hasUnsafeMailer($fallbackMailer)) {
                    return true;
                }
            }

            return false;
        };

        return ! $hasUnsafeMailer(config('mail.default'));
    }

    /**
     * Memproses logout sesi pengguna
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
