@extends('layouts.member')

@section('title', 'Edit Profil & Preferensi - BOOKNEST')

@section('styles')
<style>
    .profile-card-wrapper {
        max-width: 680px;
        margin: 40px auto 60px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 36px 32px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-size: 13.5px;
        outline: none;
    }

    .form-control:focus {
        border-color: #2e625a;
    }

    .genre-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 8px;
    }

    .genre-checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12.5px;
        cursor: pointer;
    }

    .btn-save-profile {
        background: #2e625a;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="profile-card-wrapper">
        <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">Profil Anggota</h2>
        <p style="font-size: 13px; color: #64748b; margin-bottom: 28px;">Perbarui data identitas dan preferensi genre bacaan favoritmu.</p>

        @if(session('success'))
            <div style="background: #dcfce7; color: #15803d; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nomor Identitas Anggota (QR Token)</label>
                <input type="text" class="form-control" value="{{ auth()->user()->qr_token ?? ('AG-2026-' . str_pad(auth()->id(), 5, '0', STR_PAD_LEFT)) }}" disabled style="background: #f1f5f9; color: #64748b;">
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="noTelepon" class="form-control" value="{{ old('noTelepon', auth()->user()->noTelepon) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', auth()->user()->alamat) }}</textarea>
            </div>

            <!-- Bagian Genre Favorit -->
            <div class="form-group" id="genre" style="padding-top: 10px; border-top: 1px solid #f1f5f9;">
                <label class="form-label">Genre Favorit</label>
                <span style="font-size: 12px; color: #64748b;">Pilih kategori yang paling kamu sukai untuk mempermudah rekomendasi bacaan:</span>
                <div class="genre-grid">
                    @foreach(['Fiksi', 'Pendidikan', 'Sejarah', 'Teknologi', 'Agama', 'Anak', 'Umum', 'Biografi', 'Sains'] as $genre)
                        <label class="genre-checkbox-label">
                            <input type="checkbox" name="genre_favorit[]" value="{{ $genre }}">
                            <span>{{ $genre }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn-save-profile">Simpan Perubahan Profil</button>
        </form>
    </div>
</div>
@endsection