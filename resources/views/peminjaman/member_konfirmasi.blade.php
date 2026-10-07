@extends('layouts.anggota')

@section('title', 'Konfirmasi Peminjaman Buku - BOOKNEST')

@section('styles')
<style>
    /* =========================================================
       HALAMAN PEMINJAMAN MEMBER (KONFIRMASI)
       ========================================================= */

    .loan-page {
        width: 100%;
        max-width: 1240px;
        margin: 0 auto;
        padding: 4px 0 32px;
        box-sizing: border-box;
    }

    /* --- BREADCRUMB & HEADER --- */
    .loan-header {
        margin-bottom: 24px;
    }

    .loan-breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        color: var(--brand-primary);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 6px;
    }

    .loan-breadcrumb a {
        color: var(--text-muted);
        transition: color 0.15s;
    }

    .loan-breadcrumb a:hover {
        color: var(--brand-primary);
    }

    .loan-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.6px;
        margin-bottom: 6px;
        line-height: 1.25;
    }

    .loan-subtitle {
        font-size: 14px;
        color: var(--text-muted);
        line-height: 1.6;
        margin: 0;
        max-width: 720px;
    }

    /* --- ALERT NOTIFICATIONS --- */
    .loan-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 22px;
        line-height: 1.55;
    }

    .loan-alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .loan-alert-warning {
        background: #fffbeb;
        border: 1px solid #fef3c7;
        color: #92400e;
    }

    .loan-alert-icon {
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* --- MAIN 2-COLUMN GRID --- */
    .loan-grid {
        display: grid;
        grid-template-columns: 1.05fr 1fr;
        gap: 26px;
        align-items: start;
    }

    /* --- CARDS BASE --- */
    .loan-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .loan-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .loan-card-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.3px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* --- STATUS BADGES --- */
    .pill-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.2px;
        white-space: nowrap;
    }

    .pill-badge-teal {
        background: var(--brand-primary-light);
        color: var(--brand-primary);
    }

    .pill-badge-green {
        background: #dcfce7;
        color: #166534;
    }

    .pill-badge-amber {
        background: #fef3c7;
        color: #92400e;
    }

    .pill-badge-red {
        background: #fee2e2;
        color: #991b1b;
    }

    .pill-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    /* --- BOOK SHOWCASE (LEFT CARD) --- */
    .book-showcase {
        display: flex;
        gap: 20px;
        background: #f8fafc;
        border: 1px solid #eef2f6;
        border-radius: 14px;
        padding: 18px;
    }

    .book-cover-wrap {
        width: 124px;
        aspect-ratio: 3 / 4.1;
        flex-shrink: 0;
        border-radius: 10px;
        overflow: hidden;
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 18px -4px rgba(15, 23, 42, 0.12);
        position: relative;
    }

    .book-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .book-cover-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px;
        text-align: center;
        background: linear-gradient(135deg, var(--brand-primary) 0%, #115e59 100%);
        color: #ffffff;
    }

    .book-showcase-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 8px;
        min-width: 0;
    }

    .book-showcase-title {
        font-size: 19px;
        font-weight: 800;
        color: var(--text-heading);
        line-height: 1.3;
        margin: 0;
    }

    .book-showcase-author {
        font-size: 13.5px;
        color: var(--text-muted);
        font-weight: 500;
        margin: 0;
    }

    .book-badges-row {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 4px;
    }

    .tag-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        background: #ffffff;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    /* --- DETAIL ROWS TABLE --- */
    .detail-table {
        display: flex;
        flex-direction: column;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        overflow: hidden;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
    }

    .detail-row:nth-child(even) {
        background: #fafafa;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-key {
        color: var(--text-muted);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-val {
        color: var(--text-heading);
        font-weight: 700;
        text-align: right;
    }

    .detail-val.highlight {
        color: var(--brand-primary);
        font-weight: 800;
    }

    /* --- SINOPSIS BOX --- */
    .synopsis-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 14px 16px;
    }

    .synopsis-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #94a3b8;
        margin-bottom: 6px;
    }

    .synopsis-content {
        font-size: 12.5px;
        color: var(--text-muted);
        line-height: 1.65;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* --- NOTICE / VALIDASI CARD --- */
    .notice-card {
        background: #f0fdfa;
        border: 1px solid #ccfbf1;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        gap: 12px;
    }

    .notice-icon {
        color: var(--brand-primary);
        flex-shrink: 0;
        margin-top: 1px;
    }

    .notice-title {
        font-size: 13.5px;
        font-weight: 800;
        color: #0f766e;
        margin-bottom: 4px;
    }

    .notice-desc {
        font-size: 12.5px;
        color: #334155;
        line-height: 1.6;
        margin: 0;
    }

    /* --- ACTION BUTTONS --- */
    .action-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 4px;
    }

    .btn-submit-loan {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px 22px;
        background: var(--brand-primary);
        color: #ffffff;
        font-size: 14.5px;
        font-weight: 800;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(15, 118, 110, 0.2);
        border: none;
        letter-spacing: 0.2px;
    }

    .btn-submit-loan:hover:not(:disabled) {
        background: var(--brand-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 14px -3px rgba(15, 118, 110, 0.35);
    }

    .btn-submit-loan:active:not(:disabled) {
        transform: translateY(0);
    }

    .btn-submit-loan:disabled {
        background: #cbd5e1;
        color: #64748b;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    .btn-back-detail {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: 100%;
        padding: 12px 20px;
        background: #ffffff;
        color: #475569;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        transition: all 0.15s ease;
        text-decoration: none;
        box-sizing: border-box;
    }

    .btn-back-detail:hover {
        background: #f8fafc;
        color: var(--text-heading);
        border-color: #cbd5e1;
    }

    /* --- OPSI PENGAMBILAN CARDS --- */
    .option-section-title {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-heading);
        margin: 16px 0 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .option-cards {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 18px;
    }

    .option-card {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 13px 14px;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .option-card:hover {
        border-color: var(--brand-primary);
        background: #f8faf9;
    }

    .option-card:has(input[type="radio"]:checked) {
        border-color: var(--brand-primary);
        background: #f0fdf9;
        box-shadow: 0 2px 8px rgba(35, 92, 84, 0.08);
    }

    .option-radio {
        margin-top: 3px;
        accent-color: var(--brand-primary);
        width: 17px;
        height: 17px;
        cursor: pointer;
    }

    .option-content {
        flex: 1;
    }

    .option-title {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .option-desc {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.45;
        margin: 0;
    }

    /* --- RESPONSIVE BREAKPOINTS --- */
    @media (max-width: 992px) {
        .loan-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .loan-title {
            font-size: 24px;
        }
    }

    @media (max-width: 600px) {
        .book-showcase {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .book-cover-wrap {
            width: 130px;
        }

        .book-badges-row {
            justify-content: center;
        }

        .detail-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 3px;
        }

        .detail-val {
            text-align: left;
        }

        .loan-card {
            padding: 18px;
        }
    }
</style>
@endsection

@section('content')
<div class="loan-page">

    {{-- HEADER & BREADCRUMB --}}
    <div class="loan-header">
        <div class="loan-breadcrumb">
            <a href="{{ route('dashboard') }}">BOOKNEST</a>
            <span>/</span>
            <span>Peminjaman</span>
        </div>

        <h1 class="loan-title">Peminjaman Buku</h1>
        <p class="loan-subtitle">
            Periksa detail buku dan konfirmasi informasi peminjaman sebelum Anda mengajukan peminjaman ke sistem perpustakaan.
        </p>
    </div>

    {{-- ALERT MESSAGES DARI SESI --}}
    @if (session('error'))
        <div class="loan-alert loan-alert-error">
            <div class="loan-alert-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div>
                <strong>Pemberitahuan:</strong> {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- CEK KONDISI PEMINJAMAN --}}
    @if (! $isTersedia)
        <div class="loan-alert loan-alert-warning">
            <div class="loan-alert-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div>
                <strong>Stok Buku Tidak Tersedia:</strong> Saat ini seluruh eksemplar buku ini sedang dipinjam oleh anggota lain. Anda belum dapat mengajukan peminjaman untuk judul ini.
            </div>
        </div>
    @elseif ($sedangPinjamBukuIni)
        <div class="loan-alert loan-alert-warning">
            <div class="loan-alert-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div>
                <strong>Buku Sedang Dipinjam:</strong> Anda sudah memiliki pinjaman aktif untuk buku ini. Harap kembalikan terlebih dahulu sebelum meminjam kembali.
            </div>
        </div>
    @elseif ($kuotaHabis)
        <div class="loan-alert loan-alert-warning">
            <div class="loan-alert-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div>
                <strong>Batas Kuota Tercapai:</strong> Anda telah meminjam {{ $batasMaksimalBuku }} buku aktif (batas maksimal). Kembalikan salah satu buku terlebih dahulu untuk meminjam buku baru.
            </div>
        </div>
    @endif

    {{-- GRID 2 KOLOM --}}
    <div class="loan-grid">

        {{-- =========================================================
             KOLOM KIRI: CARD DETAIL BUKU
        ========================================================== --}}
        <div class="loan-card">
            <div class="loan-card-header">
                <h2 class="loan-card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    Detail Buku
                </h2>

                <span class="pill-badge pill-badge-teal">
                    Kondisi: {{ $buku->kondisi ?? 'Baik' }}
                </span>
            </div>

            {{-- SHOWCASE SAMPUL & IDENTITAS UTAMA --}}
            <div class="book-showcase">
                <div class="book-cover-wrap">
                    @if (! empty($buku->cover))
                        <img
                            src="{{ $buku->cover }}"
                            alt="{{ $buku->judul }}"
                            class="book-cover-img"
                            onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'book-cover-fallback\'><svg width=\'28\' height=\'28\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\'><path d=\'M4 19.5A2.5 2.5 0 0 1 6.5 17H20\'></path><path d=\'M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z\'></path></svg></div>';"
                        >
                    @else
                        <div class="book-cover-fallback">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="book-showcase-info">
                    <h3 class="book-showcase-title">{{ $buku->judul }}</h3>
                    <p class="book-showcase-author">{{ $buku->penulis }}</p>

                    <div class="book-badges-row">
                        <span class="tag-chip">
                            {{ $buku->kategori?->namaKategori ?? 'Umum' }}
                        </span>

                        @if ($isTersedia)
                            <span class="pill-badge pill-badge-green">
                                <span class="pill-dot"></span>
                                Tersedia ({{ $stokTersedia }} eksemplar)
                            </span>
                        @else
                            <span class="pill-badge pill-badge-red">
                                <span class="pill-dot"></span>
                                Sedang Dipinjam
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SPESIFIKASI INFORMASI LENGKAP BUKU --}}
            <div class="detail-table">
                <div class="detail-row">
                    <span class="detail-key">Kategori</span>
                    <span class="detail-val">{{ $buku->kategori?->namaKategori ?? 'Umum' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Penulis</span>
                    <span class="detail-val">{{ $buku->penulis }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Penerbit</span>
                    <span class="detail-val">{{ $buku->penerbit }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Tahun Terbit</span>
                    <span class="detail-val">{{ $buku->tahunTerbit }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">ISBN</span>
                    <span class="detail-val">{{ $buku->isbn ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Jumlah Halaman</span>
                    <span class="detail-val">{{ $buku->jumlahHalaman ? $buku->jumlahHalaman . ' halaman' : '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Lokasi Rak</span>
                    <span class="detail-val">{{ $buku->rak ?? 'Rak Utama' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Kondisi Buku</span>
                    <span class="detail-val">{{ $buku->kondisi ?? 'Baik' }}</span>
                </div>
            </div>

            {{-- SINOPSIS SINGKAT JIKA TERSEDIA --}}
            @if (! empty($buku->sinopsis))
                <div class="synopsis-card">
                    <div class="synopsis-title">Ringkasan Sinopsis</div>
                    <p class="synopsis-content">{{ $buku->sinopsis }}</p>
                </div>
            @endif
        </div>


        {{-- =========================================================
             KOLOM KANAN: CARD INFORMASI PEMINJAMAN & AKSI
        ========================================================== --}}
        <div class="loan-card">
            <div class="loan-card-header">
                <h2 class="loan-card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <polyline points="17 11 19 13 23 9"></polyline>
                    </svg>
                    Rincian Peminjaman
                </h2>

                @if ($isTersedia && ! $kuotaHabis && ! $sedangPinjamBukuIni && $user->status === 'aktif')
                    <span class="pill-badge pill-badge-green">
                        <span class="pill-dot"></span>
                        Siap Diajukan
                    </span>
                @else
                    <span class="pill-badge pill-badge-amber">
                        <span class="pill-dot"></span>
                        Perlu Perhatian
                    </span>
                @endif
            </div>

            {{-- TABEL RINCIAN PEMINJAMAN ANGGOTA --}}
            <div class="detail-table">
                <div class="detail-row">
                    <span class="detail-key">Anggota</span>
                    <span class="detail-val">{{ $user->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Nomor Anggota</span>
                    <span class="detail-val">{{ $user->kodeAnggota }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Buku yang Dipilih</span>
                    <span class="detail-val" title="{{ $buku->judul }}">{{ Str::limit($buku->judul, 32) }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Tanggal Pinjam</span>
                    <span class="detail-val">{{ $tanggalPinjam->translatedFormat('d M Y') }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Batas Pengembalian</span>
                    <span class="detail-val highlight">{{ $batasKembali->translatedFormat('d M Y') }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Lokasi Rak</span>
                    <span class="detail-val highlight">{{ $buku->rak ?? 'Perpustakaan Pusat' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Batas Ambil Booking</span>
                    <span class="detail-val">{{ $estimasiBatasAmbil->translatedFormat('d M Y, H:i') }} WIB ({{ $batasAmbilJam }} Jam)</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Durasi Masa Pinjam</span>
                    <span class="detail-val">{{ $durasiHari }} hari (dihitung sejak serah terima)</span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Sisa Kuota Pinjam</span>
                    <span class="detail-val">
                        {{ $sisaKuota }} dari {{ $batasMaksimalBuku }} buku
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-key">Status Anggota</span>
                    <span class="detail-val">
                        <span class="pill-badge {{ $user->status === 'aktif' ? 'pill-badge-green' : 'pill-badge-red' }}" style="padding: 2px 8px; font-size: 11px;">
                            {{ ucfirst($user->status) }}
                        </span>
                    </span>
                </div>
            </div>

            {{-- FORM BOOKING & PILIHAN PENGAMBILAN --}}
            @php
                $bisaMeminjam = $isTersedia && ! $kuotaHabis && ! $sedangPinjamBukuIni && $user->status === 'aktif';
            @endphp

            <form method="POST" action="{{ route('peminjaman.ajukan', $buku->idBuku) }}">
                @csrf

                <div class="option-section-title">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Pilih Metode Pengambilan Buku
                </div>

                <div class="option-cards">
                    {{-- Opsi 1: Disiapkan Petugas --}}
                    <label class="option-card">
                        <input type="radio" name="opsi_pengambilan" value="siapkan_petugas" class="option-radio" checked>
                        <div class="option-content">
                            <div class="option-title">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--brand-primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                                Disiapkan oleh Petugas (Rekomendasi)
                            </div>
                            <p class="option-desc">
                                Petugas akan menyiapkan buku di meja sirkulasi. Anda cukup datang, tunjukkan QR booking, dan langsung terima buku.
                            </p>
                        </div>
                    </label>

                    {{-- Opsi 2: Ambil Mandiri di Rak --}}
                    <label class="option-card">
                        <input type="radio" name="opsi_pengambilan" value="ambil_mandiri" class="option-radio">
                        <div class="option-content">
                            <div class="option-title">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                Ambil Mandiri di Rak
                            </div>
                            <p class="option-desc">
                                Anda mencari sendiri buku di {{ $buku->rak ?? 'rak koleksi' }} saat di perpustakaan, lalu membawanya ke meja layanan untuk scan serah terima.
                            </p>
                        </div>
                    </label>
                </div>

                {{-- CARD INFORMASI / NOTICE --}}
                <div class="notice-card">
                    <div class="notice-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>
                    <div>
                        <div class="notice-title">Petunjuk Tiket QR Code</div>
                        <p class="notice-desc">
                            Setelah klik booking, sistem akan membuatkan <strong>QR Code Tiket Booking</strong> unik. Tunjukkan tiket tersebut ke petugas saat Anda tiba di perpustakaan.
                        </p>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="action-group">
                    <button
                        type="submit"
                        class="btn-submit-loan"
                        @disabled(! $bisaMeminjam)
                    >
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        Booking Buku & Dapatkan QR Code
                    </button>

                    <a href="{{ route('katalog.show', $buku->idBuku) }}" class="btn-back-detail">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali ke Detail Buku
                    </a>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
