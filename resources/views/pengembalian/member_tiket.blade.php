@extends('layouts.anggota')

@section('title', 'Tiket Pengembalian Buku - BOOKNEST')

@section('styles')
<style>
    /* =========================================================
       HALAMAN TIKET PENGEMBALIAN BUKU (MEMBER RETURN PASS)
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
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
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

    .ticket-code-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }

    .ticket-code-val {
        font-size: 20px;
        font-weight: 900;
        color: var(--brand-primary);
        letter-spacing: 1px;
        margin-bottom: 14px;
    }

    .qr-instruction-text {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.5;
        padding: 12px 14px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        margin-bottom: 18px;
    }

    .qr-actions-box {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .btn-qr-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
        border: none;
        width: 100%;
        box-sizing: border-box;
    }

    .btn-qr-print {
        background: var(--brand-primary);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(15, 118, 110, 0.25);
    }

    .btn-qr-print:hover {
        background: #0d655e;
        color: #ffffff;
        transform: translateY(-1px);
    }

    .btn-qr-cancel {
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
    }

    .btn-qr-cancel:hover {
        background: #ffe4e6;
        color: #be123c;
    }

    /* --- TICKET DETAIL CARD (KOLOM KANAN) --- */
    .ticket-detail-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 26px 28px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    .detail-card-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 20px;
        gap: 16px;
    }

    .detail-head-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--text-heading);
        margin: 0 0 4px;
    }

    .detail-head-subtitle {
        font-size: 12.5px;
        color: var(--text-muted);
        margin: 0;
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

    .book-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
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

    .badge-copy-num {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
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
    .return-steps {
        background: #f0fdf9;
        border: 1px solid #ccfbf1;
        border-radius: 14px;
        padding: 16px 18px;
        margin-bottom: 24px;
    }

    .return-steps-title {
        font-size: 13.5px;
        font-weight: 800;
        color: #0f766e;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .return-steps-list {
        margin: 0;
        padding-left: 20px;
        font-size: 12.5px;
        color: #115e59;
        line-height: 1.6;
    }

    /* Bottom Back Button */
    .ticket-nav-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s;
    }

    .ticket-nav-back:hover {
        color: var(--brand-primary);
    }

    @media (max-width: 860px) {
        .ticket-grid {
            grid-template-columns: 1fr;
        }

        .ticket-qr-card {
            max-width: 420px;
            margin: 0 auto;
            width: 100%;
        }
    }

    /* --- PRINT STYLES --- */
    @media print {
        header, .sidebar, .ticket-breadcrumb, .btn-qr-action, .ticket-nav-back, .app-header {
            display: none !important;
        }

        body {
            background: #ffffff !important;
        }

        .ticket-page {
            max-width: 100% !important;
            padding: 0 !important;
        }

        .ticket-grid {
            grid-template-columns: 320px 1fr !important;
        }

        .ticket-qr-card, .ticket-detail-card {
            box-shadow: none !important;
            border: 1px solid #333333 !important;
        }
    }

    /* --- POPUP NOTIFICATION MODAL STYLES --- */
    .popup-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .popup-modal-overlay.show {
        display: flex;
    }

    .popup-modal-container {
        position: relative;
        background: #ffffff;
        border-radius: 20px;
        width: 100%;
        max-width: 440px;
        padding: 28px 24px;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35), 0 0 0 1px rgba(15, 23, 42, 0.08);
        text-align: center;
        animation: popupZoomIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        z-index: 10;
    }

    @keyframes popupZoomIn {
        from { transform: scale(0.92); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .popup-modal-close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f1f5f9;
        border: none;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .popup-modal-close:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .popup-icon-wrapper {
        position: relative;
        width: 68px;
        height: 68px;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .popup-icon-pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #10b981;
        opacity: 0.2;
        animation: popupPulse 2s infinite;
    }

    @keyframes popupPulse {
        0% { transform: scale(0.95); opacity: 0.4; }
        50% { transform: scale(1.25); opacity: 0; }
        100% { transform: scale(0.95); opacity: 0; }
    }

    .popup-icon-circle {
        position: relative;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 18px rgba(16, 185, 129, 0.35);
    }

    .popup-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #dcfce7;
        color: #15803d;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .popup-pulse-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #16a34a;
    }

    .popup-modal-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
        margin: 0 0 6px;
        line-height: 1.3;
    }

    .popup-modal-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.55;
        margin: 0 0 18px;
    }

    .popup-info-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        text-align: left;
    }

    .popup-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12.5px;
    }

    .popup-info-row .info-label {
        color: #64748b;
        font-weight: 500;
    }

    .popup-info-row .info-value {
        color: #0f172a;
        font-weight: 700;
    }

    .popup-info-row .info-value.font-mono {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 12px;
        background: #e2e8f0;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .popup-info-row .info-value.text-teal {
        color: #0f766e;
    }

    .popup-info-row .info-badge-success {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 700;
        color: #15803d;
        background: #dcfce7;
        padding: 2px 8px;
        border-radius: 6px;
    }

    .popup-modal-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .btn-popup-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 11px 16px;
        border-radius: 10px;
        background: #0f766e;
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);
    }

    .btn-popup-primary:hover {
        background: #115e59;
        transform: translateY(-1px);
        color: #ffffff;
    }

    .btn-popup-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 9px 16px;
        border-radius: 10px;
        background: transparent;
        border: 1px solid #cbd5e1;
        color: #64748b;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-popup-secondary:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
