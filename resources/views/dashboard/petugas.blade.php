@extends('layouts.petugas')

@section('title', 'Dasbor Petugas - BOOKNEST')

@section('styles')
<style>
    /* --- BREADCRUMB & HEADER --- */
    .dashboard-header {
        margin-bottom: 22px;
    }

    .breadcrumb {
        font-size: 12px;
        font-weight: 700;
        color: #0f766e;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 6px;
    }

    .dashboard-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.6px;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .dashboard-subtitle {
        font-size: 13.5px;
        color: var(--text-muted);
    }

    /* --- STATS GRID (5 COLS) --- */
    .stats-grid-5 {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    /* --- NOTIFICATION BANNER BOOKING BARU --- */
    .booking-notification-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%);
        border: 1px solid #fde68a;
        border-left: 5px solid #d97706;
        border-radius: 14px;
        padding: 14px 20px;
        margin-bottom: 22px;
        box-shadow: 0 2px 8px rgba(217, 119, 6, 0.08);
        animation: pulseSubtle 3s infinite;
    }

    @keyframes pulseSubtle {
        0%, 100% { box-shadow: 0 2px 8px rgba(217, 119, 6, 0.08); }
        50% { box-shadow: 0 4px 14px rgba(217, 119, 6, 0.18); }
    }

    .banner-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .banner-icon-badge {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f59e0b;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .banner-title {
        font-size: 14.5px;
        font-weight: 800;
        color: #92400e;
        margin-bottom: 2px;
    }

    .banner-desc {
        font-size: 12.5px;
        color: #b45309;
        margin: 0;
    }

    .banner-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: #d97706;
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 700;
        border-radius: 8px;
        text-decoration: none;
        transition: background 0.15s ease;
        white-space: nowrap;
    }

    .banner-cta-btn:hover {
        background: #b45309;
        color: #ffffff;
    }

    /* --- BADGES & BUTTONS BOOKING --- */
    .badge-booking-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .badge-booking-menunggu {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-booking-siap {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-option-petugas {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11.5px;
        font-weight: 700;
        color: #065f46;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        padding: 3px 9px;
        border-radius: 6px;
    }

    .badge-option-mandiri {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11.5px;
        font-weight: 700;
        color: #1e40af;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        padding: 3px 9px;
        border-radius: 6px;
    }

    .rack-tag {
        font-weight: 800;
        color: #0f766e;
        background: #ccfbf1;
        padding: 2px 7px;
        border-radius: 5px;
        font-size: 11.5px;
    }

    .btn-siapkan-action {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        background: #0284c7;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .btn-siapkan-action:hover {
        background: #0369a1;
    }

    .btn-proses-action {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        background: #0f766e;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        border-radius: 8px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-proses-action:hover {
        background: #115e59;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(15, 118, 110, 0.25);
    }

    .stat-box {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .stat-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.04);
    }

    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: #ccfbf1;
        color: #0f766e;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    .stat-label {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.5px;
        line-height: 1.1;
        margin-bottom: 6px;
    }

    .stat-caption {
        font-size: 11.5px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* --- TABLE SECTION: TRANSAKSI PEMINJAMAN --- */
    .section-container {
        margin-bottom: 30px;
    }

    .section-header-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .section-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.3px;
        margin-bottom: 2px;
    }

    .section-subtitle {
        font-size: 12.5px;
        color: var(--text-muted);
    }

    .table-search-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-search-label {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        white-space: nowrap;
    }

    .table-search-input-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 9px;
        padding: 7px 12px;
        width: 260px;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .table-search-input-wrap:focus-within {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
    }

    .table-search-input-wrap input {
        border: none;
        outline: none;
        background: transparent;
        font-size: 12.5px;
        width: 100%;
        color: var(--text-heading);
    }

    .btn-table-search {
        padding: 8px 18px;
        background: #0f766e;
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 700;
        border-radius: 9px;
        transition: background 0.15s;
    }

    .btn-table-search:hover {
        background: #115e59;
    }

    .table-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        text-align: left;
    }

    .custom-table th {
        background: #ffffff;
        padding: 14px 18px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        border-bottom: 1px solid #f1f5f9;
    }

    .custom-table td {
        padding: 14px 18px;
        color: #334155;
        border-bottom: 1px solid #f8fafc;
        vertical-align: middle;
    }

    .custom-table tr:last-child td {
        border-bottom: none;
    }

    .custom-table tr:hover td {
        background-color: #fbfcfd;
    }

    .td-judul-buku {
        font-weight: 700;
        color: var(--text-heading);
    }

    .td-kode {
        font-size: 12px;
        color: #64748b;
        font-family: monospace;
        font-weight: 600;
    }

    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-status-pill.dipinjam {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge-status-pill.dipinjam .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #2563eb;
    }

    .badge-status-pill.terlambat {
        background: #fee2e2;
        color: #ef4444;
    }

    .badge-status-pill.terlambat .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #ef4444;
    }

    /* --- BOTTOM SECTION: 2 COLUMNS --- */
    .bottom-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .card-bottom {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .card-bottom-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 16px;
        letter-spacing: -0.3px;
    }

    /* Aktivitas List */
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .activity-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        font-size: 13px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f8fafc;
    }

    .activity-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .activity-left {
        color: #64748b;
    }

    .activity-member {
        font-weight: 700;
        color: var(--text-heading);
    }

    .activity-right {
        font-weight: 600;
        color: #334155;
        text-align: right;
    }

    /* Pengingat Petugas Box */
    .card-pengingat {
        background: #f0fdfa;
        border-color: #99f6e4;
    }

    .pengingat-body {
        font-size: 13px;
        color: #475569;
        line-height: 1.6;
    }

    .pengingat-footer {
        margin-top: 14px;
        font-size: 13.5px;
        font-weight: 800;
        color: #0f766e;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 1200px) {
        .stats-grid-5 {
            grid-template-columns: repeat(3, 1fr);
        }

        .bottom-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-grid-5 {
            grid-template-columns: 1fr;
        }

        .action-buttons-row {
            flex-direction: column;
            align-items: stretch;
        }

        .action-buttons-row a {
            justify-content: center;
        }

        .table-search-form {
            width: 100%;
        }

        .table-search-input-wrap {
            width: 100%;
        }

        .table-card {
            overflow-x: auto;
        }

        .custom-table {
            min-width: 580px;
        }
    }
