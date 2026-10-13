@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'petugas' ? 'layouts.petugas' : 'layouts.anggota'))

@section('title', 'Pembayaran QRIS - BOOKNEST')

@section('styles')
<style>
    .qris-container {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
    }

    /* --- BREADCRUMB --- */
    .qris-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .qris-breadcrumb .brand-crumb {
        color: #0f766e;
        text-decoration: none;
    }

    .qris-breadcrumb .brand-crumb:hover {
        text-decoration: underline;
    }

    .qris-breadcrumb .divider-crumb {
        color: #94a3b8;
    }

    .qris-breadcrumb .active-crumb {
        color: #64748b;
    }

    /* --- PAGE HEADER --- */
    .qris-header-area {
        margin-bottom: 28px;
    }

    .qris-main-heading {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        line-height: 1.25;
        color: #0f172a;
        letter-spacing: -0.6px;
    }

    .qris-main-subtitle {
        margin: 6px 0 0;
        font-size: 14px;
        color: #64748b;
        line-height: 1.5;
    }

    /* --- MAIN 2-COLUMN GRID --- */
    .qris-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 28px;
        align-items: flex-start;
    }

    /* --- LEFT COLUMN: QRIS CARD --- */
    .qris-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .qris-title-center {
        text-align: center;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 0.5px;
        margin-bottom: 18px;
    }

    .qris-merchant-info {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .qris-amount-large {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.8px;
        line-height: 1.1;
        margin-bottom: 22px;
    }

    .qris-qr-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 18px;
    }

    .qris-qr-box {
        padding: 14px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        display: inline-flex;
    }

    .qris-qr-box img {
        display: block;
        width: 220px;
        height: 220px;
        object-fit: contain;
    }

    .qris-status-center {
        text-align: center;
        margin-bottom: 18px;
    }

    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 700;
    }

    .badge-menunggu {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-menunggu .badge-dot {
        color: #d97706;
        font-size: 9px;
    }

    .qris-expiry-info {
        text-align: center;
        font-size: 13px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 22px;
    }

    .btn-cek-status {
        display: block;
        width: 100%;
        padding: 14px;
        background: #0f766e;
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        text-align: center;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
        box-shadow: 0 2px 4px rgba(15, 118, 110, 0.15);
    }

    .btn-cek-status:hover {
        background: #115e59;
    }

    .qris-disclaimer {
        text-align: center;
        font-size: 12px;
        color: #94a3b8;
        margin-top: 14px;
    }

    /* --- RIGHT COLUMN: DETAILS & HOW-TO --- */
    .rincian-card-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        margin-bottom: 22px;
    }

    .card-heading-title {
        margin: 0 0 18px;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
    }

    .kv-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .kv-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        line-height: 1.4;
    }

    .kv-label {
        color: #64748b;
        font-weight: 500;
    }

    .kv-val {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
    }

    .kv-val.mono {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 700;
        color: #334155;
    }

    .kv-item.total-row {
        padding-top: 10px;
        margin-top: 4px;
        border-top: 1px solid #f1f5f9;
        font-size: 13.5px;
    }

    .kv-item.total-row .kv-val {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
    }

    /* Cara Membayar Box */
    .cara-bayar-box {
        background: #f0fdfa;
        border: 1px solid #ccfbf1;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 22px;
    }

    .cara-title {
        margin: 0 0 14px;
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
    }

    .cara-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }

    .cara-list li {
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .btn-back-denda {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 13px;
        background: #2563eb;
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        border-radius: 10px;
        transition: background 0.15s ease;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.15);
    }

    .btn-back-denda:hover {
        background: #1d4ed8;
    }

    @media (max-width: 992px) {
        .qris-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .qris-main-heading { font-size: 22px; }
        .qris-card, .rincian-card-box, .cara-bayar-box { padding: 20px 16px; border-radius: 16px; }
        .qris-amount-large { font-size: 26px; }
        .qris-qr-box img { width: 180px; height: 180px; }
    }
</style>
@endsection

