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

    @media (max-width: 600px) {
        .dashboard-title {
            font-size: 22px;
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

    <!-- 3. TABLE SECTION: TRANSAKSI PEMINJAMAN -->
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
