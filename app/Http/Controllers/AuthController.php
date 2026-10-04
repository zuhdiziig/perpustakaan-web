<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Tampilkan form registrasi member
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses pendaftaran member (Validasi -> Simpan -> Redirect)
    public function register(Request $request)
    {
        // 1. Validasi data member
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'noTelepon' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'noTelepon.required' => 'Nomor telepon wajib diisi.',
            'alamat.required' => 'Alamat domisili wajib diisi.',
        ]);

        // Generate token unik QR untuk member (contoh: MBR-UUID)
        $qrToken = 'MBR-' . strtoupper(Str::random(12));

        // 2. Simpan data member ke tabel users
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'member',
            'status' => 'aktif',
            'qr_token' => $qrToken,
            'alamat' => $validated['alamat'],
            'noTelepon' => $validated['noTelepon'],
        ]);

        // 3. Tampilkan pesan berhasil dan arahkan ke halaman login
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan masuk dengan akun baru Anda.');
    }

    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses data login (Validasi & Pembuatan Sesi)
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Cek apakah data valid
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            // Auto-generate QR Token jika akun lama belum memiliki token
            if (empty($user->qr_token)) {
                $user->qr_token = 'MBR-' . strtoupper(Str::random(12));
                $user->save();
            }

            // Redirect sesuai role
            if ($user->role === 'admin' || $user->role === 'petugas') {
                return redirect()->intended('/dashboard');
            }

            return redirect()->intended('/katalog');
        }

        // 3. Jika login gagal
        return back()->withErrors([
            'login_gagal' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil keluar.');
    }
}