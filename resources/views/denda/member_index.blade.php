@extends('layouts.anggota')

@section('title', 'Denda Peminjaman - BOOKNEST')

@section('styles')
<style>
    .denda-container {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
    }

    /* --- BREADCRUMB --- */
    .denda-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .denda-breadcrumb .brand-crumb {
        color: var(--theme-accent, #0f766e);
        text-decoration: none;
    }

    .denda-breadcrumb .brand-crumb:hover {
        text-decoration: underline;
    }

    .denda-breadcrumb .divider-crumb {
        color: #94a3b8;
    }

    .denda-breadcrumb .active-crumb {
        color: #64748b;
    }

    /* --- PAGE HEADER --- */
    .denda-header-area {
        margin-bottom: 24px;
    }

    .denda-main-heading {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        line-height: 1.25;
        color: #0f172a;
        letter-spacing: -0.6px;
    }

    .denda-main-subtitle {
        margin: 6px 0 0;
        font-size: 14px;
        color: #64748b;
        line-height: 1.5;
    }

    /* --- TOP STATS GRID (3 COLUMNS) --- */
    .denda-stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .stat-box:hover {
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
    }

    .stat-icon-tile {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--theme-accent-light, #ccfbf1);
        color: var(--theme-accent, #0f766e);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
    }

    .stat-caption {
        font-size: 13.5px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 6px;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        letter-spacing: -0.5px;
        margin-bottom: 6px;
    }

    .stat-note {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.4;
    }

    /* --- SECTION: DENDA BELUM DIBAYAR --- */
    .denda-section-group {
        margin-bottom: 32px;
    }

    .section-title-wrap {
        margin-bottom: 16px;
    }

    .section-headline {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
    }

    .section-meta-text {
        margin: 4px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .figma-table-container {
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        width: 100%;
    }

    .figma-table {
        width: 100%;
        min-width: 620px;
        border-collapse: collapse;
        font-size: 13.5px;
        text-align: left;
    }

    .figma-table th {
        background: #f8fafc;
        padding: 14px 20px;
        font-size: 12.5px;
        font-weight: 600;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        letter-spacing: -0.1px;
    }

    .figma-table td {
        padding: 16px 20px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .figma-table tr:last-child td {
        border-bottom: none;
    }

    .figma-table tr.clickable-row {
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .figma-table tr.clickable-row:hover {
        background-color: #f8fafc;
    }

    .figma-table tr.active-selected-row {
        background-color: #f0fdfa !important;
    }

    .book-title-cell {
        font-weight: 700;
        color: #0f172a;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: -0.2px;
    }

    .badge-belum {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-belum .badge-dot {
        color: #d97706;
        font-size: 9px;
    }

    .badge-lunas {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-lunas .badge-dot {
        color: #16a34a;
        font-size: 9px;
    }

    /* --- BOTTOM GRID (2 COLUMNS: RINCIAN & TOTAL PEMBAYARAN) --- */
    .denda-bottom-grid {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        gap: 24px;
        align-items: stretch;
    }

    /* Left Card: Rincian Keterlambatan */
    .rincian-card {
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
    }

    .card-block-title {
        margin: 0 0 20px;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
    }

    .rincian-data-list {
        display: flex;
        flex-direction: column;
        flex: 1;
        justify-content: space-around;
    }

    .rincian-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13.5px;
    }

    .rincian-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .rincian-label {
        color: #64748b;
        font-weight: 500;
    }

    .rincian-value {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
    }

    .rincian-value.mono {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
    }

    /* Right Card: Total Pembayaran */
    .bayar-card {
        background: var(--theme-accent-light, #f0fdfa);
        border: 1px solid var(--theme-border, #ccfbf1);
        border-radius: 16px;
        padding: 26px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .bayar-card-title {
        margin: 0 0 12px;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
    }

    .bayar-total-nominal {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.8px;
        line-height: 1.1;
        margin-bottom: 12px;
    }

    .bayar-status-wrap {
        margin-bottom: 16px;
    }

    .bayar-disclaimer {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        margin: 0 0 24px;
        flex: 1;
    }

    .btn-bayar-denda {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 13px 20px;
        background: var(--theme-btn-primary-bg, #0f766e);
        color: var(--theme-btn-primary-text, #ffffff);
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.15s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    .btn-bayar-denda:hover {
        background: var(--theme-btn-primary-hover, #115e59);
        color: var(--theme-btn-primary-text, #ffffff);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-bayar-denda.disabled {
        background: #cbd5e1;
        color: #64748b;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .denda-stats-row {
            grid-template-columns: 1fr;
        }

        .denda-bottom-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .denda-main-heading {
            font-size: 22px;
        }
        .stat-number {
            font-size: 24px;
        }
        .bayar-total-nominal {
            font-size: 26px;
        }
        .bayar-card, .rincian-card {
            padding: 18px 14px;
        }
    }

    /* ========================================================
       SCOPED THEME OVERRIDES FOR MEMBER DENDA
       ======================================================== */

    /* 1. DARK MODE */
    [data-theme="dark"] .denda-main-heading,
    [data-theme="dark"] .stat-number,
    [data-theme="dark"] .section-headline,
    [data-theme="dark"] .book-title-cell,
    [data-theme="dark"] .card-block-title,
    [data-theme="dark"] .rincian-value,
    [data-theme="dark"] .bayar-total-nominal {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .denda-main-subtitle,
    [data-theme="dark"] .stat-caption,
    [data-theme="dark"] .stat-note,
    [data-theme="dark"] .section-meta-text,
    [data-theme="dark"] .rincian-label,
    [data-theme="dark"] .denda-breadcrumb .active-crumb {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .stat-box,
    [data-theme="dark"] .figma-table-container,
    [data-theme="dark"] .rincian-card {
        background: #131d31 !important;
        border-color: #23314d !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
    }

    [data-theme="dark"] .figma-table th {
        background: #0b1220 !important;
        color: #94a3b8 !important;
        border-color: #23314d !important;
    }

    [data-theme="dark"] .figma-table td,
    [data-theme="dark"] .rincian-item {
        border-color: #23314d !important;
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .figma-table tr.clickable-row:hover {
        background-color: #172339 !important;
    }

    [data-theme="dark"] .figma-table tr.active-selected-row {
        background-color: rgba(20, 184, 166, 0.16) !important;
    }

    [data-theme="dark"] .bayar-card {
        background: #042f2e !important;
        border-color: #115e59 !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
    }

    [data-theme="dark"] .bayar-card-title {
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .bayar-disclaimer {
        color: #99f6e4 !important;
    }

    [data-theme="dark"] .btn-bayar-denda.disabled {
        background: #1e293b !important;
        color: #64748b !important;
    }

    /* 2. VAN GOGH (STARRY NIGHT) */
    [data-theme="van-gogh"] .denda-main-heading,
    [data-theme="van-gogh"] .stat-number,
    [data-theme="van-gogh"] .section-headline,
    [data-theme="van-gogh"] .book-title-cell,
    [data-theme="van-gogh"] .card-block-title,
    [data-theme="van-gogh"] .rincian-value,
    [data-theme="van-gogh"] .bayar-total-nominal {
        color: #f6f0df !important;
    }

    [data-theme="van-gogh"] .denda-main-subtitle,
    [data-theme="van-gogh"] .stat-caption,
    [data-theme="van-gogh"] .stat-note,
    [data-theme="van-gogh"] .section-meta-text,
    [data-theme="van-gogh"] .rincian-label,
    [data-theme="van-gogh"] .denda-breadcrumb .active-crumb {
        color: #9fb3c8 !important;
    }

    [data-theme="van-gogh"] .stat-box,
    [data-theme="van-gogh"] .figma-table-container,
    [data-theme="van-gogh"] .rincian-card {
        background: linear-gradient(180deg, #14243b 0%, #101c2e 100%) !important;
        border: 1px solid #1e3a5f !important;
        border-top: 2px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45), 0 0 1px rgba(245, 158, 11, 0.15) !important;
        color: #e2e8f0 !important;
    }

    [data-theme="van-gogh"] .figma-table th {
        background: #0f2035 !important;
        color: #f6f0df !important;
        border-color: #1e3a5f !important;
        font-weight: 700 !important;
    }

    [data-theme="van-gogh"] .figma-table td,
    [data-theme="van-gogh"] .rincian-item {
        border-color: #172c47 !important;
        color: #cbd5e1 !important;
    }

    [data-theme="van-gogh"] .figma-table tr.clickable-row:hover {
        background-color: #193354 !important;
    }

    [data-theme="van-gogh"] .figma-table tr.active-selected-row {
        background-color: rgba(36, 91, 143, 0.35) !important;
    }

    [data-theme="van-gogh"] .bayar-card {
        background: linear-gradient(135deg, #182b42 0%, #101c2e 100%) !important;
        border: 1px solid #2e4a6e !important;
        border-left: 4px solid #f59e0b !important;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.45) !important;
    }

    [data-theme="van-gogh"] .bayar-card-title {
        color: #fcd34d !important;
        text-shadow: 0 0 12px rgba(245, 158, 11, 0.3) !important;
    }

    [data-theme="van-gogh"] .bayar-disclaimer {
        color: #9fb3c8 !important;
    }

    [data-theme="van-gogh"] .btn-bayar-denda.disabled {
        background: #172c47 !important;
        color: #7892ab !important;
        border-color: #22446a !important;
    }

    /* 3. MIDNIGHT SLATE */
    [data-theme="midnight-slate"] .denda-main-heading,
    [data-theme="midnight-slate"] .stat-number,
    [data-theme="midnight-slate"] .section-headline,
    [data-theme="midnight-slate"] .book-title-cell,
    [data-theme="midnight-slate"] .card-block-title,
    [data-theme="midnight-slate"] .rincian-value,
    [data-theme="midnight-slate"] .bayar-total-nominal {
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .denda-main-subtitle,
    [data-theme="midnight-slate"] .stat-caption,
    [data-theme="midnight-slate"] .stat-note,
    [data-theme="midnight-slate"] .section-meta-text,
    [data-theme="midnight-slate"] .rincian-label,
    [data-theme="midnight-slate"] .denda-breadcrumb .active-crumb {
        color: #94a3b8 !important;
    }

    [data-theme="midnight-slate"] .stat-box,
    [data-theme="midnight-slate"] .figma-table-container,
    [data-theme="midnight-slate"] .rincian-card {
        background: #141f33 !important;
        border-color: #24354e !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3) !important;
        color: #e2e8f0 !important;
    }

    [data-theme="midnight-slate"] .figma-table th {
        background: #0f172a !important;
        color: #94a3b8 !important;
        border-color: #24354e !important;
    }

    [data-theme="midnight-slate"] .figma-table td,
    [data-theme="midnight-slate"] .rincian-item {
        border-color: #24354e !important;
        color: #cbd5e1 !important;
    }

    [data-theme="midnight-slate"] .figma-table tr.clickable-row:hover {
        background-color: #1e2c42 !important;
    }

    [data-theme="midnight-slate"] .figma-table tr.active-selected-row {
        background-color: rgba(56, 189, 248, 0.16) !important;
    }

    [data-theme="midnight-slate"] .bayar-card {
        background: #141f33 !important;
        border-color: #24354e !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="midnight-slate"] .bayar-card-title {
        color: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .bayar-disclaimer {
        color: #94a3b8 !important;
    }

    [data-theme="midnight-slate"] .btn-bayar-denda.disabled {
        background: #1e293b !important;
        color: #64748b !important;
    }

    /* 4. SOFT BLUSH */
    [data-theme="soft-blush"] .stat-box,
    [data-theme="soft-blush"] .figma-table-container,
    [data-theme="soft-blush"] .rincian-card {
        background: #ffffff !important;
        border-color: #eedde2 !important;
        box-shadow: 0 4px 16px rgba(184, 93, 119, 0.06) !important;
    }

    [data-theme="soft-blush"] .figma-table th {
        background: #fdf2f5 !important;
        color: #826e75 !important;
        border-color: #eedde2 !important;
    }

    [data-theme="soft-blush"] .figma-table td,
    [data-theme="soft-blush"] .rincian-item {
        border-color: #f7e8ec !important;
        color: #4a2835 !important;
    }

    [data-theme="soft-blush"] .bayar-card {
        background: #fdf2f5 !important;
        border-color: #eedde2 !important;
        box-shadow: 0 4px 16px rgba(184, 93, 119, 0.08) !important;
    }

    [data-theme="soft-blush"] .bayar-card-title {
        color: #b85d77 !important;
    }

    [data-theme="soft-blush"] .bayar-disclaimer {
        color: #826e75 !important;
    }

    [data-theme="soft-blush"] .btn-bayar-denda.disabled {
        background: #eedde2 !important;
        color: #826e75 !important;
    }
</style>
@endsection

@section('content')
<div class="denda-container">

    <!-- BREADCRUMB -->
    <nav class="denda-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}" class="brand-crumb">BOOKNEST</a>
        <span class="divider-crumb">/</span>
        <span class="active-crumb">Anggota</span>
    </nav>

    <!-- PAGE HEADER -->
    <div class="denda-header-area">
        <h1 class="denda-main-heading">Denda Peminjaman</h1>
        <p class="denda-main-subtitle">Rincian keterlambatan dan pembayaran, transparan dalam satu tempat.</p>
    </div>

    <!-- 3 SUMMARY STAT CARDS -->
    <div class="denda-stats-row">
        <!-- Card 1: Total Belum Dibayar -->
        <div class="stat-box">
            <div class="stat-icon-tile">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    <line x1="9" y1="6" x2="15" y2="6"></line>
                    <line x1="9" y1="10" x2="15" y2="10"></line>
                </svg>
            </div>
            <div class="stat-caption">Total belum dibayar</div>
            <div class="stat-number">
                Rp{{ number_format($totalTunggakan, 0, ',', '.') }}
            </div>
            <div class="stat-note">
                @if ($dendaBelumDibayar->count() > 0)
                    {{ $dendaBelumDibayar->count() }} transaksi keterlambatan
                @else
                    Tidak ada tunggakan
                @endif
            </div>
        </div>

        <!-- Card 2: Keterlambatan -->
        <div class="stat-box">
            <div class="stat-icon-tile">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    <line x1="9" y1="6" x2="15" y2="6"></line>
                    <line x1="9" y1="10" x2="15" y2="10"></line>
                </svg>
            </div>
            <div class="stat-caption">Keterlambatan</div>
            <div class="stat-number">
                {{ $hariTerlambat }} hari
            </div>
            <div class="stat-note">
                {{ $rentangTanggal }}
            </div>
        </div>

        <!-- Card 3: Tarif per hari -->
        <div class="stat-box">
            <div class="stat-icon-tile">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    <line x1="9" y1="6" x2="15" y2="6"></line>
                    <line x1="9" y1="10" x2="15" y2="10"></line>
                </svg>
            </div>
            <div class="stat-caption">Tarif per hari</div>
            <div class="stat-number">
                Rp1.000
            </div>
            <div class="stat-note">
                Per buku · Tanpa biaya tambahan
            </div>
        </div>
    </div>

    <!-- SECTION: DENDA BELUM DIBAYAR (TABLE) -->
    <div class="denda-section-group">
        <div class="section-title-wrap">
            <h2 class="section-headline">Denda belum dibayar</h2>
            <p class="section-meta-text">
                @php
                    $firstUnpaidBook = $activeDenda?->pengembalian?->peminjaman?->details?->first()?->buku?->judul
                        ?? ($dendaBelumDibayar->first()?->pengembalian?->peminjaman?->details?->first()?->buku?->judul ?? null);
                @endphp
                @if ($dendaBelumDibayar->count() > 0 && $firstUnpaidBook)
                    {{ $dendaBelumDibayar->count() }} transaksi · {{ $firstUnpaidBook }}
                @elseif ($dendaBelumDibayar->count() > 0)
                    {{ $dendaBelumDibayar->count() }} transaksi denda aktif
                @else
                    Semua tagihan tercatat lunas atau belum ada catatan denda
                @endif
            </p>
        </div>

        <div class="figma-table-container">
            <table class="figma-table">
                <thead>
                    <tr>
                        <th style="width: 32%;">Judul Buku</th>
                        <th style="width: 15%;">Terlambat</th>
                        <th style="width: 18%;">Denda</th>
                        <th style="width: 18%;">Dikembalikan</th>
                        <th style="width: 17%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dendas as $d)
                        @php
                            $rowBuku = $d->pengembalian?->peminjaman?->details?->first()?->buku;
                            $rowJudul = $rowBuku?->judul ?? 'Buku Perpustakaan';
                            $rowBatas = $d->pengembalian?->peminjaman?->batasKembali ? \Carbon\Carbon::parse($d->pengembalian->peminjaman->batasKembali) : null;
                            $rowKembali = $d->pengembalian?->tanggalKembali ? \Carbon\Carbon::parse($d->pengembalian->tanggalKembali) : null;

                            $rowHari = 0;
                            if ($rowBatas && $rowKembali && $rowKembali->greaterThan($rowBatas)) {
                                $rowHari = (int) $rowBatas->diffInDays($rowKembali);
                            }
                            if ($rowHari === 0 && $d->jumlah > 0 && $d->jenisDenda === 'Keterlambatan') {
                                $rowHari = max(1, (int) round($d->jumlah / 1000));
                            }

                            $isSelected = $activeDenda && $activeDenda->idDenda === $d->idDenda;
                        @endphp
                        <tr class="clickable-row {{ $isSelected ? 'active-selected-row' : '' }}" onclick="window.location='{{ route('denda.saya', ['selected' => $d->idDenda]) }}'" title="Klik untuk melihat rincian denda ini">
                            <td class="book-title-cell">
                                {{ $rowJudul }}
                            </td>
                            <td>
                                {{ $rowHari > 0 ? $rowHari . ' hari' : '0 hari' }}
                            </td>
                            <td>
                                <strong>Rp{{ number_format($d->jumlah, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                {{ $rowKembali ? $rowKembali->locale('id')->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td>
                                @if ($d->status === 'Belum Dibayar')
                                    <span class="badge-status badge-belum">
                                        <span class="badge-dot">●</span> Belum Lunas
                                    </span>
                                @else
                                    <span class="badge-status badge-lunas">
                                        <span class="badge-dot">●</span> Tercatat lunas · belum diverifikasi
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted, #94a3b8); padding: 40px 20px; background: transparent;">
                                Belum ada riwayat keterlambatan atau tagihan denda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($dendas->hasPages())
            <div style="margin-top: 16px;">
                {{ $dendas->links() }}
            </div>
        @endif
    </div>

    <!-- BOTTOM GRID: 2 COLUMNS (RINCIAN KETERLAMBATAN & TOTAL PEMBAYARAN) -->
    <div class="denda-bottom-grid">
        <!-- Kolom Kiri: Rincian Keterlambatan -->
        <div class="rincian-card">
            <h3 class="card-block-title">Rincian keterlambatan</h3>

            <div class="rincian-data-list">
                @php
                    $kodeTx = $peminjaman?->kode_transaksi
                        ?? ($activeDenda ? 'PJ-' . date('Ymd') . '-' . str_pad($activeDenda->idDenda, 4, '0', STR_PAD_LEFT) : '-');
                    $tglPinjamText = $peminjaman?->tanggalPinjam
                        ? \Carbon\Carbon::parse($peminjaman->tanggalPinjam)->locale('id')->translatedFormat('d M Y')
                        : '-';
                    $tglJatuhTempoText = $peminjaman?->batasKembali
                        ? \Carbon\Carbon::parse($peminjaman->batasKembali)->locale('id')->translatedFormat('d M Y')
                        : '-';
                    $tglKembaliText = $pengembalian?->tanggalKembali
                        ? \Carbon\Carbon::parse($pengembalian->tanggalKembali)->locale('id')->translatedFormat('d M Y')
                        : '-';
                @endphp

                <div class="rincian-item">
                    <span class="rincian-label">Nomor transaksi</span>
                    <span class="rincian-value mono">{{ $kodeTx }}</span>
                </div>

                <div class="rincian-item">
                    <span class="rincian-label">Tanggal pinjam</span>
                    <span class="rincian-value">{{ $tglPinjamText }}</span>
                </div>

                <div class="rincian-item">
                    <span class="rincian-label">Jatuh tempo</span>
                    <span class="rincian-value">{{ $tglJatuhTempoText }}</span>
                </div>

                <div class="rincian-item">
                    <span class="rincian-label">Tanggal kembali</span>
                    <span class="rincian-value">{{ $tglKembaliText }}</span>
                </div>

                <div class="rincian-item">
                    <span class="rincian-label">Perhitungan</span>
                    <span class="rincian-value">{{ $perhitunganText }}</span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Total Pembayaran -->
        <div class="bayar-card">
            <div>
                <h3 class="bayar-card-title">Total pembayaran</h3>

                @php
                    $nominalTampil = $activeDenda?->status === 'Belum Dibayar'
                        ? $activeDenda->jumlah
                        : ($totalTunggakan > 0 ? $totalTunggakan : ($activeDenda?->jumlah ?? 0));
                    $isUnpaid = ($activeDenda?->status === 'Belum Dibayar') || ($totalTunggakan > 0);
                @endphp

                <div class="bayar-total-nominal">
                    Rp{{ number_format($nominalTampil, 0, ',', '.') }}
                </div>

                <div class="bayar-status-wrap">
                    @if ($isUnpaid)
                        <span class="badge-status badge-belum">
                            <span class="badge-dot">●</span> Belum Lunas
                        </span>
                    @else
                        <span class="badge-status badge-lunas">
                            <span class="badge-dot">●</span> Tercatat lunas · belum diverifikasi
                        </span>
                    @endif
                </div>

                <p class="bayar-disclaimer">
                    Status pembayaran tersimpan di BOOKNEST dan belum dapat diverifikasi gateway. Nota tersedia untuk catatan transaksi berstatus sukses.
                </p>
            </div>

            <div>
                @if ($activeDenda && $activeDenda->status === 'Belum Dibayar')
                    <a href="{{ route('bayar.qr', $activeDenda->idDenda) }}" class="btn-bayar-denda">
                        Bayar Denda
                    </a>
                @elseif ($dendaBelumDibayar->isNotEmpty())
                    <a href="{{ route('bayar.qr', $dendaBelumDibayar->first()->idDenda) }}" class="btn-bayar-denda">
                        Bayar Denda
                    </a>
                @else
                    <button type="button" class="btn-bayar-denda disabled" disabled>
                        Tagihan tercatat lunas
                    </button>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
