@extends('layouts.petugas')

@section('title', 'Periksa Fisik Eksemplar - BOOKNEST')

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
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.5px;
    }

    .edit-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        max-width: 640px;
        margin: 0 auto;
        padding: 24px;
    }

    .book-meta-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 22px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        font-size: 13.5px;
    }

    .meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 6px;
        border-bottom: 1px dashed #e2e8f0;
    }

    .meta-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .meta-label {
        color: #64748b;
        font-weight: 600;
    }

    .meta-val {
        color: #0f172a;
        font-weight: 700;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 7px;
    }

    .form-select, .form-textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13.5px;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.15s;
    }

    .form-select:focus, .form-textarea:focus {
        border-color: #0f766e;
    }

    .form-hint {
        font-size: 12px;
        color: #64748b;
        margin-top: 5px;
    }

    .btn-submit {
        background: #0f766e;
        color: #ffffff;
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 0.15s;
    }

    .btn-submit:hover {
        background: #115e59;
    }

    .btn-cancel {
        display: block;
        text-align: center;
        margin-top: 14px;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-cancel:hover {
        color: #0f172a;
    }
</style>
@endsection

@section('content')
<div class="kondisi-edit-page">

    <div class="page-header">
        <div class="breadcrumb">
            <a href="{{ route('kondisi.index') }}" style="color: inherit; text-decoration: none;">Kondisi Eksemplar</a>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            <span>Pemeriksaan Fisik</span>
        </div>
        <h1 class="page-title">Pemeriksaan Fisik Eksemplar</h1>
    </div>

    <div class="edit-card">
        <!-- DETAIL EKSEMPLAR -->
        <div class="book-meta-box">
            <div class="meta-row">
                <span class="meta-label">Judul Buku</span>
                <span class="meta-val">{{ $eksemplar->buku->judul ?? '-' }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Nomor Eksemplar</span>
                <span class="meta-val">Eksemplar #{{ $eksemplar->nomor_eksemplar }} ({{ $eksemplar->kode_display }})</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Barcode / QR</span>
                <span class="meta-val"><code>{{ $eksemplar->buku->barcode->kodeBarcode ?? '-' }}</code></span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Status Inventaris</span>
                <span class="meta-val">{{ $eksemplar->status }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Nilai Penggantian Buku</span>
                <span class="meta-val" style="color: #0f766e;">Rp {{ number_format($eksemplar->buku->harga ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <form action="{{ route('kondisi.update', $eksemplar->idEksemplar) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Kondisi Fisik Eksemplar</label>
                <select name="kondisi" class="form-select" id="kondisiSelect" required>
                    <option value="Baik" {{ $eksemplar->kondisi === 'Baik' ? 'selected' : '' }}>
                        Baik (Bebas Denda Kerusakan / Siap Dipinjam)
                    </option>
                    <option value="Rusak" {{ $eksemplar->kondisi === 'Rusak' ? 'selected' : '' }}>
                        Rusak (Karantina ke Gudang & Biaya Penggantian 100%)
                    </option>
                    <option value="Hilang" {{ $eksemplar->kondisi === 'Hilang' ? 'selected' : '' }}>
                        Hilang (Nonaktifkan dari Sirkulasi & Denda Penggantian 100%)
                    </option>
                </select>
                <div class="form-hint">Jika diset ke "Rusak", lokasi fisik buku otomatis dialihkan ke Gudang demi menjaga mutu display rak.</div>
            </div>

            <div class="form-group">
                <label class="form-label">Lokasi Fisik Eksemplar</label>
                <select name="lokasi" class="form-select" id="lokasiSelect">
                    <option value="Rak" {{ ($eksemplar->lokasi ?? 'Rak') === 'Rak' ? 'selected' : '' }}>Rak Display</option>
                    <option value="Gudang" {{ ($eksemplar->lokasi ?? 'Rak') === 'Gudang' ? 'selected' : '' }}>Gudang Simpan</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Kerusakan / Pemeriksaan (Opsional)</label>
                <textarea name="catatan" class="form-textarea" rows="3" placeholder="Contoh: Halaman 12-15 sobek atau basah terkena air..."></textarea>
            </div>

            <button type="submit" class="btn-submit">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Simpan & Update Inventaris
            </button>

            <a href="{{ route('kondisi.index') }}" class="btn-cancel">Batal & Kembali</a>
        </form>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kondisiSelect = document.getElementById('kondisiSelect');
        const lokasiSelect = document.getElementById('lokasiSelect');

        kondisiSelect.addEventListener('change', function() {
            if (this.value === 'Rusak') {
                lokasiSelect.value = 'Gudang';
            }
        });
    });
</script>
@endsection