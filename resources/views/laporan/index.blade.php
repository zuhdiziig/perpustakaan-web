@extends('layouts.admin')

@section('title', 'Laporan Perpustakaan - BOOKNEST')

@section('styles')
<style>
    /* --- PAGE HEADER --- */
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

    .breadcrumb a {
        color: var(--theme-accent, #0f766e);
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
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

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .period-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 12.5px;
        color: #334155;
        font-weight: 600;
    }

    .period-badge svg {
        color: var(--theme-accent, #0f766e);
        flex-shrink: 0;
    }

    .btn-print-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        background: #0f172a;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.12);
    }

    .btn-print-action:hover {
        background: #1e293b;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
    }

    /* --- STATS GRID (4 COLS) --- */
    .stats-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
        transition: opacity 0.2s ease;
    }

    @media (max-width: 1100px) {
        .stats-grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .stats-grid-4 {
            grid-template-columns: 1fr;
        }
    }

    .stat-box {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.15s, box-shadow 0.15s;
        position: relative;
        overflow: hidden;
    }

    .stat-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.04);
    }

    .stat-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
    }

    .stat-box--teal::before { background: var(--theme-accent, #0f766e); }
    .stat-box--emerald::before { background: #10b981; }
    .stat-box--amber::before { background: #f59e0b; }
    .stat-box--blue::before { background: #2563eb; }

    .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon--teal { background: var(--theme-accent-light, #ccfbf1); color: var(--theme-accent, #0f766e); }
    .stat-icon--emerald { background: #d1fae5; color: #059669; }
    .stat-icon--amber { background: #fef3c7; color: #d97706; }
    .stat-icon--blue { background: #dbeafe; color: #2563eb; }

    .stat-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted, #64748b);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        letter-spacing: -0.5px;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .stat-caption {
        font-size: 11.5px;
        color: #64748b;
        line-height: 1.4;
    }

    /* --- FILTER PANEL CARD --- */
    .filter-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 20px 22px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .filter-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .filter-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-heading, #0f172a);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-card-title svg {
        color: var(--theme-accent, #0f766e);
    }

    .filter-main-grid {
        display: grid;
        grid-template-columns: 1.8fr 1.2fr 1.2fr auto;
        gap: 14px;
        align-items: flex-end;
        margin-bottom: 16px;
    }

    @media (max-width: 960px) {
        .filter-main-grid {
            grid-template-columns: 1fr 1fr;
        }
        .form-group--actions {
            grid-column: span 2;
        }
    }

    @media (max-width: 600px) {
        .filter-main-grid {
            grid-template-columns: 1fr;
        }
        .form-group--actions {
            grid-column: span 1;
        }
    }

    .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 6px;
        display: block;
    }

    .form-control-select,
    .form-control-date {
        width: 100%;
        padding: 9.5px 13px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13px;
        font-family: inherit;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        box-sizing: border-box;
    }

    .form-control-select {
        font-weight: 600;
        cursor: pointer;
    }

    .form-control-select:focus,
    .form-control-date:focus {
        border-color: var(--theme-accent, #0f766e);
        box-shadow: 0 0 0 3px var(--theme-accent-light, rgba(15, 118, 110, 0.12));
    }

    .filter-actions-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-apply-filter {
        padding: 9.5px 18px;
        background: var(--theme-btn-primary-bg, #0f766e);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s;
        white-space: nowrap;
    }

    .btn-apply-filter:hover {
        background: var(--theme-btn-primary-hover, #115e59);
    }

    .btn-reset-filter {
        padding: 9.5px 14px;
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s;
        white-space: nowrap;
        cursor: pointer;
    }

    .btn-reset-filter:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* PRESETS ROW */
    .presets-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
    }

    .presets-label {
        font-size: 11.5px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-right: 4px;
    }

    .presets-group {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .preset-btn {
        padding: 6px 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .preset-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .preset-btn.active {
        background: var(--theme-accent-light, #ccfbf1);
        border-color: var(--theme-border, #99f6e4);
        color: var(--theme-accent, #0f766e);
        font-weight: 700;
    }

    /* --- REPORT CARD & TABLE --- */
    .report-tables-wrapper {
        position: relative;
        transition: opacity 0.2s ease;
    }

    .report-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .report-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .report-card-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        letter-spacing: -0.3px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .count-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 9999px;
        background: var(--theme-accent-light, #f0fdfa);
        color: var(--theme-accent, #0f766e);
        border: 1px solid var(--theme-border, #ccfbf1);
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .report-table {
        width: 100%;
        min-width: 760px;
        border-collapse: collapse;
        font-size: 13px;
    }

    .report-table th {
        text-align: left;
        padding: 13px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 700;
        color: #475569;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .report-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #334155;
    }

    .report-table tbody tr:hover {
        background: #fbfcfe;
    }

    .report-table tbody tr:last-child td {
        border-bottom: none;
    }

    .trx-id {
        font-weight: 800;
        color: var(--theme-accent, #0f766e);
        letter-spacing: 0.3px;
    }

    .user-cell {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 700;
        color: #0f172a;
    }

    .book-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .book-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        color: #1e293b;
    }

    .book-item-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--theme-accent, #0f766e);
        flex-shrink: 0;
    }

    /* BADGES */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-success { background: #dcfce7; color: #166534; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-danger  { background: #fee2e2; color: #991b1b; }
    .badge-info    { background: #e0f2fe; color: #0369a1; }
    .badge-neutral { background: #f1f5f9; color: #475569; }

    /* EMPTY STATE */
    .empty-state {
        padding: 45px 20px;
        text-align: center;
        color: #64748b;
    }

    .empty-state-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #f1f5f9;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }

    .empty-state-text {
        font-size: 13.5px;
        font-weight: 600;
    }

    /* --- PRINT STYLES --- */
    .print-only-header {
        display: none;
    }

    @media print {
        .topbar,
        .sidebar,
        .sidebar-backdrop,
        .breadcrumb,
        .page-subtitle,
        .header-actions,
        .filter-card,
        .btn-print-action {
            display: none !important;
        }

        .page-wrapper {
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .content-area {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        body {
            background: #ffffff !important;
            color: #000000 !important;
            font-size: 11pt;
            padding: 0 !important;
        }

        .print-only-header {
            display: block !important;
            margin-bottom: 20px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
        }

        .print-only-header h2 {
            font-size: 18pt;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 4px;
        }

        .print-only-header p {
            font-size: 10pt;
            color: #475569;
            margin: 0;
        }

        .stat-box,
        .report-card {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .stats-grid-4 {
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 10px !important;
            margin-bottom: 20px !important;
        }

        .stat-box {
            padding: 12px !important;
        }

        .stat-value {
            font-size: 16pt !important;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #e2e8f0 !important;
            padding: 8px 10px !important;
            font-size: 9.5pt !important;
        }
    }
</style>
@endsection

@section('content')
<div class="laporan-page-container">

    <!-- PRINT ONLY HEADER -->
    <div class="print-only-header">
        <h2>BOOKNEST — LAPORAN PERPUSTAKAAN</h2>
        <p>
            Periode: <strong>{{ \Carbon\Carbon::parse($tglMulai)->translatedFormat('d F Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d F Y') }}</strong>
            &nbsp;•&nbsp; Tanggal Cetak: <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</strong>
        </p>
    </div>

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">BOOKNEST</a>
                <span>/</span>
                <span>ADMIN</span>
                <span>/</span>
                <span style="color: #0f172a;">LAPORAN</span>
            </div>
            <h1 class="page-title">Laporan Perpustakaan</h1>
            <p class="page-subtitle">
                Pantau ringkasan sirkulasi peminjaman, pengembalian, denda, dan arus keuangan perpustakaan secara transparan.
            </p>
        </div>

        <div class="header-actions">
            <div class="period-badge" id="periodBadgeContainer">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>
                    Periode: <strong>{{ \Carbon\Carbon::parse($tglMulai)->translatedFormat('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d M Y') }}</strong>
                </span>
            </div>

            <button type="button" class="btn-print-action" onclick="window.print()" title="Cetak Dokumen Laporan">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <!-- STATS GRID (4 COLS) -->
    <div class="stats-grid-4" id="reportStatsGrid">
        <!-- 1. TOTAL PEMINJAMAN -->
        <div class="stat-box stat-box--teal">
            <div class="stat-top">
                <div class="stat-icon stat-icon--teal">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <div class="stat-label">Total Peminjaman</div>
                <div class="stat-value">{{ $ringkasan['total_pinjam'] }} Transaksi</div>
                <div class="stat-caption">Transaksi peminjaman dalam periode aktif</div>
            </div>
        </div>

        <!-- 2. TOTAL PENGEMBALIAN -->
        <div class="stat-box stat-box--emerald">
            <div class="stat-top">
                <div class="stat-icon stat-icon--emerald">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                </div>
            </div>
            <div>
                <div class="stat-label">Total Pengembalian</div>
                <div class="stat-value">{{ $ringkasan['total_kembali'] }} Buku</div>
                <div class="stat-caption">Koleksi buku kembali ke sirkulasi</div>
            </div>
        </div>

        <!-- 3. TOTAL DENDA DITERBITKAN -->
        <div class="stat-box stat-box--amber">
            <div class="stat-top">
                <div class="stat-icon stat-icon--amber">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
            </div>
            <div>
                <div class="stat-label">Total Denda Diterbitkan</div>
                <div class="stat-value" style="color: #d97706;">Rp {{ number_format($ringkasan['total_denda'], 0, ',', '.') }}</div>
                <div class="stat-caption">Akumulasi denda keterlambatan & buku</div>
            </div>
        </div>

        <!-- 4. PEMBAYARAN SUKSES -->
        <div class="stat-box stat-box--blue">
            <div class="stat-top">
                <div class="stat-icon stat-icon--blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                        <line x1="2" y1="10" x2="22" y2="10"></line>
                    </svg>
                </div>
            </div>
            <div>
                <div class="stat-label">Pembayaran Berstatus Sukses (Belum Diverifikasi Gateway)</div>
                <div class="stat-value" style="color: #16a34a;">Rp {{ number_format($ringkasan['denda_lunas'], 0, ',', '.') }}</div>
                <div class="stat-caption">Nominal pembayaran berstatus sukses, belum diverifikasi gateway.</div>
            </div>
        </div>
    </div>

    @php
        $todayStr = now()->toDateString();
        $startMonthStr = now()->startOfMonth()->toDateString();
        $startYearStr = now()->startOfYear()->toDateString();
        $sevenDaysAgoStr = now()->subDays(6)->toDateString();
        $lastMonthStartStr = now()->subMonth()->startOfMonth()->toDateString();
        $lastMonthEndStr = now()->subMonth()->endOfMonth()->toDateString();

        $isHariIni = ($tglMulai === $todayStr && $tglSelesai === $todayStr);
        $is7Hari = ($tglMulai === $sevenDaysAgoStr && $tglSelesai === $todayStr);
        $isBulanIni = ($tglMulai === $startMonthStr && $tglSelesai === $todayStr);
        $isBulanLalu = ($tglMulai === $lastMonthStartStr && $tglSelesai === $lastMonthEndStr);
        $isTahunIni = ($tglMulai === $startYearStr && $tglSelesai === $todayStr);
    @endphp

    <!-- FILTER & KONFIGURASI LAPORAN -->
    <div class="filter-card">
        <div class="filter-card-header">
            <div class="filter-card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                <span>Filter & Konfigurasi Laporan</span>
            </div>
        </div>

        <form action="{{ route('laporan.index') }}" method="GET" id="reportFilterForm">
            <div class="filter-main-grid">
                <!-- 1. DROPDOWN JENIS LAPORAN (OTOMATIS MEMBUKA LAPORAN SAAT DIGANTI) -->
                <div class="form-group form-group--jenis">
                    <label for="filterJenisSelect" class="filter-label">Jenis Laporan</label>
                    <select name="jenis" id="filterJenisSelect" class="form-control-select" onchange="handleJenisChange(this.value)">
                        <option value="semua" {{ $jenisLaporan === 'semua' ? 'selected' : '' }}>Semua Laporan</option>
                        <option value="peminjaman" {{ $jenisLaporan === 'peminjaman' ? 'selected' : '' }}>Laporan Peminjaman</option>
                        <option value="pengembalian" {{ $jenisLaporan === 'pengembalian' ? 'selected' : '' }}>Laporan Pengembalian</option>
                        <option value="denda" {{ $jenisLaporan === 'denda' ? 'selected' : '' }}>Laporan Denda & Keuangan</option>
                    </select>
                </div>

                <!-- 2. DARI TANGGAL -->
                <div class="form-group">
                    <label for="filterTglMulai" class="filter-label">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" id="filterTglMulai" class="form-control-date" value="{{ $tglMulai }}" onchange="handleDateChange()" required>
                </div>

                <!-- 3. SAMPAI TANGGAL -->
                <div class="form-group">
                    <label for="filterTglSelesai" class="filter-label">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" id="filterTglSelesai" class="form-control-date" value="{{ $tglSelesai }}" onchange="handleDateChange()" required>
                </div>

                <!-- 4. AKSI TERAPKAN & RESET -->
                <div class="form-group form-group--actions">
                    <div class="filter-actions-wrap">
                        <button type="submit" class="btn-apply-filter" title="Terapkan filter">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span>Terapkan</span>
                        </button>
                        <a href="{{ route('laporan.index') }}" class="btn-reset-filter" onclick="event.preventDefault(); resetReportFilter();" title="Reset ke default (Bulan Ini)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"></path>
                            </svg>
                            <span>Reset</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- PILIHAN CEPAT RENTANG TANGGAL -->
            <div class="presets-row">
                <span class="presets-label">Pilihan Cepat:</span>
                <div class="presets-group">
                    <button type="button" class="preset-btn {{ $isHariIni ? 'active' : '' }}" data-preset="hari_ini" onclick="setDatePreset('hari_ini')">Hari Ini</button>
                    <button type="button" class="preset-btn {{ $is7Hari ? 'active' : '' }}" data-preset="7_hari" onclick="setDatePreset('7_hari')">7 Hari Terakhir</button>
                    <button type="button" class="preset-btn {{ $isBulanIni ? 'active' : '' }}" data-preset="bulan_ini" onclick="setDatePreset('bulan_ini')">Bulan Ini</button>
                    <button type="button" class="preset-btn {{ $isBulanLalu ? 'active' : '' }}" data-preset="bulan_lalu" onclick="setDatePreset('bulan_lalu')">Bulan Lalu</button>
                    <button type="button" class="preset-btn {{ $isTahunIni ? 'active' : '' }}" data-preset="tahun_ini" onclick="setDatePreset('tahun_ini')">Tahun Ini</button>
                </div>
            </div>
        </form>
    </div>

    <!-- TAMPILAN TABEL LAPORAN SESUAI JENIS (HANYA AREA INI YANG DIRENOTIFIKASI / DIRELOAD SECARA MULUS) -->
    <div id="reportTablesContainer" class="report-tables-wrapper">
        {{-- 1. TABEL LAPORAN PEMINJAMAN --}}
        @if ($jenisLaporan === 'semua' || $jenisLaporan === 'peminjaman')
            <div class="report-card">
                <div class="report-card-header">
                    <h3 class="report-card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--theme-accent, #0f766e);">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span>Laporan Transaksi Peminjaman Buku</span>
                    </h3>
                    <span class="count-badge">{{ $dataPeminjaman ? $dataPeminjaman->count() : 0 }} Data Transaksi</span>
                </div>

                <div class="table-container">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>No. Peminjaman</th>
                                <th>Member</th>
                                <th>Petugas</th>
                                <th>Daftar Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataPeminjaman ?? [] as $p)
                                <tr>
                                <td>
                                    <span class="trx-id">#TRX-{{ str_pad($p->idPeminjaman, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <span class="user-name">{{ $p->member?->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span style="color: #64748b; font-weight: 500;">{{ $p->petugas?->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="book-list">
                                        @foreach ($p->details as $d)
                                            <div class="book-item">
                                                <span class="book-item-dot"></span>
                                                <span>{{ $d->buku->judul ?? '-' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: #334155;">{{ $p->tanggal_pinjam_realtime }}</span>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: #334155;">{{ $p->batas_kembali_realtime }}</span>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($p->status) {
                                            'Dipinjam' => 'badge-warning',
                                            'Selesai', 'Kembali' => 'badge-success',
                                            'Booking' => 'badge-info',
                                            default => 'badge-neutral',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="8" y1="12" x2="16" y2="12"></line>
                                            </svg>
                                        </div>
                                        <div class="empty-state-text">Tidak ada data peminjaman pada periode ini.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- 2. TABEL LAPORAN PENGEMBALIAN --}}
    @if ($jenisLaporan === 'semua' || $jenisLaporan === 'pengembalian')
        <div class="report-card">
            <div class="report-card-header">
                <h3 class="report-card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #059669;">
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    <span>Laporan Sirkulasi Pengembalian Buku</span>
                </h3>
                <span class="count-badge">{{ $dataPengembalian ? $dataPengembalian->count() : 0 }} Data Pengembalian</span>
            </div>

            <div class="table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>No. Return</th>
                            <th>Member Peminjam</th>
                            <th>Petugas Penerima</th>
                            <th>Tanggal Kembali</th>
                            <th>Kondisi Buku</th>
                            <th>Status Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataPengembalian ?? [] as $ret)
                            <tr>
                                <td>
                                    <span class="trx-id">#RET-{{ str_pad($ret->idPengembalian, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <span class="user-name">{{ $ret->peminjaman?->member?->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span style="color: #64748b; font-weight: 500;">{{ $ret->petugas?->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: #334155;">{{ $ret->tanggalKembali }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $ret->kondisiBuku === 'Baik' ? 'badge-success' : ($ret->kondisiBuku === 'Rusak' ? 'badge-warning' : 'badge-danger') }}">
                                        {{ $ret->kondisiBuku }}
                                    </span>
                                </td>
                                <td>
                                    @if ($ret->denda)
                                        <strong style="color: #dc2626;">Rp {{ number_format($ret->denda->jumlah, 0, ',', '.') }}</strong>
                                        <span style="color: #64748b; font-size: 12px; margin-left: 4px;">({{ $ret->denda->status }})</span>
                                    @else
                                        <span style="color: #16a34a; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                            Bebas Denda
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="8" y1="12" x2="16" y2="12"></line>
                                            </svg>
                                        </div>
                                        <div class="empty-state-text">Tidak ada data pengembalian pada periode ini.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- 3. TABEL LAPORAN DENDA & KEUANGAN --}}
    @if ($jenisLaporan === 'semua' || $jenisLaporan === 'denda')
        <div class="report-card">
            <div class="report-card-header">
                <h3 class="report-card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #d97706;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>Laporan Tagihan Denda & Arus Pembayaran</span>
                </h3>
                <span class="count-badge">{{ $dataDenda ? $dataDenda->count() : 0 }} Data Denda</span>
            </div>

            <div class="table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>No. Denda</th>
                            <th>Member</th>
                            <th>Rincian Pelanggaran</th>
                            <th>Nominal Tagihan</th>
                            <th>Metode Bayar</th>
                            <th>Status</th>
                            <th>Tgl Terbit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataDenda ?? [] as $d)
                            <tr>
                                <td>
                                    <span class="trx-id">#DND-{{ str_pad($d->idDenda, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <span class="user-name">{{ $d->pengembalian?->peminjaman?->member?->name ?? $d->details?->first()?->peminjaman?->member?->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: #1e293b;">{{ $d->jenisDenda }}</span>
                                </td>
                                <td>
                                    <strong style="color: #0f172a; font-size: 13.5px;">Rp {{ number_format($d->jumlah, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @php
                                        $suksesPay = $d->pembayaran->firstWhere('status', 'Sukses');
                                        $pay = $suksesPay ?? $d->pembayaran->first();
                                    @endphp
                                    @if ($pay)
                                        <span class="badge {{ $d->status === 'Lunas' ? 'badge-success' : 'badge-warning' }}">
                                            {{ $pay->metode }}
                                        </span>
                                    @elseif ($d->status === 'Lunas')
                                        <span class="badge badge-success">Tunai</span>
                                    @else
                                        <span style="color: #64748b; font-size: 12px; font-weight: 500;">Belum Dibayar</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $d->status === 'Lunas' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $d->status }}
                                    </span>
                                </td>
                                <td>
                                    <span style="color: #64748b; font-size: 12.5px;">{{ $d->created_at->format('Y-m-d') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="8" y1="12" x2="16" y2="12"></line>
                                            </svg>
                                        </div>
                                        <div class="empty-state-text">Tidak ada data denda pada periode ini.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    </div>

</div>

<script>
    let isReportFetching = false;

    // AJAX Refresh hanya bagian laporan transaksi & stats tanpa merefresh seluruh halaman
    function fetchReportData(url) {
        if (isReportFetching) return;
        isReportFetching = true;

        const container = document.getElementById('reportTablesContainer');
        const statsGrid = document.getElementById('reportStatsGrid');
        if (container) {
            container.style.opacity = '0.35';
            container.style.pointerEvents = 'none';
        }
        if (statsGrid) {
            statsGrid.style.opacity = '0.7';
        }

        fetch(url, {
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

            // 1. Refresh hanya bagian laporan transaksi (tabel & card laporan)
            const newTables = doc.getElementById('reportTablesContainer');
            if (newTables && container) {
                container.innerHTML = newTables.innerHTML;
            }

            // 2. Refresh stats ringkasan & badge periode
            const newStats = doc.getElementById('reportStatsGrid');
            if (newStats && statsGrid) {
                statsGrid.innerHTML = newStats.innerHTML;
            }

            const newPeriodBadge = doc.getElementById('periodBadgeContainer');
            const curPeriodBadge = document.getElementById('periodBadgeContainer');
            if (newPeriodBadge && curPeriodBadge) {
                curPeriodBadge.innerHTML = newPeriodBadge.innerHTML;
            }

            // 3. Update URL browser tanpa reload halaman penuh
            window.history.pushState({ path: url }, '', url);
        })
        .catch(err => {
            console.warn('AJAX refresh gagal, memuat via submit form biasa:', err);
            document.getElementById('reportFilterForm').submit();
        })
        .finally(() => {
            isReportFetching = false;
            if (container) {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
            if (statsGrid) {
                statsGrid.style.opacity = '1';
            }
        });
    }

    // Mengganti jenis laporan: refresh HANYA bagian tabel transaksi
    function handleJenisChange(jenis) {
        const form = document.getElementById('reportFilterForm');
        const url = new URL(form.action, window.location.origin);
        const formData = new FormData(form);
        formData.set('jenis', jenis);
        for (const [key, val] of formData.entries()) {
            url.searchParams.set(key, val);
        }
        fetchReportData(url.toString());
    }

    // Preset tanggal cepat yang otomatis mengisi & refresh via AJAX
    function setDatePreset(type) {
        const today = new Date();
        const pad = (n) => String(n).padStart(2, '0');
        const formatDate = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

        let start = new Date(today);
        let end = new Date(today);

        if (type === 'hari_ini') {
            start = new Date(today);
            end = new Date(today);
        } else if (type === '7_hari') {
            start = new Date(today);
            start.setDate(today.getDate() - 6);
            end = new Date(today);
        } else if (type === 'bulan_ini') {
            start = new Date(today.getFullYear(), today.getMonth(), 1);
            end = new Date(today);
        } else if (type === 'bulan_lalu') {
            start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            end = new Date(today.getFullYear(), today.getMonth(), 0);
        } else if (type === 'tahun_ini') {
            start = new Date(today.getFullYear(), 0, 1);
            end = new Date(today);
        }

        const tglMulaiInput = document.getElementById('filterTglMulai');
        const tglSelesaiInput = document.getElementById('filterTglSelesai');

        if (tglMulaiInput && tglSelesaiInput) {
            tglMulaiInput.value = formatDate(start);
            tglSelesaiInput.value = formatDate(end);
        }

        document.querySelectorAll('.preset-btn').forEach(btn => btn.classList.remove('active'));
        const clickedBtn = document.querySelector(`.preset-btn[data-preset="${type}"]`);
        if (clickedBtn) clickedBtn.classList.add('active');

        const form = document.getElementById('reportFilterForm');
        const url = new URL(form.action, window.location.origin);
        const formData = new FormData(form);
        for (const [key, val] of formData.entries()) {
            url.searchParams.set(key, val);
        }
        fetchReportData(url.toString());
    }

    function handleDateChange() {
        const start = document.getElementById('filterTglMulai')?.value;
        const end = document.getElementById('filterTglSelesai')?.value;
        if (start && end && start <= end) {
            const form = document.getElementById('reportFilterForm');
            const url = new URL(form.action, window.location.origin);
            const formData = new FormData(form);
            for (const [key, val] of formData.entries()) {
                url.searchParams.set(key, val);
            }
            fetchReportData(url.toString());
        }
    }

    function resetReportFilter() {
        const select = document.getElementById('filterJenisSelect');
        if (select) select.value = 'peminjaman';
        setDatePreset('bulan_ini');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('reportFilterForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const url = new URL(form.action, window.location.origin);
                const formData = new FormData(form);
                for (const [key, val] of formData.entries()) {
                    url.searchParams.set(key, val);
                }
                fetchReportData(url.toString());
            });
        }
    });

    window.addEventListener('popstate', function() {
        window.location.reload();
    });
</script>
@endsection