</style>
@endsection

@section('content')
<div class="petugas-dashboard">

    <!-- 1. BREADCRUMB & HEADER -->
    <div class="dashboard-header">
        <div class="breadcrumb">BOOKNEST / Petugas</div>
        <h1 class="dashboard-title">Dasbor Petugas</h1>
        <p class="dashboard-subtitle">
            Selamat bertugas, {{ $petugas->name }}. Ringkasan layanan hari ini, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}.
        </p>
    </div>

    <!-- FLASH ALERT -->
    @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 18px; border-radius: 12px; font-size: 13.5px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 18px; border-radius: 12px; font-size: 13.5px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- NOTIFIKASI BOOKING BARU -->
    @if(isset($antreanBooking) && $antreanBooking->isNotEmpty())
        <div class="booking-notification-banner">
            <div class="banner-left">
                <div class="banner-icon-badge">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </div>
                <div>
                    <div class="banner-title">Pemberitahuan Sirkulasi: {{ $antreanBooking->count() }} Booking Buku Menunggu Pengambilan</div>
                    <p class="banner-desc">
                        Terdapat <strong>{{ $totalBookingPerluDisiapkan }}</strong> buku yang dipilih untuk <em>Disiapkan oleh Petugas</em>. Silakan ambil buku di rak dan letakkan di meja layanan reservasi.
                    </p>
                </div>
            </div>
            <a href="#antrean-booking" class="banner-cta-btn">
                <span>Lihat Antrean</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </a>
        </div>
    @endif

    <!-- 2. STATS CARDS (5 COLS) -->
    <div class="stats-grid-5">
        <!-- Card 1: Antrean Booking Menunggu -->
        <div class="stat-box" style="{{ ($statistik['bookingMenunggu'] ?? 0) > 0 ? 'border-color: #fde68a; background: #fffdf5;' : '' }}">
            <div>
                <div class="stat-icon" style="background: #fef3c7; color: #b45309;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </div>
                <div class="stat-label">Booking menunggu</div>
                <div class="stat-value" style="{{ ($statistik['bookingMenunggu'] ?? 0) > 0 ? 'color: #b45309;' : '' }}">
                    {{ $statistik['bookingMenunggu'] ?? 0 }}
                </div>
            </div>
            <div class="stat-caption">{{ $statistik['bookingPerluDisiapkan'] ?? 0 }} perlu disiapkan di meja</div>
        </div>

        <!-- Card 2: Peminjaman Hari Ini -->
        <div class="stat-box">
            <div>
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                </div>
                <div class="stat-label">Peminjaman hari ini</div>
                <div class="stat-value">{{ $statistik['peminjamanHariIni'] }}</div>
            </div>
            <div class="stat-caption">
                @if($statistik['peminjamanHariIni'] > 0)
                    {{ $statistik['peminjamanHariIni'] }} buku diserahkan
                @else
                    Belum ada pinjaman hari ini
                @endif
            </div>
        </div>

        <!-- Card 3: Pengembalian Hari Ini -->
        <div class="stat-box">
            <div>
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <div class="stat-label">Pengembalian hari ini</div>
                <div class="stat-value">{{ $statistik['pengembalianHariIni'] }}</div>
            </div>
            <div class="stat-caption">{{ $statistik['tepatWaktuHariIni'] }} tepat waktu</div>
        </div>

        <!-- Card 4: Peminjaman Aktif -->
        <div class="stat-box">
            <div>
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="16 3 21 3 21 8"></polyline>
                        <line x1="4" y1="20" x2="21" y2="3"></line>
                        <polyline points="21 16 21 21 16 21"></polyline>
                        <line x1="15" y1="15" x2="21" y2="21"></line>
                        <line x1="4" y1="4" x2="9" y2="9"></line>
                    </svg>
                </div>
                <div class="stat-label">Peminjaman aktif</div>
                <div class="stat-value">{{ $statistik['peminjamanAktif'] }}</div>
            </div>
            <div class="stat-caption">{{ $statistik['jatuhTempoHariIni'] }} jatuh tempo hari ini</div>
        </div>

        <!-- Card 5: Terlambat -->
        <div class="stat-box">
            <div>
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div class="stat-label">Terlambat</div>
                <div class="stat-value">{{ $statistik['terlambat'] }}</div>
            </div>
            <div class="stat-caption">Perlu pengingat anggota</div>
        </div>
    </div>

    <!-- 4. TABLE SECTION: ANTREAN BOOKING BUKU (ONLINE RESERVATION) -->
    <div class="section-container" id="antrean-booking">
        <div class="section-header-row">
            <div>
                <h3 class="section-title" style="display: flex; align-items: center; gap: 10px;">
                    <span>Antrean Booking & Reservasi Mandiri</span>
                    @if(isset($antreanBooking) && $antreanBooking->isNotEmpty())
                        <span class="badge-booking-pill badge-booking-menunggu">
                            {{ $antreanBooking->count() }} Booking Aktif
                        </span>
                    @endif
                </h3>
                <div class="section-subtitle">
                    Daftar buku yang dibooking member dari website. Siapkan buku di meja layanan atau pantau member yang mengambil mandiri di rak.
                </div>
            </div>
        </div>

        <div class="table-card">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Kode Booking</th>
                        <th style="width: 18%;">Anggota Pemesan</th>
                        <th style="width: 27%;">Buku & Lokasi Rak</th>
                        <th style="width: 18%;">Metode Ambil</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 12%; text-align: right;">Aksi Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($antreanBooking as $booking)
                        @php
                            $detailBooking = $booking->details->first();
                            $bukuBooking = $detailBooking?->buku;
                            $eksBooking = $detailBooking?->eksemplar;
                        @endphp
                        <tr>
                            <td>
                                <span class="booking-code-pill" style="font-family: monospace; font-weight: 800; color: #0f766e; background: #ccfbf1; padding: 4px 8px; border-radius: 6px; font-size: 12px; display: inline-block;">
                                    {{ $booking->kode_booking ?? $booking->kodeTransaksi }}
                                </span>
                                <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">
                                    {{ $booking->created_at ? $booking->created_at->diffForHumans() : '-' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--text-heading);">{{ $booking->member?->name ?? 'Anggota' }}</div>
                                <div style="font-size: 11.5px; color: #64748b;">{{ $booking->member?->kode_anggota ?? '-' }}</div>
                                @if($booking->member?->noTelepon)
                                    <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">📞 {{ $booking->member->noTelepon }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="td-judul-buku">{{ $bukuBooking?->judul ?? 'Judul Buku' }}</div>
                                <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px; flex-wrap: wrap;">
                                    <span class="rack-tag">
                                        📍 {{ $bukuBooking?->rak ?? 'Perpustakaan Pusat' }}
                                    </span>
                                    @if($eksBooking)
                                        <span style="font-size: 11px; color: #475569; background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">
                                            Eks #{{ $eksBooking->nomor_eksemplar }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($booking->opsi_pengambilan === 'siapkan_petugas')
                                    <span class="badge-option-petugas">
                                        📦 Disiapkan Petugas
                                    </span>
                                @else
                                    <span class="badge-option-mandiri">
                                        🔍 Ambil Mandiri di Rak
                                    </span>
                                @endif
                                <div style="font-size: 11px; color: #92400e; margin-top: 4px;">
                                    Batas: {{ $booking->batasAmbil ? \Carbon\Carbon::parse($booking->batasAmbil)->translatedFormat('d M, H:i') : '48 jam' }}
                                </div>
                            </td>
                            <td>
                                @if($booking->status === 'Booking')
                                    <span class="badge-booking-pill badge-booking-menunggu">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #d97706;"></span>
                                        Menunggu
                                    </span>
                                @elseif($booking->status === 'Siap Diambil')
                                    <span class="badge-booking-pill badge-booking-siap">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #2563eb;"></span>
                                        Siap di Meja
                                    </span>
                                @else
                                    <span class="badge-booking-pill">
                                        {{ $booking->status }}
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; flex-direction: column; gap: 5px; align-items: flex-end;">
                                    @if($booking->status === 'Booking' && $booking->opsi_pengambilan === 'siapkan_petugas')
                                        <form method="POST" action="{{ route('petugas.booking.siapkan', $booking->idPeminjaman) }}" style="margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn-siapkan-action" title="Tandai buku sudah diambil dari rak dan diletakkan di meja layanan">
                                                📦 Tandai Siap
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('peminjaman.create', ['booking' => $booking->kode_booking]) }}" class="btn-proses-action" title="Buka menu peminjaman untuk memproses & scan tiket barcode member">
                                        🔍 Proses
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 32px 20px; color: #94a3b8;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 6px;">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2">
                                        <rect x="3" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="14" width="7" height="7"></rect>
                                        <rect x="3" y="14" width="7" height="7"></rect>
                                    </svg>
                                    <span>Tidak ada antrean booking aktif saat ini. Semua pesanan telah diserahkan atau belum ada booking baru.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. TABLE SECTION: TRANSAKSI PEMINJAMAN -->
    <div class="section-container">
        <div class="section-header-row">
            <div>
                <h3 class="section-title">Transaksi peminjaman</h3>
                <div class="section-subtitle">
                    {{ $totalTransaksiAktif }} transaksi aktif · {{ $transaksi->count() }} terbaru ditampilkan
                </div>
            </div>

            <form action="{{ route('dashboard') }}" method="GET" class="table-search-form">
                <span class="table-search-label">Cari Transaksi :</span>
                <div class="table-search-input-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="q" value="{{ $kataKunci }}" placeholder="Cari buku atau anggota...">
                </div>
                <button type="submit" class="btn-table-search">Cari</button>
            </form>
        </div>

        <div class="table-card">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 32%;">Judul Buku</th>
                        <th style="width: 15%;">Kode</th>
                        <th style="width: 22%;">Anggota</th>
                        <th style="width: 16%;">Jatuh Tempo</th>
                        <th style="width: 15%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $trx)
                        <tr>
                            <td class="td-judul-buku">{{ $trx['judul'] }}</td>
                            <td class="td-kode">{{ $trx['kode'] }}</td>
                            <td style="font-weight: 600;">{{ $trx['anggota'] }}</td>
                            <td>{{ $trx['jatuhTempo']->translatedFormat('d M Y') }}</td>
                            <td>
                                @if($trx['status'] === 'Dipinjam')
                                    <span class="badge-status-pill dipinjam">
                                        <span class="dot"></span> Dipinjam
                                    </span>
                                @else
                                    <span class="badge-status-pill terlambat">
                                        <span class="dot"></span> Terlambat
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 36px 20px; color: #94a3b8;">
                                @if($kataKunci !== '')
                                    Tidak ditemukan transaksi dengan kata kunci "{{ $kataKunci }}".
                                    <br><a href="{{ route('dashboard') }}" style="color: #0f766e; font-weight: 700; margin-top: 6px; display: inline-block;">Reset Pencarian</a>
                                @else
                                    Saat ini tidak ada transaksi peminjaman aktif.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 5. BOTTOM SECTION: AKTIVITAS TERBARU & PENGINGAT PETUGAS -->
    <div class="bottom-grid-2">
        <!-- Box Kiri: Aktivitas Terbaru -->
        <div class="card-bottom">
            <h3 class="card-bottom-title">Aktivitas terbaru</h3>

            @if($aktivitasTerbaru->isNotEmpty())
                <div class="activity-list">
                    @foreach($aktivitasTerbaru as $act)
                        <div class="activity-item">
                            <div class="activity-left">
                                <span>{{ $act['waktu'] }}</span> · <span class="activity-member">{{ $act['anggota'] }}</span>
                            </div>
                            <div class="activity-right">
                                {{ $act['deskripsi'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #94a3b8; font-size: 13px; margin: 0;">
                    Belum ada riwayat aktivitas sirkulasi terbaru hari ini.
                </p>
            @endif
        </div>

        <!-- Box Kanan: Pengingat Petugas -->
        <div class="card-bottom card-pengingat">
            <h3 class="card-bottom-title">Pengingat petugas</h3>
            <p class="pengingat-body">
                Periksa kartu anggota dan kondisi buku sebelum konfirmasi. Pastikan barcode sesuai dengan kode buku pada transaksi.
            </p>
            <div class="pengingat-footer">
                Meja layanan buka hingga 17.00 WIB.
            </div>
        </div>
    </div>

</div>
@endsection
