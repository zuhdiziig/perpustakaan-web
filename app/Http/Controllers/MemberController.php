<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    // Buka menu member & tampilkan data terbaru
    public function index(Request $request)
    {
        $query = User::where('role', 'member')->orderBy('name', 'asc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('noTelepon', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['aktif', 'nonaktif'], true)) {
            $query->where('status', $request->status);
        }

        $members = $query->paginate(10)->withQueryString();

        $totalMember = User::where('role', 'member')->count();
        $memberAktif = User::where('role', 'member')->where('status', 'aktif')->count();
        $memberNonaktif = $totalMember - $memberAktif;

        return view('member.index', compact('members', 'totalMember', 'memberAktif', 'memberNonaktif'));
    }

    // Tampilkan form tambah member
    public function create()
    {
        return view('member.create');
    }

    // Validasi & simpan data member baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'noTelepon' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama member wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'noTelepon.required' => 'Nomor telepon wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'member',
            'status' => 'aktif',
            'noTelepon' => $validated['noTelepon'],
            'alamat' => $validated['alamat'],
            'qr_token' => 'usr_'.bin2hex(random_bytes(16)),
        ]);

        return redirect()->route('member.index')->with('success', 'Data member baru berhasil ditambahkan.');
    }

    // Tampilkan form ubah member
    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Hanya Administrator yang berwenang mengubah data anggota.');
        }

        $member = User::where('role', 'member')->findOrFail($id);

        return view('member.edit', compact('member'));
    }

    // Validasi & simpan perubahan data member
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Hanya Administrator yang berwenang mengubah data anggota.');
        }

        $member = User::where('role', 'member')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'password' => ['nullable', 'string', 'min:6'],
            'noTelepon' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:500'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'name.required' => 'Nama member wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'noTelepon.required' => 'Nomor telepon wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);

        $member->name = $validated['name'];
        $member->email = $validated['email'];
        $member->noTelepon = $validated['noTelepon'];
        $member->alamat = $validated['alamat'];
        $member->status = $validated['status'];

        if (! empty($validated['password'])) {
            $member->password = Hash::make($validated['password']);
        }

        $member->save();

        return redirect()->route('member.index')->with('success', 'Perubahan data member berhasil disimpan.');
    }

    // Aksi cepat toggle status: Aktifkan / Nonaktifkan member
    public function toggleStatus($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Hanya Administrator yang berwenang mengubah status anggota.');
        }

        $member = User::where('role', 'member')->findOrFail($id);
        $member->status = ($member->status === 'aktif') ? 'nonaktif' : 'aktif';
        $member->save();

        $pesan = $member->status === 'aktif' ? 'Member berhasil diaktifkan kembali.' : 'Member berhasil dinonaktifkan.';

        return redirect()->route('member.index')->with('success', $pesan);
    }

    // Hapus data akun member secara permanen (Khusus Admin)
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Hanya Administrator yang berwenang menghapus akun anggota.');
        }

        if ((int) $id === (int) auth()->id()) {
            return redirect()->route('member.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $member = User::where('role', 'member')->findOrFail($id);

        // Cek tanggungan peminjaman aktif atau booking
        $jumlahBukuAktif = $member->jumlahBukuAktif();
        if ($jumlahBukuAktif > 0) {
            return redirect()->route('member.index')->with('error', "Tidak dapat menghapus akun {$member->name}. Anggota ini masih memiliki {$jumlahBukuAktif} buku yang sedang dipinjam atau dibooking.");
        }

        // Cek tanggungan denda belum dibayar / lunas
        $memilikiDendaBelumLunas = Denda::where('status', '!=', 'Lunas')
            ->where(function ($query) use ($id) {
                $query->whereHas('pengembalian.peminjaman', function ($q) use ($id) {
                    $q->where('idUserMember', $id);
                })->orWhereHas('details.peminjaman', function ($q) use ($id) {
                    $q->where('idUserMember', $id);
                });
            })
            ->exists();

        if ($memilikiDendaBelumLunas) {
            return redirect()->route('member.index')->with('error', "Tidak dapat menghapus akun {$member->name}. Anggota ini masih memiliki tanggungan denda yang belum lunas.");
        }

        // Hapus foto profil dari storage jika ada
        if ($member->foto && Storage::disk('public')->exists($member->foto)) {
            Storage::disk('public')->delete($member->foto);
        }

        $namaMember = $member->name;
        $member->delete();

        return redirect()->route('member.index')->with('success', "Akun anggota {$namaMember} berhasil dihapus dari sistem.");
    }
}
