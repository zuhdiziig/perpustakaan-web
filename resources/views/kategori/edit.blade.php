@extends('layouts.admin')

@section('title', 'Ubah Kategori Buku - BOOKNEST')

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
        padding: 24px 28px;
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
        padding: 11px 24px;
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
        padding: 11px 18px;
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
<div class="kategori-edit-container">
    <div class="page-header">
        <div class="breadcrumb">
            <a href="{{ route('kategori.index') }}" style="color: inherit; text-decoration: none;">Kelola Kategori</a>
            <span>/</span>
            <span>Ubah Kategori</span>
        </div>
        <h1 class="page-title">Ubah Kategori Buku</h1>
    </div>

    <div class="card-panel">
        <form action="{{ route('kategori.update', $kategori->idKategori) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="namaKategori">Nama Kategori <span style="color: #dc2626;">*</span></label>
                <input type="text"
                       id="namaKategori"
                       name="namaKategori"
                       class="form-input"
                       value="{{ old('namaKategori', $kategori->namaKategori) }}"
                       required>
                @error('namaKategori')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="deskripsi">Deskripsi Kategori</label>
                <textarea id="deskripsi"
                          name="deskripsi"
                          class="form-input"
                          rows="3">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                @error('deskripsi')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit-update">Simpan Perubahan</button>
                <a href="{{ route('kategori.index') }}" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection