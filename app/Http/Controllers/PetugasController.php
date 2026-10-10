<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    // Buka menu petugas & tampilkan daftar petugas terbaru
    public function index(Request $request)
    {
        $query = User::where('role', 'petugas');

        if ($request->filled('search')) {
            $search = mb_strtolower(trim($request->search));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(noTelepon) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(alamat) LIKE ?', ["%{$search}%"]);
            });
        }

        $petugas = $query->latest('id')->paginate(10)->withQueryString();

        return view('petugas.index', compact('petugas'));
    }

    // Tampilkan form tambah petugas baru
    public function create()
    {
        return view('petugas.create');
    }

    // Validasi & Simpan data petugas baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'noTelepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama petugas wajib diisi.',
            'email.required' => 'Email petugas wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'petugas',
            'noTelepon' => $validated['noTelepon'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
        ]);

        return redirect()->route('petugas.index')->with('success', 'Akun petugas baru berhasil ditambahkan.');
    }

    // Tampilkan form edit data petugas
    public function edit($id)
    {
        $petugas = User::where('role', 'petugas')->findOrFail($id);

        return view('petugas.edit', compact('petugas'));
    }

    // Validasi & Simpan perubahan data petugas
    public function update(Request $request, $id)
    {
        $petugas = User::where('role', 'petugas')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'password' => ['nullable', 'string', 'min:6'],
            'noTelepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama petugas wajib diisi.',
            'email.required' => 'Email petugas wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 6 karakter jika ingin diubah.',
        ]);

        $petugas->name = $validated['name'];
        $petugas->email = $validated['email'];
        $petugas->noTelepon = $validated['noTelepon'] ?? null;
        $petugas->alamat = $validated['alamat'] ?? null;

        // Perbarui password jika diisi
        if (! empty($validated['password'])) {
            $petugas->password = Hash::make($validated['password']);
        }

        $petugas->save();

        return redirect()->route('petugas.index')->with('success', 'Data petugas berhasil diperbarui.');
    }

    // Hapus data petugas
    public function destroy($id)
    {
        $petugas = User::where('role', 'petugas')->findOrFail($id);
        $petugas->delete();

        return redirect()->route('petugas.index')->with('success', 'Data petugas berhasil dihapus.');
    }
}
