@extends('layouts.anggota')

@section('title', 'Peminjaman Berhasil - BOOKNEST')

@section('styles')
<style>
    /* --- BREADCRUMB & HEADER --- */
    .peminjaman-header {
        margin-bottom: 24px;
    }

    .peminjaman-breadcrumb {
        font-size: 12.5px;
        font-weight: 600;
        color: #0f766e;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
    }

    .peminjaman-breadcrumb a {
        color: #0f766e;
        transition: color 0.15s;
    }

    .peminjaman-breadcrumb a:hover {
        color: #115e59;
    }

    .peminjaman-breadcrumb .separator {
        color: #94a3b8;
    }

    .peminjaman-breadcrumb .current {
        color: #0f766e;
        font-weight: 700;
    }

    .peminjaman-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.6px;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .peminjaman-subtitle {
        font-size: 13.5px;
        color: var(--text-muted);
        margin-bottom: 24px;
    }

    /* --- TWO-COLUMN GRID --- */
    .sukses-grid {
        display: grid;
        grid-template-columns: 1fr 390px;
        gap: 28px;
        align-items: start;
    }

    /* --- CARD 1: STATUS HERO CARD --- */
    .status-banner-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 30px 28px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
    }

    .status-icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #dcfce7;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .status-banner-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 8px;
        letter-spacing: -0.3px;
    }

    .status-banner-desc {
        font-size: 13.5px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .badge-status-recorded {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 700;
        background: #dcfce7;
        color: #15803d;
    }

    .badge-status-recorded .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* --- CARD 2: RINCIAN PEMINJAMAN --- */
    .card-section {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 26px 28px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .section-header-title {
        font-size: 19px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 18px;
        letter-spacing: -0.3px;
    }

    .rincian-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .rincian-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 16px;
        font-size: 13.5px;
        padding-bottom: 12px;
        border-bottom: 1px dashed #f1f5f9;
    }

    .rincian-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .rincian-label {
        color: var(--text-muted);
        font-weight: 500;
    }

    .rincian-value {
        color: var(--text-heading);
        font-weight: 700;
        text-align: right;
    }

    /* --- RIGHT COLUMN: BARCODE CARD --- */
    .card-barcode {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 28px 26px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .barcode-card-title {
        font-size: 19px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 22px;
        letter-spacing: -0.3px;
    }

    .barcode-graphic-box {
        background: #ffffff;
        padding: 6px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .barcode-svg-container {
        width: 100%;
        height: 78px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .barcode-code-text {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 0.5px;
        text-align: left;
        margin-bottom: 18px;
    }

    .barcode-info-list {
        font-size: 13px;
        color: #475569;
        line-height: 1.65;
        margin-bottom: 24px;
    }

    .barcode-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-simpan-barcode {
        width: 100%;
        padding: 12px 20px;
        background: #0f766e;
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
    }

    .btn-simpan-barcode:hover {
        background: #115e59;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 118, 110, 0.28);
    }

    .btn-ke-dasbor {
        width: 100%;
        padding: 12px 20px;
        background: #2563eb;
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
    }

    .btn-ke-dasbor:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    /* --- RESPONSIVE BREAKPOINT --- */
    @media (max-width: 960px) {
        .sukses-grid {
            grid-template-columns: 1fr;
        }

        .card-barcode {
            max-width: 480px;
            margin: 0 auto;
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
@php
    $member = $peminjaman->member;
    $bukuJudul = $buku->judul ?? 'Buku';
    $kodeBarcodeBuku = $detail?->eksemplar?->kode_barcode ?? $buku?->barcode?->kodeBarcode ?? ('BK-' . str_pad($buku?->idBuku ?? 1, 5, '0', STR_PAD_LEFT));
    $tanggalPinjamFormatted = \Carbon\Carbon::parse($peminjaman->tanggalPinjam)->translatedFormat('d M Y');
    $batasKembaliFormatted = \Carbon\Carbon::parse($peminjaman->batasKembali)->translatedFormat('d M Y');
@endphp

<div class="peminjaman-container">

    <!-- PAGE HEADER -->
    <div class="peminjaman-header">
        <div class="peminjaman-breadcrumb">
            <a href="{{ route('home') }}">BOOKNEST</a>
            <span class="separator">/</span>
            <span class="current">Anggota</span>
        </div>
        <h1 class="peminjaman-title">Peminjaman Berhasil</h1>
        <p class="peminjaman-subtitle">Permintaanmu telah tercatat. Simpan barcode untuk pengambilan buku.</p>
    </div>

    <!-- MAIN TWO-COLUMN GRID -->
    <div class="sukses-grid">

        <!-- ============================================ -->
        <!-- KOLOM KIRI: STATUS & RINCIAN -->
        <!-- ============================================ -->
        <div>
            <!-- CARD 1: STATUS HERO -->
            <div class="status-banner-card">
                <div class="status-icon-circle">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>

                <h2 class="status-banner-title">Bacaan baru siap menemanimu</h2>
                <p class="status-banner-desc">
                    {{ $bukuJudul }} berhasil dipinjam. Tunjukkan barcode dan kartu anggota kepada petugas di Perpustakaan Pusat.
                </p>

                <div>
                    <span class="badge-status-recorded">
                        <span class="dot"></span>
                        <span>Peminjaman tercatat</span>
                    </span>
                </div>
            </div>

            <!-- CARD 2: RINCIAN PEMINJAMAN -->
            <div class="card-section">
                <h2 class="section-header-title">Rincian peminjaman</h2>

                <div class="rincian-list">
                    <div class="rincian-row">
                        <span class="rincian-label">Transaksi</span>
                        <span class="rincian-value">{{ $peminjaman->kode_transaksi }}</span>
                    </div>

                    <div class="rincian-row">
                        <span class="rincian-label">Anggota</span>
                        <span class="rincian-value">{{ $member->name }}</span>
                    </div>

                    <div class="rincian-row">
                        <span class="rincian-label">Nomor anggota</span>
                        <span class="rincian-value">{{ $member->kode_anggota }}</span>
                    </div>

                    <div class="rincian-row">
                        <span class="rincian-label">Buku</span>
                        <span class="rincian-value">{{ $bukuJudul }} · {{ $kodeBarcodeBuku }}</span>
                    </div>

                    <div class="rincian-row">
                        <span class="rincian-label">Tanggal pinjam</span>
                        <span class="rincian-value">{{ $tanggalPinjamFormatted }}</span>
                    </div>

                    <div class="rincian-row">
                        <span class="rincian-label">Batas pengembalian</span>
                        <span class="rincian-value">{{ $batasKembaliFormatted }}</span>
                    </div>

                    <div class="rincian-row">
                        <span class="rincian-label">Durasi / jumlah</span>
                        <span class="rincian-value">14 hari / 1 buku</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- KOLOM KANAN: BARCODE PEMINJAMAN -->
        <!-- ============================================ -->
        <div>
            <div class="card-barcode">
                <h2 class="barcode-card-title">Barcode Peminjaman</h2>

                <!-- 1D Linear Barcode Display -->
                <div class="barcode-graphic-box" id="barcodeContainer">
                    <div class="barcode-svg-container">
                        {!! $barcodeSvg !!}
                    </div>
                </div>

                <div class="barcode-code-text" id="barcodeCodeText">
                    {{ $peminjaman->kode_transaksi }}
                </div>

                <!-- Pickup Notes -->
                <div class="barcode-info-list">
                    <p>Pengambilan: meja layanan, lantai 1.</p>
                    <p>Jatuh tempo: {{ $batasKembaliFormatted }}.</p>
                    <p>Bawa kartu anggota asli.</p>
                </div>

                <!-- Actions -->
                <div class="barcode-actions">
                    <button type="button" class="btn-simpan-barcode" id="btnSimpanBarcode" onclick="simpanBarcode()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Simpan Barcode</span>
                    </button>

                    <a href="{{ route('dashboard') }}" class="btn-ke-dasbor">
                        Ke Dasbor
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Canvas tersembunyi untuk konversi Barcode ke Gambar PNG -->
<canvas id="barcodeCanvas" style="display: none;"></canvas>
@endsection

@section('scripts')
<script>
    function simpanBarcode() {
        const svgElement = document.querySelector('#barcodeContainer svg');
        if (!svgElement) {
            alert('Elemen barcode tidak ditemukan.');
            return;
        }

        const btn = document.getElementById('btnSimpanBarcode');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span>Menyiapkan gambar...</span>';
        btn.disabled = true;

        try {
            // Ambil data SVG
            const serializer = new XMLSerializer();
            const svgString = serializer.serializeToString(svgElement);
            const svgBlob = new Blob([svgString], { type: 'image/svg+xml;charset=utf-8' });
            const URL = window.URL || window.webkitURL || window;
            const blobURL = URL.createObjectURL(svgBlob);

            const image = new Image();
            image.onload = function () {
                const canvas = document.getElementById('barcodeCanvas');
                const ctx = canvas.getContext('2d');

                // Dimensi kartu tiket barcode
                const cardWidth = 600;
                const cardHeight = 360;
                canvas.width = cardWidth;
                canvas.height = cardHeight;

                // Latar belakang putih dengan rounded corner
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, cardWidth, cardHeight);

                // Header Brand
                ctx.fillStyle = '#0f766e';
                ctx.font = 'bold 20px "Plus Jakarta Sans", sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('BOOKNEST PERPUSTAKAAN', cardWidth / 2, 45);

                ctx.fillStyle = '#64748b';
                ctx.font = '13px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('BUKTI & BARCODE SIRKULASI PEMINJAMAN', cardWidth / 2, 70);

                // Garis pemisah
                ctx.strokeStyle = '#e2e8f0';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(40, 85);
                ctx.lineTo(cardWidth - 40, 85);
                ctx.stroke();

                // Gambar Barcode di tengah
                const barcodeWidth = cardWidth - 80;
                const barcodeHeight = 110;
                ctx.drawImage(image, 40, 105, barcodeWidth, barcodeHeight);

                // Kode Transaksi di bawah barcode
                const kodeTransaksi = document.getElementById('barcodeCodeText')?.innerText.trim() || 'PJ-TRANSAKSI';
                ctx.fillStyle = '#0f172a';
                ctx.font = 'bold 20px "Plus Jakarta Sans", monospace';
                ctx.fillText(kodeTransaksi, cardWidth / 2, 245);

                // Informasi buku & pengambilan
                ctx.fillStyle = '#475569';
                ctx.font = '13px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('Buku: {{ addslashes($bukuJudul) }} · Meja Layanan Lantai 1', cardWidth / 2, 280);
                ctx.fillText('Jatuh Tempo: {{ $batasKembaliFormatted }} · Bawa Kartu Anggota', cardWidth / 2, 305);

                // Download File PNG
                const pngURL = canvas.toDataURL('image/png');
                const downloadLink = document.createElement('a');
                downloadLink.download = `barcode-${kodeTransaksi}.png`;
                downloadLink.href = pngURL;
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);

                URL.revokeObjectURL(blobURL);

                btn.innerHTML = originalText;
                btn.disabled = false;
            };

            image.onerror = function() {
                // Fallback jika blob gagal di-render oleh browser
                URL.revokeObjectURL(blobURL);
                btn.innerHTML = originalText;
                btn.disabled = false;
                window.print();
            };

            image.src = blobURL;
        } catch (err) {
            console.error(err);
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Gagal mengunduh barcode secara otomatis. Anda dapat melakukan tangkapan layar (screenshot).');
        }
    }
</script>
@endsection
