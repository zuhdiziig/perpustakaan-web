@extends('layouts.anggota')

@section('title', 'Dasbor Anggota - BOOKNEST')

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

    /* --- WELCOME BANNER --- */
    .welcome-banner {
        background: #ccfbf1;
        border: 1px solid #99f6e4;
        border-radius: 18px;
        padding: 24px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .welcome-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f766e;
        margin-bottom: 6px;
        letter-spacing: -0.3px;
    }

    .welcome-meta {
        font-size: 13px;
        font-weight: 600;
        color: #134e4a;
        margin-bottom: 3px;
    }

    .welcome-desc {
        font-size: 13px;
        color: #115e59;
    }

    .btn-cari-buku {
        padding: 10px 22px;
        background: #0f766e;
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 10px;
        white-space: nowrap;
        transition: all 0.15s ease;
        display: inline-block;
        flex-shrink: 0;
    }

    .btn-cari-buku:hover {
        background: #115e59;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
    }

    /* --- STATS GRID (4 COLS) --- */
    .stats-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 22px;
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
        font-size: 24px;
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

    /* --- ALERT DENDA --- */
    .denda-alert-banner {
        background: #f0fdfa;
        border: 1px solid #99f6e4;
        border-radius: 16px;
        padding: 18px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .denda-alert-title {
        font-size: 14.5px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 4px;
    }

    .denda-alert-text {
        font-size: 12.5px;
        color: #475569;
        line-height: 1.5;
        max-width: 820px;
    }

    .btn-lihat-denda {
        padding: 9px 20px;
        background: #0f766e;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        border-radius: 9px;
        white-space: nowrap;
        flex-shrink: 0;
        transition: all 0.15s;
    }

    .btn-lihat-denda:hover {
        background: #115e59;
        transform: translateY(-1px);
    }

    /* --- TABLE SECTION: PEMINJAMAN & RIWAYAT --- */
    .section-container {
        margin-bottom: 34px;
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
        width: 250px;
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

    .badge-status-pill.dikembalikan {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-status-pill.dikembalikan .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #16a34a;
    }

    /* --- REKOMENDASI BUKU --- */
    .rekomendasi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }

    .rekomendasi-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .rekomendasi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
    }

    .rekomendasi-cover-wrap {
        width: 100%;
        height: 190px;
        border-radius: 12px;
        overflow: hidden;
        background: #f1f5f9;
        margin-bottom: 12px;
        position: relative;
    }

    .rekomendasi-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .rekomendasi-badges {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 8px;
    }

    .badge-tersedia {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2px 9px;
        border-radius: 20px;
        background: #dcfce7;
        color: #15803d;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-tersedia .dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #15803d;
    }

    .badge-rating {
        font-size: 11.5px;
        font-weight: 700;
        color: #d97706;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    .rekomendasi-judul {
        font-size: 14.5px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 3px;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .rekomendasi-author {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 10px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .rekomendasi-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 11.5px;
        color: #64748b;
        margin-bottom: 14px;
        padding-top: 4px;
        border-top: 1px solid #f8fafc;
    }

    .btn-pinjam-buku {
        margin-top: auto;
        width: 100%;
        padding: 9px 12px;
        background: #0f766e;
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 700;
        border-radius: 9px;
        text-align: center;
        display: block;
        transition: all 0.15s;
    }

    .btn-pinjam-buku:hover {
        background: #115e59;
        box-shadow: 0 4px 10px rgba(15, 118, 110, 0.2);
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 1200px) {
        .stats-grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }

        .rekomendasi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .welcome-banner {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
        }

        .stats-grid-4 {
            grid-template-columns: 1fr;
        }

        .denda-alert-banner {
            flex-direction: column;
            align-items: flex-start;
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
            min-width: 600px;
        }

        .rekomendasi-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="member-dashboard">

    <!-- 1. BREADCRUMB & TITLE -->
    <div class="dashboard-header">
        <div class="breadcrumb">BOOKNEST / Anggota</div>
        <h1 class="dashboard-title">Dasbor Anggota</h1>
        <p class="dashboard-subtitle">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} · Semua aktivitas membacamu dalam satu tempat.
        </p>
    </div>

    <!-- 2. WELCOME BANNER -->
    <div class="welcome-banner">
        <div>
            <h2 class="welcome-title">Selamat membaca, {{ explode(' ', $member->name)[0] }}!</h2>
            <div class="welcome-meta">Keanggotaan aktif · {{ $member->kode_anggota }}</div>
            <p class="welcome-desc">Masih ada cerita baru yang menunggumu.</p>
        </div>

        <a href="{{ route('katalog.index') }}" class="btn-cari-buku">
            Cari Buku Baru
        </a>
    </div>

    <!-- 3. STATS CARDS (4 COLS) -->
    <div class="stats-grid-4">
        <!-- Card 1: Sedang Dipinjam -->
        <div class="stat-box">
            <div>
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                </div>
                <div class="stat-label">Sedang dipinjam</div>
                <div class="stat-value">{{ $statistik['sedangDipinjam'] }} buku</div>
            </div>
            <div class="stat-caption">Batas maksimal {{ $statistik['batasPinjam'] }} buku</div>
        </div>

        <!-- Card 2: Total Dibaca -->
        <div class="stat-box">
            <div>
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <div class="stat-label">Total dibaca</div>
                <div class="stat-value">{{ $statistik['totalDibaca'] }} buku</div>
            </div>
            <div class="stat-caption">Sejak menjadi anggota</div>
        </div>

        <!-- Card 3: Jatuh Tempo Berikutnya -->
        <div class="stat-box">
            <div>
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <div class="stat-label">Jatuh tempo berikutnya</div>
                <div class="stat-value">
                    @if($statistik['jatuhTempo'])
                        {{ $statistik['jatuhTempo']['tanggal']->translatedFormat('d M') }}
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="stat-caption" title="{{ $statistik['jatuhTempo']['judul'] ?? '' }}">
                @if($statistik['jatuhTempo'])
                    {{ Str::limit($statistik['jatuhTempo']['judul'], 18) }} ·
                    @if($statistik['jatuhTempo']['sisaHari'] < 0)
                        terlambat {{ abs($statistik['jatuhTempo']['sisaHari']) }} hari
                    @elseif($statistik['jatuhTempo']['sisaHari'] === 0)
                        hari ini
                    @else
                        {{ $statistik['jatuhTempo']['sisaHari'] }} hari
                    @endif
                @else
                    Tidak ada pinjaman aktif
                @endif
            </div>
        </div>

        <!-- Card 4: Denda Belum Dibayar -->
        <div class="stat-box">
            <div>
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                </div>
                <div class="stat-label">Denda belum dibayar</div>
                <div class="stat-value">
                    Rp{{ number_format($statistik['totalDenda'], 0, ',', '.') }}
                </div>
            </div>
            <div class="stat-caption" title="{{ $statistik['dendaTerbaru']['judul'] ?? '' }}">
                @if($statistik['dendaTerbaru'])
                    {{ Str::limit($statistik['dendaTerbaru']['judul'], 18) }} · {{ $statistik['dendaTerbaru']['hariTerlambat'] }} hari
                @else
                    Semua tagihan lunas
                @endif
            </div>
        </div>
    </div>

    <!-- 4. ALERT DENDA (JIKA ADA DENDA BELUM DIBAYAR) -->
    @if($statistik['totalDenda'] > 0)
        <div class="denda-alert-banner">
            <div>
                <div class="denda-alert-title">Ada denda yang perlu diselesaikan</div>
                <p class="denda-alert-text">
                    @if($statistik['dendaTerbaru'])
                        {{ $statistik['dendaTerbaru']['judul'] }} dikembalikan {{ $statistik['dendaTerbaru']['tanggalKembali']->translatedFormat('d M Y') }}, terlambat {{ $statistik['dendaTerbaru']['hariTerlambat'] }} hari. Total Rp{{ number_format($statistik['totalDenda'], 0, ',', '.') }}. Bayar dengan QRIS agar akun tetap nyaman digunakan.
                    @else
                        Terdapat total tagihan denda sebesar Rp{{ number_format($statistik['totalDenda'], 0, ',', '.') }}. Silakan lakukan pembayaran agar riwayat tetap bersih.
                    @endif
                </p>
            </div>

            <a href="{{ route('denda.saya') }}" class="btn-lihat-denda">
                Lihat Denda
            </a>
        </div>
    @endif

    <!-- 5. TABLE SECTION: PEMINJAMAN & RIWAYAT -->
    <div class="section-container">
        <div class="section-header-row">
            <div>
                <h3 class="section-title">Peminjaman & riwayat</h3>
                <div class="section-subtitle">
                    {{ $transaksi->count() }} transaksi ditampilkan
                </div>
            </div>

            <form action="{{ route('dashboard') }}" method="GET" class="table-search-form">
                <span class="table-search-label">Cari Transaksi :</span>
                <div class="table-search-input-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="q" value="{{ $kataKunci }}" placeholder="Cari buku atau kode...">
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
                        <th style="width: 20%;">Anggota</th>
                        <th style="width: 18%;">Jatuh Tempo</th>
                        <th style="width: 15%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $trx)
                        <tr>
                            <td class="td-judul-buku">{{ $trx['judul'] }}</td>
                            <td class="td-kode">{{ $trx['kode'] }}</td>
                            <td>{{ $trx['anggota'] }}</td>
                            <td>{{ $trx['jatuhTempo']->translatedFormat('d M Y') }}</td>
                            <td>
                                @if($trx['status'] === 'Dipinjam')
                                    <span class="badge-status-pill dipinjam">
                                        <span class="dot"></span> Dipinjam
                                    </span>
                                @elseif($trx['status'] === 'Terlambat')
                                    <span class="badge-status-pill terlambat">
                                        <span class="dot"></span> Terlambat
                                    </span>
                                @else
                                    <span class="badge-status-pill dikembalikan">
                                        <span class="dot"></span> Dikembalikan
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
                                    Belum ada transaksi peminjaman. Jelajahi katalog dan pinjam buku favoritmu!
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 6. REKOMENDASI UNTUKMU -->
    <div class="section-container">
        <h3 class="section-title" style="margin-bottom: 16px;">Rekomendasi untukmu</h3>

        <div class="rekomendasi-grid">
            @forelse($rekomendasi as $buku)
                <div class="rekomendasi-card">
                    <div class="rekomendasi-cover-wrap">
                        @if($buku->cover)
                            <img src="{{ asset('storage/' . $buku->cover) }}"
                                 alt="{{ $buku->judul }}"
                                 class="rekomendasi-cover-img"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400';">
                        @else
                            <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400"
                                 alt="{{ $buku->judul }}"
                                 class="rekomendasi-cover-img">
                        @endif
                    </div>

                    <div class="rekomendasi-badges">
                        <span class="badge-tersedia">
                            <span class="dot"></span> Tersedia
                        </span>
                        <span class="badge-rating">
                            ★ 4,8
                        </span>
                    </div>

                    <h4 class="rekomendasi-judul" title="{{ $buku->judul }}">
                        {{ $buku->judul }}
                    </h4>

                    <div class="rekomendasi-author">
                        {{ $buku->penulis ?? 'Penulis tidak tersedia' }} · {{ $buku->kategori->namaKategori ?? 'Umum' }}
                    </div>

                    <div class="rekomendasi-meta">
                        <span>📖 {{ $buku->jumlahHalaman ?? 350 }} halaman</span>
                        <span>📍 Rak {{ $buku->rak ?? 'F-01' }}</span>
                    </div>

                    <a href="{{ route('katalog.show', $buku->idBuku) }}" class="btn-pinjam-buku">
                        Pinjam Buku
                    </a>
                </div>
            @empty
                <div style="grid-column: 1 / -1; background: #ffffff; border: 1px solid var(--border-color); border-radius: 14px; padding: 36px 20px; text-align: center; color: #94a3b8;">
                    Koleksi buku rekomendasi sedang disiapkan untukmu.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
