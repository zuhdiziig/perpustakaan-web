@extends('layouts.anggota')

@section('title', 'Pembayaran Berhasil - BOOKNEST')

@section('styles')
<style>
    .sukses-container {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
    }

    /* --- BREADCRUMB --- */
    .sukses-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .sukses-breadcrumb .brand-crumb {
        color: #0f766e;
        text-decoration: none;
    }

    .sukses-breadcrumb .brand-crumb:hover {
        text-decoration: underline;
    }

    .sukses-breadcrumb .divider-crumb {
        color: #94a3b8;
    }

    .sukses-breadcrumb .active-crumb {
        color: #64748b;
    }

    /* --- PAGE HEADER --- */
    .sukses-header-area {
        margin-bottom: 28px;
    }

    .sukses-main-heading {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        line-height: 1.25;
        color: #0f172a;
        letter-spacing: -0.6px;
    }

    .sukses-main-subtitle {
        margin: 6px 0 0;
        font-size: 14px;
        color: #64748b;
        line-height: 1.5;
    }

    /* --- MAIN 2-COLUMN GRID --- */
    .sukses-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 28px;
        align-items: flex-start;
    }

    /* --- LEFT COLUMN: SUCCESS CARD --- */
    .sukses-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .sukses-icon-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #dcfce7;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 22px;
    }

    .sukses-card-title {
        margin: 0 0 10px;
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
    }

    .sukses-card-desc {
        font-size: 14px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 20px;
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

    .sukses-meta-list {
        margin: 24px 0 28px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        border-top: 1px solid #f1f5f9;
        padding-top: 20px;
    }

    .sukses-meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13.5px;
    }

    .sukses-meta-label {
        color: #64748b;
        font-weight: 500;
    }

    .sukses-meta-value {
        color: #0f172a;
        font-weight: 700;
    }

    .btn-action-stack {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-tiket-kembali {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 13.5px;
        background: #0f766e;
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        border-radius: 10px;
        transition: background 0.15s ease;
        box-shadow: 0 2px 4px rgba(15, 118, 110, 0.2);
    }

    .btn-tiket-kembali:hover {
        background: #115e59;
    }

    .btn-lihat-nota {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 13.5px;
        background: #f1f5f9;
        color: #334155;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        transition: background 0.15s ease;
    }

    .btn-lihat-nota:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .btn-kembali-dasbor {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 13.5px;
        background: #ffffff;
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        transition: all 0.15s ease;
    }

    .btn-kembali-dasbor:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    /* --- RIGHT COLUMN: DETAILS & AUTO NOTE --- */
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

    /* Card Pembayaran Tercatat Otomatis */
    .catat-box {
        background: #f0fdfa;
        border: 1px solid #ccfbf1;
        border-radius: 16px;
        padding: 24px;
    }

    .catat-title {
        margin: 0 0 8px;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.2px;
    }

    .catat-desc {
        margin: 0;
        font-size: 13px;
        color: #64748b;
        line-height: 1.55;
    }

    @media (max-width: 992px) {
        .sukses-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="sukses-container">

    <!-- BREADCRUMB -->
    <nav class="sukses-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}" class="brand-crumb">BOOKNEST</a>
        <span class="divider-crumb">/</span>
        <span class="active-crumb">Anggota</span>
    </nav>

    <!-- PAGE HEADER -->
    <div class="sukses-header-area">
        <h1 class="sukses-main-heading">Pembayaran Berhasil</h1>
        <p class="sukses-main-subtitle">Denda telah dilunasi. Bukti pembayaranmu siap disimpan.</p>
    </div>

    <!-- MAIN 2-COLUMN GRID -->
    <div class="sukses-grid">

        <!-- KOLOM KIRI: STATUS SUKSES -->
        <div class="sukses-card">
            <div class="sukses-icon-circle">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <h2 class="sukses-card-title">Terima kasih, {{ $namaPanggilan }}!</h2>
            <p class="sukses-card-desc">
                Pembayaran denda {{ $buku?->judul ?? 'Buku' }} sebesar Rp{{ number_format($pembayaran->nominal, 0, ',', '.') }} berhasil diterima melalui QRIS.
            </p>

            <div>
                <span class="badge-status-pill badge-lunas">
                    <span class="badge-dot">●</span> LUNAS
                </span>
            </div>

            <div class="sukses-meta-list">
                <div class="sukses-meta-row">
                    <span class="sukses-meta-label">Waktu pembayaran</span>
                    <span class="sukses-meta-value">{{ $waktuPembayaranText }}</span>
                </div>
                <div class="sukses-meta-row">
                    <span class="sukses-meta-label">Metode</span>
                    <span class="sukses-meta-value">QRIS</span>
                </div>
                <div class="sukses-meta-row">
                    <span class="sukses-meta-label">Sisa denda</span>
                    <span class="sukses-meta-value">Rp0</span>
                </div>
            </div>

            <div class="btn-action-stack">
                @php
                    $detailKembali = $denda?->details?->first() 
                        ?? \App\Models\DetailPeminjaman::where('id_denda', $denda?->idDenda)->first()
                        ?? ($pengembalian?->idPeminjaman ? \App\Models\DetailPeminjaman::where('idPeminjaman', $pengembalian->idPeminjaman)->whereIn('statusBuku', ['Diajukan Kembali', 'Dipinjam'])->first() : null);
                    $kodeBatch = $detailKembali?->kode_batch_kembali;
                @endphp

                @if($kodeBatch)
                    <a href="{{ route('pengembalian.member.batch-tiket', $kodeBatch) }}" class="btn-tiket-kembali">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span>Buka Tiket Pengembalian Fisik</span>
                    </a>
                @elseif($detailKembali)
                    <a href="{{ route('pengembalian.member.tiket', $detailKembali->id) }}" class="btn-tiket-kembali">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span>Buka Tiket Pengembalian Fisik</span>
                    </a>
                @endif

                <a href="{{ route('pembayaran.nota', $pembayaran->idPembayaran) }}" class="btn-lihat-nota">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Lihat Nota</span>
                </a>

                @if($detailKembali)
                    <a href="{{ route('pengembalian.member') }}" class="btn-kembali-dasbor" style="background: #f8fafc;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Ke Halaman Pengembalian</span>
                    </a>
                @endif

                <a href="{{ route('dashboard') }}" class="btn-kembali-dasbor">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Kembali ke Dasbor</span>
                </a>
            </div>
        </div>

        <!-- KOLOM KANAN: RINCIAN & CATAT OTOMATIS -->
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

            <!-- Card Pembayaran Tercatat Otomatis -->
            <div class="catat-box">
                <h3 class="catat-title">Pembayaran tercatat otomatis</h3>
                <p class="catat-desc">
                    Tidak perlu mengirim bukti ke petugas. Nota digital tersimpan pada riwayat pembayaran akunmu.
                </p>
            </div>
        </div>

    </div>

</div>
@endsection
