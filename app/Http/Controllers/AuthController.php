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
    public function showLoginForm(Request $request)
    {
        if ($request->has('redirect')) {
            $redirectUrl = $request->query('redirect');
            if ($this->isValidRedirectUrl($redirectUrl)) {
                session(['url.intended' => $redirectUrl]);
            }
        }

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

        $redirectUrl = $request->input('redirect') ?: session('url.intended');

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

            // Jika ada parameter redirect yang valid (misal: halaman buku yang dipilih)
            if ($redirectUrl && $this->isValidRedirectUrl($redirectUrl)) {
                session()->forget('url.intended');

                return redirect()->to($redirectUrl);
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
    public function showRegisterForm(Request $request)
    {
        if ($request->has('redirect')) {
            $redirectUrl = $request->query('redirect');
            if ($this->isValidRedirectUrl($redirectUrl)) {
                session(['url.intended' => $redirectUrl]);
            }
        }

        return view('auth.register');
    }

    /**
     * Memvalidasi apakah URL redirect aman dan berada dalam aplikasi yang sama.
     */
    private function isValidRedirectUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        // Relative path (misal: /katalog/15) dan bukan protocol-relative (//evil.com)
        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return true;
        }

        // Absolute URL yang memiliki host sama dengan host request saat ini
        $parsed = parse_url($url);
        if (isset($parsed['host'])) {
            $currentHost = request()->getHost();

            return strcasecmp($parsed['host'], $currentHost) === 0;
        }

        return false;
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

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'noTelepon' => $validated['noTelepon'],
            'alamat' => $validated['alamat'],
            'password' => Hash::make($validated['password']),
            'role' => 'member',
            'status' => 'aktif',
            'qr_token' => 'usr_'.bin2hex(random_bytes(16)),
        ]);

        if ($request->filled('redirect')) {
            $redirectUrl = $request->input('redirect');
            if ($this->isValidRedirectUrl($redirectUrl)) {
                session(['url.intended' => $redirectUrl]);
            }
        }

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
