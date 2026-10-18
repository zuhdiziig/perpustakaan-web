@extends('layouts.petugas')

@section('title', 'Pemeriksaan Kondisi Eksemplar Fisik - BOOKNEST')

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
        color: var(--theme-accent, #0f766e);
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
        color: #0f172a;
        margin: 0 0 6px 0;
        letter-spacing: -0.6px;
    }

    .page-subtitle {
        font-size: 13.5px;
        color: #64748b;
        margin: 0;
    }

    /* STATS GRID */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-val {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
    }

    /* FILTER BAR */
    .filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .filter-input-wrap {
        flex: 1;
        min-width: 220px;
        position: relative;
    }

    .filter-input {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13.5px;
        color: #0f172a;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.15s;
    }

    .filter-input:focus {
        border-color: var(--theme-accent, #0f766e);
    }

    .filter-icon {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .filter-select {
        padding: 9px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13px;
        color: #334155;
        background: #ffffff;
        outline: none;
        cursor: pointer;
    }

    .filter-select:focus {
        border-color: var(--theme-accent, #0f766e);
    }

    .btn-filter {
        background: var(--theme-btn-primary-bg, #0f766e);
        color: #ffffff;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s;
    }

    .btn-filter:hover {
        background: var(--theme-btn-primary-hover, #115e59);
    }

    .btn-reset {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        padding: 9px 12px;
    }

    .btn-reset:hover {
        color: #0f172a;
    }

    /* TABLE CARD */
    .table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        width: 100%;
        min-width: 860px;
        border-collapse: collapse;
        font-size: 13.5px;
    }

    th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        text-align: left;
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
        font-size: 12.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 13px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover td {
        background: #f8fafc;
    }

    /* BADGES */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 9px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-baik { background: #dcfce7; color: #166534; }
    .badge-rusak { background: #fef3c7; color: #92400e; }
    .badge-hilang { background: #fee2e2; color: #991b1b; }

    .badge-rak { background: #e0f2fe; color: #0369a1; }
    .badge-gudang { background: #f3e8ff; color: #7e22ce; }

    .badge-status-tersedia { background: #d1fae5; color: #065f46; }
    .badge-status-dipinjam { background: #ffedd5; color: #9a3412; }
    .badge-status-dibooking { background: #ede9fe; color: #5b21b6; }
    .badge-status-hilang { background: #fee2e2; color: #991b1b; }

    .eks-badge {
        background: #f1f5f9;
        color: #0f172a;
        font-weight: 800;
        font-family: monospace;
        padding: 3px 7px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        font-size: 12px;
    }

    .btn-action {
        background: var(--theme-btn-primary-bg, #0f766e);
        color: #ffffff;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s;
    }

    .btn-action:hover {
        background: var(--theme-btn-primary-hover, #115e59);
        color: #ffffff;
    }

    /* ALERTS */
    .alert-biaya {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-left: 5px solid #ef4444;
        padding: 14px 18px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-size: 13.5px;
        color: #991b1b;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .alert-aman {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-left: 5px solid #22c55e;
        padding: 14px 18px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-size: 13.5px;
        color: #166534;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
</style>
@endsection

@section('content')
<div class="kondisi-page">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                <span>Inventaris Fisik & Sirkulasi</span>
            </div>
            <h1 class="page-title">Pemeriksaan Kondisi Eksemplar Fisik</h1>
            <p class="page-subtitle">Kontrol kondisi fisik tiap kopi buku individual (Baik, Rusak, Hilang), isolasi gudang, dan kalkulasi ganti rugi.</p>
        </div>
    </div>

    <!-- FLASH NOTIFICATIONS -->
    @if (session('success'))
        @if (session('biayaKerusakan') > 0)
            <div class="alert-biaya">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                <div>
                    <strong>Status Kondisi: {{ session('kondisi') }}</strong> &mdash; Eksemplar #{{ session('nomorEksemplar') }} buku <em>"{{ session('judulBuku') }}"</em> berhasil diperbarui.<br>
                    <span>Lokasi fisik dialihkan ke: <strong>{{ session('lokasi', 'Gudang') }}</strong>. Estimasi biaya penggantian inventaris: <strong>Rp {{ number_format(session('biayaKerusakan'), 0, ',', '.') }}</strong> (Seharga nilai buku).</span>
                </div>
            </div>
        @else
            <div class="alert-aman">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <div>
                    <strong>Status Kondisi: Baik</strong> &mdash; Eksemplar #{{ session('nomorEksemplar') }} buku <em>"{{ session('judulBuku') }}"</em> siap disirkulasikan.<br>
                    <span>Fisik buku terawat dengan baik. Tidak ada biaya kerusakan inventaris.</span>
                </div>
            </div>
        @endif
    @endif

    <!-- STATS CARDS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #e0f2fe; color: #0284c7;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            </div>
            <div>
                <div class="stat-val">{{ number_format($stats['total'] ?? 0) }}</div>
                <div class="stat-label">Total Eksemplar</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #dcfce7; color: #16a34a;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div>
                <div class="stat-val">{{ number_format($stats['baik'] ?? 0) }}</div>
                <div class="stat-label">Kondisi Baik</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </div>
            <div>
                <div class="stat-val">{{ number_format($stats['rusak'] ?? 0) }}</div>
                <div class="stat-label">Kondisi Rusak (Gudang)</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </div>
            <div>
                <div class="stat-val">{{ number_format($stats['hilang'] ?? 0) }}</div>
                <div class="stat-label">Kondisi Hilang</div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH -->
    <div class="filter-card">
        <form action="{{ route('kondisi.index') }}" method="GET" class="filter-form">
            <div class="filter-input-wrap">
                <svg class="filter-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari judul, penulis, barcode, atau token..." class="filter-input">
            </div>

            <select name="kondisi" class="filter-select">
                <option value="">Semua Kondisi</option>
                <option value="Baik" {{ ($statusFilter ?? '') === 'Baik' ? 'selected' : '' }}>Kondisi: Baik</option>
                <option value="Rusak" {{ ($statusFilter ?? '') === 'Rusak' ? 'selected' : '' }}>Kondisi: Rusak</option>
                <option value="Hilang" {{ ($statusFilter ?? '') === 'Hilang' ? 'selected' : '' }}>Kondisi: Hilang</option>
            </select>

            <select name="lokasi" class="filter-select">
                <option value="">Semua Lokasi</option>
                <option value="Rak" {{ ($lokasiFilter ?? '') === 'Rak' ? 'selected' : '' }}>Lokasi: Rak Display</option>
                <option value="Gudang" {{ ($lokasiFilter ?? '') === 'Gudang' ? 'selected' : '' }}>Lokasi: Gudang Simpan</option>
            </select>

            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="Tersedia" {{ ($statusInventaris ?? '') === 'Tersedia' ? 'selected' : '' }}>Status: Tersedia</option>
                <option value="Dibooking" {{ ($statusInventaris ?? '') === 'Dibooking' ? 'selected' : '' }}>Status: Dibooking</option>
                <option value="Dipinjam" {{ ($statusInventaris ?? '') === 'Dipinjam' ? 'selected' : '' }}>Status: Dipinjam</option>
                <option value="Hilang" {{ ($statusInventaris ?? '') === 'Hilang' ? 'selected' : '' }}>Status: Hilang</option>
            </select>

            <button type="submit" class="btn-filter">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                Terapkan
            </button>

            @if(!empty($search) || !empty($statusFilter) || !empty($lokasiFilter) || !empty($statusInventaris))
                <a href="{{ route('kondisi.index') }}" class="btn-reset">Reset</a>
            @endif
        </form>
    </div>

    <!-- TABLE OF BOOK COPIES -->
    <div class="table-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Eksemplar</th>
                        <th>Judul Buku & Info</th>
                        <th>Barcode / Token</th>
                        <th>Lokasi Fisik</th>
                        <th>Status Sirkulasi</th>
                        <th>Kondisi Fisik</th>
                        <th>Harga Buku</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($eksemplars as $eks)
                        <tr>
                            <td>
                                <span class="eks-badge">Eksemplar #{{ $eks->nomor_eksemplar }}</span>
                            </td>
                            <td>
                                <strong style="color: #0f172a;">{{ $eks->buku->judul ?? '-' }}</strong>
                                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                                    {{ $eks->buku->penulis ?? '-' }} &bull; {{ $eks->buku->kategori->namaKategori ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-family: monospace; font-size: 12px; color: #334155;">
                                    <code>{{ $eks->buku->barcode->kodeBarcode ?? $eks->kode_display }}</code>
                                </div>
                                <div style="font-size: 11px; color: #94a3b8; font-family: monospace;">
                                    {{ Str::limit($eks->qr_token, 16) }}
                                </div>
                            </td>
                            <td>
                                @if($eks->lokasi === 'Gudang')
                                    <span class="badge badge-gudang">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                                        Gudang
                                    </span>
                                @else
                                    <span class="badge badge-rak">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                        Rak Display
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match($eks->status) {
                                        'Tersedia' => 'badge-status-tersedia',
                                        'Dipinjam' => 'badge-status-dipinjam',
                                        'Dibooking' => 'badge-status-dibooking',
                                        default => 'badge-status-hilang'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ $eks->status }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $kondisiClass = match($eks->kondisi) {
                                        'Baik' => 'badge-baik',
                                        'Rusak' => 'badge-rusak',
                                        default => 'badge-hilang'
                                    };
                                @endphp
                                <span class="badge {{ $kondisiClass }}">
                                    {{ $eks->kondisi }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #334155;">
                                    Rp {{ number_format($eks->buku->harga ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('kondisi.edit', $eks->idEksemplar) }}" class="btn-action">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    Periksa Kondisi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px 16px; color: #64748b;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    <span style="font-weight: 600; font-size: 14px;">Tidak ada data eksemplar fisik ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($eksemplars->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9;">
                {{ $eksemplars->links() }}
            </div>
        @endif
    </div>

</div>
@endsection