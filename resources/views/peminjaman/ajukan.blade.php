@extends('layouts.anggota')

@section('title', 'Ajukan Peminjaman - ' . $buku->judul . ' - BOOKNEST')

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
        margin-bottom: 18px;
    }

    /* --- STEPPER --- */
    .stepper-nav {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        font-weight: 600;
        color: #94a3b8;
        margin-bottom: 24px;
    }

    .step-item.active {
        color: #0f766e;
        font-weight: 700;
    }

    .step-separator {
        color: #cbd5e1;
        font-weight: 400;
    }

    /* --- MAIN GRID LAYOUT --- */
    .peminjaman-grid {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 28px;
        align-items: start;
    }

    /* --- LEFT COLUMN: BOOK CARDS --- */
    .book-preview-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .book-cover-wrapper {
        width: 100%;
        height: 380px;
        background: linear-gradient(135deg, #e7e5e4 0%, #d6d3d1 100%);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .book-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .book-cover-wrapper:hover .book-cover-img {
        transform: scale(1.03);
    }

    .book-cover-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 24px;
        text-align: center;
        color: #ffffff;
        background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
    }

    .book-info-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 22px 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-top: 18px;
    }

    .book-info-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 8px;
        line-height: 1.3;
        letter-spacing: -0.3px;
    }

    .book-info-author {
        font-size: 13.5px;
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .book-info-location {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 16px;
    }

    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        background: #dcfce7;
        color: #15803d;
    }

    .badge-status-pill .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* --- RIGHT COLUMN: DETAILS & TERMS --- */
    .card-section {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 26px 28px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 20px;
    }

    .card-ketentuan {
        background: #f0fdfa;
        border: 1px solid #ccfbf1;
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

    .ketentuan-text {
        font-size: 13px;
        color: #475569;
        line-height: 1.65;
        margin-bottom: 18px;
    }

    /* --- CUSTOM CHECKBOX --- */
    .terms-checkbox-label {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        user-select: none;
        font-size: 13.5px;
        color: #334155;
        font-weight: 600;
        transition: color 0.15s;
    }

    .terms-checkbox-label:hover {
        color: var(--text-heading);
    }

    .custom-checkbox {
        position: relative;
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkbox-mark {
        position: absolute;
        top: 0;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #ffffff;
        border: 2px solid #cbd5e1;
        border-radius: 5px;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-checkbox input:checked ~ .checkbox-mark {
        background-color: #ffffff;
        border-color: #0f766e;
    }

    .checkbox-mark svg {
        display: none;
        color: #0f766e;
    }

    .custom-checkbox input:checked ~ .checkbox-mark svg {
        display: block;
    }

    /* --- ACTION BUTTONS --- */
    .actions-row {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 24px;
    }

    .btn-kembali {
        padding: 12px 28px;
        background: #2563eb;
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
    }

    .btn-kembali:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-lanjutkan {
        flex: 1;
        padding: 12px 24px;
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
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
    }

    .btn-lanjutkan:hover:not(:disabled) {
        background: #115e59;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 118, 110, 0.28);
    }

    .btn-lanjutkan:disabled {
        background: #94a3b8;
        cursor: not-allowed;
        box-shadow: none;
        opacity: 0.7;
    }

    /* --- POP-UP MODAL KONFIRMASI (GAMBAR 2) --- */
    .modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        z-index: 100;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeInBackdrop 0.2s ease;
    }

    .modal-backdrop.show {
        display: flex;
    }

    .modal-box {
        background: #ffffff;
        border-radius: 18px;
        width: 100%;
        max-width: 460px;
        box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.3);
        overflow: hidden;
        animation: scaleUpModal 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid var(--border-color);
    }

    .modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 22px 24px 12px;
        position: relative;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 4px;
        letter-spacing: -0.3px;
    }

    .modal-subtitle {
        font-size: 12.5px;
        color: var(--text-muted);
        line-height: 1.5;
        padding-right: 20px;
    }

    .btn-modal-close {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        color: #94a3b8;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
        flex-shrink: 0;
    }

    .btn-modal-close:hover {
        background: #e2e8f0;
        color: var(--text-heading);
    }

    .modal-body {
        padding: 12px 24px 20px;
    }

    .modal-summary-box {
        background: #f0fdfa;
        border: 1px solid #99f6e4;
        border-radius: 14px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 18px;
    }

    .modal-icon-square {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #ccfbf1;
        color: #0f766e;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .modal-summary-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .modal-book-title {
        font-size: 13.5px;
        font-weight: 800;
        color: var(--text-heading);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .modal-member-name {
        font-size: 12.5px;
        color: #475569;
        font-weight: 600;
    }

    .modal-dates-range {
        font-size: 12px;
        font-weight: 700;
        color: #0f766e;
    }

    .modal-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-modal-batal {
        padding: 9px 20px;
        background: #2563eb;
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-modal-batal:hover {
        background: #1d4ed8;
    }

    .btn-modal-konfirmasi {
        padding: 9px 22px;
        background: #0f766e;
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-modal-konfirmasi:hover {
        background: #115e59;
    }

    @keyframes fadeInBackdrop {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes scaleUpModal {
        from { transform: scale(0.95) translateY(6px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }

    /* --- RESPONSIVE BREAKPOINTS --- */
    @media (max-width: 960px) {
        .peminjaman-grid {
            grid-template-columns: 1fr;
        }

        .book-preview-card,
        .book-info-card {
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }
    }

    @media (max-width: 540px) {
        .actions-row {
            flex-direction: column;
        }

        .btn-kembali,
        .btn-lanjutkan {
            width: 100%;
        }

        .modal-actions {
            flex-direction: column-reverse;
        }

        .btn-modal-batal,
        .btn-modal-konfirmasi {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="peminjaman-container">

    <!-- PAGE HEADER -->
    <div class="peminjaman-header">
        <div class="peminjaman-breadcrumb">
            <a href="{{ route('home') }}">BOOKNEST</a>
            <span class="separator">/</span>
            <span class="current">Anggota</span>
        </div>
        <h1 class="peminjaman-title">Ajukan Peminjaman</h1>
        <p class="peminjaman-subtitle">Pilih buku, periksa rincian, lalu konfirmasi peminjamanmu.</p>

        <!-- STEPPER -->
        <div class="stepper-nav">
            <span class="step-item active">1. Rincian buku</span>
            <span class="step-separator">/</span>
            <span class="step-item">2. Konfirmasi</span>
            <span class="step-separator">/</span>
            <span class="step-item">3. Berhasil</span>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="peminjaman-grid">

        <!-- ============================================ -->
        <!-- KOLOM KIRI: COVER & INFO BUKU -->
        <!-- ============================================ -->
        <div>
            <!-- COVER CARD -->
            <div class="book-preview-card">
                <div class="book-cover-wrapper">
                    @if(!empty($buku->cover))
                        <img src="{{ $buku->cover }}" alt="{{ $buku->judul }}" class="book-cover-img" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=800&auto=format&fit=crop';">
                    @else
                        <div class="book-cover-fallback">
                            <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <h3 style="font-size: 18px; font-weight: 800; margin-top: 10px;">{{ $buku->judul }}</h3>
                            <p style="font-size: 13px; color: #ccfbf1;">{{ $buku->penulis }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- INFO CARD -->
            <div class="book-info-card">
                <h3 class="book-info-title">{{ $buku->judul }}</h3>
                <p class="book-info-author">{{ $buku->penulis }}</p>
                <p class="book-info-location">Perpustakaan Pusat · {{ $buku->rak ?? 'Rak F-12' }}</p>

                <div>
                    <span class="badge-status-pill">
                        <span class="dot"></span>
                        <span>Tersedia</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- KOLOM KANAN: RINCIAN & KETENTUAN PEMINJAMAN -->
        <!-- ============================================ -->
        <div>

            <!-- CARD 1: RINCIAN PEMINJAMAN -->
            <div class="card-section">
                <h2 class="section-header-title">Rincian peminjaman</h2>

                <div class="rincian-list">
                    <div class="rincian-row">
                        <span class="rincian-label">Transaksi</span>
                        <span class="rincian-value">{{ $calonKodeTransaksi }}</span>
                    </div>

                    <div class="rincian-row">
                        <span class="rincian-label">Anggota</span>
                        <span class="rincian-value">{{ $user->name }}</span>
                    </div>

                    <div class="rincian-row">
                        <span class="rincian-label">Nomor anggota</span>
                        <span class="rincian-value">{{ $user->kode_anggota }}</span>
                    </div>

                    <div class="rincian-row">
                        <span class="rincian-label">Buku</span>
                        <span class="rincian-value">{{ $buku->judul }} · {{ $buku->barcode?->kodeBarcode ?? ('BK-'.str_pad($buku->idBuku, 5, '0', STR_PAD_LEFT)) }}</span>
                    </div>

                    <div class="rincian-row">
                        <span class="rincian-label">Tanggal pinjam</span>
                        <span class="rincian-value">{{ $tanggalPinjam->translatedFormat('d M Y') }}</span>
                    </div>

                    <div class="rincian-row">
                        <span class="rincian-label">Batas pengembalian</span>
                        <span class="rincian-value">{{ $batasKembali->translatedFormat('d M Y') }}</span>
                    </div>

                    <div class="rincian-row">
                        <span class="rincian-label">Durasi / jumlah</span>
                        <span class="rincian-value">{{ $durasiHari }} hari / 1 buku</span>
                    </div>
                </div>
            </div>

            <!-- CARD 2: KETENTUAN PEMINJAMAN -->
            <div class="card-section card-ketentuan">
                <h2 class="section-header-title">Ketentuan peminjaman</h2>

                <p class="ketentuan-text">
                    Ambil buku secara mandiri di rak koleksi ({{ $buku->rak ?? 'Rak Utama' }}) dan bawa ke meja layanan dengan menunjukkan kartu anggota atau tiket booking. Kembalikan paling lambat {{ $batasKembali->translatedFormat('d M Y') }}. Keterlambatan dikenakan denda 10% per minggu dari harga buku (maksimal 100%), serta denda 100% seharga buku jika buku rusak atau hilang.
                </p>

                <label class="terms-checkbox-label">
                    <span class="custom-checkbox">
                        <input type="checkbox" id="checkAgreeTerms" onchange="toggleConfirmButton(this)">
                        <span class="checkbox-mark">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                    </span>
                    <span>Saya memahami ketentuan peminjaman.</span>
                </label>
            </div>

            <!-- ACTIONS -->
            <div class="actions-row">
                <a href="{{ route('katalog.show', $buku->idBuku) }}" class="btn-kembali">
                    Kembali
                </a>

                <button type="button" class="btn-lanjutkan" id="btnLanjutkanKonfirmasi" onclick="openConfirmModal()" disabled>
                    Lanjutkan Konfirmasi
                </button>
            </div>

        </div>

    </div>

</div>

<!-- ============================================ -->
<!-- MODAL POP-UP KONFIRMASI (GAMBAR 2) -->
<!-- ============================================ -->
<div id="confirmModal" class="modal-backdrop" onclick="closeConfirmModalOnBackdrop(event)">
    <div class="modal-box" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Konfirmasi Peminjaman</h3>
                <p class="modal-subtitle">Periksa data anggota dan buku. Peminjaman berlangsung {{ $durasiHari }} hari, tanpa biaya.</p>
            </div>

            <button type="button" class="btn-modal-close" onclick="closeConfirmModal()" aria-label="Tutup">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="modal-body">
            <!-- Box Ringkasan Mint -->
            <div class="modal-summary-box">
                <div class="modal-icon-square">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>

                <div class="modal-summary-details">
                    <span class="modal-book-title">{{ $buku->judul }} · {{ $buku->barcode?->kodeBarcode ?? ('BK-'.str_pad($buku->idBuku, 5, '0', STR_PAD_LEFT)) }}</span>
                    <span class="modal-member-name">{{ $user->name }} · {{ $user->kode_anggota }}</span>
                    <span class="modal-dates-range">{{ $tanggalPinjam->translatedFormat('d M Y') }} — {{ $batasKembali->translatedFormat('d M Y') }}</span>
                </div>
            </div>

            <!-- Form Submit -->
            <form action="{{ route('peminjaman.ajukan.proses', $buku->idBuku) }}" method="POST">
                @csrf
                <input type="hidden" name="setuju_ketentuan" value="1">

                <div class="modal-actions">
                    <button type="button" class="btn-modal-batal" onclick="closeConfirmModal()">
                        Batal
                    </button>
                    <button type="submit" class="btn-modal-konfirmasi">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleConfirmButton(checkbox) {
        const btn = document.getElementById('btnLanjutkanKonfirmasi');
        if (checkbox.checked) {
            btn.removeAttribute('disabled');
        } else {
            btn.setAttribute('disabled', 'disabled');
        }
    }

    function openConfirmModal() {
        const checkbox = document.getElementById('checkAgreeTerms');
        if (!checkbox.checked) {
            alert('Silakan setujui ketentuan peminjaman terlebih dahulu.');
            return;
        }

        const modal = document.getElementById('confirmModal');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }

    function closeConfirmModalOnBackdrop(e) {
        if (e.target.id === 'confirmModal') {
            closeConfirmModal();
        }
    }

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmModal();
        }
    });
</script>
@endsection
