@extends('layouts.petugas')

@section('title', 'Barcode Berhasil - BOOKNEST')

@section('styles')
<style>
    .berhasil-header {
        margin-bottom: 24px;
    }

    .berhasil-breadcrumb {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 6px;
        letter-spacing: -0.2px;
    }

    .berhasil-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
        line-height: 1.2;
        margin-bottom: 6px;
    }

    .berhasil-subtitle {
        font-size: 14px;
        color: #64748b;
    }

    /* Dua Kolom Grid Layout */
    .berhasil-grid {
        display: grid;
        grid-template-columns: 1.28fr 1fr;
        grid-template-areas:
            "status rincian"
            "actions rincian";
        column-gap: 24px;
        row-gap: 16px;
        align-items: start;
    }

    /* Card Status Berhasil (Kiri) */
    .card-status-berhasil {
        grid-area: status;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .success-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #dcfce7;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
    }

    .status-heading {
        font-size: 19px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.3px;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .status-desc {
        font-size: 13.5px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .status-badge-wrapper {
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 4px 12px;
        background: #e6f7ef;
        color: #166534;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #16a34a;
    }

    .meta-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .meta-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .meta-label {
        font-size: 13.5px;
        color: #64748b;
        font-weight: 500;
    }

    .meta-value {
        font-size: 13.5px;
        color: #0f172a;
        font-weight: 600;
    }

    /* Tombol Aksi */
    .action-buttons-wrap {
        grid-area: actions;
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn-action-scan {
        flex: 1.65;
        padding: 12px 18px;
        background: #0f766e;
        color: #ffffff;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: background 0.15s ease;
        display: inline-block;
    }

    .btn-action-scan:hover {
        background: #115e59;
        color: #ffffff;
    }

    .btn-action-dashboard {
        flex: 1;
        padding: 12px 18px;
        background: #4361ee;
        color: #ffffff;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: background 0.15s ease;
        display: inline-block;
    }

    .btn-action-dashboard:hover {
        background: #3250df;
        color: #ffffff;
    }

    /* Card Rincian (Kanan) */
    .card-rincian {
        grid-area: rincian;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .rincian-title {
        font-size: 19px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.3px;
        margin-bottom: 22px;
        line-height: 1.3;
    }

    .rincian-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .rincian-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .rincian-label {
        font-size: 13.5px;
        color: #64748b;
        font-weight: 500;
        flex-shrink: 0;
    }

    .rincian-value {
        font-size: 13.5px;
        color: #0f172a;
        font-weight: 600;
        text-align: right;
        word-break: break-word;
    }

    .book-mini-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-top: 6px;
        gap: 8px;
    }

    /* Responsive Mobile */
    @media (max-width: 992px) {
        .berhasil-grid {
            grid-template-columns: 1fr;
            grid-template-areas:
                "status"
                "rincian"
                "actions";
            row-gap: 20px;
        }

        .action-buttons-wrap {
            flex-direction: row;
        }
    }

    @media (max-width: 576px) {
        .card-status-berhasil,
        .card-rincian {
            padding: 20px;
        }

        .action-buttons-wrap {
            flex-direction: column;
        }

        .btn-action-scan,
        .btn-action-dashboard {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
@php
    $member = $pengembalian->peminjaman->member ?? null;
    $namaAnggota = $member->name ?? 'Anggota';
    $kodeAnggota = $member->kode_anggota ?? sprintf('AG-%s-%05d', date('Y'), $pengembalian->peminjaman->idUserMember ?? 0);
    $namaPetugas = $pengembalian->petugas->name ?? auth()->user()->name ?? 'Petugas';
    $tanggalPinjamFormatted = $pengembalian->peminjaman ? \Carbon\Carbon::parse($pengembalian->peminjaman->tanggalPinjam)->locale('id')->translatedFormat('d M Y') : '-';
    $tanggalKembaliFormatted = \Carbon\Carbon::parse($pengembalian->tanggalKembali)->locale('id')->translatedFormat('d M Y');
    $waktuKonfirmasiFormatted = \Carbon\Carbon::parse($pengembalian->created_at)->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';

    $isBatch = $batchDetails && $batchDetails->count() > 1;
    $totalBuku = $isBatch ? $batchDetails->count() : 1;

    $kodeTransaksiDisplay = $kodeBatch ?? $detailBuku?->kode_kembali ?? sprintf(
        '#RET-%05d',
        $pengembalian->idPengembalian
    );
@endphp

<div class="berhasil-header">
    <div class="berhasil-breadcrumb">BOOKNEST / Petugas</div>
    <h1 class="berhasil-title">Barcode Berhasil</h1>
    <p class="berhasil-subtitle">Validasi selesai. Buku fisik telah diterima kembali di meja sirkulasi.</p>
</div>

<div class="berhasil-grid">
    {{-- Card Kiri: Status Keberhasilan --}}
    <div class="card-status-berhasil">
        <div class="success-icon-box" aria-hidden="true">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>

        <h2 class="status-heading">Pengembalian berhasil dikonfirmasi</h2>

        <p class="status-desc">
            @if($isBatch)
                Seluruh {{ $totalBuku }} buku fisik pada tiket <strong>{{ $kodeBatch }}</strong> telah diterima dari <strong>{{ $namaAnggota }}</strong>. Fisik buku dan stok eksemplar telah berhasil disinkronkan menjadi Tersedia.
            @else
                Buku fisik <strong>{{ $detailBuku?->buku?->judul ?? 'Buku' }}</strong> telah diterima dari <strong>{{ $namaAnggota }}</strong>. Fisik buku dan stok eksemplar telah berhasil disinkronkan menjadi Tersedia.
            @endif
        </p>

        <div class="status-badge-wrapper">
            <span class="status-badge">
                <span class="status-badge-dot"></span>
                Dikembalikan
            </span>

            @if($pengembalian->denda)
                <span class="status-badge" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                    ✓ Denda: Rp {{ number_format($pengembalian->denda->jumlah, 0, ',', '.') }} ({{ $pengembalian->denda->status }})
                </span>
            @else
                <span class="status-badge" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                    ✓ Bebas Biaya Denda
                </span>
            @endif
        </div>

        <div class="meta-list">
            <div class="meta-item">
                <span class="meta-label">No. Pengembalian</span>
                <span class="meta-value">#RET-{{ str_pad($pengembalian->idPengembalian, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Petugas penerima</span>
                <span class="meta-value">{{ $namaPetugas }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Waktu konfirmasi</span>
                <span class="meta-value">{{ $waktuKonfirmasiFormatted }}</span>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="action-buttons-wrap">
        <a href="{{ route('pengembalian.create') }}" class="btn-action-scan" id="btnScanBerikutnya">
            Scan Pengembalian Lagi
        </a>
        <a href="{{ route('dashboard') }}" class="btn-action-dashboard" id="btnKeDasbor">
            Ke Dasbor
        </a>
    </div>

    {{-- Card Kanan: Rincian Pengembalian --}}
    <div class="card-rincian">
        <h2 class="rincian-title">Rincian pengembalian</h2>

        <div class="rincian-list">
            <div class="rincian-item">
                <span class="rincian-label">Tiket / Transaksi</span>
                <span class="rincian-value" id="dispRincianTransaksi">{{ $kodeTransaksiDisplay }}</span>
            </div>
            <div class="rincian-item">
                <span class="rincian-label">Anggota</span>
                <span class="rincian-value" id="dispRincianAnggota">{{ $namaAnggota }}</span>
            </div>
            <div class="rincian-item">
                <span class="rincian-label">Nomor anggota</span>
                <span class="rincian-value" id="dispRincianNomorAnggota">{{ $kodeAnggota }}</span>
            </div>

            @if($isBatch)
                <div style="padding-top: 4px; border-top: 1px dashed #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span class="rincian-label">Daftar Buku ({{ $totalBuku }} Buku):</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px; max-height: 220px; overflow-y: auto;">
                        @foreach($batchDetails as $item)
                            <div class="book-mini-card">
                                <div style="min-width: 0; flex: 1;">
                                    <div style="font-size: 13px; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $item->buku->judul ?? 'Buku' }}
                                    </div>
                                    <div style="font-size: 11.5px; color: #64748b;">
                                        {{ $item->eksemplar?->kode_barcode ?? $item->buku?->barcode?->kodeBarcode ?? ('BK-' . $item->idBuku) }} · Eks #{{ $item->eksemplar?->nomor_eksemplar ?? 1 }}
                                    </div>
                                </div>
                                <span style="font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 4px; background: #e2e8f0; color: #334155;">
                                    {{ $item->kondisi_laporan ?? 'Baik' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="rincian-item">
                    <span class="rincian-label">Buku</span>
                    <span class="rincian-value" id="dispRincianBuku">
                        {{ $detailBuku?->buku?->judul ?? 'Buku' }} · {{ $detailBuku?->eksemplar?->kode_barcode ?? $detailBuku?->buku?->barcode?->kodeBarcode ?? ('BK-'.($detailBuku?->idBuku ?? 0)) }} (Eks #{{ $detailBuku?->eksemplar?->nomor_eksemplar ?? 1 }})
                    </span>
                </div>
                <div class="rincian-item">
                    <span class="rincian-label">Kondisi fisik</span>
                    <span class="rincian-value">{{ $pengembalian->kondisiBuku ?? 'Baik' }}</span>
                </div>
            @endif

            <div class="rincian-item">
                <span class="rincian-label">Tanggal pinjam</span>
                <span class="rincian-value" id="dispRincianTglPinjam">{{ $tanggalPinjamFormatted }}</span>
            </div>
            <div class="rincian-item">
                <span class="rincian-label">Tanggal kembali</span>
                <span class="rincian-value" id="dispRincianTglKembali">{{ $tanggalKembaliFormatted }}</span>
            </div>
            <div class="rincian-item">
                <span class="rincian-label">Tagihan denda</span>
                <span class="rincian-value" style="color: {{ $pengembalian->denda ? '#dc2626' : '#166534' }};">
                    @if($pengembalian->denda)
                        Rp {{ number_format($pengembalian->denda->jumlah, 0, ',', '.') }} ({{ $pengembalian->denda->status }})
                    @else
                        Rp 0 (Bebas Denda)
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>
@endsection