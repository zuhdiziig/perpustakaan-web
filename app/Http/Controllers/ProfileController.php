<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil, keanggotaan, dan keamanan akun.
     */
    public function edit(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'batasPinjam' => Peminjaman::BATAS_MAKSIMAL_BUKU,
        ]);
    }

    /**
     * Memperbarui data pribadi anggota (Nama, NIK, Email, Telepon, Alamat, Tanggal Lahir).
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        // Normalisasi tanggal lahir jika diinput dalam format teks
        if ($request->filled('tanggal_lahir')) {
            try {
                $parsedDate = Carbon::parse($request->input('tanggal_lahir'))->toDateString();
                $request->merge(['tanggal_lahir' => $parsedDate]);
            } catch (\Throwable) {
                // Biarkan validasi date bawaan menangani jika gagal diparse
            }
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => [
                'nullable',
                'string',
                'max:25',
                Rule::unique('users', 'nik')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'noTelepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'tanggal_lahir' => ['nullable', 'date'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'nik.unique' => 'NIK ini sudah terdaftar di sistem.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
        ]);

        // Jika NIK sudah ada dan user adalah member biasa, kunci NIK dari pengubahan mandiri
        if (! empty($user->nik) && $user->role === 'member' && ! empty($validated['nik']) && $validated['nik'] !== $user->nik) {
            return redirect()
                ->route('profile.edit')
                ->withErrors(['nik' => 'NIK sudah terkunci. Hubungi petugas untuk memperbarui identitas resmi.'])
                ->withInput();
        }

        $user->update($validated);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Data pribadi berhasil diperbarui.');
    }

    /**
     * Memperbarui password akun anggota.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Mengunggah atau memperbarui foto profil anggota.
     */
    public function updateFoto(Request $request): RedirectResponse|JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'foto.required' => 'Pilih file foto yang akan diunggah.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WebP.',
            'foto.max' => 'Ukuran foto maksimal adalah 2 MB.',
        ]);

        // Hapus file lama jika ada
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $path = $request->file('foto')->store('avatars', 'public');
        $user->update(['foto' => $path]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui.',
                'foto_url' => asset('storage/'.$path),
            ]);
        }

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Menghapus foto profil anggota (kembali ke inisial).
     */
    public function destroyFoto(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->update(['foto' => null]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Foto profil berhasil dihapus, kembali ke inisial nama.');
    }

    /**
     * Memperbarui preferensi notifikasi anggota.
     */
    public function updateNotifikasi(Request $request): RedirectResponse|JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->update([
            'notif_jatuh_tempo' => $request->boolean('notif_jatuh_tempo'),
            'notif_koleksi_baru' => $request->boolean('notif_koleksi_baru'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Preferensi notifikasi berhasil disimpan.',
            ]);
        }

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Preferensi notifikasi berhasil disimpan.');
    }
}
