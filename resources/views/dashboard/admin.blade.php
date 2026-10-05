@extends('layouts.admin')

@section('title', 'Dasbor Admin - BOOKNEST')

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

    /* --- STATS GRID (4 COLS) --- */
    .stats-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 30px;
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
        font-size: 26px;
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

    /* --- SECTION TITLE --- */
    .section-heading-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.3px;
        margin-bottom: 16px;
    }

    /* --- MANAGEMENT GRID --- */
    .management-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 34px;
    }

    .manage-card {
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

    .manage-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.04);
    }

    .manage-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        color: #0f766e;
        margin-bottom: 10px;
    }

    .manage-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 4px;
    }

    .manage-subtitle {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 16px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .btn-manage {
        width: 100%;
        padding: 9px 12px;
        background: #0f766e;
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 700;
        border-radius: 9px;
        text-align: center;
        display: block;
        transition: background 0.15s;
    }

    .btn-manage:hover {
        background: #115e59;
    }

    /* --- TABLE SECTION: TRANSAKSI TERBARU --- */
    .section-container {
        margin-bottom: 32px;
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

    /* --- SECTION: LAPORAN BULANAN (BANNER CARD) --- */
    .monthly-report-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 24px 28px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .report-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        gap: 14px;
        flex-wrap: wrap;
    }

    .report-card-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.3px;
    }

    .report-period-badge {
        font-size: 12.5px;
        font-weight: 600;
        color: #64748b;
    }

    .report-period-badge strong {
        color: var(--text-heading);
    }

    .report-card-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 18px;
        max-width: 820px;
    }

    .btn-unduh-laporan {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background: #2563eb;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        border-radius: 10px;
        transition: all 0.15s ease;
    }

    .btn-unduh-laporan:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 1200px) {
        .stats-grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }

        .management-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid-4 {
            grid-template-columns: 1fr;
        }

        .management-grid {
            grid-template-columns: 1fr;
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

        .report-card-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="admin-dashboard">

    <!-- 1. BREADCRUMB & HEADER -->
    <div class="dashboard-header">
        <div class="breadcrumb">BOOKNEST / Admin</div>
        <h1 class="dashboard-title">Dasbor Admin</h1>
        <p class="dashboard-subtitle">
            Pantau perpustakaan dan kelola seluruh data BOOKNEST · {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}.
        </p>
    </div>

    <!-- 2. STATS CARDS (4 COLS) -->
    <div class="stats-grid-4">
        <!-- Card 1: Koleksi Buku -->
        <div class="stat-box">
            <div>
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                </div>
                <div class="stat-label">Koleksi buku</div>
                <div class="stat-value">{{ number_format($statistik['totalEksemplar'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-caption">{{ $statistik['totalJudul'] }} judul tersedia daring</div>
        </div>

        <!-- Card 2: Anggota Aktif -->
        <div class="stat-box">
            <div>
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="stat-label">Anggota aktif</div>
                <div class="stat-value">{{ number_format($statistik['anggotaAktif'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-caption">{{ $statistik['anggotaBaruBulanIni'] }} anggota baru bulan ini</div>
        </div>

        <!-- Card 3: Petugas Aktif -->
        <div class="stat-box">
            <div>
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div class="stat-label">Petugas aktif</div>
                <div class="stat-value">{{ $statistik['petugasAktif'] }}</div>
            </div>
            <div class="stat-caption">{{ $statistik['petugasBertugasHariIni'] }} petugas bertugas hari ini</div>
        </div>

        <!-- Card 4: Denda Terkumpul -->
        <div class="stat-box">
            <div>
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                </div>
                <div class="stat-label">Denda terkumpul</div>
                <div class="stat-value">Rp{{ number_format($statistik['dendaTerkumpulBulanIni'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-caption">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }} · {{ $statistik['transaksiDendaBulanIni'] }} transaksi</div>
        </div>
    </div>

    <!-- 3. SECTION: MANAJEMEN PERPUSTAKAAN (GRID) -->
    <div class="section-container">
        <h2 class="section-heading-title">Manajemen perpustakaan</h2>

        <div class="management-grid">
            <!-- 1. Buku -->
            <div class="manage-card">
                <div>
                    <div class="manage-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                    </div>
                    <h3 class="manage-title">Buku</h3>
                    <p class="manage-subtitle">{{ number_format($manajemen['buku']['eksemplar'], 0, ',', '.') }} eksemplar · {{ $manajemen['buku']['judul'] }} judul</p>
                </div>
                <a href="{{ route('buku.index') }}" class="btn-manage">Kelola Buku</a>
            </div>

            <!-- 2. Kategori -->
            <div class="manage-card">
                <div>
                    <div class="manage-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                    </div>
                    <h3 class="manage-title">Kategori</h3>
                    <p class="manage-subtitle">{{ $manajemen['kategori']['total'] }} kategori · {{ $manajemen['kategori']['terpopuler'] }} paling populer</p>
                </div>
                <a href="{{ route('kategori.index') }}" class="btn-manage">Kelola Kategori</a>
            </div>

            <!-- 3. Anggota -->
            <div class="manage-card">
                <div>
                    <div class="manage-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h3 class="manage-title">Anggota</h3>
                    <p class="manage-subtitle">{{ number_format($manajemen['anggota']['aktif'], 0, ',', '.') }} aktif · {{ $manajemen['anggota']['baruBulanIni'] }} baru bulan ini</p>
                </div>
                <a href="{{ route('member.index') }}" class="btn-manage">Kelola Anggota</a>
            </div>

            <!-- 4. Petugas -->
            <div class="manage-card">
                <div>
                    <div class="manage-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <polyline points="17 11 19 13 23 9"></polyline>
                        </svg>
                    </div>
                    <h3 class="manage-title">Petugas</h3>
                    <p class="manage-subtitle">{{ $manajemen['petugas']['total'] }} akun · {{ $manajemen['petugas']['bertugas'] }} bertugas hari ini</p>
                </div>
                <a href="{{ route('petugas.index') }}" class="btn-manage">Kelola Petugas</a>
            </div>

            <!-- 5. Transaksi -->
            <div class="manage-card">
                <div>
                    <div class="manage-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 3 21 3 21 8"></polyline>
                            <line x1="4" y1="20" x2="21" y2="3"></line>
                            <polyline points="21 16 21 21 16 21"></polyline>
                            <line x1="15" y1="15" x2="21" y2="21"></line>
                            <line x1="4" y1="4" x2="9" y2="9"></line>
                        </svg>
                    </div>
                    <h3 class="manage-title">Transaksi</h3>
                    <p class="manage-subtitle">{{ $manajemen['transaksi']['aktif'] }} aktif · {{ $manajemen['transaksi']['pinjamHariIni'] }} pinjam hari ini</p>
                </div>
                <a href="{{ route('peminjaman.index') }}" class="btn-manage">Kelola Transaksi</a>
            </div>

            <!-- 6. Denda -->
            <div class="manage-card">
                <div>
                    <div class="manage-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                    </div>
                    <h3 class="manage-title">Denda</h3>
                    <p class="manage-subtitle">{{ $manajemen['denda']['belumLunasCount'] }} belum lunas · Rp{{ number_format($manajemen['denda']['belumLunasNominal'], 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('denda.index') }}" class="btn-manage">Kelola Denda</a>
            </div>

            <!-- 7. Laporan -->
            <div class="manage-card">
                <div>
                    <div class="manage-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                    </div>
                    <h3 class="manage-title">Laporan</h3>
                    <p class="manage-subtitle">{{ $manajemen['laporan']['periode'] }} · Pembaruan harian</p>
                </div>
                <a href="{{ route('laporan.index') }}" class="btn-manage">Lihat Laporan</a>
            </div>
        </div>
    </div>

    <!-- 4. TABLE SECTION: TRANSAKSI TERBARU -->
    <div class="section-container">
        <div class="section-header-row">
            <div>
                <h3 class="section-title">Transaksi terbaru</h3>
                <div class="section-subtitle">
                    Data layanan Perpustakaan Pusat
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
                                    Saat ini belum ada transaksi peminjaman aktif.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 5. SECTION: LAPORAN BULANAN (BANNER CARD) -->
    <div class="monthly-report-card">
        <div class="report-card-header">
            <h3 class="report-card-title">Laporan bulanan</h3>
            <div class="report-period-badge">
                Periode laporan: <strong>{{ $periodeLaporan }}</strong>
            </div>
        </div>

        <p class="report-card-desc">
            Ringkasan koleksi, aktivitas anggota, peminjaman, pengembalian, dan pembayaran denda tersedia untuk evaluasi layanan.
        </p>

        <a href="{{ route('laporan.index') }}" class="btn-unduh-laporan">
            Unduh Laporan
        </a>
    </div>

</div>
@endsection
