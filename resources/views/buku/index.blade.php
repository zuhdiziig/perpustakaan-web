@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.petugas')

@section('title', 'Koleksi & Inventaris Buku - BOOKNEST')

@section('styles')
<style>
    /* --- BREADCRUMB & HEADER --- */
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

    /* --- STATS SUMMARY GRID --- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
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
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
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

    .stat-icon.primary { background: #f0fdfa; color: #0f766e; }
    .stat-icon.blue { background: #eff6ff; color: #2563eb; }
    .stat-icon.success { background: #dcfce7; color: #166534; }
    .stat-icon.warning { background: #fef3c7; color: #b45309; }

    .stat-meta {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        line-height: 1.1;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted, #64748b);
        margin-top: 2px;
    }

    /* --- TOOLBAR FILTER & SEARCH --- */
    .filter-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .filter-form {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        flex: 1;
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
        min-width: 240px;
        max-width: 380px;
    }

    .search-input-wrapper svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border-radius: 8px;
        border: 1px solid var(--border-color, #e2e8f0);
        font-size: 13px;
        font-family: inherit;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .search-input:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
    }

    .filter-select {
        padding: 9px 12px;
        border-radius: 8px;
        border: 1px solid var(--border-color, #e2e8f0);
        font-size: 13px;
        font-family: inherit;
        background: #ffffff;
        color: #334155;
        outline: none;
        cursor: pointer;
    }

    .filter-select:focus {
        border-color: #0f766e;
    }

    .btn-filter-submit {
        padding: 9px 16px;
        background: #0f766e;
        color: #ffffff;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s;
    }

    .btn-filter-submit:hover {
        background: #115e59;
    }

    .btn-filter-reset {
        padding: 9px 14px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.15s;
    }

    .btn-filter-reset:hover {
        background: #e2e8f0;
    }

    /* --- DATA TABLE CARD --- */
    .table-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .buku-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
        text-align: left;
    }

    .buku-table th {
        background: #f8fafc;
        padding: 13px 18px;
        font-size: 11.5px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-color, #e2e8f0);
    }

    .buku-table td {
        padding: 14px 18px;
        font-size: 13.5px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .buku-table tr:hover td {
        background-color: #f8fafc;
    }

    /* Book Info Cell */
    .book-cell {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .book-thumb {
        width: 42px;
        height: 56px;
        border-radius: 6px;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
        overflow: hidden;
    }

    .book-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-meta-title {
        font-weight: 700;
        color: var(--text-heading, #0f172a);
        font-size: 14px;
        line-height: 1.3;
        margin-bottom: 3px;
    }

    .book-meta-author {
        font-size: 12px;
        color: var(--text-muted, #64748b);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Barcode Tag */
    .barcode-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: monospace;
        font-size: 12px;
        font-weight: 700;
        background: #f1f5f9;
        color: #0f172a;
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }

    /* Category Pill */
    .badge-category {
        display: inline-block;
        padding: 4px 9px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        background: #f0fdfa;
        color: #0f766e;
        border: 1px solid #ccfbf1;
    }

    /* Stock Badge */
    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 12.5px;
        font-weight: 700;
    }

    .stock-badge.available {
        background: #dcfce7;
        color: #166534;
    }

    .stock-badge.empty {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Condition Badge */
    .condition-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .condition-badge.baik { background: #dcfce7; color: #166534; }
    .condition-badge.rusak { background: #fef3c7; color: #b45309; }
    .condition-badge.hilang { background: #fee2e2; color: #991b1b; }

    /* Action Buttons */
    .action-group {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-action-qr {
        background: #0f172a;
        color: #ffffff;
        padding: 6px 11px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.15s;
    }

    .btn-action-qr:hover {
        background: #334155;
    }

    .btn-action-edit {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
        padding: 6px 11px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s;
    }

    .btn-action-edit:hover {
        background: #d97706;
        color: #ffffff;
    }

    .btn-action-delete {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }

    .btn-action-delete:hover {
        background: #dc2626;
        color: #ffffff;
    }

    .btn-create-buku {
        padding: 10px 18px;
        background: #0f766e;
        color: #ffffff;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 4px rgba(15, 118, 110, 0.2);
        transition: background 0.15s, transform 0.1s;
    }

    .btn-create-buku:hover {
        background: #115e59;
        transform: translateY(-1px);
    }

    .badge-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        background: #f0fdfa;
        color: #0f766e;
        border: 1px solid #ccfbf1;
    }

    .flash-alert {
        padding: 14px 18px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .flash-alert.success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .table-footer {
        padding: 16px 20px;
        background: #ffffff;
        border-top: 1px solid var(--border-color, #e2e8f0);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-card {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input-wrapper {
            max-width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="buku-page">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                <span>Inventaris Perpustakaan</span>
            </div>
            <h1 class="page-title">
                @if (auth()->user()->role === 'admin')
                    Kelola Master Data Buku
                @else
                    Koleksi & Inventaris Buku
                @endif
            </h1>
            <p class="page-subtitle">
                @if (auth()->user()->role === 'admin')
                    Kelola koleksi master buku, klasifikasi kategori, stok eksemplar fisik, serta pemeliharaan data buku.
                @else
                    Pantau ketersediaan stok buku secara real-time, lokasi rak, dan kondisi fisik buku perpustakaan.
                @endif
            </p>
        </div>

        <div>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('buku.create') }}" class="btn-create-buku">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Buku Baru
                </a>
            @else
                <div class="badge-role-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                    Mode Petugas: Monitoring Stok Buku
                </div>
            @endif
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    @if (session('success'))
        <div class="flash-alert success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- SUMMARY STATS GRID -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $totalJudul ?? $bukus->total() }}</span>
                <span class="stat-label">Total Judul Buku</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                    <polyline points="2 17 12 22 22 17"></polyline>
                    <polyline points="2 12 12 17 22 12"></polyline>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $totalStok ?? 0 }}</span>
                <span class="stat-label">Total Stok Fisik</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $bukuBaik ?? 0 }}</span>
                <span class="stat-label">Kondisi Baik</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $bukuRusak ?? 0 }}</span>
                <span class="stat-label">Rusak / Hilang</span>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="filter-card">
        <form action="{{ route('buku.index') }}" method="GET" class="filter-form">
            <div class="search-input-wrapper">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text"
                       name="search"
                       class="search-input"
                       placeholder="Cari judul, penulis, penerbit..."
                       value="{{ request('search') }}">
            </div>

            <select name="kategori" class="filter-select">
                <option value="">Semua Kategori</option>
                @if(isset($kategoris))
                    @foreach ($kategoris as $kat)
                        <option value="{{ $kat->idKategori }}" {{ request('kategori') == $kat->idKategori ? 'selected' : '' }}>
                            {{ $kat->namaKategori }}
                        </option>
                    @endforeach
                @endif
            </select>

            <select name="kondisi" class="filter-select">
                <option value="">Semua Kondisi</option>
                <option value="Baik" {{ request('kondisi') === 'Baik' ? 'selected' : '' }}>Kondisi Baik</option>
                <option value="Rusak" {{ request('kondisi') === 'Rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="Hilang" {{ request('kondisi') === 'Hilang' ? 'selected' : '' }}>Hilang</option>
            </select>

            <button type="submit" class="btn-filter-submit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                Filter Buku
            </button>

            @if(request()->filled('search') || request()->filled('kategori') || request()->filled('kondisi'))
                <a href="{{ route('buku.index') }}" class="btn-filter-reset">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- DATA TABLE CARD -->
    <div class="table-card">
        <div class="table-container">
            <table class="buku-table">
                <thead>
                    <tr>
                        <th style="width: 45px;">#</th>
                        <th>Informasi Buku</th>
                        <th>Kategori</th>
                        <th>Stok Fisik</th>
                        <th>Kondisi</th>
                        @if (auth()->user()->role === 'admin')
                            <th style="text-align: right; width: 140px;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bukus as $item)
                        <tr>
                            <td style="color: #94a3b8; font-weight: 700;">
                                {{ ($bukus->currentPage() - 1) * $bukus->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="book-cell">
                                    <div class="book-thumb">
                                        @if ($item->cover)
                                            <img src="{{ asset('storage/' . $item->cover) }}"
                                                 alt="Cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background: #f1f5f9; color: #94a3b8;">
                                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="book-meta-title">{{ $item->judul }}</div>
                                        <div class="book-meta-author">
                                            <span>{{ $item->penulis }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $item->penerbit }}</span>
                                            <span>({{ $item->tahunTerbit }})</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-category">
                                    {{ $item->kategori->namaKategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td>
                                @if ($item->stok > 0)
                                    <span class="stock-badge available">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        {{ $item->stok }} Tersedia
                                    </span>
                                @else
                                    <span class="stock-badge empty">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        Habis
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="condition-badge {{ strtolower($item->kondisi ?? 'baik') }}">
                                    {{ $item->kondisi ?? 'Baik' }}
                                </span>
                            </td>
                            @if (auth()->user()->role === 'admin')
                                <td>
                                    <div class="action-group" style="justify-content: flex-end;">
                                        {{-- Tombol Ubah: Khusus Admin --}}
                                        <a href="{{ route('buku.edit', $item->idBuku) }}"
                                           class="btn-action-edit"
                                           title="Edit Master Data Buku">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Ubah
                                        </a>

                                        {{-- Tombol Hapus: Khusus Admin --}}
                                        <form action="{{ route('buku.destroy', $item->idBuku) }}"
                                              method="POST"
                                              style="display: inline; margin: 0;"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku \'{{ $item->judul }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-delete" title="Hapus Data Buku">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" style="text-align: center; padding: 48px 20px;">
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px;">
                                    <div style="width: 52px; height: 52px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                    </div>
                                    <div style="font-weight: 700; color: #334155; font-size: 15px;">Tidak Ada Buku Ditemukan</div>
                                    <div style="font-size: 13px; color: #64748b; max-width: 380px;">
                                        @if(request()->filled('search') || request()->filled('kategori') || request()->filled('kondisi'))
                                            Tidak ada buku yang sesuai dengan filter pencarian Anda. Coba reset filter.
                                        @else
                                            Belum ada koleksi buku yang terdaftar di perpustakaan.
                                        @endif
                                    </div>
                                    @if(request()->filled('search') || request()->filled('kategori') || request()->filled('kondisi'))
                                        <a href="{{ route('buku.index') }}" class="btn-filter-reset" style="margin-top: 6px;">
                                            Reset Filter Pencarian
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div style="font-size: 13px; color: #64748b;">
                Menampilkan <strong>{{ $bukus->firstItem() ?? 0 }}</strong> - <strong>{{ $bukus->lastItem() ?? 0 }}</strong> dari <strong>{{ $bukus->total() }}</strong> buku
            </div>
            <div>
                {{ $bukus->links() }}
            </div>
        </div>
    </div>
</div>
@endsection