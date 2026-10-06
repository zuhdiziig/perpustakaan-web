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
        background: #3b7068;
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
        background: #2e5953;
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

    /* Card Rincian Peminjaman (Kanan) */
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
    $firstDetail = $peminjaman->details->first();
    $firstBuku = $firstDetail?->buku;
    $firstEksemplar = $firstDetail?->eksemplar;

    $detailBukuJudul = $firstBuku?->judul ?? 'Buku';
    $detailBukuStok = (int) ($firstBuku?->stok ?? 0);
    $kodeBuku = $firstEksemplar?->kode_barcode ?? $firstBuku?->barcode?->kodeBarcode ?? sprintf('BK-%05d', $firstBuku?->idBuku ?? 0);

    $kodeTransaksi = $peminjaman->kode_transaksi ?? sprintf(
        'PJ-%s-%04d',
        \Carbon\Carbon::parse($peminjaman->tanggalPinjam ?? now())->format('Ymd'),
        $peminjaman->idPeminjaman
    );

    $tanggalPinjamFormatted = \Carbon\Carbon::parse($peminjaman->tanggalPinjam)->locale('id')->translatedFormat('d M Y');
    $batasKembaliFormatted = \Carbon\Carbon::parse($peminjaman->batasKembali)->locale('id')->translatedFormat('d M Y');
    $waktuKonfirmasiFormatted = \Carbon\Carbon::parse($peminjaman->created_at)->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';

    $durasiHari = max(1, (int) \Carbon\Carbon::parse($peminjaman->tanggalPinjam)->diffInDays(\Carbon\Carbon::parse($peminjaman->batasKembali)));
    $namaAnggota = $peminjaman->member->name ?? 'Anggota';
    $kodeAnggota = $peminjaman->member->kode_anggota ?? sprintf('AG-%s-%05d', date('Y'), $peminjaman->idUserMember);
    $namaPetugas = $peminjaman->petugas->name ?? auth()->user()->name ?? 'Petugas';
@endphp

<div class="berhasil-header">
    <div class="berhasil-breadcrumb">BOOKNEST / Petugas</div>
    <h1 class="berhasil-title">Barcode Berhasil</h1>
    <p class="berhasil-subtitle">Validasi selesai. Buku telah diserahkan kepada anggota.</p>
</div>

<div class="berhasil-grid">
    {{-- Card Kiri: Status Keberhasilan --}}
    <div class="card-status-berhasil">
        <div class="success-icon-box" aria-hidden="true">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="9"></circle>
                <polyline points="9 12 11.5 14.5 15.5 9.5"></polyline>
            </svg>
        </div>

        <h2 class="status-heading">Peminjaman berhasil dikonfirmasi</h2>

        <p class="status-desc">
            @if($peminjaman->totalBuku > 1)
                {{ $detailBukuJudul }} dan {{ $peminjaman->totalBuku - 1 }} buku lainnya telah diserahkan kepada {{ $namaAnggota }}. Stok tersedia kini {{ $detailBukuStok }} eksemplar. Anggota menerima pengingat jatuh tempo {{ $batasKembaliFormatted }}.
            @else
                {{ $detailBukuJudul }} telah diserahkan kepada {{ $namaAnggota }}. Stok tersedia kini {{ $detailBukuStok }} eksemplar. Anggota menerima pengingat jatuh tempo {{ $batasKembaliFormatted }}.
            @endif
        </p>

        <div class="status-badge-wrapper">
            <span class="status-badge">
                <span class="status-badge-dot"></span>
                Dipinjam
            </span>
        </div>

        <div class="meta-list">
            <div class="meta-item">
                <span class="meta-label">Petugas</span>
                <span class="meta-value">{{ $namaPetugas }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Waktu konfirmasi</span>
                <span class="meta-value">{{ $waktuKonfirmasiFormatted }}</span>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi (Di bawah Card Kiri pada Desktop) --}}
    <div class="action-buttons-wrap">
        <a href="{{ route('peminjaman.create') }}" class="btn-action-scan" id="btnScanBerikutnya">
            Scan Berikutnya
        </a>
        <a href="{{ route('dashboard') }}" class="btn-action-dashboard" id="btnKeDasbor">
            Ke Dasbor
        </a>
    </div>

    {{-- Card Kanan: Rincian Peminjaman --}}
    <div class="card-rincian">
        <h2 class="rincian-title">Rincian peminjaman</h2>

        <div class="rincian-list">
            <div class="rincian-item">
                <span class="rincian-label">Transaksi</span>
                <span class="rincian-value" id="dispRincianTransaksi">{{ $kodeTransaksi }}</span>
            </div>
            <div class="rincian-item">
                <span class="rincian-label">Anggota</span>
                <span class="rincian-value" id="dispRincianAnggota">{{ $namaAnggota }}</span>
            </div>
            <div class="rincian-item">
                <span class="rincian-label">Nomor anggota</span>
                <span class="rincian-value" id="dispRincianNomorAnggota">{{ $kodeAnggota }}</span>
            </div>
            <div class="rincian-item">
                <span class="rincian-label">Buku</span>
                <span class="rincian-value" id="dispRincianBuku">
                    @if($peminjaman->totalBuku > 1)
                        {{ $detailBukuJudul }} · {{ $kodeBuku }} (+{{ $peminjaman->totalBuku - 1 }} lainnya)
                    @else
                        {{ $detailBukuJudul }} · {{ $kodeBuku }}
                    @endif
                </span>
            </div>
            <div class="rincian-item">
                <span class="rincian-label">Tanggal pinjam</span>
                <span class="rincian-value" id="dispRincianTglPinjam">{{ $tanggalPinjamFormatted }}</span>
            </div>
            <div class="rincian-item">
                <span class="rincian-label">Batas pengembalian</span>
                <span class="rincian-value" id="dispRincianBatasKembali">{{ $batasKembaliFormatted }}</span>
            </div>
            <div class="rincian-item">
                <span class="rincian-label">Durasi / jumlah</span>
                <span class="rincian-value" id="dispRincianDurasi">{{ $durasiHari }} hari / {{ $peminjaman->totalBuku }} buku</span>
            </div>
        </div>
    </div>
</div>
@endsection