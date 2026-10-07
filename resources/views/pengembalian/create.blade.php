@extends('layouts.petugas')

@section('title', 'Barcode Pengembalian - BOOKNEST')

@section('styles')
<style>
    .pengembalian-header {
        margin-bottom: 24px;
    }

    .pengembalian-breadcrumb {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 6px;
        letter-spacing: -0.2px;
    }

    .pengembalian-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
        line-height: 1.2;
        margin-bottom: 6px;
    }

    .pengembalian-desc {
        font-size: 14px;
        color: #64748b;
    }

    .pengembalian-grid {
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
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .btn-scan-action:hover {
        background: #115e59;
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

    .btn-confirm-return {
        width: 100%;
        padding: 13px 20px;
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 14.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .btn-confirm-return:hover {
        background: #115e59;
    }

    .btn-confirm-return:disabled {
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
        border-color: #0f766e;
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
        align-items: center;
        gap: 16px;
        font-size: 13px;
    }

    .rincian-label {
        color: #64748b;
        font-weight: 500;
        flex-shrink: 0;
    }

    .rincian-val {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
        word-break: break-word;
    }

    /* Select Kondisi & Fine Box */
    .select-kondisi {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 13.5px;
        color: #0f172a;
        background: #ffffff;
        font-weight: 600;
        outline: none;
        transition: border-color 0.15s;
    }

    .select-kondisi:focus {
        border-color: #0f766e;
    }

    .fine-calc-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 18px;
        margin-top: 14px;
    }

    .fine-calc-box.has-fine {
        background: #fff1f2;
        border-color: #fecdd3;
    }

    .fine-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12.5px;
        color: #64748b;
        margin-bottom: 6px;
    }

    .fine-row.total-row {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed #cbd5e1;
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0;
    }

    .fine-row.total-row.danger {
        color: #be123c;
        border-top-color: #fca5a5;
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

    /* Modal Overlay & Dialog */
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
        margin-bottom: 16px;
    }

    .modal-title {
        font-size: 16.5px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 4px;
        letter-spacing: -0.2px;
    }

    .modal-subtitle {
        font-size: 12.5px;
        color: #64748b;
        margin: 0;
    }

    .modal-close-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        color: #94a3b8;
        border-radius: 6px;
        transition: color 0.15s ease;
    }

    .modal-close-btn:hover {
        color: #0f172a;
    }

    .modal-card-info {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #ccfbf1;
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

    .modal-info-fine {
        font-size: 12px;
        font-weight: 700;
        color: #166534;
        line-height: 1.3;
    }

    .modal-info-fine.has-fine {
        color: #be123c;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
    }

    .btn-modal-cancel {
        padding: 9px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        transition: background 0.15s ease;
    }

    .btn-modal-cancel:hover {
        background: #f1f5f9;
    }

    .btn-modal-confirm {
        padding: 9px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        background: #0f766e;
        color: #ffffff;
        transition: background 0.15s ease;
    }

    .btn-modal-confirm:hover {
        background: #115e59;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 992px) {
        .pengembalian-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')

    <!-- HEADER / BREADCRUMB -->
    <div class="pengembalian-header">
        <div class="pengembalian-breadcrumb">BOOKNEST / Petugas</div>
        <h1 class="pengembalian-title">Barcode Pengembalian</h1>
        <p class="pengembalian-desc">Scan tiket pengembalian, barcode buku, atau anggota untuk memproses pengembalian di meja layanan.</p>
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

    @if (session('error'))
        <div style="background: #fef2f2; border: 1px solid #fee2e2; border-radius: 12px; padding: 12px 18px; margin-bottom: 20px; color: #991b1b; font-size: 13px; font-weight: 600;">
            ✕ {{ session('error') }}
        </div>
    @endif

    @php
        $initialDetail = $selectedDetail ?? null;
        $initialPeminjaman = $initialDetail?->peminjaman;
        $initialMember = $initialPeminjaman?->member;
        $initialBuku = $initialDetail?->buku;
        $initialEksemplar = $initialDetail?->eksemplar;
        $kodeBukuDisplay = $initialEksemplar?->kode_barcode ?? ($initialBuku?->barcode?->kodeBarcode ?? sprintf('BK-%05d', $initialBuku?->idBuku ?? 0));
        $initialKodeKembali = $initialDetail?->kode_kembali ?? ($initialPeminjaman ? sprintf('PJ-%s-%04d', \Carbon\Carbon::parse($initialPeminjaman->tanggalPinjam)->format('Ymd'), $initialPeminjaman->idPeminjaman) : '-');
    @endphp

    <!-- MAIN GRID DUA KOLOM -->
    <div class="pengembalian-grid">

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
                        <span class="scanner-prompt" id="cameraPromptText">Arahkan barcode tiket atau buku ke kamera</span>
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
                    Kode tiket pengembalian / buku / transaksi
                </label>

                <input type="text"
                       id="inputManual"
                       class="input-code-manual"
                       placeholder="Contoh: KB-20261007-0001, ret_..., atau barcode buku"
                       value="{{ request('code', request('barcode', request('tiket', request('kode', '')))) }}"
                       autocomplete="off">

                <!-- Tombol Cari Transaksi -->
                <button type="button" class="btn-search-trx" id="btnCariTransaksi">
                    Cari Pengembalian
                </button>
            </div>
        </div>

        <!-- ==================== KOLOM KANAN ==================== -->
        <div>
            <!-- Status Pill: Barcode Ditemukan -->
            <div id="badgeContainer" style="display: {{ $initialDetail ? 'block' : 'none' }};">
                <span class="badge-barcode-found" id="badgeBarcodeDitemukan">
                    <span class="badge-dot"></span>
                    <span id="badgeText">Pengembalian Teridentifikasi</span>
                </span>
            </div>

            <!-- CARD 1: RINCIAN PENGEMBALIAN -->
            <div class="card-panel" style="margin-bottom: 20px;">
                <h2 class="card-title-xl">Rincian pengembalian</h2>

                <div class="rincian-list">
                    <!-- Row 1: Tiket / Transaksi -->
                    <div class="rincian-item">
                        <span class="rincian-label">Tiket / Transaksi</span>
                        <span class="rincian-val" id="dispTransaksi">{{ $initialKodeKembali }}</span>
                    </div>

                    <!-- Row 2: Anggota -->
                    <div class="rincian-item">
                        <span class="rincian-label">Anggota</span>
                        <span class="rincian-val" id="dispAnggota">
                            {{ $initialMember->name ?? '-' }}
                        </span>
                    </div>

                    <!-- Row 3: Nomor anggota -->
                    <div class="rincian-item">
                        <span class="rincian-label">Nomor anggota</span>
                        <span class="rincian-val" id="dispNomorAnggota">
                            {{ $initialMember->kode_anggota ?? '-' }}
                        </span>
                    </div>

                    <!-- Row 4: Buku -->
                    <div class="rincian-item">
                        <span class="rincian-label">Buku</span>
                        <span class="rincian-val" id="dispBuku">
                            @if($initialBuku)
                                {{ $initialBuku->judul }} · {{ $kodeBukuDisplay }} (Eks #{{ $initialEksemplar->nomor_eksemplar ?? '1' }})
                            @else
                                -
                            @endif
                        </span>
                    </div>

                    <!-- Row 5: Tanggal pinjam -->
                    <div class="rincian-item">
                        <span class="rincian-label">Tanggal pinjam</span>
                        <span class="rincian-val" id="dispTanggalPinjam">
                            {{ $initialPeminjaman ? \Carbon\Carbon::parse($initialPeminjaman->tanggalPinjam)->translatedFormat('d M Y') : '-' }}
                        </span>
                    </div>

                    <!-- Row 6: Batas pengembalian -->
                    <div class="rincian-item">
                        <span class="rincian-label">Batas pengembalian</span>
                        <span class="rincian-val" id="dispBatasPengembalian">
                            {{ $initialPeminjaman ? \Carbon\Carbon::parse($initialPeminjaman->batasKembali)->translatedFormat('d M Y') : '-' }}
                        </span>
                    </div>

                    <!-- Row 7: Status jatuh tempo -->
                    <div class="rincian-item">
                        <span class="rincian-label">Status jatuh tempo</span>
                        <span class="rincian-val" id="dispStatusTerlambat" style="color: {{ $isOverdue ? '#dc2626' : '#166534' }};">
                            @if($initialDetail)
                                @if($isOverdue)
                                    ● Terlambat {{ $hariTerlambat }} Hari ({{ $mingguTerlambat }} Minggu)
                                @else
                                    ✓ Tepat Waktu (Bebas Denda Keterlambatan)
                                @endif
                            @else
                                -
                            @endif
                        </span>
                    </div>

                    <!-- Row 8: Kondisi laporan member -->
                    <div class="rincian-item">
                        <span class="rincian-label">Laporan member</span>
                        <span class="rincian-val" id="dispKondisiLaporan">
                            {{ $initialDetail->kondisi_laporan ?? 'Baik' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- CARD 2: VERIFIKASI KONDISI & KALKULASI DENDA -->
            <div class="card-panel" style="margin-bottom: 20px;">
                <h3 class="card-title-md" style="margin-bottom: 8px;">Verifikasi Kondisi & Kalkulasi Denda</h3>
                <label for="kondisiBukuSelect" style="display: block; font-size: 12.5px; font-weight: 600; color: #475569; margin-bottom: 6px;">
                    Kondisi Fisik Buku (Pemeriksaan Petugas Meja Sirkulasi):
                </label>
                <select name="kondisiBuku" id="kondisiBukuSelect" class="select-kondisi" onchange="recalculateFine()">
                    <option value="Baik" {{ ($initialDetail->kondisi_laporan ?? 'Baik') === 'Baik' ? 'selected' : '' }}>Baik (Buku utuh, bersih & bebas denda fisik)</option>
                    <option value="Rusak" {{ ($initialDetail->kondisi_laporan ?? '') === 'Rusak' ? 'selected' : '' }}>Rusak (Denda 100% Harga Buku)</option>
                    <option value="Hilang" {{ ($initialDetail->kondisi_laporan ?? '') === 'Hilang' ? 'selected' : '' }}>Hilang (Denda 100% Harga Buku)</option>
                </select>

                <!-- Box Kalkulasi Denda Real-Time -->
                <div class="fine-calc-box {{ ($estDendaKeterlambatan > 0) ? 'has-fine' : '' }}" id="fineCalcBox">
                    <div class="fine-row">
                        <span>Denda Keterlambatan:</span>
                        <strong id="dispFineOverdue">Rp {{ number_format($estDendaKeterlambatan, 0, ',', '.') }}</strong>
                    </div>
                    <div class="fine-row">
                        <span>Denda Kondisi Fisik:</span>
                        <strong id="dispFineCondition">Rp 0</strong>
                    </div>
                    <div class="fine-row total-row {{ ($estDendaKeterlambatan > 0) ? 'danger' : '' }}" id="totalFineRow">
                        <span>Total Tagihan Denda:</span>
                        <span id="dispFineTotal">Rp {{ number_format($estDendaKeterlambatan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- CARD 3: VALIDASI PETUGAS -->
            <div class="validation-box" id="boxValidasiPetugas">
                <h4 class="validation-title">Validasi petugas</h4>
                <p class="validation-text" id="dispValidasiPetugas">
                    @if($initialDetail)
                        Buku fisik telah diperiksa dan diverifikasi oleh petugas sirkulasi. Pastikan fisik buku telah diterima sebelum melakukan konfirmasi.
                    @else
                        Silakan scan barcode tiket pengembalian dari member atau barcode fisik buku untuk memuat rincian pengembalian.
                    @endif
                </p>
            </div>

            <!-- FORM & TOMBOL: KONFIRMASI PENGEMBALIAN -->
            <form id="formPengembalian" action="{{ route('pengembalian.store') }}" method="POST">
                @csrf
                <input type="hidden" name="idPeminjaman" id="formIdPeminjaman" value="{{ $initialPeminjaman->idPeminjaman ?? '' }}">
                <input type="hidden" name="idBuku" id="formIdBuku" value="{{ $initialBuku->idBuku ?? '' }}">
                <input type="hidden" name="idEksemplar" id="formIdEksemplar" value="{{ $initialEksemplar->idEksemplar ?? '' }}">
                <input type="hidden" name="idUserMember" id="formIdUserMember" value="{{ $initialMember->id ?? '' }}">
                <input type="hidden" name="kondisiBuku" id="formKondisiBuku" value="{{ $initialDetail->kondisi_laporan ?? 'Baik' }}">

                <button type="button" 
                        class="btn-confirm-return" 
                        id="btnKonfirmasiPengembalian"
                        {{ ! $initialDetail ? 'disabled' : '' }}>
                    Konfirmasi Pengembalian
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================
         OVERLAY & MODAL KONFIRMASI PENGEMBALIAN
         ============================================================ -->
    <div id="modalOverlay" class="modal-overlay" style="display: none;">
        <div class="modal-dialog">
            <!-- Header Modal -->
            <div class="modal-header">
                <div style="padding-right: 32px;">
                    <h3 class="modal-title">Konfirmasi Pengembalian Buku</h3>
                    <p class="modal-subtitle">Periksa fisik buku dan status denda sebelum menyelesaikan pengembalian.</p>
                </div>
                <button type="button" class="modal-close-btn" id="btnModalClose" aria-label="Tutup modal">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Card Informasi Modal -->
            <div class="modal-card-info">
                <div class="modal-icon-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <div style="min-width: 0; flex: 1;">
                    <div class="modal-info-book" id="modalBuku">
                        {{ $initialBuku->judul ?? '-' }} · {{ $kodeBukuDisplay }}
                    </div>
                    <div class="modal-info-member" id="modalMember">
                        {{ $initialMember->name ?? '-' }} ({{ $initialMember->kode_anggota ?? '-' }})
                    </div>
                    <div class="modal-info-fine {{ $estDendaKeterlambatan > 0 ? 'has-fine' : '' }}" id="modalFine">
                        Total Denda: Rp {{ number_format($estDendaKeterlambatan, 0, ',', '.') }} · Kondisi: {{ $initialDetail->kondisi_laporan ?? 'Baik' }}
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Modal (Batal & Konfirmasi) -->
            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" id="btnModalBatal">
                    Batal
                </button>
                <button type="button" class="btn-modal-confirm" id="btnModalKonfirmasi">
                    Selesaikan Pengembalian
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

        // State Aplikasi Pengembalian
        const state = {
            detail: {!! json_encode($initialDetail ? [
                'idDetail' => $initialDetail->id,
                'idPeminjaman' => $initialPeminjaman?->idPeminjaman,
                'kodeKembali' => $initialKodeKembali,
                'statusBuku' => $initialDetail->statusBuku,
                'kondisiLaporan' => $initialDetail->kondisi_laporan ?? 'Baik',
                'member' => [
                    'id' => $initialMember?->id,
                    'name' => $initialMember?->name,
                    'kodeAnggota' => $initialMember?->kode_anggota,
                    'email' => $initialMember?->email,
                ],
                'buku' => [
                    'idBuku' => $initialBuku?->idBuku,
                    'idEksemplar' => $initialEksemplar?->idEksemplar,
                    'nomor_eksemplar' => $initialEksemplar?->nomor_eksemplar ?? 1,
                    'judul' => $initialBuku?->judul,
                    'kodeBuku' => $kodeBukuDisplay,
                    'harga' => (float) ($initialBuku?->harga ?? 0),
                ],
                'keterlambatan' => [
                    'isOverdue' => (bool) $isOverdue,
                    'hariTerlambat' => (int) $hariTerlambat,
                    'mingguTerlambat' => (int) $mingguTerlambat,
                    'estDenda' => (float) $estDendaKeterlambatan,
                    'batasKembali' => $batasKembali ? $batasKembali->translatedFormat('d M Y') : '-',
                ],
                'tanggalPinjam' => $initialPeminjaman ? \Carbon\Carbon::parse($initialPeminjaman->tanggalPinjam)->translatedFormat('d M Y') : '-',
            ] : null) !!},
            kondisiPetugas: '{{ $initialDetail->kondisi_laporan ?? "Baik" }}'
        };

        // DOM Elemen
        const inputManual = document.getElementById('inputManual');
        const btnCariTransaksi = document.getElementById('btnCariTransaksi');
        const btnToggleScan = document.getElementById('btnToggleScan');
        const cameraScreen = document.getElementById('cameraScreen');
        const cameraPlaceholder = document.getElementById('cameraPlaceholder');
        const qrReaderDiv = document.getElementById('qrReader');
        const cameraPromptText = document.getElementById('cameraPromptText');

        const badgeContainer = document.getElementById('badgeContainer');
        const dispTransaksi = document.getElementById('dispTransaksi');
        const dispAnggota = document.getElementById('dispAnggota');
        const dispNomorAnggota = document.getElementById('dispNomorAnggota');
        const dispBuku = document.getElementById('dispBuku');
        const dispTanggalPinjam = document.getElementById('dispTanggalPinjam');
        const dispBatasPengembalian = document.getElementById('dispBatasPengembalian');
        const dispStatusTerlambat = document.getElementById('dispStatusTerlambat');
        const dispKondisiLaporan = document.getElementById('dispKondisiLaporan');

        const kondisiBukuSelect = document.getElementById('kondisiBukuSelect');
        const fineCalcBox = document.getElementById('fineCalcBox');
        const dispFineOverdue = document.getElementById('dispFineOverdue');
        const dispFineCondition = document.getElementById('dispFineCondition');
        const dispFineTotal = document.getElementById('dispFineTotal');
        const totalFineRow = document.getElementById('totalFineRow');

        const dispValidasiPetugas = document.getElementById('dispValidasiPetugas');
        const btnKonfirmasiPengembalian = document.getElementById('btnKonfirmasiPengembalian');

        const formPengembalian = document.getElementById('formPengembalian');
        const formIdPeminjaman = document.getElementById('formIdPeminjaman');
        const formIdBuku = document.getElementById('formIdBuku');
        const formIdEksemplar = document.getElementById('formIdEksemplar');
        const formIdUserMember = document.getElementById('formIdUserMember');
        const formKondisiBuku = document.getElementById('formKondisiBuku');

        const modalOverlay = document.getElementById('modalOverlay');
        const modalBuku = document.getElementById('modalBuku');
        const modalMember = document.getElementById('modalMember');
        const modalFine = document.getElementById('modalFine');
        const btnModalClose = document.getElementById('btnModalClose');
        const btnModalBatal = document.getElementById('btnModalBatal');
        const btnModalKonfirmasi = document.getElementById('btnModalKonfirmasi');

        const toastFeedback = document.getElementById('toastFeedback');

        let html5QrCode = null;
        let isScanning = false;

        // Tampilkan Toast Notifikasi
        function showToast(message, isSuccess = true) {
            toastFeedback.textContent = message;
            toastFeedback.className = 'toast-feedback ' + (isSuccess ? 'toast-success' : 'toast-error');
            toastFeedback.style.display = 'block';

            setTimeout(() => {
                toastFeedback.style.display = 'none';
            }, 3200);
        }

        // Format Angka ke Rupiah
        function formatRupiah(number) {
            return 'Rp ' + Number(number).toLocaleString('id-ID');
        }

        // Hitung Ulang Denda Berdasarkan Kondisi Pilihan Petugas
        window.recalculateFine = function () {
            const kondisi = kondisiBukuSelect.value;
            state.kondisiPetugas = kondisi;
            formKondisiBuku.value = kondisi;

            if (!state.detail) {
                dispFineOverdue.textContent = 'Rp 0';
                dispFineCondition.textContent = 'Rp 0';
                dispFineTotal.textContent = 'Rp 0';
                fineCalcBox.classList.remove('has-fine');
                totalFineRow.classList.remove('danger');
                return;
            }

            const overdueFine = state.detail.keterlambatan ? (state.detail.keterlambatan.estDenda || 0) : 0;
            const bookPrice = state.detail.buku ? (state.detail.buku.harga || 0) : 0;

            let conditionFine = 0;
            if (kondisi === 'Rusak' || kondisi === 'Hilang') {
                conditionFine = bookPrice;
            }

            const totalFine = overdueFine + conditionFine;

            dispFineOverdue.textContent = formatRupiah(overdueFine);
            dispFineCondition.textContent = formatRupiah(conditionFine);
            dispFineTotal.textContent = formatRupiah(totalFine);

            if (totalFine > 0) {
                fineCalcBox.classList.add('has-fine');
                totalFineRow.classList.add('danger');
            } else {
                fineCalcBox.classList.remove('has-fine');
                totalFineRow.classList.remove('danger');
            }
        };

        // Render Rincian dari State
        function renderState() {
            if (!state.detail) {
                badgeContainer.style.display = 'none';
                dispTransaksi.textContent = '-';
                dispAnggota.textContent = '-';
                dispNomorAnggota.textContent = '-';
                dispBuku.textContent = '-';
                dispTanggalPinjam.textContent = '-';
                dispBatasPengembalian.textContent = '-';
                dispStatusTerlambat.textContent = '-';
                dispStatusTerlambat.style.color = '#64748b';
                dispKondisiLaporan.textContent = '-';

                dispValidasiPetugas.textContent = 'Silakan scan barcode tiket pengembalian dari member atau barcode fisik buku untuk memuat rincian.';
                btnKonfirmasiPengembalian.disabled = true;

                formIdPeminjaman.value = '';
                formIdBuku.value = '';
                formIdEksemplar.value = '';
                formIdUserMember.value = '';

                window.recalculateFine();
                return;
            }

            const d = state.detail;
            badgeContainer.style.display = 'block';
            dispTransaksi.textContent = d.kodeKembali || ('#TRX-' + String(d.idPeminjaman).padStart(5, '0'));
            dispAnggota.textContent = d.member?.name || '-';
            dispNomorAnggota.textContent = d.member?.kodeAnggota || '-';

            const kodeBuku = d.buku?.kodeBuku || 'BK-00000';
            const eksNum = d.buku?.nomor_eksemplar || 1;
            dispBuku.textContent = `${d.buku?.judul || 'Buku'} · ${kodeBuku} (Eks #${eksNum})`;

            dispTanggalPinjam.textContent = d.tanggalPinjam || '-';
            dispBatasPengembalian.textContent = d.keterlambatan?.batasKembali || '-';

            if (d.keterlambatan?.isOverdue) {
                dispStatusTerlambat.textContent = `● Terlambat ${d.keterlambatan.hariTerlambat} Hari (${d.keterlambatan.mingguTerlambat} Minggu)`;
                dispStatusTerlambat.style.color = '#dc2626';
            } else {
                dispStatusTerlambat.textContent = '✓ Tepat Waktu (Bebas Denda Keterlambatan)';
                dispStatusTerlambat.style.color = '#166534';
            }

            dispKondisiLaporan.textContent = d.kondisiLaporan || 'Baik';
            kondisiBukuSelect.value = d.kondisiLaporan || 'Baik';

            dispValidasiPetugas.textContent = `Buku fisik '${d.buku?.judul}' milik anggota ${d.member?.name} teridentifikasi. Pastikan buku diterima secara fisik sebelum konfirmasi.`;

            formIdPeminjaman.value = d.idPeminjaman || '';
            formIdBuku.value = d.buku?.idBuku || '';
            formIdEksemplar.value = d.buku?.idEksemplar || '';
            formIdUserMember.value = d.member?.id || '';

            btnKonfirmasiPengembalian.disabled = false;

            window.recalculateFine();
        }

        // Cari & Identifikasi Kode (AJAX)
        async function prosesIdentifikasi(rawCode) {
            const cleanCode = (rawCode || '').trim();
            if (!cleanCode) {
                showToast('Masukkan kode tiket atau barcode buku terlebih dahulu.', false);
                return;
            }

            btnCariTransaksi.disabled = true;
            btnCariTransaksi.textContent = 'Mencari...';

            try {
                const response = await fetch('{{ route("api.scan.identifikasi") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        code: cleanCode,
                        context: 'pengembalian'
                    })
                });

                const res = await response.json();

                if (res.success && res.type === 'pengembalian') {
                    state.detail = res.data;
                    renderState();
                    showToast(res.message || 'Data pengembalian berhasil diidentifikasi!', true);
                } else if (res.success && (res.type === 'buku' || res.type === 'transaksi' || res.type === 'member')) {
                    showToast(res.message || 'Item ditemukan namun tidak memiliki pengembalian aktif.', false);
                } else {
                    showToast(res.message || `Kode '${cleanCode}' tidak ditemukan dalam database.`, false);
                }
            } catch (err) {
                showToast('Gagal memproses identifikasi. Periksa jaringan.', false);
            } finally {
                btnCariTransaksi.disabled = false;
                btnCariTransaksi.textContent = 'Cari Pengembalian';
            }
        }

        // Event Listener Manual Input
        btnCariTransaksi.addEventListener('click', function () {
            prosesIdentifikasi(inputManual.value);
        });

        inputManual.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                prosesIdentifikasi(inputManual.value);
            }
        });

        // Kontrol Kamera & QR Scanner
        async function startScanner() {
            try {
                html5QrCode = new Html5Qrcode("qrReader");
                cameraPlaceholder.style.display = 'none';
                qrReaderDiv.style.display = 'block';

                await html5QrCode.start(
                    { facingMode: "environment" },
                    {
                        fps: 10,
                        qrbox: { width: 180, height: 180 }
                    },
                    (decodedText) => {
                        // Scan sukses
                        stopScanner();
                        inputManual.value = decodedText;
                        prosesIdentifikasi(decodedText);
                    },
                    (errorMessage) => {
                        // ignore parse frame errors
                    }
                );

                isScanning = true;
                btnToggleScan.textContent = 'Hentikan Scan';
                btnToggleScan.classList.add('active');
            } catch (err) {
                cameraPlaceholder.style.display = 'flex';
                qrReaderDiv.style.display = 'none';
                cameraPromptText.textContent = 'Kamera tidak dapat diakses';
                showToast('Gagal mengakses kamera perangkat: ' + err, false);
            }
        }

        async function stopScanner() {
            if (html5QrCode && isScanning) {
                try {
                    await html5QrCode.stop();
                    html5QrCode.clear();
                } catch (e) {}
            }
            isScanning = false;
            qrReaderDiv.style.display = 'none';
            cameraPlaceholder.style.display = 'flex';
            cameraPromptText.textContent = 'Arahkan barcode tiket atau buku ke kamera';
            btnToggleScan.textContent = 'Mulai Scan';
            btnToggleScan.classList.remove('active');
        }

        btnToggleScan.addEventListener('click', function () {
            if (isScanning) {
                stopScanner();
            } else {
                startScanner();
            }
        });

        // Modal Konfirmasi Pengembalian
        btnKonfirmasiPengembalian.addEventListener('click', function () {
            if (!state.detail) return;

            const d = state.detail;
            const kodeBuku = d.buku?.kodeBuku || 'BK-00000';
            modalBuku.textContent = `${d.buku?.judul || 'Buku'} · ${kodeBuku}`;
            modalMember.textContent = `${d.member?.name || '-'} (${d.member?.kodeAnggota || '-'})`;

            const overdueFine = d.keterlambatan ? (d.keterlambatan.estDenda || 0) : 0;
            const bookPrice = d.buku ? (d.buku.harga || 0) : 0;
            let conditionFine = 0;
            if (state.kondisiPetugas === 'Rusak' || state.kondisiPetugas === 'Hilang') {
                conditionFine = bookPrice;
            }
            const totalFine = overdueFine + conditionFine;

            modalFine.textContent = `Total Denda: ${formatRupiah(totalFine)} · Kondisi: ${state.kondisiPetugas}`;
            if (totalFine > 0) {
                modalFine.classList.add('has-fine');
            } else {
                modalFine.classList.remove('has-fine');
            }

            modalOverlay.style.display = 'flex';
        });

        function closeModal() {
            modalOverlay.style.display = 'none';
        }

        btnModalClose.addEventListener('click', closeModal);
        btnModalBatal.addEventListener('click', closeModal);

        btnModalKonfirmasi.addEventListener('click', function () {
            btnModalKonfirmasi.disabled = true;
            btnModalKonfirmasi.textContent = 'Memproses...';
            formPengembalian.submit();
        });

        modalOverlay.addEventListener('click', function (e) {
            if (e.target === modalOverlay) closeModal();
        });

        // Initial calculation
        window.recalculateFine();
    });
</script>
@endsection