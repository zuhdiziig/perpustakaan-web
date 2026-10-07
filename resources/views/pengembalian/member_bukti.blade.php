@extends('layouts.anggota')

@section('title', 'Tanda Terima Pengembalian #RET-' . str_pad($pengembalian->idPengembalian, 5, '0', STR_PAD_LEFT) . ' - BOOKNEST')

@section('styles')
<style>
    .receipt-page {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .top-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .btn-nav-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        background: #ffffff;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-nav-back:hover {
        background: #f8fafc;
        color: var(--brand-primary);
        border-color: #cbd5e1;
    }

    .btn-print {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 10px;
        background: #0f766e;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(15, 118, 110, 0.2);
    }

    .btn-print:hover {
        background: #115e59;
        transform: translateY(-1px);
    }

    /* --- RECEIPT CARD --- */
    .receipt-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        position: relative;
    }

    .receipt-banner {
        background: linear-gradient(135deg, #0f766e, #115e59);
        color: #ffffff;
        padding: 28px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .receipt-brand-text h2 {
        font-size: 20px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.3px;
    }

    .receipt-brand-text p {
        margin: 4px 0 0;
        font-size: 13px;
        color: #ccfbf1;
    }

    .receipt-no-tag {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 6px 14px;
        border-radius: 8px;
        font-family: monospace;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .receipt-body {
        padding: 32px;
    }

    .section-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #94a3b8;
        margin-bottom: 12px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px 24px;
        padding-bottom: 24px;
        border-bottom: 1px dashed var(--border-color);
        margin-bottom: 24px;
    }

    .info-item-label {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 2px;
    }

    .info-item-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .book-item-box {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .book-icon-square {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #ccfbf1;
        color: #0f766e;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .book-meta-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 3px;
    }

    .book-meta-detail {
        font-size: 12.5px;
        color: var(--text-muted);
    }

    /* --- DENDA STATUS BOX --- */
    .fine-result-box {
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 24px;
    }

    .fine-result-box.free {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fine-result-box.danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .receipt-footer-sign {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
        margin-top: 12px;
    }

    .verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #0f766e;
        font-weight: 700;
        font-size: 12.5px;
    }

    @media print {
        body {
            background: #ffffff !important;
        }
        .topbar, .sidebar, .top-actions {
            display: none !important;
        }
        .page-wrapper {
            padding: 0 !important;
            margin: 0 !important;
        }
        .receipt-card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="receipt-page">

    {{-- TOP NAVIGATION ACTIONS --}}
    <div class="top-actions">
        <a href="{{ route('pengembalian.member') }}" class="btn-nav-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali ke Pengembalian Saya</span>
        </a>

        <div style="display: flex; gap: 10px;">
            <button type="button" class="btn-print" onclick="window.print()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Cetak Tanda Terima</span>
            </button>
        </div>
    </div>

    {{-- RECEIPT CARD --}}
    <div class="receipt-card">
        <div class="receipt-banner">
            <div class="receipt-brand-text">
                <h2>BUKTI PENGEMBALIAN BUKU</h2>
                <p>Perpustakaan Digital BOOKNEST • Layanan Sirkulasi</p>
            </div>
            <div class="receipt-no-tag">
                #RET-{{ str_pad($pengembalian->idPengembalian, 5, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        <div class="receipt-body">

            {{-- 1. INFORMASI TRANSAKSI --}}
            <div class="section-label">Informasi Transaksi</div>
            <div class="info-grid">
                <div>
                    <div class="info-item-label">Nama Anggota (Member)</div>
                    <div class="info-item-value">{{ $pengembalian->peminjaman->member->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="info-item-label">Tanggal Dikembalikan</div>
                    <div class="info-item-value">
                        {{ \Carbon\Carbon::parse($pengembalian->tanggalKembali)->translatedFormat('l, d F Y') }}
                    </div>
                </div>
                <div>
                    <div class="info-item-label">No. Transaksi Peminjaman Asal</div>
                    <div class="info-item-value">#TRX-{{ str_pad($pengembalian->idPeminjaman, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div>
                    <div class="info-item-label">Petugas Penerima</div>
                    <div class="info-item-value">{{ $pengembalian->petugas->name ?? 'Sistem Layanan Mandiri' }}</div>
                </div>
            </div>

            {{-- 2. DETAIL BUKU --}}
            <div class="section-label">Buku Fisik yang Dikembalikan</div>
            @php
                $detailBuku = $pengembalian->peminjaman->details->first();
            @endphp
            <div class="book-item-box">
                <div class="book-icon-square">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <div style="flex: 1;">
                    <div class="book-meta-title">{{ $detailBuku->buku->judul ?? 'Buku Perpustakaan' }}</div>
                    <div class="book-meta-detail">
                        Penulis: {{ $detailBuku->buku->penulis ?? '-' }} • 
                        Kategori: {{ $detailBuku->buku->kategori->namaKategori ?? 'Umum' }} • 
                        Eksemplar: #{{ $detailBuku->eksemplar->nomor_eksemplar ?? '1' }}
                    </div>
                </div>
                <div>
                    <span style="font-size: 12px; font-weight: 700; color: #475569; background: #e2e8f0; padding: 5px 10px; border-radius: 8px;">
                        Kondisi: {{ $pengembalian->kondisiBuku }}
                    </span>
                </div>
            </div>

            {{-- 3. STATUS DENDA --}}
            <div class="section-label">Status Biaya & Denda</div>
            @if($pengembalian->denda)
                <div class="fine-result-box danger">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-weight: 800; font-size: 13.5px;">Terdapat Tagihan Denda</span>
                        <span style="font-size: 16px; font-weight: 900; color: #b91c1c;">
                            Rp {{ number_format($pengembalian->denda->jumlah, 0, ',', '.') }}
                        </span>
                    </div>
                    <div style="font-size: 12.5px; margin-bottom: 12px;">
                        Keterangan: {{ $pengembalian->denda->jenisDenda }}
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; border-top: 1px solid #fca5a5; padding-top: 10px;">
                        <span>Status Pembayaran: <strong>{{ $pengembalian->denda->status }}</strong></span>
                        @if($pengembalian->denda->status === 'Belum Dibayar')
                            <a href="{{ route('bayar.qr', $pengembalian->denda->idDenda) }}" style="background: #be123c; color: white; padding: 5px 12px; border-radius: 6px; font-weight: 700; text-decoration: none;">
                                Bayar Denda via QR &rarr;
                            </a>
                        @else
                            <span style="color: #15803d; font-weight: 700;">✓ Lunas</span>
                        @endif
                    </div>
                </div>
            @else
                <div class="fine-result-box free">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div>
                        <strong style="font-size: 13.5px; display: block;">Pengembalian Tepat Waktu & Kondisi Baik</strong>
                        <span style="font-size: 12px;">Buku telah berhasil diverifikasi dalam kondisi baik dan bebas dari segala biaya denda keterlambatan.</span>
                    </div>
                </div>
            @endif

            {{-- 4. FOOTER & VERIFIKASI RESMI --}}
            <div class="receipt-footer-sign">
                <div class="verified-badge">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span>Dokumen Resmi Terverifikasi Sistem BOOKNEST</span>
                </div>
                <div style="text-align: right; font-size: 11.5px; color: #94a3b8;">
                    Dicetak secara otomatis pada: {{ now()->translatedFormat('d M Y, H:i') }} WIB
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
