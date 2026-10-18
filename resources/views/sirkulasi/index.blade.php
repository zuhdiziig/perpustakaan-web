@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.petugas')

@section('title', 'Sirkulasi Buku - BOOKNEST')

@section('styles')
<style>
    .sirkulasi-page {
        width: 100%;
        max-width: 1240px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .sirkulasi-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .sirkulasi-title-group {
        flex: 1;
        min-width: 280px;
    }

    .sirkulasi-heading {
        margin: 0;
        font-size: 26px;
        line-height: 1.25;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
    }

    .sirkulasi-subtitle {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 13.5px;
        line-height: 1.5;
    }

    .sirkulasi-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border-radius: 10px;
        background: var(--theme-btn-primary-bg, #0f766e);
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(15, 118, 110, 0.2);
    }

    .btn-action-primary:hover {
        background: var(--theme-btn-primary-hover, #115e59);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(15, 118, 110, 0.25);
    }

    .btn-action-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: #ffffff;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-action-secondary:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        transform: translateY(-1px);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .stat-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon-amber { background: #fef3c7; color: #b45309; }
    .stat-icon-teal { background: var(--theme-accent-light, #ccfbf1); color: var(--theme-accent, #0f766e); }
    .stat-icon-emerald { background: #dcfce7; color: #15803d; }
    .stat-icon-blue { background: #dbeafe; color: #1e40af; }

    .stat-number {
        font-size: 22px;
        font-weight: 800;
        line-height: 1.2;
        color: #0f172a;
        letter-spacing: -0.5px;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-top: 2px;
    }

    /* Nav Tabs & Filter Card */
    .filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .nav-pills-sirkulasi {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
    }

    .nav-pill-btn {
        padding: 8px 16px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        text-decoration: none;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .nav-pill-btn.active {
        background: #ffffff;
        color: var(--theme-accent, #0f766e);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .search-filter-form {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-input-wrapper {
        position: relative;
        flex: 0 0 260px;
        width: 260px;
        min-width: 260px;
        max-width: 260px;
    }

    .search-input {
        width: 100%;
        height: 38px;
        padding: 0 36px 0 12px;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        background: #ffffff;
        color: #0f172a;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .search-input:focus {
        border-color: var(--theme-accent, #0f766e);
        box-shadow: 0 0 0 3px var(--theme-accent-light, rgba(15, 118, 110, 0.12));
    }

    .search-icon-btn {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        color: var(--theme-accent, #0f766e);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }

    .search-icon-btn:hover {
        background: var(--theme-accent-light, #f0fdfa);
        color: var(--theme-btn-primary-hover, #115e59);
        transform: translateY(-50%) scale(1.08);
    }

    .search-icon-btn:active {
        transform: translateY(-50%) scale(0.95);
    }

    .filter-select {
        height: 38px;
        padding: 0 14px;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        font-family: inherit;
        background: #ffffff;
        color: #334155;
        outline: none;
        cursor: pointer;
        box-sizing: border-box;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .filter-select:focus {
        border-color: var(--theme-accent, #0f766e);
        box-shadow: 0 0 0 3px var(--theme-accent-light, rgba(15, 118, 110, 0.12));
    }

    .btn-filter-reset {
        height: 38px;
        padding: 0 14px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-sizing: border-box;
        border: 1px solid transparent;
        transition: background 0.15s, color 0.15s;
        white-space: nowrap;
    }

    .btn-filter-reset:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* Table Card */
    .table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .pagination-footer {
        padding: 16px 20px;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .sirkulasi-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .sirkulasi-table th {
        background: #f8fafc;
        padding: 14px 18px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }

    .sirkulasi-table td {
        padding: 16px 18px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #334155;
    }

    .sirkulasi-table tr:hover td {
        background: #fcfdfe;
    }

    .trx-badge {
        font-family: monospace;
        font-size: 12px;
        font-weight: 700;
        color: var(--theme-accent, #0f766e);
        background: var(--theme-accent-light, #ccfbf1);
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .ret-badge {
        font-family: monospace;
        font-size: 12px;
        font-weight: 700;
        color: #2563eb;
        background: #dbeafe;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .user-meta-name {
        font-weight: 700;
        color: #0f172a;
    }

    .user-meta-sub {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 2px;
    }

    .book-list-item {
        margin-bottom: 3px;
        font-weight: 600;
        color: #1e293b;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 11.5px;
    }

    .badge-dipinjam { background: #fef3c7; color: #b45309; }
    .badge-selesai { background: #dcfce7; color: #15803d; }
    .badge-booking { background: #f1f5f9; color: #475569; }
    .badge-terlambat { background: #fee2e2; color: #dc2626; }

    .btn-detail-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 8px;
        background: #f1f5f9;
        color: var(--theme-accent, #0f766e);
        font-weight: 700;
        font-size: 12px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-detail-link:hover {
        background: var(--theme-accent-light, #ccfbf1);
        color: var(--theme-btn-primary-hover, #115e59);
    }

    .pagination-footer {
        padding: 16px 20px;
        border-top: 1px solid #f1f5f9;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: #64748b;
    }

    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .filter-card {
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
        }
        .search-filter-form {
            width: 100%;
        }
        .search-input-wrapper {
            flex: 1 1 240px;
            width: auto;
            min-width: 200px;
            max-width: 100%;
        }
    }
    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
        .sirkulasi-header { flex-direction: column; align-items: flex-start; }
        .search-input-wrapper {
            flex: 1 1 100%;
            width: 100%;
            min-width: 100%;
            max-width: 100%;
        }
        .filter-select {
            flex: 1 1 100%;
            width: 100%;
        }
        .nav-pills-sirkulasi {
            flex-direction: column;
            width: 100%;
        }
        .nav-pill-btn {
            width: 100%;
            justify-content: space-between;
        }
    }

    .petugas-meta-name {
        font-weight: 600;
        color: #475569;
    }

    /* --- DARK THEME OVERRIDES --- */
    [data-theme="dark"] .sirkulasi-heading {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .sirkulasi-subtitle {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .stat-card {
        background: #131d31 !important;
        border-color: #23314d !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
    }

    [data-theme="dark"] .stat-number {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .stat-label {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .filter-card {
        background: #131d31 !important;
        border-color: #23314d !important;
    }

    [data-theme="dark"] .nav-pills-sirkulasi {
        background: #0b1220 !important;
        border: 1px solid #23314d !important;
    }

    [data-theme="dark"] .nav-pill-btn {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .nav-pill-btn:hover {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .nav-pill-btn.active {
        background: #1e293b !important;
        color: var(--theme-accent, #2dd4bf) !important;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.4) !important;
    }

    [data-theme="dark"] .nav-pill-btn span:last-child {
        background: rgba(255, 255, 255, 0.08) !important;
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .search-input {
        background: #0b1220 !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .search-input:focus {
        border-color: var(--theme-accent, #14b8a6) !important;
        box-shadow: 0 0 0 3px var(--theme-accent-light, rgba(20, 184, 166, 0.2)) !important;
    }

    [data-theme="dark"] .search-input::placeholder {
        color: #64748b !important;
    }

    [data-theme="dark"] .search-icon-btn {
        color: var(--theme-accent, #2dd4bf) !important;
    }

    [data-theme="dark"] .search-icon-btn:hover {
        background: #1e293b !important;
        color: var(--theme-accent, #5eead4) !important;
    }

    [data-theme="dark"] .filter-select {
        background: #0b1220 !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .filter-select:focus {
        border-color: var(--theme-accent, #14b8a6) !important;
        box-shadow: 0 0 0 3px var(--theme-accent-light, rgba(20, 184, 166, 0.2)) !important;
    }

    [data-theme="dark"] .btn-filter-reset {
        background: #1e293b !important;
        color: #cbd5e1 !important;
        border-color: #334155 !important;
    }

    [data-theme="dark"] .btn-filter-reset:hover {
        background: #334155 !important;
        color: #f8fafc !important;
        border-color: #475569 !important;
    }

    [data-theme="dark"] .btn-action-secondary {
        background: #1e293b !important;
        color: #cbd5e1 !important;
        border-color: #334155 !important;
    }

    [data-theme="dark"] .btn-action-secondary:hover {
        background: #334155 !important;
        color: #f8fafc !important;
        border-color: #475569 !important;
    }

    [data-theme="dark"] .table-card {
        background: #131d31 !important;
        border-color: #23314d !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
    }

    [data-theme="dark"] .sirkulasi-table th {
        background: #0b1220 !important;
        color: #94a3b8 !important;
        border-bottom-color: #23314d !important;
    }

    [data-theme="dark"] .sirkulasi-table td {
        border-bottom-color: #23314d !important;
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .sirkulasi-table tr:hover td {
        background: #172339 !important;
    }

    /* Nama Peminjam & Nama Buku */
    [data-theme="dark"] .user-meta-name {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .user-meta-sub {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .book-list-item {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .book-list-item span {
        color: #38bdf8 !important;
    }

    [data-theme="dark"] .petugas-meta-name,
    [data-theme="dark"] .sirkulasi-table td div[style*="color: #475569"] {
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .trx-badge {
        background: var(--theme-accent-light, rgba(20, 184, 166, 0.16)) !important;
        color: var(--theme-accent, #2dd4bf) !important;
        border: 1px solid var(--theme-border, rgba(20, 184, 166, 0.3)) !important;
    }

    [data-theme="dark"] .ret-badge {
        background: rgba(59, 130, 246, 0.16) !important;
        color: #93c5fd !important;
        border: 1px solid rgba(59, 130, 246, 0.3) !important;
    }

    [data-theme="dark"] .btn-detail-link {
        background: #1e293b !important;
        color: var(--theme-accent, #2dd4bf) !important;
        border: 1px solid #334155 !important;
    }

    [data-theme="dark"] .btn-detail-link:hover {
        background: #042f2e !important;
        color: var(--theme-accent, #5eead4) !important;
        border-color: var(--theme-accent, #14b8a6) !important;
    }

    [data-theme="dark"] .pagination-footer {
        background: #131d31 !important;
        border-top-color: #23314d !important;
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .pagination-footer strong {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .empty-state {
        color: #94a3b8 !important;
    }
</style>
@endsection

@section('content')
<div class="sirkulasi-page">

    {{-- HEADER --}}
    <div class="sirkulasi-header">
        <div class="sirkulasi-title-group">
            <h1 class="sirkulasi-heading">Sirkulasi Buku</h1>
            <p class="sirkulasi-subtitle">
                Pantau seluruh aktivitas transaksi peminjaman & pengembalian buku di meja sirkulasi perpustakaan.
            </p>
        </div>

        <div class="sirkulasi-actions">
            <a href="{{ route('barcode.scan') }}" class="btn-action-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                    <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                    <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                    <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                    <line x1="7" y1="12" x2="17" y2="12"></line>
                </svg>
                <span>Meja Sirkulasi (Scan QR)</span>
            </a>
            <a href="{{ route('peminjaman.create') }}" class="btn-action-secondary">
                <span>+ Peminjaman</span>
            </a>
            <a href="{{ route('pengembalian.create') }}" class="btn-action-secondary">
                <span>+ Pengembalian</span>
            </a>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-box stat-icon-amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalPinjamAktif }}</div>
                <div class="stat-label">Sedang Dipinjam</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box stat-icon-teal">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="16 3 21 3 21 8"></polyline>
                    <line x1="4" y1="20" x2="21" y2="3"></line>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalPinjamHariIni }}</div>
                <div class="stat-label">Peminjaman Hari Ini</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box stat-icon-emerald">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                    <path d="M21 3v5h-5"></path>
                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                    <path d="M3 21v-5h5"></path>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalKembaliHariIni }}</div>
                <div class="stat-label">Pengembalian Hari Ini</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box stat-icon-blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalAntreanBooking }}</div>
                <div class="stat-label">Antrean Booking</div>
            </div>
        </div>
    </div>

    {{-- FILTER & NAV TABS CARD --}}
    <div class="filter-card">
        <div class="nav-pills-sirkulasi">
            <a href="{{ route('sirkulasi.index', array_merge(request()->query(), ['tab' => 'peminjaman'])) }}"
               class="nav-pill-btn {{ $tab === 'peminjaman' ? 'active' : '' }}">
                <span>📋 Transaksi Peminjaman</span>
                <span style="font-size: 11px; background: rgba(0,0,0,0.06); padding: 1px 6px; border-radius: 999px;">{{ $peminjamans->total() }}</span>
            </a>
            <a href="{{ route('sirkulasi.index', array_merge(request()->query(), ['tab' => 'pengembalian'])) }}"
               class="nav-pill-btn {{ $tab === 'pengembalian' ? 'active' : '' }}">
                <span>🔄 Transaksi Pengembalian</span>
                <span style="font-size: 11px; background: rgba(0,0,0,0.06); padding: 1px 6px; border-radius: 999px;">{{ $pengembalians->total() }}</span>
            </a>
        </div>

        <form action="{{ route('sirkulasi.index') }}" method="GET" class="search-filter-form" id="sirkulasiFilterForm">
            <input type="hidden" name="tab" id="sirkulasiTabInput" value="{{ $tab }}">

            <div class="search-input-wrapper">
                <input type="text"
                       name="q"
                       id="sirkulasiSearchInput"
                       class="search-input"
                       value="{{ $q }}"
                       placeholder="Cari #TRX, nama, judul buku..."
                       autocomplete="off">
                <button type="submit"
                        id="sirkulasiSearchBtn"
                        class="search-icon-btn"
                        title="Cari transaksi sirkulasi"
                        aria-label="Cari transaksi sirkulasi">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </div>

            @if($tab === 'peminjaman')
                <select name="status" id="sirkulasiStatusSelect" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="Dipinjam" {{ $status === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="Selesai" {{ $status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Booking" {{ $status === 'Booking' ? 'selected' : '' }}>Booking</option>
                </select>
            @endif

            <div id="sirkulasiResetWrapper" style="{{ ($q || $status) ? 'display: inline-flex;' : 'display: none;' }}">
                <a href="{{ route('sirkulasi.index', ['tab' => $tab]) }}" id="sirkulasiResetBtn" class="btn-filter-reset">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div id="sirkulasiTableCard" style="transition: opacity 0.2s ease;">
    {{-- TAB 1: TABEL PEMINJAMAN --}}
    @if($tab === 'peminjaman')
        <div class="table-card">
            <div class="table-responsive">
                <table class="sirkulasi-table">
                    <thead>
                        <tr>
                            <th style="width: 140px;">No. Transaksi</th>
                            <th style="width: 200px;">Peminjam</th>
                            <th style="width: 150px;">Petugas</th>
                            <th>Buku yang Dipinjam</th>
                            <th style="width: 130px;">Tgl Pinjam</th>
                            <th style="width: 130px;">Jatuh Tempo</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 110px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $item)
                            <tr>
                                <td>
                                    <span class="trx-badge">
                                        #TRX-{{ str_pad($item->idPeminjaman, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                    @if($item->kode_booking)
                                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                            {{ $item->kode_booking }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="user-meta-name">{{ $item->member->name ?? 'Member' }}</div>
                                    <div class="user-meta-sub">{{ $item->member->email ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="petugas-meta-name">{{ $item->petugas->name ?? '-' }}</div>
                                </td>
                                <td>
                                    @foreach($item->details as $d)
                                        <div class="book-list-item">
                                            • {{ $d->buku->judul ?? 'Buku' }}
                                            <span style="font-size: 11px; color: #64748b; font-weight: normal;">
                                                (#{{ $d->eksemplar->nomor_eksemplar ?? '1' }})
                                            </span>
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    <span style="font-weight: 600;">
                                        {{ \Carbon\Carbon::parse($item->tanggalPinjam)->translatedFormat('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-weight: 600; {{ $item->status === 'Dipinjam' && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($item->batasKembali)) ? 'color: #dc2626;' : '' }}">
                                        {{ \Carbon\Carbon::parse($item->batasKembali)->translatedFormat('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->status === 'Dipinjam')
                                        <span class="badge-status badge-dipinjam">Dipinjam</span>
                                    @elseif($item->status === 'Selesai')
                                        <span class="badge-status badge-selesai">Selesai</span>
                                    @elseif(in_array($item->status, ['Booking', 'Siap Diambil']))
                                        <span class="badge-status badge-booking">{{ $item->status }}</span>
                                    @else
                                        <span class="badge-status badge-booking">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('peminjaman.show', $item->idPeminjaman) }}" class="btn-detail-link">
                                        <span>Detail &rarr;</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty-state">
                                    Tidak ada data transaksi peminjaman ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($peminjamans->hasPages())
                <div class="pagination-footer">
                    <div style="font-size: 13px; color: #64748b;">
                        Menampilkan <strong>{{ $peminjamans->firstItem() ?? 0 }}</strong> - <strong>{{ $peminjamans->lastItem() ?? 0 }}</strong> dari <strong>{{ $peminjamans->total() }}</strong> transaksi
                    </div>
                    <div>
                        {{ $peminjamans->links() }}
                    </div>
                </div>
            @endif
        </div>
    @else
    {{-- TAB 2: TABEL PENGEMBALIAN --}}
        <div class="table-card">
            <div class="table-responsive">
                <table class="sirkulasi-table">
                    <thead>
                        <tr>
                            <th style="width: 140px;">No. Return</th>
                            <th style="width: 140px;">Ref. Pinjam</th>
                            <th style="width: 200px;">Peminjam</th>
                            <th style="width: 150px;">Petugas Penerima</th>
                            <th>Buku yang Dikembalikan</th>
                            <th style="width: 130px;">Tgl Kembali</th>
                            <th style="width: 110px;">Kondisi</th>
                            <th style="width: 130px;">Denda</th>
                            <th style="width: 110px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengembalians as $ret)
                            <tr>
                                <td>
                                    <span class="ret-badge">
                                        #RET-{{ str_pad($ret->idPengembalian, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="trx-badge">
                                        #TRX-{{ str_pad($ret->idPeminjaman, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="user-meta-name">{{ $ret->peminjaman->member->name ?? '-' }}</div>
                                    <div class="user-meta-sub">{{ $ret->peminjaman->member->email ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="petugas-meta-name">{{ $ret->petugas->name ?? '-' }}</div>
                                </td>
                                <td>
                                    @php
                                        $retDetails = $ret->peminjaman->details ?? collect();
                                    @endphp
                                    @foreach($retDetails as $d)
                                        <div class="book-list-item">
                                            • {{ $d->buku->judul ?? 'Buku' }}
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    <span style="font-weight: 600;">
                                        {{ \Carbon\Carbon::parse($ret->tanggalKembali)->translatedFormat('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-weight: 700; color: {{ $ret->kondisiBuku === 'Baik' ? '#15803d' : '#dc2626' }};">
                                        {{ $ret->kondisiBuku }}
                                    </span>
                                </td>
                                <td>
                                    @if($ret->denda)
                                        <strong style="color: #dc2626;">Rp {{ number_format($ret->denda->jumlah, 0, ',', '.') }}</strong>
                                        <div style="font-size: 11px; color: {{ $ret->denda->status === 'Lunas' ? '#15803d' : '#b45309' }}; font-weight: 700;">
                                            {{ $ret->denda->status }}
                                        </div>
                                    @else
                                        <span style="color: #15803d; font-weight: 600;">Rp 0 (Bebas Denda)</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('pengembalian.show', $ret->idPengembalian) }}" class="btn-detail-link">
                                        <span>Detail &rarr;</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="empty-state">
                                    Tidak ada data transaksi pengembalian ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pengembalians->hasPages())
                <div class="pagination-footer">
                    <div style="font-size: 13px; color: #64748b;">
                        Menampilkan <strong>{{ $pengembalians->firstItem() ?? 0 }}</strong> - <strong>{{ $pengembalians->lastItem() ?? 0 }}</strong> dari <strong>{{ $pengembalians->total() }}</strong> transaksi
                    </div>
                    <div>
                        {{ $pengembalians->links() }}
                    </div>
                </div>
            @endif
        </div>
    @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
    let sirkulasiSearchDebounceTimer = null;
    let sirkulasiAbortController = null;

    function buildSirkulasiFilterUrl() {
        const form = document.getElementById('sirkulasiFilterForm');
        if (!form) return window.location.href;
        const formData = new FormData(form);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value !== '' && value !== null) {
                params.set(key, value);
            }
        }
        return `${form.getAttribute('action')}?${params.toString()}`;
    }

    function updateSirkulasiResetVisibility() {
        const searchInput = document.getElementById('sirkulasiSearchInput');
        const statusSelect = document.getElementById('sirkulasiStatusSelect');
        const resetWrapper = document.getElementById('sirkulasiResetWrapper');
        if (!resetWrapper) return;

        const hasSearch = searchInput && searchInput.value.trim() !== '';
        const hasStatus = statusSelect && statusSelect.value !== '';
        resetWrapper.style.display = (hasSearch || hasStatus) ? 'inline-flex' : 'none';
    }

    function fetchSirkulasiData(url) {
        if (sirkulasiAbortController) {
            sirkulasiAbortController.abort();
        }
        sirkulasiAbortController = new AbortController();

        const tableCard = document.getElementById('sirkulasiTableCard');
        if (tableCard) {
            tableCard.style.opacity = '0.35';
            tableCard.style.pointerEvents = 'none';
        }

        fetch(url, {
            signal: sirkulasiAbortController.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error: ' + response.status);
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newTableCard = doc.getElementById('sirkulasiTableCard');
            if (newTableCard && tableCard) {
                tableCard.innerHTML = newTableCard.innerHTML;
            }

            updateSirkulasiResetVisibility();
            window.history.pushState({ path: url }, '', url);
            bindSirkulasiPaginationLinks();
        })
        .catch(err => {
            if (err.name === 'AbortError') return;
            console.warn('AJAX sirkulasi gagal, reload biasa:', err);
            const form = document.getElementById('sirkulasiFilterForm');
            if (form) form.submit();
        })
        .finally(() => {
            if (tableCard) {
                tableCard.style.opacity = '1';
                tableCard.style.pointerEvents = 'auto';
            }
        });
    }

    function bindSirkulasiPaginationLinks() {
        const tableCard = document.getElementById('sirkulasiTableCard');
        if (!tableCard) return;
        const links = tableCard.querySelectorAll('.pagination a, .pagination-footer a');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = link.getAttribute('href');
                if (href && !href.startsWith('#')) {
                    e.preventDefault();
                    fetchSirkulasiData(href);
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        bindSirkulasiPaginationLinks();
        updateSirkulasiResetVisibility();

        const form = document.getElementById('sirkulasiFilterForm');
        const searchInput = document.getElementById('sirkulasiSearchInput');
        const statusSelect = document.getElementById('sirkulasiStatusSelect');

        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                clearTimeout(sirkulasiSearchDebounceTimer);
                updateSirkulasiResetVisibility();
                fetchSirkulasiData(buildSirkulasiFilterUrl());
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(sirkulasiSearchDebounceTimer);
                updateSirkulasiResetVisibility();
                sirkulasiSearchDebounceTimer = setTimeout(() => {
                    fetchSirkulasiData(buildSirkulasiFilterUrl());
                }, 350);
            });
        }

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                clearTimeout(sirkulasiSearchDebounceTimer);
                fetchSirkulasiData(buildSirkulasiFilterUrl());
            });
        }

        document.addEventListener('click', function(e) {
            const resetBtn = e.target.closest('#sirkulasiResetBtn');
            if (resetBtn && resetBtn.getAttribute('href')) {
                e.preventDefault();
                if (searchInput) searchInput.value = '';
                if (statusSelect) statusSelect.value = '';
                updateSirkulasiResetVisibility();
                fetchSirkulasiData(resetBtn.getAttribute('href'));
            }
        });

        window.addEventListener('popstate', function() {
            window.location.reload();
        });
    });
</script>
@endsection
