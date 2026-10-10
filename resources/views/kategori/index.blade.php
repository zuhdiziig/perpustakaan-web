@extends('layouts.admin')

@section('title', 'Kelola Kategori Buku - BOOKNEST')

@section('styles')
<style>
    .page-header {
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
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

    .page-subtitle {
        font-size: 13.5px;
        color: var(--text-muted, #64748b);
        max-width: 680px;
    }

    /* GRID STATS */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0fdfa;
        color: #0f766e;
        flex-shrink: 0;
    }

    .stat-meta {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }

    /* CARDS */
    .card-panel {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        margin-bottom: 24px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 16px;
    }

    /* FORM STYLES */
    .form-grid-create {
        display: grid;
        grid-template-columns: 1fr 1.5fr auto;
        gap: 16px;
        align-items: flex-end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-label {
        font-size: 12.5px;
        font-weight: 700;
        color: #475569;
    }

    .form-input {
        padding: 10px 14px;
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

    .btn-create-submit {
        padding: 11px 22px;
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
        white-space: nowrap;
    }

    .btn-create-submit:hover {
        background: #115e59;
    }

    /* FILTER & SEARCH */
    .filter-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 14px 18px;
        margin-bottom: 20px;
    }

    .filter-form {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 240px;
    }

    .search-input-wrap svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input-field {
        width: 100%;
        padding: 9px 14px 9px 40px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
    }

    .search-input-field:focus {
        border-color: #0f766e;
    }

    .btn-filter-submit {
        padding: 9px 18px;
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-filter-reset {
        padding: 9px 14px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    /* TABLE */
    .table-container {
        overflow-x: auto;
    }

    .kategori-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .kategori-table th {
        text-align: left;
        padding: 12px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 700;
        color: #475569;
    }

    .kategori-table td {
        padding: 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .kategori-table tr:hover {
        background: #f8fafc;
    }

    .badge-buku-count {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        background: #f0fdfa;
        color: #0f766e;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .action-group {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
    }

    .btn-edit-action {
        padding: 6px 12px;
        background: #f1f5f9;
        color: #0f766e;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        transition: all 0.15s;
    }

    .btn-edit-action:hover {
        background: #ccfbf1;
        border-color: #99f6e4;
    }

    .btn-delete-action {
        padding: 6px 12px;
        background: #fee2e2;
        color: #dc2626;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #fecaca;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-delete-action:hover {
        background: #dc2626;
        color: #ffffff;
    }

    @media (max-width: 900px) {
        .form-grid-create {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="kategori-container">
    <!-- HEADER -->
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Admin / Master Data
            </div>
            <h1 class="page-title">Kelola Kategori Buku</h1>
            <p class="page-subtitle">
                Atur taksonomi dan klasifikasi koleksi buku perpustakaan untuk memudahkan pencarian katalog anggota.
            </p>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <!-- STATS SUMMARY -->
    @php
        $totalKategori = $kategoris->total();
        $kategoriWithBooks = $kategoris->filter(fn($k) => $k->buku_count > 0)->count();
    @endphp
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $totalKategori }}</span>
                <span class="stat-label">Total Kategori Buku</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #dcfce7; color: #16a34a;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $kategoriWithBooks }}</span>
                <span class="stat-label">Kategori Memiliki Judul</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line></svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ max(0, $totalKategori - $kategoriWithBooks) }}</span>
                <span class="stat-label">Kategori Masih Kosong</span>
            </div>
        </div>
    </div>

    <!-- CARD FORM TAMBAH KATEGORI BARU -->
    <div class="card-panel">
        <h3 class="card-title">+ Tambah Kategori Baru</h3>
        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf
            <div class="form-grid-create">
                <div class="form-group">
                    <label class="form-label" for="namaKategori">Nama Kategori <span style="color: #dc2626;">*</span></label>
                    <input type="text"
                           id="namaKategori"
                           name="namaKategori"
                           class="form-input"
                           placeholder="Contoh: Pemrograman & Komputer"
                           value="{{ old('namaKategori') }}"
                           required>
                    @error('namaKategori')
                        <span style="font-size: 11.5px; color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="deskripsi">Deskripsi Kategori (Opsional)</label>
                    <input type="text"
                           id="deskripsi"
                           name="deskripsi"
                           class="form-input"
                           placeholder="Keterangan singkat tentang lingkup kategori..."
                           value="{{ old('deskripsi') }}">
                    @error('deskripsi')
                        <span style="font-size: 11.5px; color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-create-submit">
                    + Simpan Kategori
                </button>
            </div>
        </form>
    </div>

    <!-- SEARCH BAR -->
    <div class="filter-card">
        <form action="{{ route('kategori.index') }}" method="GET" class="filter-form">
            <div class="search-input-wrap">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text"
                       name="search"
                       class="search-input-field"
                       placeholder="Cari nama atau deskripsi kategori..."
                       value="{{ request('search') }}">
            </div>

            <button type="submit" class="btn-filter-submit">Cari Kategori</button>

            @if(request()->filled('search'))
                <a href="{{ route('kategori.index') }}" class="btn-filter-reset">Reset</a>
            @endif
        </form>
    </div>

    <!-- TABLE CARD -->
    <div class="card-panel">
        <h3 class="card-title">Daftar Kategori Buku ({{ $kategoris->total() }})</h3>
        <div class="table-container">
            <table class="kategori-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th style="text-align: center; width: 140px;">Jumlah Judul</th>
                        <th style="text-align: right; width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategoris as $k)
                        <tr>
                            <td style="color: #94a3b8; font-weight: 700;">
                                {{ ($kategoris->currentPage() - 1) * $kategoris->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <strong style="color: #0f172a; font-size: 14px;">{{ $k->namaKategori }}</strong>
                            </td>
                            <td style="color: #64748b; max-width: 320px;">
                                {{ $k->deskripsi ?: '-' }}
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-buku-count">
                                    📚 {{ $k->buku_count }} Judul
                                </span>
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('kategori.edit', $k->idKategori) }}" class="btn-edit-action" title="Ubah Kategori">
                                        Edit
                                    </a>
                                    <form action="{{ route('kategori.destroy', $k->idKategori) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $k->namaKategori }}?');"
                                          style="display: inline; margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete-action" title="Hapus Kategori">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 36px; color: #94a3b8;">
                                Tidak ada data kategori yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 18px;">
            {{ $kategoris->links() }}
        </div>
    </div>
</div>
@endsection