@section('content')
<div class="qris-container">

    <!-- BREADCRUMB -->
    <nav class="qris-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}" class="brand-crumb">BOOKNEST</a>
        <span class="divider-crumb">/</span>
        <span class="active-crumb">{{ in_array(auth()->user()->role ?? '', ['admin', 'petugas']) ? 'Kelola Denda' : 'Anggota' }}</span>
    </nav>

    <!-- PAGE HEADER -->
    <div class="qris-header-area">
        <h1 class="qris-main-heading">Pembayaran QRIS</h1>
        <p class="qris-main-subtitle">Selesaikan denda dengan aplikasi bank atau dompet digital pilihanmu.</p>
    </div>

    <!-- MAIN 2-COLUMN GRID -->
    <div class="qris-grid">

        <!-- KOLOM KIRI: QRIS SCANNER -->
        <div class="qris-card">
            <div class="qris-title-center">QRIS</div>

            <div class="qris-merchant-info">BOOKNEST · Perpustakaan Pusat</div>
            <div class="qris-amount-large">Rp{{ number_format($pembayaran->nominal, 0, ',', '.') }}</div>

            <div class="qris-qr-wrapper">
                <div class="qris-qr-box">
                    <img src="{{ $qrImageUrl }}" alt="Kode QRIS Pembayaran Denda">
                </div>
            </div>

            <div class="qris-status-center">
                <span class="badge-status-pill badge-menunggu">
                    <span class="badge-dot">●</span> Menunggu Pembayaran
                </span>
            </div>

            <div class="qris-expiry-info">
                <div>Kode berlaku hingga {{ $berlakuHingga }}</div>
                <div>Sisa waktu pembayaran: <strong id="countdownTimer">14:32</strong></div>
            </div>

            <form action="{{ route('bayar.proses_qr', $pembayaran->idPembayaran) }}" method="POST">
                @csrf
                <input type="hidden" name="simulasi_status" value="berhasil">
                <button type="submit" class="btn-cek-status">
                    Cek Status Pembayaran
                </button>
            </form>

            <div class="qris-disclaimer">
                Kode QR adalah ilustrasi untuk desain statis.
            </div>
        </div>

        <!-- KOLOM KANAN: RINCIAN & CARA MEMBAYAR -->
        <div>
            <!-- Card Rincian Pembayaran -->
            <div class="rincian-card-box">
                <h3 class="card-heading-title">Rincian pembayaran</h3>

                <div class="kv-list">
                    <div class="kv-item">
                        <span class="kv-label">ID pembayaran</span>
                        <span class="kv-val mono">{{ $idPembayaranText }}</span>
                    </div>

                    <div class="kv-item">
                        <span class="kv-label">Anggota</span>
                        <span class="kv-val">{{ $member?->name ?? 'Anggota' }}</span>
                    </div>

                    <div class="kv-item">
                        <span class="kv-label">Nomor anggota</span>
                        <span class="kv-val mono">{{ $nomorAnggota }}</span>
                    </div>

                    <div class="kv-item">
                        <span class="kv-label">Buku</span>
                        <span class="kv-val">{{ $buku?->judul ?? 'Buku' }} · {{ $kodeBuku }}</span>
                    </div>

                    <div class="kv-item">
                        <span class="kv-label">Keterlambatan</span>
                        <span class="kv-val">{{ $hariTerlambat }} hari × Rp1.000</span>
                    </div>

                    <div class="kv-item">
                        <span class="kv-label">Denda</span>
                        <span class="kv-val">Rp{{ number_format($denda->jumlah, 0, ',', '.') }}</span>
                    </div>

                    <div class="kv-item">
                        <span class="kv-label">Biaya administrasi</span>
                        <span class="kv-val">Rp0</span>
                    </div>

                    <div class="kv-item total-row">
                        <span class="kv-label" style="font-weight: 700; color: #0f172a;">Total QRIS</span>
                        <span class="kv-val">Rp{{ number_format($pembayaran->nominal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Card Cara Membayar -->
            <div class="cara-bayar-box">
                <h3 class="cara-title">Cara membayar</h3>
                <ul class="cara-list">
                    <li><span>1.</span> <span>Buka aplikasi bank atau dompet digital.</span></li>
                    <li><span>2.</span> <span>Pilih Scan QR, lalu pindai kode QRIS.</span></li>
                    <li><span>3.</span> <span>Pastikan penerima BOOKNEST dan total Rp{{ number_format($pembayaran->nominal, 0, ',', '.') }}.</span></li>
                    <li><span>4.</span> <span>Konfirmasi pembayaran di aplikasimu.</span></li>
                </ul>
            </div>

            <!-- Tombol Kembali -->
            <a href="{{ in_array(auth()->user()->role ?? '', ['admin', 'petugas']) ? route('denda.index') : route('denda.saya') }}" class="btn-back-denda">
                {{ in_array(auth()->user()->role ?? '', ['admin', 'petugas']) ? 'Kembali ke Kelola Denda' : 'Kembali ke Denda' }}
            </a>
        </div>

    </div>

</div>

<script>
    // Simple visual countdown simulation for 14:32 timer
    (function() {
        let totalSeconds = 14 * 60 + 32;
        const timerEl = document.getElementById('countdownTimer');
        if (!timerEl) return;

        const interval = setInterval(() => {
            if (totalSeconds <= 0) {
                clearInterval(interval);
                return;
            }
            totalSeconds--;
            const mins = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
            const secs = String(totalSeconds % 60).padStart(2, '0');
            timerEl.textContent = `${mins}:${secs}`;
        }, 1000);
    })();
</script>
@endsection