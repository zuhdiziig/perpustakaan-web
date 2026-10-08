@extends('layouts.petugas')

@section('title', 'Barcode Peminjaman - BOOKNEST')

@section('styles')
<style>
    .peminjaman-header {
        margin-bottom: 24px;
    }

    .peminjaman-breadcrumb {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 6px;
        letter-spacing: -0.2px;
    }

    .peminjaman-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
        line-height: 1.2;
        margin-bottom: 6px;
    }

    .peminjaman-desc {
        font-size: 14px;
        color: #64748b;
    }

    .peminjaman-grid {
        display: grid;
        grid-template-columns: 1fr 1.15fr;
        gap: 24px;
        align-items: start;
    }

    .card-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .card-title-lg {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 16px;
    }

    .card-title-md {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 12px;
    }

    .card-title-xl {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 22px;
    }

    /* Scanner Viewfinder Box */
    .scanner-screen {
        background: #0f172a;
        border-radius: 12px;
        height: 215px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    #qrReader {
        width: 100% !important;
        height: 100% !important;
        position: absolute;
        inset: 0;
        object-fit: cover;
        border: none !important;
    }

    #qrReader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
        border-radius: 12px;
    }

    .scanner-overlay {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }

    .scanner-prompt {
        font-size: 13px;
        color: #cbd5e1;
        font-weight: 500;
        text-align: center;
    }

    /* Action Buttons */
    .btn-scan-action {
        width: 100%;
        padding: 12px 18px;
        background: #3b7068;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .btn-scan-action:hover {
        background: #2f5d56;
    }

    .btn-scan-action.active {
        background: #dc2626;
    }

    .btn-scan-action.active:hover {
        background: #b91c1c;
    }

    .btn-search-trx {
        width: 100%;
        padding: 11px 18px;
        background: #3b66f5;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .btn-search-trx:hover {
        background: #2563eb;
    }

    .btn-confirm-loan {
        width: 100%;
        padding: 13px 20px;
        background: #3b7068;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 14.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .btn-confirm-loan:hover {
        background: #2f5d56;
    }

    .btn-confirm-loan:disabled {
        background: #94a3b8;
        cursor: not-allowed;
        opacity: 0.8;
    }

    /* Manual Input */
    .input-code-manual {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13.5px;
        color: #1e293b;
        outline: none;
        margin-bottom: 14px;
        box-sizing: border-box;
        transition: border-color 0.15s ease;
    }

    .input-code-manual:focus {
        border-color: #3b66f5;
    }

    /* Status Badge */
    .badge-barcode-found {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #dcfce7;
        color: #166534;
        font-size: 12px;
        font-weight: 600;
        border-radius: 20px;
        margin-bottom: 12px;
    }

    .badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #22c55e;
    }

    /* Rincian List */
    .rincian-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .rincian-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        font-size: 13px;
    }

    .rincian-label {
        color: #64748b;
        font-weight: 500;
        flex-shrink: 0;
        padding-top: 1px;
    }

    .rincian-val {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
        word-break: break-word;
    }

    /* Validasi Petugas Card */
    .validation-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 16px;
    }

    .validation-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .validation-text {
        font-size: 12.5px;
        line-height: 1.6;
        color: #475569;
        margin: 0;
    }

    /* Toast Notification */
    .toast-feedback {
        position: fixed;
        bottom: 24px;
        right: 24px;
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        z-index: 110;
        display: none;
        animation: fadeIn 0.2s ease;
    }

    .toast-success {
        background: #166534;
        color: #ffffff;
    }

    .toast-error {
        background: #b91c1c;
        color: #ffffff;
    }

    /* ============================================================
       MODAL KONFIRMASI BARCODE (OVERLAY & DIALOG)
       ============================================================ */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
    }

    .modal-dialog {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 480px;
        padding: 24px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
        position: relative;
        animation: modalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalPop {
        from {
            opacity: 0;
            transform: scale(0.96) translateY(6px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        position: relative;
    }

    .modal-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        letter-spacing: -0.2px;
    }

    .modal-subtitle {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.45;
    }

    .modal-close-btn {
        position: absolute;
        top: 0;
        right: 0;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: #f1f5f9;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .modal-close-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .modal-card-info {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 14px 16px;
        margin: 18px 0 22px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .modal-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: #dcfce7;
        color: #0f766e;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .modal-info-book {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
        line-height: 1.3;
    }

    .modal-info-member {
        font-size: 12.5px;
        color: #64748b;
        margin-bottom: 3px;
        line-height: 1.3;
    }

    .modal-info-date {
        font-size: 12px;
        font-weight: 600;
        color: #16a34a;
        line-height: 1.3;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
    }

    .btn-modal-cancel {
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        background: #3b66f5;
        color: #ffffff;
        transition: background 0.15s ease;
    }

    .btn-modal-cancel:hover {
        background: #2563eb;
    }

    .btn-modal-confirm {
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        background: #3b7068;
        color: #ffffff;
        transition: background 0.15s ease;
    }

    .btn-modal-confirm:hover {
        background: #2f5d56;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 992px) {
        .peminjaman-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .peminjaman-title { font-size: 22px; }
        .card-panel { padding: 18px 14px; border-radius: 14px; }
        .btn-scan-action { padding: 11px 14px; font-size: 13px; }
    }
</style>
@endsection

@section('content')

    <!-- HEADER / BREADCRUMB -->
    <div class="peminjaman-header">
        <div class="peminjaman-breadcrumb">BOOKNEST / Petugas</div>
        <h1 class="peminjaman-title">Barcode Peminjaman</h1>
        <p class="peminjaman-desc">Scan barcode anggota atau buku untuk memproses peminjaman di meja layanan.</p>
    </div>

    @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fee2e2; border-radius: 12px; padding: 12px 18px; margin-bottom: 20px; color: #991b1b; font-size: 13px;">
            @foreach ($errors->all() as $err)
                <div>• {{ $err }}</div>
            @endforeach
        </div>
    @endif

    @if (session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 12px 18px; margin-bottom: 20px; color: #166534; font-size: 13px; font-weight: 600;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <!-- MAIN GRID DUA KOLOM -->
    <div class="peminjaman-grid">

        <!-- ==================== KOLOM KIRI ==================== -->
        <div>
            <!-- CARD 1: SCAN BARCODE -->
            <div class="card-panel" style="margin-bottom: 20px;">
                <h2 class="card-title-lg">Scan barcode</h2>

                <!-- Area Viewfinder / Kamera -->
                <div class="scanner-screen" id="cameraScreen">
                    <!-- Div Target HTML5-QRCode -->
                    <div id="qrReader" style="display: none;"></div>

                    <!-- Placeholder Target Frame & Teks -->
                    <div class="scanner-overlay" id="cameraPlaceholder">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;">
                            <path d="M4 8V5a1 1 0 0 1 1-1h3"></path>
                            <path d="M16 4h3a1 1 0 0 1 1 1v3"></path>
                            <path d="M20 16v3a1 1 0 0 1-1 1h-3"></path>
                            <path d="M8 20H5a1 1 0 0 1-1-1v-3"></path>
                            <line x1="9" y1="9" x2="9" y2="15"></line>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="15" y1="9" x2="15" y2="15"></line>
                        </svg>
                        <span class="scanner-prompt" id="cameraPromptText">Arahkan barcode ke kamera</span>
                    </div>
                </div>

                <!-- Tombol Mulai Scan -->
                <button type="button" class="btn-scan-action" id="btnToggleScan">
                    Mulai Scan
                </button>
            </div>

            <!-- CARD 2: ATAU MASUKKAN KODE MANUAL -->
            <div class="card-panel">
                <h3 class="card-title-md">Atau masukkan kode manual</h3>

                <label for="inputManual" style="display: block; font-size: 12.5px; font-weight: 600; color: #475569; margin-bottom: 6px;">
                    Kode transaksi / buku
                </label>

                <input type="text"
                       id="inputManual"
                       class="input-code-manual"
                       placeholder="Contoh: BK-20261007-0001, AG-..., atau PJ-..."
                       value="{{ request('booking', request('code', request('kode', ''))) }}"
                       autocomplete="off">

                <!-- Tombol Cari Transaksi -->
                <button type="button" class="btn-search-trx" id="btnCariTransaksi">
                    Cari Transaksi
                </button>
            </div>
        </div>

        <!-- ==================== KOLOM KANAN ==================== -->
        <div>
            <!-- Status Pill: Barcode Ditemukan (Awalnya tersembunyi hingga discan) -->
            <div id="badgeContainer" style="{{ $selectedBooking ? 'display: block;' : 'display: none;' }}">
                <span class="badge-barcode-found" id="badgeBarcodeDitemukan">
                    <span class="badge-dot"></span>
                    <span id="badgeText">{{ $selectedBooking ? 'Tiket Booking: ' . $selectedBooking->status : 'Barcode ditemukan' }}</span>
                </span>
            </div>

            <!-- CARD 1: RINCIAN PEMINJAMAN -->
            <div class="card-panel" style="margin-bottom: 20px;">
                <h2 class="card-title-xl">Rincian peminjaman</h2>

                <div class="rincian-list">
                    <!-- Row 1: Transaksi -->
                    <div class="rincian-item">
                        <span class="rincian-label">Transaksi</span>
                        <span class="rincian-val" id="dispTransaksi">{{ $selectedBooking->kode_booking ?? '-' }}</span>
                    </div>

                    <!-- Row 2: Anggota -->
                    <div class="rincian-item">
                        <span class="rincian-label">Anggota</span>
                        <span class="rincian-val" id="dispAnggota">
                            {{ $defaultMember->name ?? '-' }}
                        </span>
                    </div>

                    <!-- Row 3: Nomor anggota -->
                    <div class="rincian-item">
                        <span class="rincian-label">Nomor anggota</span>
                        <span class="rincian-val" id="dispNomorAnggota">
                            {{ $defaultMember->kode_anggota ?? '-' }}
                        </span>
                    </div>

                    <!-- Row 3.5: Status kuota pinjam -->
                    <div class="rincian-item">
                        <span class="rincian-label">Status kuota</span>
                        <span class="rincian-val" id="dispKuotaAnggota">-</span>
                    </div>

                    <!-- Row 4: Buku -->
                    <div class="rincian-item">
                        <span class="rincian-label">Buku</span>
                        <span class="rincian-val" id="dispBuku">
                            @if(isset($selectedBooking) && $selectedBooking && $selectedBooking->details->count() > 1)
                                <div style="display: flex; flex-direction: column; gap: 4px; text-align: right;">
                                    @foreach($selectedBooking->details as $detail)
                                        <div style="line-height: 1.45;">- {{ $detail->buku?->judul ?? 'Buku' }}</div>
                                    @endforeach
                                </div>
                            @elseif($defaultBuku)
                                {{ $defaultBuku->judul }} · {{ $defaultEksemplar->kode_barcode ?? $defaultBuku->barcode->kodeBarcode ?? ('BK-' . $defaultBuku->idBuku) }}
                            @else
                                -
                            @endif
                        </span>
                    </div>

                    <!-- Row 5: Tanggal pinjam -->
                    <div class="rincian-item">
                        <span class="rincian-label">Tanggal pinjam</span>
                        <span class="rincian-val" id="dispTanggalPinjam">-</span>
                    </div>

                    <!-- Row 6: Batas pengembalian -->
                    <div class="rincian-item">
                        <span class="rincian-label">Batas pengembalian</span>
                        <span class="rincian-val" id="dispBatasPengembalian">-</span>
                    </div>

                    <!-- Row 7: Durasi / jumlah -->
                    <div class="rincian-item">
                        <span class="rincian-label">Durasi / jumlah</span>
                        <span class="rincian-val" id="dispDurasiJumlah">-</span>
                    </div>
                </div>
            </div>

            <!-- CARD 2: VALIDASI PETUGAS -->
            <div class="validation-box" id="boxValidasiPetugas">
                <h4 class="validation-title">Validasi petugas</h4>
                <p class="validation-text" id="dispValidasiPetugas">
                    @if($selectedBooking)
                        Tiket Booking Online Teridentifikasi. Tekan tombol konfirmasi di bawah untuk serah terima buku.
                    @else
                        Arahkan barcode anggota atau buku ke kamera scanner, atau masukkan kode manual di sebelah kiri untuk memproses peminjaman.
                    @endif
                </p>
            </div>

            <!-- FORM & TOMBOL: KONFIRMASI PEMINJAMAN -->
            <form id="formPeminjaman" action="{{ route('peminjaman.store') }}" method="POST">
                @csrf
                <input type="hidden" name="idUserMember" id="formIdUserMember" value="{{ $defaultMember->id ?? '' }}">
                <input type="hidden" name="barcodes[]" id="formBarcodeBuku" value="{{ $defaultEksemplar->qr_token ?? ($defaultEksemplar->kode_barcode ?? ($defaultBuku->barcode->kodeBarcode ?? '')) }}">

                <button type="submit" class="btn-confirm-loan" id="btnKonfirmasiPeminjaman" {{ ($defaultMember && $defaultBuku) || $selectedBooking ? '' : 'disabled' }}>
                    Konfirmasi Peminjaman
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================
         OVERLAY & MODAL KONFIRMASI BARCODE
         ============================================================ -->
    <div id="modalOverlay" class="modal-overlay" style="display: none;">
        <div class="modal-dialog">
            <!-- Header Modal -->
            <div class="modal-header">
                <div style="padding-right: 32px;">
                    <h3 class="modal-title">Konfirmasi Barcode</h3>
                    <p class="modal-subtitle">Periksa data anggota dan buku. Peminjaman berlangsung 30 hari, tanpa biaya.</p>
                </div>
                <button type="button" class="modal-close-btn" id="btnModalClose" aria-label="Tutup modal">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Card Informasi Barcode -->
            <div class="modal-card-info">
                <div class="modal-icon-box">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <div style="min-width: 0; flex: 1;">
                    <div class="modal-info-book" id="modalBuku">Laut Bercerita · BK-00417</div>
                    <div class="modal-info-member" id="modalMember">Rizky Pratama · AG-2026-00128</div>
                    <div class="modal-info-date" id="modalTanggal">03 Okt 2026 → 17 Okt 2026</div>
                </div>
            </div>

            <!-- Tombol Aksi Modal (Batal & Konfirmasi) -->
            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" id="btnModalBatal">
                    Batal
                </button>
                <button type="button" class="btn-modal-confirm" id="btnModalKonfirmasi">
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFIKASI -->
    <div id="toastFeedback" class="toast-feedback toast-success"></div>

@endsection

@section('scripts')
<!-- Library Scanner HTML5-QRCode -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // State Transaksi & Sirkulasi
        const state = {
            booking: {!! isset($selectedBooking) && $selectedBooking ? json_encode([
                'idPeminjaman' => $selectedBooking->idPeminjaman,
                'kodeBooking' => $selectedBooking->kode_booking,
                'opsiPengambilan' => $selectedBooking->opsi_pengambilan,
                'status' => $selectedBooking->status,
                'totalBuku' => $selectedBooking->totalBuku ?: $selectedBooking->details->count(),
                'daftarBuku' => $selectedBooking->details->map(function ($d) {
                    $b = $d->buku;
                    $e = $d->eksemplar;
                    return [
                        'idBuku' => $b?->idBuku,
                        'idEksemplar' => $e?->idEksemplar,
                        'judul' => $b?->judul ?? 'Buku',
                        'rak' => $b?->rak ?? '-',
                        'kodeBuku' => $e?->kode_barcode ?? $b?->barcode?->kodeBarcode ?? sprintf('BK-%05d', $b?->idBuku ?? 0),
                        'nomor_eksemplar' => $e?->nomor_eksemplar,
                    ];
                })->values()->all(),
                'member' => [
                    'id' => $selectedBooking->member?->id,
                    'name' => $selectedBooking->member?->name ?? 'Anggota',
                    'kodeAnggota' => $selectedBooking->member?->kode_anggota,
                    'email' => $selectedBooking->member?->email,
                    'status' => $selectedBooking->member?->status ?? 'aktif',
                    'sedangDipinjam' => $selectedBooking->member?->jumlahBukuSedangDipinjam() ?? 0,
                    'sisaKuota' => $selectedBooking->member?->sisaKuotaPinjam() ?? 7,
                    'kuotaPenuh' => $selectedBooking->member?->sudahMencapaiBatasMaksimalPinjam() ?? false,
                ],
                'buku' => [
                    'idBuku' => $selectedBooking->details->first()?->buku?->idBuku,
                    'idEksemplar' => $selectedBooking->details->first()?->eksemplar?->idEksemplar,
                    'judul' => $selectedBooking->details->first()?->buku?->judul ?? 'Buku',
                    'rak' => $selectedBooking->details->first()?->buku?->rak ?? '-',
                    'kodeBuku' => $selectedBooking->details->first()?->eksemplar?->kode_barcode ?? ($selectedBooking->details->first()?->buku?->barcode?->kodeBarcode ?? sprintf('BK-%05d', $selectedBooking->details->first()?->buku?->idBuku ?? 0)),
                    'kondisi' => $selectedBooking->details->first()?->eksemplar?->kondisi ?? 'Baik',
                    'statusEksemplar' => $selectedBooking->details->first()?->eksemplar?->status ?? 'Dibooking',
                    'nomor_eksemplar' => $selectedBooking->details->first()?->eksemplar?->nomor_eksemplar,
                ],
                'batasAmbil' => $selectedBooking->batasAmbil ? \Carbon\Carbon::parse($selectedBooking->batasAmbil)->translatedFormat('d M Y, H:i') : '-',
            ]) : 'null' !!},
            member: {!! json_encode($defaultMember ? [
                'id' => $defaultMember->id,
                'name' => $defaultMember->name,
                'kodeAnggota' => $defaultMember->kode_anggota,
                'status' => $defaultMember->status,
                'sedangDipinjam' => $defaultMember->jumlahBukuSedangDipinjam(),
                'sisaKuota' => $defaultMember->sisaKuotaPinjam(),
                'kuotaPenuh' => $defaultMember->sudahMencapaiBatasMaksimalPinjam(),
            ] : null) !!},
            buku: {!! json_encode($defaultBuku && $defaultEksemplar ? [
                'idBuku' => $defaultBuku->idBuku,
                'idEksemplar' => $defaultEksemplar->idEksemplar,
                'judul' => $defaultBuku->judul,
                'kodeBuku' => $defaultEksemplar->kode_barcode ?? $defaultBuku->barcode?->kodeBarcode ?? ('BK-' . $defaultBuku->idBuku),
                'qr_token' => $defaultEksemplar->qr_token,
                'kondisi' => $defaultEksemplar->kondisi ?? 'Baik',
                'status' => $defaultEksemplar->status ?? 'Tersedia',
            ] : null) !!},
            transaksiCode: {!! isset($selectedBooking) && $selectedBooking ? json_encode($selectedBooking->kode_booking) : 'null' !!},
            tanggalPinjam: '{{ now()->translatedFormat("d M Y") }}',
            batasKembali: '{{ now()->addDays(30)->translatedFormat("d M Y") }}',
            durasiJumlah: '30 hari / 1 buku',
            isCameraRunning: false
        };

        // DOM Elements
        const btnToggleScan = document.getElementById('btnToggleScan');
        const cameraPlaceholder = document.getElementById('cameraPlaceholder');
        const cameraPromptText = document.getElementById('cameraPromptText');
        const qrReaderDiv = document.getElementById('qrReader');
        const inputManual = document.getElementById('inputManual');
        const btnCariTransaksi = document.getElementById('btnCariTransaksi');

        const badgeContainer = document.getElementById('badgeContainer');
        const badgeText = document.getElementById('badgeText');
        const dispTransaksi = document.getElementById('dispTransaksi');
        const dispAnggota = document.getElementById('dispAnggota');
        const dispNomorAnggota = document.getElementById('dispNomorAnggota');
        const dispKuotaAnggota = document.getElementById('dispKuotaAnggota');
        const dispBuku = document.getElementById('dispBuku');
        const dispTanggalPinjam = document.getElementById('dispTanggalPinjam');
        const dispBatasPengembalian = document.getElementById('dispBatasPengembalian');
        const dispDurasiJumlah = document.getElementById('dispDurasiJumlah');
        const dispValidasiPetugas = document.getElementById('dispValidasiPetugas');

        const formIdUserMember = document.getElementById('formIdUserMember');
        const formBarcodeBuku = document.getElementById('formBarcodeBuku');
        const btnKonfirmasi = document.getElementById('btnKonfirmasiPeminjaman');
        const formPeminjaman = document.getElementById('formPeminjaman');
        const toast = document.getElementById('toastFeedback');

        // Modal Elements
        const modalOverlay = document.getElementById('modalOverlay');
        const modalBuku = document.getElementById('modalBuku');
        const modalMember = document.getElementById('modalMember');
        const modalTanggal = document.getElementById('modalTanggal');
        const btnModalClose = document.getElementById('btnModalClose');
        const btnModalBatal = document.getElementById('btnModalBatal');
        const btnModalKonfirmasi = document.getElementById('btnModalKonfirmasi');

        let html5Scanner = null;
        let isSubmitting = false;

        // Feedback Audio Beep
        function playScanBeep() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.frequency.value = 920;
                gain.gain.value = 0.15;
                osc.start();
                setTimeout(() => { osc.stop(); audioCtx.close(); }, 140);
            } catch (e) {}
        }

        // Tampilkan Toast
        function showToast(message, isError = false) {
            toast.textContent = message;
            toast.className = 'toast-feedback ' + (isError ? 'toast-error' : 'toast-success');
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3800);
        }

        // Format Tanggal Indonesia
        function formatTanggalIndo(date) {
            const bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const d = String(date.getDate()).padStart(2, '0');
            const m = bulan[date.getMonth()];
            const y = date.getFullYear();
            return `${d} ${m} ${y}`;
        }

        // Buka Modal Konfirmasi Barcode
        function openConfirmModal() {
            if (!state.member || !state.buku) {
                showToast('Lengkapi identifikasi anggota dan buku terlebih dahulu!', true);
                return;
            }

            if (state.member && (state.member.kuotaPenuh || (state.member.sedangDipinjam >= 7))) {
                showToast(`⛔ Batas maksimal 7 buku tercapai untuk ${state.member.name}. Member wajib mengembalikan buku terlebih dahulu.`, true);
                return;
            }

            const daftarBukuModal = (state.booking && Array.isArray(state.booking.daftarBuku) && state.booking.daftarBuku.length > 0)
                ? state.booking.daftarBuku
                : (Array.isArray(state.daftarBuku) && state.daftarBuku.length > 0 ? state.daftarBuku : null);

            if (daftarBukuModal && daftarBukuModal.length > 1) {
                modalBuku.innerHTML = `
                    <div style="display: flex; flex-direction: column; gap: 3px;">
                        <span style="font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 2px;">${daftarBukuModal.length} Buku:</span>
                        ${daftarBukuModal.map(b => `<div style="line-height: 1.4;">- ${b.judul}</div>`).join('')}
                    </div>
                `;
            } else {
                modalBuku.textContent = `${state.buku.judul} · ${state.buku.kodeBuku}`;
            }
            modalMember.textContent = `${state.member.name} · ${state.member.kodeAnggota}`;
            modalTanggal.textContent = `${state.tanggalPinjam} → ${state.batasKembali}`;

            modalOverlay.style.display = 'flex';
        }

        // Tutup Modal Konfirmasi Barcode
        function closeConfirmModal() {
            modalOverlay.style.display = 'none';
        }

        // Render Seluruh Rincian & Form Hidden Sesuai State
        function updateUI() {
            if (state.transaksiCode) {
                dispTransaksi.textContent = state.transaksiCode;
            } else {
                dispTransaksi.textContent = '-';
            }

            if (state.member) {
                dispAnggota.textContent = state.member.name;
                dispNomorAnggota.textContent = state.member.kodeAnggota;
                formIdUserMember.value = state.member.id;

                if (dispKuotaAnggota) {
                    const sedangDipinjam = state.member.sedangDipinjam ?? 0;
                    const sisaKuota = state.member.sisaKuota ?? Math.max(0, 7 - sedangDipinjam);
                    const kuotaPenuh = state.member.kuotaPenuh || (sedangDipinjam >= 7);
                    if (kuotaPenuh) {
                        dispKuotaAnggota.innerHTML = `<span style="color: #b91c1c; font-weight: 700; background: #fee2e2; padding: 2px 8px; border-radius: 9999px;">7/7 Buku (Penuh)</span>`;
                    } else {
                        dispKuotaAnggota.innerHTML = `<span style="color: #166534; font-weight: 600;">${sedangDipinjam}/7 Buku (Sisa: ${sisaKuota})</span>`;
                    }
                }
            } else {
                dispAnggota.textContent = '-';
                dispNomorAnggota.textContent = '-';
                if (dispKuotaAnggota) {
                    dispKuotaAnggota.textContent = '-';
                }
                formIdUserMember.value = '';
            }

            const daftarBuku = (state.booking && Array.isArray(state.booking.daftarBuku) && state.booking.daftarBuku.length > 0)
                ? state.booking.daftarBuku
                : (Array.isArray(state.daftarBuku) && state.daftarBuku.length > 0 ? state.daftarBuku : null);

            if (daftarBuku && daftarBuku.length > 1) {
                dispBuku.innerHTML = `
                    <div style="display: flex; flex-direction: column; gap: 4px; text-align: right;">
                        ${daftarBuku.map(b => `<div style="line-height: 1.45;">- ${b.judul}</div>`).join('')}
                    </div>
                `;
                formBarcodeBuku.value = state.buku ? (state.buku.qr_token || state.buku.kodeBuku) : '';
            } else if (daftarBuku && daftarBuku.length === 1) {
                const single = daftarBuku[0];
                dispBuku.textContent = `${single.judul} · ${single.kodeBuku}`;
                formBarcodeBuku.value = single.qr_token || single.kodeBuku;
            } else if (state.buku) {
                dispBuku.textContent = `${state.buku.judul} · ${state.buku.kodeBuku}`;
                formBarcodeBuku.value = state.buku.qr_token || state.buku.kodeBuku;
            } else {
                dispBuku.textContent = '-';
                formBarcodeBuku.value = '';
            }

            const hasActiveData = Boolean(state.member || state.buku || state.booking || state.transaksiCode);
            dispTanggalPinjam.textContent = hasActiveData ? state.tanggalPinjam : '-';
            dispBatasPengembalian.textContent = hasActiveData ? state.batasKembali : '-';
            dispDurasiJumlah.textContent = hasActiveData ? state.durasiJumlah : '-';

            const isMemberPenuh = state.member && (state.member.kuotaPenuh || (state.member.sedangDipinjam >= 7));

            // Perbarui Pesan Validasi Petugas
            if (state.booking) {
                if (isMemberPenuh) {
                    dispValidasiPetugas.innerHTML = `
                        <strong style="color: #b91c1c;">⛔ Serah Terima Ditahan: Kuota Pinjaman Penuh!</strong><br>
                        Anggota <b>${state.booking.member.name}</b> saat ini telah meminjam <b>${state.booking.member.sedangDipinjam ?? 7} buku</b> (Batas maksimal 7 buku).<br>
                        <span style="display:inline-block; margin-top: 5px; font-size: 12px; color: #b91c1c; font-weight: 600;">
                            Sesuai aturan perpustakaan, member wajib mengembalikan minimal 1 buku yang sedang dipinjam terlebih dahulu sebelum mengambil buku baru.
                        </span>
                    `;
                    badgeContainer.style.display = 'block';
                    badgeContainer.style.borderColor = '#fca5a5';
                    badgeContainer.style.background = '#fef2f2';
                    badgeText.style.color = '#b91c1c';
                    badgeText.textContent = 'Batas Maksimal 7 Buku Tercapai';
                    btnKonfirmasi.disabled = true;
                    btnKonfirmasi.textContent = '⛔ Kuota Penuh (Maks 7 Buku)';
                    btnKonfirmasi.style.background = '#9ca3af';
                } else {
                    const totalBukuBooking = state.booking.totalBuku ?? 1;
                    dispValidasiPetugas.innerHTML = `
                        <strong>Tiket Booking Online Teridentifikasi!</strong><br>
                        Kode: <b>${state.booking.kodeBooking}</b> &middot; Anggota: <b>${state.booking.member.name}</b> (${state.booking.member.kodeAnggota})<br>
                        Buku (${totalBukuBooking} item): <b>${state.booking.buku.judul}</b> (📍 Rak: <b>${state.booking.buku.rak}</b>)<br>
                        Metode Ambil: <b>${state.booking.opsiPengambilan === 'siapkan_petugas' ? '📦 Disiapkan Petugas di Meja' : '🚶 Ambil Mandiri dari Rak'}</b> &middot; Status: <b><span style="color: ${state.booking.status === 'Siap Diambil' ? '#166534' : '#b45309'}">${state.booking.status}</span></b><br>
                        <span style="display:inline-block; margin-top: 5px; font-size: 12px; color: #0f766e; font-weight: 600;">
                            Tekan tombol konfirmasi di bawah untuk menyelesaikan serah terima buku secara instan (30 hari aktif).
                        </span>
                    `;
                    badgeContainer.style.display = 'block';
                    badgeContainer.style.borderColor = '';
                    badgeContainer.style.background = '';
                    badgeText.style.color = '';
                    badgeText.textContent = `Tiket Booking: ${state.booking.status}`;
                    btnKonfirmasi.disabled = false;
                    btnKonfirmasi.textContent = `🤝 Konfirmasi Serah Terima (${totalBukuBooking} Buku)`;
                    btnKonfirmasi.style.background = '#0f766e';
                    formPeminjaman.action = `/peminjaman/booking/${state.booking.idPeminjaman}/serah-terima`;
                }
            } else if (state.member && state.buku) {
                formPeminjaman.action = '{{ route("peminjaman.store") }}';
                if (isMemberPenuh) {
                    dispValidasiPetugas.innerHTML = `
                        <strong style="color: #b91c1c;">⛔ Transaksi Ditolak: Batas Maksimal 7 Buku Tercapai!</strong><br>
                        Anggota <b>${state.member.name}</b> telah meminjam <b>${state.member.sedangDipinjam ?? 7} buku</b>.<br>
                        <span style="display:inline-block; margin-top: 5px; font-size: 12px; color: #b91c1c; font-weight: 600;">
                            Satu member hanya diperbolehkan meminjam maksimal 7 buku dengan status dipinjam. Member harus mengembalikan buku terlebih dahulu.
                        </span>
                    `;
                    badgeContainer.style.display = 'block';
                    badgeContainer.style.borderColor = '#fca5a5';
                    badgeContainer.style.background = '#fef2f2';
                    badgeText.style.color = '#b91c1c';
                    badgeText.textContent = 'Batas Maksimal 7 Buku Tercapai';
                    btnKonfirmasi.disabled = true;
                    btnKonfirmasi.textContent = '⛔ Kuota Penuh (Maks 7 Buku)';
                    btnKonfirmasi.style.background = '#9ca3af';
                } else {
                    btnKonfirmasi.textContent = 'Konfirmasi Peminjaman';
                    btnKonfirmasi.style.background = '';
                    dispValidasiPetugas.textContent = `Anggota aktif (Sisa kuota: ${state.member.sisaKuota ?? 7} buku). Kode buku ${state.buku.kodeBuku} sesuai. Buku dalam kondisi baik dan siap diserahkan. Pastikan identitas sebelum melanjutkan.`;
                    badgeContainer.style.display = 'block';
                    badgeContainer.style.borderColor = '';
                    badgeContainer.style.background = '';
                    badgeText.style.color = '';
                    badgeText.textContent = 'Barcode ditemukan';
                    btnKonfirmasi.disabled = false;
                }
            } else if (state.member && !state.buku) {
                formPeminjaman.action = '{{ route("peminjaman.store") }}';
                btnKonfirmasi.textContent = 'Konfirmasi Peminjaman';
                btnKonfirmasi.style.background = '';
                badgeContainer.style.borderColor = '';
                badgeContainer.style.background = '';
                badgeText.style.color = '';
                if (isMemberPenuh) {
                    dispValidasiPetugas.innerHTML = `
                        <strong style="color: #b91c1c;">⛔ Kuota Peminjaman Penuh!</strong><br>
                        Anggota <b>${state.member.name}</b> telah meminjam <b>${state.member.sedangDipinjam ?? 7} buku</b> (Batas maksimal 7 buku). Member harus mengembalikan buku terlebih dahulu.
                    `;
                    badgeContainer.style.display = 'block';
                    badgeContainer.style.borderColor = '#fca5a5';
                    badgeContainer.style.background = '#fef2f2';
                    badgeText.style.color = '#b91c1c';
                    badgeText.textContent = 'Batas Maksimal 7 Buku Tercapai';
                    btnKonfirmasi.disabled = true;
                } else {
                    dispValidasiPetugas.textContent = `Anggota aktif (${state.member.name}). Sisa kuota peminjaman ${state.member.sisaKuota ?? 7} buku. Silakan scan barcode buku fisik untuk melanjutkan.`;
                    badgeContainer.style.display = 'block';
                    badgeText.textContent = 'Anggota teridentifikasi';
                    btnKonfirmasi.disabled = true;
                }
            } else if (!state.member && state.buku) {
                formPeminjaman.action = '{{ route("peminjaman.store") }}';
                btnKonfirmasi.textContent = 'Konfirmasi Peminjaman';
                btnKonfirmasi.style.background = '';
                badgeContainer.style.borderColor = '';
                badgeContainer.style.background = '';
                badgeText.style.color = '';
                dispValidasiPetugas.textContent = `Buku fisik '${state.buku.judul}' (${state.buku.kodeBuku}) tersedia. Silakan scan barcode kartu anggota untuk mengonfirmasi peminjam.`;
                badgeContainer.style.display = 'block';
                badgeText.textContent = 'Buku teridentifikasi';
                btnKonfirmasi.disabled = true;
            } else {
                formPeminjaman.action = '{{ route("peminjaman.store") }}';
                btnKonfirmasi.textContent = 'Konfirmasi Peminjaman';
                btnKonfirmasi.style.background = '';
                badgeContainer.style.borderColor = '';
                badgeContainer.style.background = '';
                badgeText.style.color = '';
                dispValidasiPetugas.textContent = 'Arahkan barcode anggota atau buku ke kamera, atau masukkan kode manual untuk memulai proses peminjaman.';
                badgeContainer.style.display = 'none';
                btnKonfirmasi.disabled = true;
            }
        }

        // Proses Identifikasi Kode melalui API
        function prosesIdentifikasi(rawCode) {
            const code = rawCode.trim();
            if (!code) return;

            btnCariTransaksi.disabled = true;
            btnCariTransaksi.textContent = 'Memeriksa...';

            fetch('{{ route("api.scan.identifikasi") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ code: code, context: 'peminjaman' })
            })
            .then(res => res.json())
            .then(res => {
                btnCariTransaksi.disabled = false;
                btnCariTransaksi.textContent = 'Cari Transaksi';

                if (!res.success) {
                    showToast(res.message || 'Kode tidak ditemukan.', true);
                    return;
                }

                playScanBeep();

                // 0. Jika teridentifikasi sebagai Tiket Booking Online
                if (res.type === 'booking') {
                    const booking = res.data;
                    state.booking = booking;
                    state.daftarBuku = booking.daftarBuku || [];
                    state.transaksiCode = booking.kodeBooking;
                    state.member = booking.member;
                    state.buku = booking.buku;

                    const today = new Date();
                    const due = new Date();
                    due.setDate(today.getDate() + 30);
                    state.tanggalPinjam = formatTanggalIndo(today);
                    state.batasKembali = formatTanggalIndo(due);
                    const totalBooking = booking.totalBuku ?? (booking.daftarBuku ? booking.daftarBuku.length : 1);
                    state.durasiJumlah = `30 hari / ${totalBooking} buku`;
                    inputManual.value = booking.kodeBooking;

                    updateUI();

                    if (state.member && (state.member.kuotaPenuh || (state.member.sedangDipinjam >= 7))) {
                        showToast(`⛔ Serah terima booking ditahan: Anggota ${state.member.name} telah meminjam ${state.member.sedangDipinjam ?? 7} buku (Batas maksimal 7 buku). Wajib pengembalian terlebih dahulu.`, true);
                        return;
                    }

                    if (booking.daftarBuku && booking.daftarBuku.length > 1) {
                        modalBuku.innerHTML = `
                            <div style="display: flex; flex-direction: column; gap: 3px;">
                                <span style="font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 2px;">${booking.daftarBuku.length} Buku:</span>
                                ${booking.daftarBuku.map(b => `<div>- ${b.judul}</div>`).join('')}
                            </div>
                        `;
                    } else {
                        modalBuku.textContent = `${booking.buku.judul} · ${booking.buku.kodeBuku} (📍 ${booking.buku.rak})`;
                    }
                    modalMember.textContent = `${booking.member.name} · ${booking.member.kodeAnggota}`;
                    modalTanggal.textContent = `Serah Terima Booking ${totalBooking} Buku (${booking.opsiPengambilan === 'siapkan_petugas' ? 'Disiapkan Petugas' : 'Ambil Mandiri'})`;
                    btnModalKonfirmasi.textContent = `Konfirmasi Serah Terima (${totalBooking} Buku)`;

                    showToast(`Tiket Booking '${booking.kodeBooking}' (${totalBooking} buku) berhasil diidentifikasi. Silakan periksa rincian di sebelah kanan.`);
                    return;
                }

                // 1. Jika teridentifikasi sebagai transaksi utuh
                if (res.type === 'transaksi') {
                    state.booking = null;
                    state.daftarBuku = null;
                    const trx = res.data;
                    state.transaksiCode = trx.kodeTransaksi;
                    state.member = trx.member;
                    state.buku = trx.buku;
                    state.tanggalPinjam = trx.tanggalPinjam;
                    state.batasKembali = trx.batasKembali;
                    state.durasiJumlah = trx.durasiJumlah;
                    inputManual.value = trx.kodeTransaksi;

                    updateUI();
                    dispValidasiPetugas.textContent = trx.validasiPesan;
                    showToast(`Barcode transaksi ${trx.kodeTransaksi} ditemukan. Rincian telah dimuat di sebelah kanan.`);
                    return;
                }

                // 2. Jika teridentifikasi sebagai data Member
                if (res.type === 'member') {
                    state.booking = null;
                    state.daftarBuku = null;
                    state.member = res.data;
                    inputManual.value = res.data.kodeAnggota;
                    cameraPromptText.textContent = 'Arahkan barcode buku ke kamera';

                    // Update tanggal dinamis
                    const today = new Date();
                    const due = new Date();
                    due.setDate(today.getDate() + 14);
                    state.tanggalPinjam = formatTanggalIndo(today);
                    state.batasKembali = formatTanggalIndo(due);
                    state.transaksiCode = `PJ-${today.getFullYear()}${String(today.getMonth()+1).padStart(2,'0')}${String(today.getDate()).padStart(2,'0')}-${String(state.buku ? state.buku.idEksemplar : 417).padStart(4,'0')}`;

                    updateUI();

                    if (state.member.kuotaPenuh || (state.member.sedangDipinjam >= 7)) {
                        showToast(`⚠️ Kuota anggota '${state.member.name}' penuh (7/7 buku). Wajib pengembalian terlebih dahulu.`, true);
                    } else if (state.buku) {
                        showToast(`Barcode anggota '${state.member.name}' ditemukan. Rincian lengkap siap dikonfirmasi.`);
                    } else {
                        showToast(`Anggota '${state.member.name}' teridentifikasi. Sisa kuota: ${state.member.sisaKuota} buku.`);
                    }
                    return;
                }

                // 3. Jika teridentifikasi sebagai Eksemplar Buku
                if (res.type === 'buku') {
                    state.booking = null;
                    state.daftarBuku = null;
                    state.buku = res.data;
                    inputManual.value = res.data.kodeBuku;

                    const today = new Date();
                    const due = new Date();
                    due.setDate(today.getDate() + 14);
                    state.tanggalPinjam = formatTanggalIndo(today);
                    state.batasKembali = formatTanggalIndo(due);
                    state.transaksiCode = `PJ-${today.getFullYear()}${String(today.getMonth()+1).padStart(2,'0')}${String(today.getDate()).padStart(2,'0')}-${String(state.buku.idEksemplar).padStart(4,'0')}`;

                    updateUI();

                    if (state.member) {
                        showToast(`Barcode buku '${state.buku.judul}' ditemukan. Rincian lengkap siap dikonfirmasi.`);
                    } else {
                        showToast(`Buku '${state.buku.judul}' teridentifikasi. Silakan scan anggota.`);
                    }
                }
            })
            .catch(err => {
                btnCariTransaksi.disabled = false;
                btnCariTransaksi.textContent = 'Cari Transaksi';
                showToast('Gagal terhubung ke server scanner.', true);
            });
        }

        // Toggle Scanner Kamera (html5-qrcode)
        function toggleScanner() {
            if (state.isCameraRunning) {
                stopScanner();
            } else {
                startScanner();
            }
        }

        function startScanner() {
            if (!html5Scanner) {
                html5Scanner = new Html5Qrcode('qrReader');
            }

            qrReaderDiv.style.display = 'block';
            cameraPlaceholder.style.display = 'none';
            btnToggleScan.textContent = 'Hentikan Scan';
            btnToggleScan.classList.add('active');

            const scanConfig = { fps: 10, qrbox: { width: 220, height: 180 } };

            html5Scanner.start(
                { facingMode: 'environment' },
                scanConfig,
                (decodedText) => {
                    prosesIdentifikasi(decodedText);
                },
                (error) => {
                    // scanning loop frame error, ignore
                }
            ).then(() => {
                state.isCameraRunning = true;
            }).catch(err => {
                console.warn('Camera error:', err);
                qrReaderDiv.style.display = 'none';
                cameraPlaceholder.style.display = 'flex';
                cameraPromptText.textContent = 'Kamera tidak tersedia / izin ditolak';
                btnToggleScan.textContent = 'Mulai Scan';
                btnToggleScan.classList.remove('active');
                state.isCameraRunning = false;
                showToast('Kamera tidak dapat diakses. Gunakan input manual.', true);
            });
        }

        function stopScanner() {
            if (html5Scanner && state.isCameraRunning) {
                html5Scanner.stop().then(() => {
                    qrReaderDiv.style.display = 'none';
                    cameraPlaceholder.style.display = 'flex';
                    btnToggleScan.textContent = 'Mulai Scan';
                    btnToggleScan.classList.remove('active');
                    state.isCameraRunning = false;
                }).catch(err => {
                    console.error('Stop scanner error:', err);
                });
            }
        }

        // Event Listeners Scanner & Input
        btnToggleScan.addEventListener('click', toggleScanner);

        btnCariTransaksi.addEventListener('click', function () {
            prosesIdentifikasi(inputManual.value);
        });

        inputManual.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                prosesIdentifikasi(inputManual.value);
            }
        });

        // Tombol Konfirmasi Peminjaman pada Halaman Utama membuka Modal Konfirmasi Barcode
        btnKonfirmasi.addEventListener('click', function (e) {
            e.preventDefault();
            if (!formIdUserMember.value || !formBarcodeBuku.value) {
                showToast('Lengkapi identifikasi anggota dan buku sebelum konfirmasi!', true);
                return;
            }
            openConfirmModal();
        });

        // Tombol Close (X) dan Batal pada Modal
        btnModalClose.addEventListener('click', closeConfirmModal);
        btnModalBatal.addEventListener('click', closeConfirmModal);

        // Klik background overlay menutup modal
        modalOverlay.addEventListener('click', function (e) {
            if (e.target === modalOverlay) {
                closeConfirmModal();
            }
        });

        // Tombol Konfirmasi pada Modal menjalankan peminjaman backend yang sebenarnya
        btnModalKonfirmasi.addEventListener('click', function () {
            if (!formIdUserMember.value || !formBarcodeBuku.value) {
                showToast('Data peminjaman belum lengkap!', true);
                closeConfirmModal();
                return;
            }

            if (isSubmitting) return;
            isSubmitting = true;

            btnModalKonfirmasi.disabled = true;
            btnModalKonfirmasi.textContent = 'Memproses...';
            btnModalBatal.disabled = true;
            btnModalClose.disabled = true;

            formPeminjaman.submit();
        });

        // Inisialisasi tampilan awal
        updateUI();
    });
</script>
@endsection