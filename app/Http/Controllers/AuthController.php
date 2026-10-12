<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
     * Memproses update password baru dengan verifikasi nomor telepon terdaftar
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'noTelepon' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'email.exists' => 'Alamat email tidak terdaftar dalam sistem.',
            'noTelepon.required' => 'Nomor telepon akun wajib diisi untuk verifikasi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Verifikasi kesesuaian nomor telepon yang tersimpan di profil
        $inputPhone = preg_replace('/\D/', '', (string) $request->noTelepon);
        $userPhone = preg_replace('/\D/', '', (string) ($user->noTelepon ?? ''));

        if (empty($userPhone) || $inputPhone !== $userPhone) {
            return back()->withErrors([
                'noTelepon' => 'Nomor telepon tidak cocok dengan data akun terdaftar.',
            ])->withInput($request->only('email', 'noTelepon'));
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui. Silakan masuk.');
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
