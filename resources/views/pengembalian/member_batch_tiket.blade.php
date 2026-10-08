@extends('layouts.anggota')

@section('title', 'Tiket Pengembalian Sekaligus - BOOKNEST')

@section('styles')
<style>
    /* =========================================================
       HALAMAN TIKET PENGEMBALIAN BUKU SEKALIGUS (BATCH RETURN)
       ========================================================= */

    .ticket-page {
        width: 100%;
        max-width: 1140px;
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
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        margin-bottom: 16px;
    }

    .qr-frame {
        background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        border-radius: 14px;
        padding: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        max-width: 250px;
    }

    .qr-frame svg, .qr-frame img {
        width: 100%;
        height: auto;
        display: block;
    }

    .ticket-code-label {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .ticket-code-val {
        font-family: monospace;
        font-size: 16px;
        font-weight: 800;
        color: var(--brand-primary);
        letter-spacing: 0.8px;
        margin-bottom: 14px;
        word-break: break-all;
    }

    .batch-summary-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        justify-content: center;
        margin-bottom: 16px;
    }

    .chip-item {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .chip-item.total {
        background: #f1f5f9;
        color: #334155;
    }

    .chip-item.baik {
        background: #dcfce7;
        color: #15803d;
    }

    .chip-item.rusak {
        background: #fef3c7;
        color: #b45309;
    }

    .chip-item.hilang {
        background: #fee2e2;
        color: #b91c1c;
    }

    .qr-instruction-text {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.55;
        margin-bottom: 20px;
        padding: 0 4px;
    }

    .qr-actions-box {
        display: flex;
        flex-direction: column;
        gap: 9px;
    }

    .btn-qr-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        border: none;
        text-decoration: none;
    }

    .btn-qr-print {
        background: #0f766e;
        color: #ffffff;
    }

    .btn-qr-print:hover {
        background: #115e59;
        transform: translateY(-1px);
    }

    .btn-qr-cancel {
        background: #ffffff;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .btn-qr-cancel:hover {
        background: #fef2f2;
    }

    /* --- DETAIL CARD (KOLOM KANAN) --- */
    .ticket-detail-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    .detail-card-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        padding-bottom: 18px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 20px;
    }

    .detail-head-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-heading);
        margin: 0 0 4px;
    }

    .detail-head-subtitle {
        font-size: 13px;
        color: var(--text-muted);
        margin: 0;
    }

    /* List of books in batch */
    .batch-books-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 22px;
    }

    .batch-book-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.15s ease;
    }

    .batch-book-row:hover {
        border-color: #cbd5e1;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .batch-book-left {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        flex: 1;
    }

    .batch-book-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #ccfbf1;
        color: #0f766e;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .batch-book-info {
        min-width: 0;
    }

    .batch-book-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-heading);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    .batch-book-meta {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
        display: block;
    }

    .batch-book-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
        flex-shrink: 0;
    }

    .condition-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .condition-pill.baik {
        background: #dcfce7;
        color: #15803d;
    }

    .condition-pill.rusak {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .condition-pill.hilang {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .condition-note {
        font-size: 11px;
        color: #e11d48;
        font-weight: 700;
    }

    /* SPEC LIST */
    .ticket-spec-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 22px;
        padding: 14px 18px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .ticket-spec-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        gap: 12px;
    }

    .ticket-spec-row .spec-key {
        color: var(--text-muted);
        font-weight: 600;
    }

    .ticket-spec-row .spec-val {
        color: var(--text-heading);
        font-weight: 700;
        text-align: right;
    }

    /* GUIDE BOX */
    .return-steps {
        background: #f0fdfa;
        border: 1px solid #ccfbf1;
        border-radius: 12px;
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
            max-width: 440px;
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
            grid-template-columns: 300px 1fr !important;
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
        <span>Tiket Pengembalian Sekaligus</span>
    </div>

    {{-- HEADER TITLE --}}
    <h1 class="ticket-page-title">Tiket Pengembalian Buku Sekaligus</h1>
    <p class="ticket-page-subtitle">
        Tunjukkan QR Code tiket pengembalian sekaligus ini kepada petugas di meja layanan sirkulasi perpustakaan BOOKNEST.
    </p>

    {{-- ALERT BANNER SUCCESS --}}
    @if(session('success'))
        <div class="ticket-alert-success">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div>
                <strong>Pengajuan Pengembalian Sekaligus Berhasil Dibuat!</strong><br>
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

            <div class="ticket-code-label">Kode Tiket Pengembalian Kolektif</div>
            <div class="ticket-code-val">{{ $kodeBatch }}</div>

            {{-- Summary Chips --}}
            <div class="batch-summary-chips">
                <span class="chip-item total">{{ $totalBuku }} Buku</span>
                <span class="chip-item baik">{{ $countBaik }} Baik</span>
                @if($countRusak > 0)
                    <span class="chip-item rusak">{{ $countRusak }} Rusak</span>
                @endif
                @if($countHilang > 0)
                    <span class="chip-item hilang">{{ $countHilang }} Hilang</span>
                @endif
            </div>

            <div class="qr-instruction-text">
                Scan satu kode QR di atas pada scanner meja sirkulasi untuk memverifikasi dan mengembalikan seluruh buku dalam daftar ini sekaligus.
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

                <form action="{{ route('pengembalian.member.batch-batal', $kodeBatch) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan pengembalian {{ $totalBuku }} buku ini sekaligus? Status seluruh buku akan dikembalikan menjadi Dipinjam.')">
                    @csrf
                    <button type="submit" class="btn-qr-action btn-qr-cancel">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Batalkan Pengajuan Sekaligus
                    </button>
                </form>
            </div>
        </div>

        {{-- KOLOM KANAN: RINCIAN BUKU DALAM BATCH --}}
        <div class="ticket-detail-card">
            <div class="detail-card-head">
                <div>
                    <h2 class="detail-head-title">Rincian Buku yang Dikembalikan</h2>
                    <p class="detail-head-subtitle">Daftar {{ $totalBuku }} buku beserta kondisi fisik yang dilaporkan</p>
                </div>
                <span style="font-size: 12px; font-weight: 700; color: #0f766e; background: #ccfbf1; padding: 5px 12px; border-radius: 8px;">
                    {{ $totalBuku }} Buku Sekaligus
                </span>
            </div>

            {{-- LIST OF BOOKS IN THIS BATCH --}}
            <div class="batch-books-list">
                @foreach($details as $item)
                    <div class="batch-book-row">
                        <div class="batch-book-left">
                            <div class="batch-book-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                            </div>
                            <div class="batch-book-info">
                                <span class="batch-book-title">{{ $item->buku->judul ?? 'Judul Buku' }}</span>
                                <span class="batch-book-meta">
                                    {{ $item->buku->penulis ?? 'Anonim' }} • 
                                    Eksemplar: #{{ $item->eksemplar->nomor_eksemplar ?? '1' }} • 
                                    Rak: {{ $item->buku->rak ?? 'Utama' }} •
                                    Ref: #TRX-{{ str_pad($item->idPeminjaman, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                        </div>

                        <div class="batch-book-right">
                            @if($item->kondisi_laporan === 'Baik')
                                <span class="condition-pill baik">✓ Kondisi Baik</span>
                            @elseif($item->kondisi_laporan === 'Rusak')
                                <span class="condition-pill rusak">⚠️ Kondisi Rusak</span>
                                <span class="condition-note">Denda: Rp {{ number_format($item->dendaKondisi, 0, ',', '.') }}</span>
                            @else
                                <span class="condition-pill hilang">✕ Buku Hilang</span>
                                <span class="condition-note">Ganti Rugi: Rp {{ number_format($item->dendaKondisi, 0, ',', '.') }}</span>
                            @endif

                            @if($item->isOverdue)
                                <span style="font-size: 11px; color: #dc2626; font-weight: 700;">
                                    Telat {{ $item->hariTerlambat }} hari (+Rp {{ number_format($item->dendaTelat, 0, ',', '.') }})
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- SUMMARY SPEC LIST --}}
            <div class="ticket-spec-list">
                <div class="ticket-spec-row">
                    <span class="spec-key">Nama Anggota (Member)</span>
                    <span class="spec-val">{{ auth()->user()->name }}</span>
                </div>
                <div class="ticket-spec-row">
                    <span class="spec-key">Nomor Anggota</span>
                    <span class="spec-val">{{ auth()->user()->kode_anggota ?? sprintf('AG-%s-%05d', date('Y'), auth()->id()) }}</span>
                </div>
                <div class="ticket-spec-row">
                    <span class="spec-key">Total Buku yang Dikembalikan</span>
                    <span class="spec-val">{{ $totalBuku }} Buku</span>
                </div>
                <div class="ticket-spec-row">
                    <span class="spec-key">Ringkasan Kondisi</span>
                    <span class="spec-val">
                        {{ $countBaik }} Baik 
                        @if($countRusak > 0), {{ $countRusak }} Rusak @endif 
                        @if($countHilang > 0), {{ $countHilang }} Hilang @endif
                    </span>
                </div>
                <div class="ticket-spec-row">
                    <span class="spec-key">Estimasi Total Denda</span>
                    <span class="spec-val" style="color: {{ $totalEstDenda > 0 ? '#dc2626' : '#166534' }}; font-size: 15px; font-weight: 800;">
                        {{ $totalEstDenda > 0 ? 'Rp ' . number_format($totalEstDenda, 0, ',', '.') : 'Rp 0 (Bebas Denda)' }}
                    </span>
                </div>
                @if($totalEstDenda > 0)
                    <div style="font-size: 11.5px; color: #94a3b8; text-align: right; margin-top: -4px;">
                        *Rincian: Denda Keterlambatan Rp {{ number_format($totalEstDendaTelat, 0, ',', '.') }} + Denda Kerusakan/Kehilangan Rp {{ number_format($totalEstDendaKondisi, 0, ',', '.') }}
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
                    Panduan Serah Terima di Meja Layanan Perpustakaan
                </div>
                <ol class="return-steps-list">
                    <li>Bawa buku fisik yang berstatus <strong>Baik</strong> atau <strong>Rusak</strong> ke Meja Sirkulasi BOOKNEST.</li>
                    <li>Tunjukkan QR Code tiket pengembalian kolektif ini kepada petugas perpustakaan.</li>
                    <li>Petugas akan memverifikasi fisik seluruh buku secara bersamaan dan menyelesaikan transaksi.</li>
                    <li>Jika ada denda (keterlambatan/kerusakan/kehilangan), Anda dapat membayarnya langsung via QRIS/Denda Saya.</li>
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
