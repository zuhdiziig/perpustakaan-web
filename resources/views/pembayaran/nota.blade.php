@extends('layouts.anggota')

@section('title', 'Nota Pembayaran - BOOKNEST')

@section('styles')
<style>
    .nota-container {
        width: 100%;
        max-width: 860px;
    }

    /* --- BREADCRUMB --- */
    .nota-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .nota-breadcrumb .brand-crumb {
        color: #0f766e;
        text-decoration: none;
    }

    .nota-breadcrumb .brand-crumb:hover {
        text-decoration: underline;
    }

    .nota-breadcrumb .divider-crumb {
        color: #94a3b8;
    }

    .nota-breadcrumb .active-crumb {
        color: #64748b;
    }

    /* --- PAGE HEADER --- */
    .nota-header-area {
        margin-bottom: 20px;
    }

    .nota-main-heading {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        line-height: 1.25;
        color: #0f172a;
        letter-spacing: -0.6px;
    }

    .nota-main-subtitle {
        margin: 6px 0 0;
        font-size: 14px;
        color: #64748b;
        line-height: 1.5;
    }

    /* --- ACTION BUTTONS --- */
    .nota-actions-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 28px;
    }

    .btn-cetak-nota {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 22px;
        background: #0f766e;
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
        box-shadow: 0 1px 2px rgba(15, 118, 110, 0.15);
    }

    .btn-cetak-nota:hover {
        background: #115e59;
    }

    .btn-kembali-dasbor {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 22px;
        background: #2563eb;
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        border-radius: 8px;
        transition: background 0.15s ease;
        box-shadow: 0 1px 2px rgba(37, 99, 235, 0.15);
    }

    .btn-kembali-dasbor:hover {
        background: #1d4ed8;
    }

    /* --- RECEIPT CONTAINER --- */
    .nota-receipt-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 36px 40px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .receipt-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .receipt-brand-name {
        margin: 0 0 6px;
        font-size: 20px;
        font-weight: 800;
        color: #0f766e;
        letter-spacing: 0.3px;
    }

    .receipt-brand-slogan {
        font-size: 13px;
        color: #475569;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .receipt-brand-address,
    .receipt-brand-contact {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.5;
    }

    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.3px;
    }

    .badge-lunas {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-lunas .badge-dot {
        color: #16a34a;
        font-size: 8px;
    }

    .receipt-section-title {
        margin: 24px 0 20px;
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.4px;
    }

    /* --- RECEIPT TABLE / LIST --- */
    .receipt-table {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .receipt-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        line-height: 1.4;
    }

    .r-label {
        color: #64748b;
        font-weight: 500;
    }

    .r-val {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
    }

    .r-val.mono {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 700;
        color: #334155;
    }

    .receipt-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 20px 0;
    }

    .receipt-summary {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .total-row {
        font-size: 13.5px;
    }

    .total-label {
        font-weight: 700;
        color: #475569;
        letter-spacing: 0.2px;
    }

    .total-val {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
    }

    .receipt-footer-notes {
        margin-top: 28px;
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.6;
    }

    .receipt-footer-notes p {
        margin: 0;
    }

    @media (max-width: 640px) {
        .nota-main-heading { font-size: 22px; }
        .nota-actions-bar { flex-direction: column; width: 100%; }
        .btn-cetak-nota, .btn-kembali-dasbor { width: 100%; text-align: center; }
        .nota-receipt-card { padding: 20px 14px; border-radius: 14px; }
        .receipt-header { flex-direction: column; gap: 14px; }
        .receipt-brand-meta { text-align: left; }
    }

    /* --- PRINT STYLES --- */
    @media print {
        @page {
            margin: 15mm;
            size: auto;
        }

        body {
            background: #ffffff !important;
            color: #000000 !important;
            font-size: 12pt;
        }

        .topbar,
        .sidebar,
        .sidebar-backdrop,
        .nota-breadcrumb,
        .nota-header-area,
        .nota-actions-bar,
        .no-print {
            display: none !important;
        }

        .page-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            display: block !important;
            max-width: 100% !important;
        }

        .content-area {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .nota-container {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .nota-receipt-card {
            border: 1px solid #cbd5e1 !important;
            box-shadow: none !important;
            padding: 24px !important;
            border-radius: 8px !important;
        }

        .receipt-brand-name {
            color: #0f766e !important;
        }

        .badge-lunas {
            border: 1px solid #16a34a !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endsection

@section('content')
<div class="nota-container">

    <!-- BREADCRUMB -->
    <nav class="nota-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}" class="brand-crumb">BOOKNEST</a>
        <span class="divider-crumb">/</span>
        <span class="active-crumb">Anggota</span>
    </nav>

    <!-- PAGE HEADER -->
    <div class="nota-header-area">
        <h1 class="nota-main-heading">Nota Pembayaran</h1>
        <p class="nota-main-subtitle">Bukti resmi pembayaran denda. Simpan atau cetak untuk arsipmu.</p>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="nota-actions-bar">
        <button type="button" onclick="window.print()" class="btn-cetak-nota">
            Cetak Nota
        </button>
        <a href="{{ route('dashboard') }}" class="btn-kembali-dasbor">
            Kembali ke Dasbor
        </a>
    </div>

    <!-- OFFICIAL RECEIPT CARD -->
    <div class="nota-receipt-card">
        
        <!-- RECEIPT TOP BRANDING -->
        <div class="receipt-header">
            <div class="receipt-brand-info">
                <h2 class="receipt-brand-name">BOOKNEST</h2>
                <div class="receipt-brand-slogan">Temukan. Baca. Berkembang.</div>
                <div class="receipt-brand-address">Jl. Merdeka No. 12, Bandung</div>
                <div class="receipt-brand-contact">(022) 420 1234 · halo@booknest.id</div>
            </div>

            <div class="receipt-status-badge">
                <span class="badge-status-pill badge-lunas">
                    <span class="badge-dot">●</span> LUNAS
                </span>
            </div>
        </div>

        <!-- TITLE -->
        <h3 class="receipt-section-title">Nota Denda Peminjaman</h3>

        <!-- DETAILS LIST -->
        <div class="receipt-table">
            <div class="receipt-row">
                <span class="r-label">Nomor nota</span>
                <span class="r-val mono">{{ $nomorNota }}</span>
            </div>

            <div class="receipt-row">
                <span class="r-label">ID pembayaran</span>
                <span class="r-val mono">{{ $idPembayaranText }}</span>
            </div>

            <div class="receipt-row">
                <span class="r-label">Tanggal pembayaran</span>
                <span class="r-val">{{ $tanggalPembayaranText }}</span>
            </div>

            <div class="receipt-row">
                <span class="r-label">Anggota</span>
                <span class="r-val">{{ $member?->name ?? 'Anggota' }} · {{ $nomorAnggota }}</span>
            </div>

            <div class="receipt-row">
                <span class="r-label">Transaksi peminjaman</span>
                <span class="r-val mono">{{ $kodeTransaksiPeminjaman }}</span>
            </div>

            <div class="receipt-row">
                <span class="r-label">Buku</span>
                <span class="r-val">{{ $buku?->judul ?? 'Buku' }} · {{ $kodeBuku }}</span>
            </div>

            <div class="receipt-row">
                <span class="r-label">Tanggal pinjam</span>
                <span class="r-val">{{ $tglPinjamText }}</span>
            </div>

            <div class="receipt-row">
                <span class="r-label">Jatuh tempo</span>
                <span class="r-val">{{ $jatuhTempoText }}</span>
            </div>

            <div class="receipt-row">
                <span class="r-label">Tanggal dikembalikan</span>
                <span class="r-val">{{ $tglKembaliText }}</span>
            </div>

            <div class="receipt-row">
                <span class="r-label">Keterlambatan / tarif</span>
                <span class="r-val">{{ $hariTerlambat }} hari × Rp1.000</span>
            </div>

            <div class="receipt-row">
                <span class="r-label">Denda</span>
                <span class="r-val">Rp{{ number_format($denda?->jumlah ?? $pembayaran->nominal, 0, ',', '.') }}</span>
            </div>

            <div class="receipt-row">
                <span class="r-label">Biaya administrasi</span>
                <span class="r-val">Rp0</span>
            </div>

            <div class="receipt-row">
                <span class="r-label">Metode pembayaran</span>
                <span class="r-val">{{ $pembayaran->metode ?? 'QRIS' }}</span>
            </div>
        </div>

        <div class="receipt-divider"></div>

        <!-- TOTALS -->
        <div class="receipt-summary">
            <div class="receipt-row total-row">
                <span class="r-label total-label">TOTAL DIBAYAR</span>
                <span class="r-val total-val">Rp{{ number_format($pembayaran->nominal, 0, ',', '.') }}</span>
            </div>

            <div class="receipt-row total-row">
                <span class="r-label total-label">SISA TAGIHAN</span>
                <span class="r-val total-val">Rp0</span>
            </div>
        </div>

        <!-- FOOTER TEXT -->
        <div class="receipt-footer-notes">
            <p>Pembayaran telah diterima dan tercatat oleh BOOKNEST.</p>
            <p>Terima kasih telah menjaga koleksi dan mendukung budaya membaca.</p>
        </div>

    </div>

</div>
@endsection
