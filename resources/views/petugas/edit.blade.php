@extends('layouts.admin')

@section('title', 'Ubah Petugas - BOOKNEST')

@section('styles')
<style>
    .page-header {
        margin-bottom: 24px;
    }

    .breadcrumb {
        font-size: 11.5px;
        font-weight: 700;
        color: #0f766e;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .page-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        letter-spacing: -0.5px;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .card-panel {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 26px 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        max-width: 640px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 18px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
    }

    .form-input {
        padding: 11px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13.5px;
        outline: none;
        transition: border-color 0.15s;
    }

    .form-input:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 24px;
    }

    .btn-submit-update {
        padding: 12px 26px;
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-submit-update:hover {
        background: #115e59;
    }

    .btn-cancel {
        padding: 12px 20px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }
</style>
@endsection

@section('content')
<div class="petugas-edit-container">
    <div class="page-header">
        <div class="breadcrumb">
            <a href="{{ route('petugas.index') }}" style="color: inherit; text-decoration: none;">Kelola Petugas</a>
            <span>/</span>
            <span>Ubah Data Petugas</span>
        </div>
        <h1 class="page-title">Ubah Data Petugas Perpustakaan</h1>
    </div>

    <div class="card-panel">
        <form action="{{ route('petugas.update', $petugas->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap <span style="color: #dc2626;">*</span></label>
                <input type="text"
                       id="name"
                       name="name"
                       class="form-input"
                       value="{{ old('name', $petugas->name) }}"
                       required>
                @error('name')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Alamat Email <span style="color: #dc2626;">*</span></label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-input"
                       value="{{ old('email', $petugas->email) }}"
                       required>
                @error('email')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password Baru (Opsional)</label>
                <div style="position: relative;">
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-input"
                           style="width: 100%; box-sizing: border-box; padding-right: 42px;"
                           placeholder="Kosongkan jika tidak ingin mengubah password">
                    <button type="button" onclick="togglePasswordEye('password', this)" title="Lihat/Sembunyikan password" aria-label="Lihat password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 4px; display: flex; align-items: center; justify-content: center;">
                        <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="noTelepon">Nomor Telepon</label>
                <input type="text"
                       id="noTelepon"
                       name="noTelepon"
                       class="form-input"
                       value="{{ old('noTelepon', $petugas->noTelepon) }}">
                @error('noTelepon')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="alamat">Alamat Domisili</label>
                <textarea id="alamat"
                          name="alamat"
                          class="form-input"
                          rows="2">{{ old('alamat', $petugas->alamat) }}</textarea>
                @error('alamat')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit-update">Simpan Perubahan</button>
                <a href="{{ route('petugas.index') }}" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePasswordEye(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        if (input.type === 'password') {
            input.type = 'text';
            if (eyeOpen) eyeOpen.style.display = 'none';
            if (eyeClosed) eyeClosed.style.display = 'block';
        } else {
            input.type = 'password';
            if (eyeOpen) eyeOpen.style.display = 'block';
            if (eyeClosed) eyeClosed.style.display = 'none';
        }
    }
</script>
@endsection