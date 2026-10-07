@extends('layouts.anggota')

@section('title', 'Tiket Booking Peminjaman - BOOKNEST')

@section('styles')
<style>
    /* =========================================================
       HALAMAN TIKET BOOKING PEMINJAMAN (MEMBER PASS)
       ========================================================= */

    .ticket-page {
        width: 100%;
        max-width: 1080px;
        margin: 0 auto;
        padding: 4px 0 40px;
        box-sizing: border-box;
    }

    /* --- BREADCRUMB & HEADER --- */
    .ticket-breadcrumb {
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

    .ticket-breadcrumb a {
        color: var(--text-muted);
        text-decoration: none;
        transition: color 0.15s;
    }

    .ticket-breadcrumb a:hover {
        color: var(--brand-primary);
    }

    .ticket-page-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.6px;
        margin-bottom: 6px;
        line-height: 1.25;
    }

    .ticket-page-subtitle {
        font-size: 14px;
        color: var(--text-muted);
        line-height: 1.6;
        margin: 0 0 22px;
    }

    /* --- FLASH ALERT --- */
    .ticket-alert-success {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        font-size: 13.5px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        margin-bottom: 24px;
        line-height: 1.55;
    }

    /* --- 2-COLUMN PASS LAYOUT --- */
    .ticket-grid {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 24px;
        align-items: start;
    }

    /* --- TICKET QR CARD (KOLOM KIRI) --- */
    .ticket-qr-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 24px 20px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        text-align: center;
        position: relative;
    }

    .qr-badge-header {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.3px;
        margin-bottom: 18px;
    }

    .badge-booking {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-siap {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-dipinjam {
        background: #dcfce7;
        color: #166534;
    }

    .qr-frame {
        width: 220px;
        height: 220px;
        margin: 0 auto 16px;
        padding: 12px;
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qr-frame svg,
    .qr-frame img {
        max-width: 100%;
        max-height: 100%;
        display: block;
    }

    .booking-code-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }

    .booking-code-val {
        font-size: 20px;
        font-weight: 900;
        color: var(--brand-primary);
        letter-spacing: 1px;
        font-family: monospace;
        margin-bottom: 8px;
    }

    .qr-instruction {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 18px;
    }

    /* Batas Ambil Pill */
    .pickup-deadline-box {
        background: #fffbeb;
        border: 1px solid #fef3c7;
        border-radius: 12px;
        padding: 12px;
        font-size: 12px;
        color: #92400e;
        line-height: 1.5;
        margin-bottom: 16px;
        text-align: left;
    }

    .pickup-deadline-box strong {
        display: block;
        font-size: 12.5px;
        margin-bottom: 2px;
        color: #78350f;
    }

    /* --- DETAIL CARD (KOLOM KANAN) --- */
    .ticket-detail-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 26px 28px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    .detail-card-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Showcase Buku Singkat */
    .book-item-box {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        margin-bottom: 22px;
    }

    .book-item-cover {
        width: 60px;
        height: 84px;
        border-radius: 8px;
        overflow: hidden;
        background: #cbd5e1;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .book-item-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-item-info {
        flex: 1;
        min-width: 0;
    }

    .book-item-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text-heading);
        margin: 0 0 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .book-item-author {
        font-size: 13px;
        color: var(--text-muted);
        margin: 0 0 8px;
    }

    .book-item-meta-badges {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .badge-shelf {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
    }

    /* Rincian List */
    .ticket-spec-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 24px;
    }

    .ticket-spec-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 10px;
        border-bottom: 1px dashed #e2e8f0;
        font-size: 13.5px;
    }

    .ticket-spec-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .spec-key {
        color: var(--text-muted);
        font-weight: 500;
    }

    .spec-val {
        color: var(--text-heading);
        font-weight: 700;
        text-align: right;
    }

    /* Info Panduan Step */
    .pickup-steps {
        background: #f0fdf9;
        border: 1px solid #ccfbf1;
        border-radius: 14px;
        padding: 16px 18px;
        margin-bottom: 24px;
    }

    .pickup-steps-title {
        font-size: 13.5px;
        font-weight: 800;
        color: #0f766e;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pickup-steps-list {
        margin: 0;
        padding-left: 20px;
        font-size: 12.5px;
        color: #115e59;
        line-height: 1.6;
    }

    /* Action Buttons Row */
    .ticket-actions-row {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-ticket-print {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 18px;
        background: var(--brand-primary);
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
        text-decoration: none;
    }

    .btn-ticket-print:hover {
        background: var(--brand-primary-dark);
    }

    .btn-ticket-history {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 18px;
        background: #ffffff;
        color: #334155;
        border: 1px solid var(--border-color);
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-ticket-history:hover {
        background: #f8fafc;
        color: var(--text-heading);
    }

    .btn-ticket-cancel {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 11px 14px;
        background: transparent;
        color: #ef4444;
        border: 1px solid #fecaca;
        font-size: 13px;
        font-weight: 600;
        border-radius: 10px;
        cursor: pointer;
        margin-left: auto;
        transition: all 0.15s ease;
    }

    .btn-ticket-cancel:hover {
        background: #fef2f2;
    }

    /* Print Styles */
    @media print {
        body {
            background: #ffffff;
        }
        .navbar, .sidebar, .ticket-breadcrumb, .ticket-actions-row, .ticket-alert-success, #sidebarToggle, #sidebarBackdrop {
            display: none !important;
        }
        .content-area, .page-wrapper, .ticket-page {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .ticket-grid {
            display: block !important;
        }
        .ticket-qr-card, .ticket-detail-card {
            border: 1px solid #000000 !important;
            box-shadow: none !important;
            page-break-inside: avoid;
            margin-bottom: 20px;
        }
    }

    @media (max-width: 820px) {
        .ticket-grid {
            grid-template-columns: 1fr;
        }

        .btn-ticket-cancel {
            margin-left: 0;
            width: 100%;
            justify-content: center;
        }

        .btn-ticket-print,
        .btn-ticket-history {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="ticket-page">

    {{-- BREADCRUMB --}}
    <div class="ticket-breadcrumb">
        <a href="{{ route('dashboard') }}">Beranda</a>
        <span>/</span>
        <a href="{{ route('katalog.index') }}">Katalog</a>
        <span>/</span>
        <span>Tiket Booking Peminjaman</span>
    </div>

    <h1 class="ticket-page-title">Tiket Booking Peminjaman</h1>
    <p class="ticket-page-subtitle">
        Tunjukkan kode QR ini kepada petugas di meja layanan sirkulasi perpustakaan untuk serah terima buku fisik.
    </p>

    {{-- ALERT SUKSES --}}
    @if (session('success'))
        <div class="ticket-alert-success">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div>
                <strong>Berhasil:</strong> {{ session('success') }}
            </div>
        </div>
    @endif

    @php
        $detailUtama = $peminjaman->details->first();
        $buku = $detailUtama?->buku;
        $eksemplar = $detailUtama?->eksemplar;
        $isDisiapkanPetugas = ($peminjaman->opsi_pengambilan ?? 'siapkan_petugas') === 'siapkan_petugas';
    @endphp

    {{-- GRID 2 KOLOM TIKET --}}
    <div class="ticket-grid">

        {{-- KOLOM KIRI: QR CARD PASS --}}
        <div class="ticket-qr-card">
            @if ($peminjaman->status === 'Booking')
                <div class="qr-badge-header badge-booking">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: #d97706; display: inline-block;"></span>
                    Menunggu Pengambilan
                </div>
            @elseif ($peminjaman->status === 'Siap Diambil')
                <div class="qr-badge-header badge-siap">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: #2563eb; display: inline-block;"></span>
                    Buku Siap di Meja Layanan
                </div>
            @elseif ($peminjaman->status === 'Dipinjam')
                <div class="qr-badge-header badge-dipinjam">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: #16a34a; display: inline-block;"></span>
                    Telah Diserahkan & Aktif
                </div>
            @else
                <div class="qr-badge-header badge-booking">
                    {{ $peminjaman->status }}
                </div>
            @endif

            {{-- FRAME QR CODE --}}
            <div class="qr-frame" id="ticketQrFrame">
                {!! $qrCodeSvg !!}
            </div>

            <div class="booking-code-label">Kode Booking Tiket</div>
            <div class="booking-code-val" id="bookingCodeText">{{ $peminjaman->kode_booking ?? $peminjaman->kodeTransaksi }}</div>

            <p class="qr-instruction">
                Perlihatkan layar ini ke pemindai barcode / kamera petugas di meja layanan.
            </p>

            {{-- BOX BATAS AMBIL --}}
            <div class="pickup-deadline-box">
                <strong>Batas Waktu Pengambilan:</strong>
                @if ($peminjaman->batasAmbil)
                    {{ \Carbon\Carbon::parse($peminjaman->batasAmbil)->translatedFormat('l, d F Y - H:i') }} WIB
                @else
                    Maksimal 48 jam sejak booking diajukan
                @endif
                <div style="font-size: 11px; margin-top: 3px; color: #a16207;">
                    *Lewat batas waktu, booking akan kedaluwarsa otomatis agar buku bisa diakses anggota lain.
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: RINCIAN BUKU & ANGGOTA --}}
        <div class="ticket-detail-card">
            <div class="detail-card-title">
                <span>Rincian Peminjaman</span>
                <span style="font-size: 12px; font-weight: 600; color: #64748b;">
                    ID Transaksi #{{ $peminjaman->idPeminjaman }}
                </span>
            </div>

            {{-- MINI BOOK CARD --}}
            @if ($buku)
                <div class="book-item-box">
                    <div class="book-item-cover">
                        @if (! empty($buku->cover))
                            <img src="{{ $buku->cover }}" alt="{{ $buku->judul }}" onerror="this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=400&auto=format&fit=crop';">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #64748b;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="book-item-info">
                        <h3 class="book-item-title">{{ $buku->judul }}</h3>
                        <p class="book-item-author">{{ $buku->penulis }} · {{ $buku->penerbit ?? 'Penerbit' }}</p>
                        <div class="book-item-meta-badges">
                            <span class="badge-shelf">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Lokasi: {{ $buku->rak ?? 'Perpustakaan Pusat' }}
                            </span>
                            @if ($eksemplar)
                                <span style="font-size: 11.5px; font-weight: 700; color: #475569; background: #f1f5f9; padding: 3px 8px; border-radius: 6px;">
                                    Eksemplar #{{ $eksemplar->nomor_eksemplar }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- SPESIFIKASI TABEL --}}
            <div class="ticket-spec-list">
                <div class="ticket-spec-row">
                    <span class="spec-key">Nama Anggota</span>
                    <span class="spec-val">{{ $peminjaman->member?->name ?? auth()->user()->name }}</span>
                </div>

                <div class="ticket-spec-row">
                    <span class="spec-key">Nomor Anggota</span>
                    <span class="spec-val">{{ $peminjaman->member?->kode_anggota ?? auth()->user()->kode_anggota }}</span>
                </div>

                <div class="ticket-spec-row">
                    <span class="spec-key">Metode Pengambilan</span>
                    <span class="spec-val" style="color: var(--brand-primary);">
                        @if ($isDisiapkanPetugas)
                            📦 Disiapkan oleh Petugas di Meja Layanan
                        @else
                            🔍 Ambil Mandiri di {{ $buku->rak ?? 'Rak Buku' }}
                        @endif
                    </span>
                </div>

                <div class="ticket-spec-row">
                    <span class="spec-key">Waktu Pengajuan</span>
                    <span class="spec-val">{{ $peminjaman->created_at ? $peminjaman->created_at->translatedFormat('d M Y, H:i') : now()->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>

                <div class="ticket-spec-row">
                    <span class="spec-key">Masa Pinjam Nanti</span>
                    <span class="spec-val">30 Hari (mulai dihitung saat buku diserahkan)</span>
                </div>
            </div>

            {{-- PANDUAN PENGAMBILAN --}}
            <div class="pickup-steps">
                <div class="pickup-steps-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                    Panduan Pengambilan Buku:
                </div>
                <ol class="pickup-steps-list">
                    @if ($isDisiapkanPetugas)
                        <li>Datang ke meja layanan sirkulasi perpustakaan pusat sebelum batas waktu berakhir.</li>
                        <li>Tunjukkan layar QR Code tiket ini ke petugas.</li>
                        <li>Petugas akan menyerahkan buku yang telah disiapkan dan mengonfirmasi peminjaman Anda.</li>
                    @else
                        <li>Datang ke perpustakaan pusat dan menuju <strong>{{ $buku->rak ?? 'rak koleksi' }}</strong>.</li>
                        <li>Ambil buku fisik dengan nomor eksemplar <strong>#{{ $eksemplar?->nomor_eksemplar ?? 1 }}</strong>.</li>
                        <li>Bawa buku ke meja layanan lalu tunjukkan QR Code tiket ini ke petugas untuk verifikasi.</li>
                    @endif
                </ol>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="ticket-actions-row">
                <button type="button" class="btn-ticket-print" onclick="window.print()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    Cetak / Simpan Tiket
                </button>

                <a href="{{ route('riwayat.index') }}" class="btn-ticket-history">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3"></path><circle cx="12" cy="12" r="9"></circle></svg>
                    Riwayat Peminjaman
                </a>

                @if (in_array($peminjaman->status, ['Booking', 'Siap Diambil']))
                    <form method="POST" action="{{ route('peminjaman.booking.batal', $peminjaman->idPeminjaman) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking peminjaman ini? Eksemplar buku akan dikembalikan ke stok umum.');" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn-ticket-cancel">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            Batalkan Booking
                        </button>
                    </form>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
