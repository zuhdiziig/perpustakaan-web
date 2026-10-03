<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    // Buka menu member & tampilkan data terbaru
    public function index()
    {
        $members = User::where('role', 'member')
            ->latest('id')
            ->paginate(10);

        return view('member.index', compact('members'));
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
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:6'],
            'noTelepon' => ['required', 'string', 'max:20'],
            'alamat'    => ['required', 'string', 'max:500'],
        ], [
            'name.required'      => 'Nama member wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'noTelepon.required' => 'Nomor telepon wajib diisi.',
            'alamat.required'    => 'Alamat wajib diisi.',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'member',
            'status'    => 'aktif',
            'noTelepon' => $validated['noTelepon'],
            'alamat'    => $validated['alamat'],
        ]);

        return redirect()->route('member.index')->with('success', 'Data member baru berhasil ditambahkan.');
    }

    // Tampilkan form ubah member
    public function edit($id)
    {
        $member = User::where('role', 'member')->findOrFail($id);
        return view('member.edit', compact('member'));
    }

    // Validasi & simpan perubahan data member
    public function update(Request $request, $id)
    {
        $member = User::where('role', 'member')->findOrFail($id);

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password'  => ['nullable', 'string', 'min:6'],
            'noTelepon' => ['required', 'string', 'max:20'],
            'alamat'    => ['required', 'string', 'max:500'],
            'status'    => ['required', 'in:aktif,nonaktif'],
        ], [
            'name.required'      => 'Nama member wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'noTelepon.required' => 'Nomor telepon wajib diisi.',
            'alamat.required'    => 'Alamat wajib diisi.',
        ]);

        $member->name = $validated['name'];
        $member->email = $validated['email'];
        $member->noTelepon = $validated['noTelepon'];
        $member->alamat = $validated['alamat'];
        $member->status = $validated['status'];

        if (!empty($validated['password'])) {
            $member->password = Hash::make($validated['password']);
        }

        $member->save();

        return redirect()->route('member.index')->with('success', 'Perubahan data member berhasil disimpan.');
    }

    // Aksi cepat toggle status: Aktifkan / Nonaktifkan member
    public function toggleStatus($id)
    {
        $member = User::where('role', 'member')->findOrFail($id);
        $member->status = ($member->status === 'aktif') ? 'nonaktif' : 'aktif';
        $member->save();

        $pesan = $member->status === 'aktif' ? 'Member berhasil diaktifkan kembali.' : 'Member berhasil dinonaktifkan.';
        return redirect()->route('member.index')->with('success', $pesan);
    }
}