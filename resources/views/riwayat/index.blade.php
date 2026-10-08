@extends('layouts.anggota')

@section('title', 'Riwayat Peminjaman - BOOKNEST')

@section('styles')
<style>
    .riwayat-page {
        width: 100%;
        max-width: 1240px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    /* --- PAGE HEADER --- */
    .riwayat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .riwayat-title-group {
        flex: 1;
        min-width: 280px;
    }

    .riwayat-heading {
        margin: 0;
        font-size: 26px;
        line-height: 1.25;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.5px;
    }

    .riwayat-subtitle {
        margin: 6px 0 0;
        color: var(--text-muted);
        font-size: 13.5px;
        line-height: 1.5;
    }

    .btn-back-dasbor {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        background: #ffffff;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .btn-back-dasbor:hover {
        border-color: #cbd5e1;
        color: var(--brand-primary);
        background: #f8fafc;
        transform: translateY(-1px);
    }

    /* --- STATS SUMMARY CARDS --- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
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

    .stat-icon-teal {
        background: #ccfbf1;
        color: #0f766e;
    }

    .stat-icon-amber {
        background: #fef3c7;
        color: #b45309;
    }

    .stat-icon-emerald {
        background: #dcfce7;
        color: #15803d;
    }

    .stat-icon-rose {
        background: #ffe4e6;
        color: #e11d48;
    }

    .stat-number {
        font-size: 22px;
        font-weight: 800;
        line-height: 1.2;
        color: var(--text-heading);
        letter-spacing: -0.5px;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        margin-top: 2px;
    }

    /* --- FILTER TABS BAR --- */
    .filter-tabs-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 12px;
        width: fit-content;
        max-width: 100%;
        overflow-x: auto;
    }

    .filter-tab-btn {
        padding: 8px 18px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        text-decoration: none;
        transition: all 0.15s ease;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .filter-tab-btn:hover {
        color: var(--text-heading);
    }

    .filter-tab-btn.active {
        background: #ffffff;
        color: #0f766e;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    }

    .filter-badge-count {
        background: #e2e8f0;
        color: #475569;
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 10px;
        font-weight: 800;
    }

    .filter-tab-btn.active .filter-badge-count {
        background: #ccfbf1;
        color: #0f766e;
    }

    /* --- MAIN RIWAYAT CARD & TABLE --- */
    .riwayat-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .riwayat-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .riwayat-card-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text-heading);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .riwayat-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 13px;
    }

    .riwayat-table th {
        background: #f8fafc;
        padding: 13px 20px;
        font-size: 11.5px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .riwayat-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text-body);
        vertical-align: middle;
    }

    .riwayat-table tbody tr:hover td {
        background-color: #f8fafc;
    }

    .riwayat-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* TRX BADGE */
    .trx-code-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-family: monospace;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
    }

    /* BOOK DETAILS */
    .book-item-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .book-icon-box {
        width: 38px;
        height: 38px;
        background: #ccfbf1;
        color: #0f766e;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .book-info-text {
        display: flex;
        flex-direction: column;
    }

    .book-title-name {
        font-weight: 700;
        color: var(--text-heading);
        font-size: 13.5px;
        line-height: 1.35;
    }

    .book-meta-sub {
        font-size: 11.5px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    /* STATUS BADGES */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 800;
        white-space: nowrap;
    }

    .badge-status.dipinjam {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .badge-status.selesai {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .badge-status.overdue {
        background: #ffe4e6;
        color: #be123c;
        border: 1px solid #fecdd3;
    }

    /* DATE DISPLAY */
    .date-display {
        font-weight: 700;
        color: var(--text-heading);
        white-space: nowrap;
    }

    .due-date-box {
        display: flex;
        flex-direction: column;
    }

    .overdue-tag {
        font-size: 11px;
        font-weight: 800;
        color: #dc2626;
        background: #fef2f2;
        padding: 2px 6px;
        border-radius: 4px;
        width: fit-content;
        margin-top: 3px;
    }

    /* RETURN & FINE ACTION */
    .action-pay-qr {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #0f766e;
        color: #ffffff;
        padding: 6px 13px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.15s ease, transform 0.15s ease;
    }

    .action-pay-qr:hover {
        background: #115e59;
        transform: translateY(-1px);
        color: #ffffff;
    }

    /* EMPTY STATE */
    .empty-state-wrap {
        padding: 60px 24px;
        text-align: center;
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        background: #ccfbf1;
        color: #0f766e;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .empty-state-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-heading);
        margin: 0;
    }

    .empty-state-desc {
        font-size: 13.5px;
        color: var(--text-muted);
        max-width: 420px;
        margin: 8px auto 20px;
        line-height: 1.5;
    }

    .btn-explore-katalog {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0f766e;
        color: #ffffff;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.15s ease;
    }

    .btn-explore-katalog:hover {
        background: #115e59;
        color: #ffffff;
    }

    .btn-kembalikan-shortcut {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        background: #0f766e;
        color: #ffffff;
        font-size: 11.5px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(15, 118, 110, 0.2);
        margin-top: 4px;
    }

    .btn-kembalikan-shortcut:hover {
        background: #115e59;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(15, 118, 110, 0.25);
    }

    .pagination-footer {
        padding: 16px 24px;
        border-top: 1px solid #f1f5f9;
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .riwayat-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-back-dasbor {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="riwayat-page">

    {{-- PAGE HEADER --}}
    <div class="riwayat-header">
        <div class="riwayat-title-group">
            <h1 class="riwayat-heading">
                {{ $statusDipilih === 'Dipinjam' ? 'Peminjaman Aktif' : ($statusDipilih === 'Selesai' ? 'Riwayat Pengembalian' : 'Riwayat Peminjaman Buku') }}
            </h1>
            <p class="riwayat-subtitle">
                Daftar lengkap seluruh transaksi peminjaman, jatuh tempo, dan status pengembalian buku Anda.
            </p>
        </div>

        <a href="{{ route('dashboard') }}" class="btn-back-dasbor">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali ke Dasbor</span>
        </a>
    </div>

    {{-- STATS CARDS ROW --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-box stat-icon-teal">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalPinjam ?? 0 }}</div>
                <div class="stat-label">Total Transaksi</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box stat-icon-amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalAktif ?? 0 }}</div>
                <div class="stat-label">Sedang Dipinjam</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box stat-icon-emerald">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalSelesai ?? 0 }}</div>
                <div class="stat-label">Selesai Dikembalikan</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box {{ ($totalTerlambat ?? 0) > 0 ? 'stat-icon-rose' : 'stat-icon-teal' }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div>
                <div class="stat-number" style="{{ ($totalTerlambat ?? 0) > 0 ? 'color: #e11d48;' : '' }}">{{ $totalTerlambat ?? 0 }}</div>
                <div class="stat-label">Terlambat Kembali</div>
            </div>
        </div>
    </div>

    {{-- FILTER TABS BAR --}}
    <div class="filter-tabs-bar">
        <a href="{{ route('riwayat.index') }}" class="filter-tab-btn {{ is_null($statusDipilih) ? 'active' : '' }}">
            <span>Semua Peminjaman</span>
            <span class="filter-badge-count">{{ $totalPinjam ?? 0 }}</span>
        </a>

        <a href="{{ route('riwayat.index', ['status' => 'Booking']) }}" class="filter-tab-btn {{ $statusDipilih === 'Booking' ? 'active' : '' }}">
            <span>Booking Menunggu Ambil</span>
            <span class="filter-badge-count">{{ $totalBooking ?? 0 }}</span>
        </a>

        <a href="{{ route('riwayat.index', ['status' => 'Dipinjam']) }}" class="filter-tab-btn {{ $statusDipilih === 'Dipinjam' ? 'active' : '' }}">
            <span>Sedang Dipinjam</span>
            <span class="filter-badge-count">{{ $totalAktif ?? 0 }}</span>
        </a>

        <a href="{{ route('riwayat.index', ['status' => 'Selesai']) }}" class="filter-tab-btn {{ $statusDipilih === 'Selesai' ? 'active' : '' }}">
            <span>Selesai</span>
            <span class="filter-badge-count">{{ $totalSelesai ?? 0 }}</span>
        </a>
    </div>

    {{-- MAIN TABLE CARD --}}
    <div class="riwayat-card">

        <div class="riwayat-card-header">
            <h3 class="riwayat-card-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #0f766e;">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                </svg>
                Daftar Transaksi Peminjaman
            </h3>

            <span style="font-size: 12.5px; color: var(--text-muted); font-weight: 600;">
                Menampilkan {{ $riwayats->count() }} dari {{ $riwayats->total() }} data
            </span>
        </div>

        @if ($riwayats->count() > 0)
            <div class="table-responsive">
                <table class="riwayat-table">
                    <thead>
                        <tr>
                            <th style="width: 130px;">Kode TRX</th>
                            <th>Detail Buku</th>
                            <th style="width: 140px;">Tgl Pinjam</th>
                            <th style="width: 150px;">Jatuh Tempo</th>
                            <th style="width: 140px;">Status</th>
                            <th style="width: 170px;">Pengembalian & Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayats as $item)
                            @php
                                $tanggalPinjam = $item->tanggalPinjam
                                    ? \Carbon\Carbon::parse($item->tanggalPinjam)
                                    : null;

                                $batasKembali = $item->batasKembali
                                    ? \Carbon\Carbon::parse($item->batasKembali)
                                    : null;

                                $isOverdue = $batasKembali &&
                                    \Carbon\Carbon::now()->greaterThan($batasKembali) &&
                                    $item->status === 'Dipinjam';

                                $selisihHariOverdue = $isOverdue
                                    ? ceil(\Carbon\Carbon::now()->diffInDays($batasKembali))
                                    : 0;
                            @endphp

                            <tr>
                                {{-- TRX CODE --}}
                                <td>
                                    <span class="trx-code-badge">
                                        #TRX-{{ str_pad($item->idPeminjaman, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                {{-- BOOKS LIST --}}
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                        @foreach ($item->details as $detail)
                                            <div class="book-item-row">
                                                <div class="book-icon-box">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                                    </svg>
                                                </div>
                                                <div class="book-info-text">
                                                    <span class="book-title-name">{{ $detail->buku->judul ?? 'Buku Tidak Ditemukan' }}</span>
                                                    <span class="book-meta-sub">
                                                        {{ $detail->buku->penulis ?? 'Anonim' }} • Rak: {{ $detail->buku->rak ?? '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- BORROW DATE --}}
                                <td>
                                    @if ($tanggalPinjam)
                                        <span class="date-display">{{ $tanggalPinjam->translatedFormat('d M Y') }}</span>
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>

                                {{-- DUE DATE --}}
                                <td>
                                    @if ($batasKembali)
                                        <div class="due-date-box">
                                            <span class="date-display" style="{{ $isOverdue ? 'color: #dc2626;' : '' }}">
                                                {{ $batasKembali->translatedFormat('d M Y') }}
                                            </span>
                                            @if ($isOverdue)
                                                <span class="overdue-tag">
                                                    Terlambat {{ $selisihHariOverdue }} Hari
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @if ($item->status === 'Booking')
                                        <span class="badge-status" style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 11.5px; display: inline-flex; align-items: center; gap: 6px;">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #d97706;"></span>
                                            Menunggu Ambil
                                        </span>
                                    @elseif ($item->status === 'Siap Diambil')
                                        <span class="badge-status" style="background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 11.5px; display: inline-flex; align-items: center; gap: 6px;">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #2563eb;"></span>
                                            Siap di Meja Layanan
                                        </span>
                                    @elseif ($item->status === 'Dipinjam')
                                        @if ($isOverdue)
                                            <span class="badge-status overdue">
                                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #be123c;"></span>
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="badge-status dipinjam">
                                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #b45309;"></span>
                                                Sedang Dipinjam
                                            </span>
                                        @endif
                                    @elseif ($item->status === 'Selesai')
                                        <span class="badge-status selesai">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #15803d;"></span>
                                            Selesai
                                        </span>
                                    @elseif ($item->status === 'Dibatalkan')
                                        <span class="badge-status" style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 11.5px;">
                                            Dibatalkan
                                        </span>
                                    @else
                                        <span class="badge-status" style="background: #f1f5f9; color: #475569;">
                                            {{ $item->status }}
                                        </span>
                                    @endif
                                </td>

                                {{-- RETURN & FINE DETAILS --}}
                                <td>
                                    @if (in_array($item->status, ['Booking', 'Siap Diambil']))
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                            <a href="{{ route('member.kartu-saya') }}" target="_blank" class="action-pay-qr" style="background: #0f766e; text-decoration: none;" title="Tunjukkan QR Anggota ke petugas meja sirkulasi">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                                    <rect x="3" y="3" width="7" height="7"></rect>
                                                    <rect x="14" y="3" width="7" height="7"></rect>
                                                    <rect x="14" y="14" width="7" height="7"></rect>
                                                    <rect x="3" y="14" width="7" height="7"></rect>
                                                </svg>
                                                <span>QR Anggota</span>
                                            </a>
                                            <span style="font-size: 11px; color: #64748b;">
                                                Tunjukkan ke Petugas
                                            </span>
                                        </div>
                                    @elseif ($item->pengembalians->isNotEmpty())
                                        <div style="display: flex; flex-direction: column; gap: 4px;">
                                            @foreach ($item->pengembalians as $ret)
                                                <span style="font-weight: 700; font-size: 12.5px; color: var(--text-heading);">
                                                    Dikembalikan: {{ \Carbon\Carbon::parse($ret->tanggalKembali)->translatedFormat('d M Y') }}
                                                </span>

                                                @if ($ret->denda)
                                                    @if ($ret->denda->status === 'Belum Dibayar')
                                                        <div style="margin-top: 4px;">
                                                            <a href="{{ route('bayar.qr', $ret->denda->idDenda) }}" class="action-pay-qr">
                                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="3" y="3" width="7" height="7"></rect>
                                                                    <rect x="14" y="3" width="7" height="7"></rect>
                                                                    <rect x="14" y="14" width="7" height="7"></rect>
                                                                    <rect x="3" y="14" width="7" height="7"></rect>
                                                                </svg>
                                                                <span>Bayar Denda</span>
                                                            </a>
                                                        </div>
                                                    @else
                                                        <span style="font-size: 11.5px; color: #16a34a; font-weight: 700;">
                                                            ✓ Denda Lunas
                                                        </span>
                                                    @endif
                                                @else
                                                    <span style="font-size: 11.5px; color: #64748b;">
                                                        Kondisi: {{ $ret->kondisiBuku ?? 'Baik' }}
                                                    </span>
                                                @endif

                                                <a href="{{ route('pengembalian.member.bukti', $ret->idPengembalian) }}" style="font-size: 11.5px; color: #0f766e; font-weight: 700; text-decoration: underline; margin-top: 2px;">
                                                    Lihat Resi &rarr;
                                                </a>
                                            @endforeach
                                        </div>
                                    @elseif ($item->status === 'Dibatalkan')
                                        <span style="font-size: 12px; color: #94a3b8; font-style: italic;">
                                            Booking Dibatalkan
                                        </span>
                                    @else
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                            <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; color: #b45309; background: #fef3c7; padding: 2px 8px; border-radius: 6px;">
                                                ● Belum Dikembalikan
                                            </span>
                                            @if($item->status === 'Dipinjam')
                                                <a href="{{ route('pengembalian.member', ['highlight' => $item->idPeminjaman]) }}" class="btn-kembalikan-shortcut" title="Menuju ke halaman pengembalian">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                                        <path d="M21 3v5h-5"></path>
                                                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                                        <path d="M3 21v-5h5"></path>
                                                    </svg>
                                                    <span>Kembalikan</span>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($riwayats->hasPages())
                <div class="pagination-footer">
                    {{ $riwayats->links() }}
                </div>
            @endif
        @else
            {{-- EMPTY STATE --}}
            <div class="empty-state-wrap">
                <div class="empty-state-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <h3 class="empty-state-title">Belum Ada Transaksi Peminjaman</h3>
                <p class="empty-state-desc">
                    @if ($statusDipilih)
                        Tidak ada transaksi peminjaman dengan status <strong>"{{ $statusDipilih }}"</strong>.
                    @else
                        Anda belum memiliki riwayat peminjaman buku di perpustakaan. Silakan cari dan pinjam buku favorit Anda di katalog!
                    @endif
                </p>
                <a href="{{ route('katalog.index') }}" class="btn-explore-katalog">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <span>Jelajahi Katalog Buku</span>
                </a>
            </div>
        @endif

    </div>

</div>
@endsection