</style>
@endsection

@section('content')
<div class="ticket-page">
    {{-- BREADCRUMB --}}
    <div class="ticket-breadcrumb">
        <a href="{{ route('dashboard') }}">Beranda</a>
        <span>›</span>
        <a href="{{ route('pengembalian.member') }}">Pengembalian Saya</a>
        <span>›</span>
        <span>Tiket QR Pengembalian</span>
    </div>

    {{-- HEADER TITLE --}}
    <h1 class="ticket-page-title">Tiket Pengembalian Buku Fisik</h1>
    <p class="ticket-page-subtitle">
        Tunjukkan QR Code tiket ini kepada petugas perpustakaan di meja layanan sirkulasi saat mengembalikan buku fisik.
    </p>

    {{-- ALERT BANNER SUCCESS --}}
    @if(session('success'))
        <div class="ticket-alert-success">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div>
                <strong>Pengajuan Pengembalian Berhasil Dibuat!</strong><br>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($detail->kode_batch_kembali)
        <div style="background: #f0fdfa; border: 1.5px solid #99f6e4; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 18px;">📚</span>
                <div style="font-size: 13px; color: #0f766e; font-weight: 600;">
                    Buku ini diajukan bersama dalam <strong>Tiket Pengembalian Sekaligus ({{ $detail->kode_batch_kembali }})</strong>.
                </div>
            </div>
            <a href="{{ route('pengembalian.member.batch-tiket', $detail->kode_batch_kembali) }}" style="padding: 7px 14px; background: #0f766e; color: #fff; font-size: 12.5px; font-weight: 700; border-radius: 8px; text-decoration: none;">
                Buka Tiket Sekaligus &rarr;
            </a>
        </div>
    @endif

    {{-- 2-COLUMN GRID PASS --}}
    <div class="ticket-grid">
        {{-- KOLOM KIRI: QR CODE CARD --}}
        <div class="ticket-qr-card">
            <div class="qr-badge-header" id="qrStatusBadge" @if(!empty($isCompleted)) style="background: #dcfce7; color: #15803d; border-color: #86efac;" @endif>
                @if(!empty($isCompleted))
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #16a34a;">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Selesai Dikonfirmasi</span>
                @else
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Menunggu Scan Petugas</span>
                @endif
            </div>

            <div class="qr-frame">
                {!! $qrCodeSvg !!}
            </div>

            <div class="ticket-code-label">Kode Tiket Pengembalian</div>
            <div class="ticket-code-val">{{ $detail->kode_kembali ?? '-' }}</div>

            <div class="qr-instruction-text">
                Scan kode QR di atas pada barcode reader meja sirkulasi untuk serah terima buku dan verifikasi kondisi buku secara langsung.
            </div>

            <div class="qr-actions-box">
                <button type="button" class="btn-qr-action btn-qr-print" onclick="window.print()">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    Cetak Tiket QR
                </button>

                @if(empty($isCompleted))
                <form id="formBatalTiket" action="{{ route('pengembalian.member.batal', $detail->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan pengembalian buku ini? Status buku akan dikembalikan menjadi Dipinjam.')">
                    @csrf
                    <button type="submit" class="btn-qr-action btn-qr-cancel">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Batalkan Pengajuan
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- KOLOM KANAN: RINCIAN PENGEMBALIAN --}}
        <div class="ticket-detail-card">
            <div class="detail-card-head">
                <div>
                    <h2 class="detail-head-title">Rincian Pengajuan Pengembalian</h2>
                    <p class="detail-head-subtitle">Informasi lengkap transaksi peminjaman & buku yang akan dikembalikan</p>
                </div>
                <span style="font-size: 12px; font-weight: 700; color: #475569; background: #f1f5f9; padding: 4px 10px; border-radius: 8px;">
                    #TRX-{{ str_pad($detail->idPeminjaman, 5, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            {{-- BUKU ITEM ROW --}}
            <div class="book-item-box">
                <div class="book-icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <div class="book-item-info">
                    <div class="book-item-title">{{ $detail->buku->judul ?? 'Judul Buku' }}</div>
                    <div class="book-item-author">{{ $detail->buku->penulis ?? 'Anonim' }} • {{ $detail->buku->kategori->namaKategori ?? 'Umum' }}</div>
                    <div class="book-item-meta-badges">
                        <span class="badge-copy-num">Eksemplar #{{ $detail->eksemplar->nomor_eksemplar ?? '1' }}</span>
                        <span class="badge-shelf">Rak: {{ $detail->buku->rak ?? 'Utama' }}</span>
                        @if($detail->eksemplar?->kode_barcode)
                            <span class="badge-copy-num">{{ $detail->eksemplar->kode_barcode }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SPEC LIST TABLE --}}
            <div class="ticket-spec-list">
                <div class="ticket-spec-row">
                    <span class="spec-key">Nama Peminjam (Member)</span>
                    <span class="spec-val">{{ $detail->peminjaman->member->name ?? auth()->user()->name }}</span>
                </div>
                <div class="ticket-spec-row">
                    <span class="spec-key">Nomor Anggota</span>
                    <span class="spec-val">{{ $detail->peminjaman->member->kode_anggota ?? '-' }}</span>
                </div>
                <div class="ticket-spec-row">
                    <span class="spec-key">Tanggal Mulai Pinjam</span>
                    <span class="spec-val">{{ \Carbon\Carbon::parse($detail->peminjaman->tanggalPinjam)->translatedFormat('l, d F Y') }}</span>
                </div>
                <div class="ticket-spec-row">
                    <span class="spec-key">Batas Jatuh Tempo</span>
                    <span class="spec-val" style="color: {{ $isOverdue ? '#dc2626' : 'inherit' }};">
                        {{ $batasKembali->translatedFormat('l, d F Y') }}
                    </span>
                </div>
                <div class="ticket-spec-row">
                    <span class="spec-key">Status Keterlambatan</span>
                    <span class="spec-val">
                        @if($isOverdue)
                            <span style="color: #dc2626; font-weight: 800;">● Terlambat {{ $hariTerlambat }} Hari</span>
                            <span style="font-size: 12px; color: #dc2626; display: block;">(Est. Denda: Rp {{ number_format($estDenda, 0, ',', '.') }})</span>
                        @else
                            <span style="color: #166534; font-weight: 800;">✓ Tepat Waktu (Bebas Denda Keterlambatan)</span>
                        @endif
                    </span>
                </div>
                <div class="ticket-spec-row">
                    <span class="spec-key">Kondisi Laporan Member</span>
                    <span class="spec-val">
                        @if($detail->kondisi_laporan === 'Baik')
                            <span style="color: #166534; font-weight: 700;">Baik (Buku utuh dan bersih)</span>
                        @elseif($detail->kondisi_laporan === 'Rusak')
                            <span style="color: #d97706; font-weight: 700;">Rusak (Ada kerusakan fisik)</span>
                        @else
                            <span style="color: #dc2626; font-weight: 700;">Hilang (Buku tidak ditemukan)</span>
                        @endif
                        <span style="font-size: 11px; color: var(--text-muted); display: block; font-weight: 400;">*Kondisi akhir akan diverifikasi langsung oleh petugas</span>
                    </span>
                </div>
                <div class="ticket-spec-row">
                    <span class="spec-key">Waktu Pengajuan Tiket</span>
                    <span class="spec-val">{{ $detail->waktu_pengajuan_kembali ? \Carbon\Carbon::parse($detail->waktu_pengajuan_kembali)->translatedFormat('d M Y, H:i') : '-' }} WIB</span>
                </div>
                @if($detail->denda)
                    <div class="ticket-spec-row" style="background: #f0fdf4; padding: 10px 14px; border-radius: 10px; border: 1px solid #bbf7d0; margin-top: 4px;">
                        <span class="spec-key" style="color: #166534; font-weight: 700;">Status Denda</span>
                        <span class="spec-val" style="color: #166534;">
                            @if($detail->denda->status === 'Lunas')
                                <span style="display: inline-flex; align-items: center; gap: 6px; background: #dcfce7; color: #15803d; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 800;">
                                    ✓ Lunas (Rp {{ number_format($detail->denda->jumlah, 0, ',', '.') }})
                                </span>
                                <span style="font-size: 11.5px; color: #15803d; display: block; margin-top: 3px;">
                                    {{ $detail->denda->jenisDenda }} (Telah dibayar via QRIS)
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 6px; background: #fee2e2; color: #dc2626; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 800;">
                                    ● Belum Lunas (Rp {{ number_format($detail->denda->jumlah, 0, ',', '.') }})
                                </span>
                            @endif
                        </span>
                    </div>
                @endif
            </div>

            {{-- RETURN STEPS --}}
            <div class="return-steps">
                <div class="return-steps-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 16 16 12 12 8"></polyline>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    Panduan Pengembalian Buku di Perpustakaan
                </div>
                <ol class="return-steps-list">
                    <li>Bawa buku fisik eksemplar di atas ke meja sirkulasi perpustakaan BOOKNEST.</li>
                    <li>Tunjukkan QR Code pada tiket ini dari ponsel Anda kepada petugas perpustakaan.</li>
                    <li>Petugas akan melakukan scan QR, memverifikasi fisik buku, dan menyelesaikan pengembalian.</li>
                    <li>Setelah transaksi selesai, Anda akan menerima bukti tanda terima pengembalian resmi (#RET).</li>
                </ol>
            </div>

            {{-- BACK BUTTON --}}
            <div>
                <a href="{{ route('pengembalian.member') }}" class="ticket-nav-back">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Kembali ke Daftar Pengembalian Saya
                </a>
            </div>
        </div>
    </div>

    {{-- POP-UP MESSAGE MODAL: PENGEMBALIAN BERHASIL DIKONFIRMASI PETUGAS --}}
    <div id="popupSuccessModal" class="popup-modal-overlay" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="popupModalTitle" onclick="if(event.target === this) closeSuccessModal()">
        <div class="popup-modal-container">
            <!-- Close Button -->
            <button type="button" class="popup-modal-close" onclick="closeSuccessModal()" aria-label="Tutup">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <!-- Animated Success Icon -->
            <div class="popup-icon-wrapper">
                <div class="popup-icon-pulse"></div>
                <div class="popup-icon-circle">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
            </div>

            <!-- Heading & Message -->
            <div class="popup-badge-pill">
                <span class="popup-pulse-dot"></span>
                Pengembalian Diterima
            </div>
            <h3 id="popupModalTitle" class="popup-modal-title">Pengembalian Berhasil Dikonfirmasi! 🎉</h3>
            <p class="popup-modal-desc">
                Petugas di Meja Sirkulasi BOOKNEST telah berhasil memeriksa dan menerima buku fisik Anda.
            </p>

            <!-- Information Card / Receipt Snapshot -->
            <div class="popup-info-card">
                <div class="popup-info-row">
                    <span class="info-label">Nomor Tiket</span>
                    <span class="info-value font-mono" id="popupTiketVal">{{ $detail->kode_kembali ?? '-' }}</span>
                </div>
                <div class="popup-info-row">
                    <span class="info-label">Judul Buku</span>
                    <span class="info-value">{{ Str::limit($detail->buku->judul ?? '-', 26) }}</span>
                </div>
                <div class="popup-info-row">
                    <span class="info-label">Petugas Penerima</span>
                    <span class="info-value text-teal" id="popupPetugasVal">Petugas Meja Sirkulasi</span>
                </div>
                <div class="popup-info-row">
                    <span class="info-label">Waktu Selesai</span>
                    <span class="info-value" id="popupWaktuVal">{{ now()->translatedFormat('d M Y, H:i') }}</span>
                </div>
                <div class="popup-info-row" style="border-top: 1px dashed #e2e8f0; padding-top: 8px; margin-top: 2px;">
                    <span class="info-label">Status Fisik</span>
                    <span class="info-badge-success">✓ Fisik Buku Diterima</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="popup-modal-actions">
                <a href="{{ route('pengembalian.member', ['tab' => 'riwayat']) }}" class="btn-popup-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 14 14"></polyline>
                    </svg>
                    Lihat Riwayat Pengembalian
                </a>
                <button type="button" class="btn-popup-secondary" onclick="redirectToDashboard()">
                    Beralih ke Dasbor Sekarang
                </button>
            </div>

            <!-- Auto-redirect Countdown Notice -->
            <div style="margin-top: 14px; font-size: 12px; color: #64748b; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 6px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0f766e" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                <span>Otomatis beralih ke Dasbor dalam <strong id="popupCountdown" style="color: #0f766e; font-weight: 800;">5</strong> detik...</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let autoRedirectTimer = null;
    let autoCountdownInterval = null;

    function redirectToDashboard() {
        if (autoRedirectTimer) clearTimeout(autoRedirectTimer);
        if (autoCountdownInterval) clearInterval(autoCountdownInterval);
        closeSuccessModal();
        window.location.href = "{{ route('dashboard') }}";
    }

    // Audio Chime synth via HTML5 Web Audio API
    function playSuccessChime() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const audioCtx = new AudioContext();
            const playTone = (freq, start, duration) => {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.12, audioCtx.currentTime + start);
                gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + start + duration);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(audioCtx.currentTime + start);
                osc.stop(audioCtx.currentTime + start + duration);
            };
            playTone(523.25, 0, 0.16);     // C5
            playTone(659.25, 0.12, 0.16);  // E5
            playTone(783.99, 0.24, 0.45);  // G5
        } catch (e) {
            // Audio error ignored
        }
    }

    function showSuccessModal(data) {
        playSuccessChime();
        const modal = document.getElementById('popupSuccessModal');
        if (data) {
            if (data.kodeTiket) {
                const el = document.getElementById('popupTiketVal');
                if (el) el.textContent = data.kodeTiket;
            }
            if (data.namaPetugas) {
                const el = document.getElementById('popupPetugasVal');
                if (el) el.textContent = data.namaPetugas;
            }
            if (data.waktuSelesai) {
                const el = document.getElementById('popupWaktuVal');
                if (el) el.textContent = data.waktuSelesai;
            }
        }

        // Perbarui badge tampilan tiket secara real-time
        const badge = document.getElementById('qrStatusBadge');
        if (badge) {
            badge.style.background = '#dcfce7';
            badge.style.color = '#15803d';
            badge.style.borderColor = '#86efac';
            badge.innerHTML = `
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #16a34a;">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Selesai Dikonfirmasi</span>
            `;
        }

        // Sembunyikan tombol batalkan pengajuan jika masih tampil
        const cancelForm = document.getElementById('formBatalTiket');
        if (cancelForm) {
            cancelForm.style.display = 'none';
        }

        if (modal) {
            modal.classList.add('show');
            modal.style.display = 'flex';
        }

        // Countdown 5 detik lalu beralih ke Dashboard Anggota
        let countdownSec = 5;
        const countdownEl = document.getElementById('popupCountdown');
        if (countdownEl) countdownEl.textContent = countdownSec;

        if (autoCountdownInterval) clearInterval(autoCountdownInterval);
        autoCountdownInterval = setInterval(() => {
            countdownSec--;
            if (countdownEl) countdownEl.textContent = countdownSec;
            if (countdownSec <= 0) {
                clearInterval(autoCountdownInterval);
            }
        }, 1000);

        if (autoRedirectTimer) clearTimeout(autoRedirectTimer);
        autoRedirectTimer = setTimeout(() => {
            redirectToDashboard();
        }, 5000);
    }

    function closeSuccessModal() {
        if (autoRedirectTimer) clearTimeout(autoRedirectTimer);
        if (autoCountdownInterval) clearInterval(autoCountdownInterval);
        const modal = document.getElementById('popupSuccessModal');
        if (modal) {
            modal.classList.remove('show');
            modal.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const isAlreadyCompleted = {{ !empty($isCompleted) ? 'true' : 'false' }};
        if (isAlreadyCompleted) {
            return;
        }

        const statusUrl = "{{ route('pengembalian.member.status-tiket', $detail->id) }}";
        let pollTimer = setInterval(() => {
            fetch(statusUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Status check failed');
                return res.json();
            })
            .then(res => {
                if (res && res.success && res.completed) {
                    clearInterval(pollTimer);
                    showSuccessModal(res);
                }
            })
            .catch(err => {
                // Silently wait for next polling tick
            });
        }, 2500);
    });
</script>
@endsection
