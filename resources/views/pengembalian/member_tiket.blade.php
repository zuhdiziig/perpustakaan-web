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

    {{-- 2-COLUMN GRID PASS --}}
    <div class="ticket-grid">
        {{-- KOLOM KIRI: QR CODE CARD --}}
        <div class="ticket-qr-card">
            <div class="qr-badge-header">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                Menunggu Scan Petugas
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

                <form action="{{ route('pengembalian.member.batal', $detail->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan pengembalian buku ini? Status buku akan dikembalikan menjadi Dipinjam.')">
                    @csrf
                    <button type="submit" class="btn-qr-action btn-qr-cancel">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Batalkan Pengajuan
                    </button>
                </form>
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
</div>
@endsection
