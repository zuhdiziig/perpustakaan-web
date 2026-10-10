@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.petugas')

@section('title', 'Sirkulasi Langsung & Scan QR Anggota - BOOKNEST')

@section('styles')
<style>
    .circ-header {
        margin-bottom: 24px;
    }

    .circ-breadcrumb {
        font-size: 11.5px;
        font-weight: 700;
        color: #0f766e;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .circ-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        letter-spacing: -0.5px;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .circ-subtitle {
        font-size: 13.5px;
        color: var(--text-muted, #64748b);
        max-width: 740px;
    }

    .circ-layout {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 24px;
        align-items: start;
    }

    .panel-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .panel-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Scanner Box */
    .scanner-viewfinder {
        background: #0f172a;
        border-radius: 12px;
        height: 220px;
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

    .scanner-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #94a3b8;
        padding: 20px;
        text-align: center;
    }

    .btn-toggle-scan {
        width: 100%;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
        background: #0f766e;
        color: #ffffff;
    }

    .btn-toggle-scan:hover {
        background: #115e59;
    }

    .btn-toggle-scan.active {
        background: #dc2626;
        color: #ffffff;
    }

    .input-manual-wrap {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .input-manual {
        flex: 1;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13px;
        outline: none;
    }

    .input-manual:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
    }

    .btn-submit-manual {
        padding: 10px 16px;
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    /* Member Card */
    .member-profile-card {
        margin-top: 20px;
        border-top: 1px solid #f1f5f9;
        padding-top: 18px;
    }

    .member-head {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
    }

    .member-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #ccfbf1;
        color: #0f766e;
        font-size: 18px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    .member-meta h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    .member-meta span {
        font-size: 12px;
        color: #64748b;
        font-family: monospace;
    }

    .member-pills {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 14px;
    }

    .stat-pill {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px 10px;
        text-align: center;
    }

    .stat-pill .val {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        display: block;
    }

    .stat-pill .lbl {
        font-size: 10.5px;
        color: #64748b;
        font-weight: 600;
    }

    /* Circulation Action Tabs */
    .circ-tabs {
        display: flex;
        gap: 8px;
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 18px;
    }

    .circ-tab-btn {
        padding: 10px 18px;
        font-size: 13.5px;
        font-weight: 700;
        color: #64748b;
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s;
    }

    .circ-tab-btn.active {
        color: #0f766e;
        border-bottom-color: #0f766e;
    }

    /* Book Search Tab */
    .search-bar-wrap {
        position: relative;
        margin-bottom: 16px;
    }

    .search-book-input {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        font-size: 13.5px;
        box-sizing: border-box;
        outline: none;
        transition: border 0.15s;
    }

    .search-book-input:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
    }

    .search-icon-svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 12px;
        max-height: 480px;
        overflow-y: auto;
        padding-right: 4px;
        margin-bottom: 20px;
    }

    .book-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .book-item-card:hover {
        border-color: #0f766e;
        box-shadow: 0 2px 8px rgba(15, 118, 110, 0.08);
    }

    .book-cover-thumb {
        width: 44px;
        height: 60px;
        border-radius: 6px;
        background: #f1f5f9;
        object-fit: cover;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }

    .book-item-meta {
        flex: 1;
        min-width: 0;
    }

    .book-item-title {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .book-item-author {
        font-size: 11.5px;
        color: #64748b;
        margin: 0 0 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .book-item-tags {
        display: flex;
        gap: 6px;
        font-size: 11px;
    }

    .tag-stok {
        background: #dcfce7;
        color: #166534;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .tag-rak {
        background: #f1f5f9;
        color: #475569;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .btn-pick-book {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #0f766e;
        background: #f0fdfa;
        color: #0f766e;
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }

    .btn-pick-book:hover {
        background: #0f766e;
        color: #ffffff;
    }

    .btn-pick-book.picked {
        background: #16a34a;
        border-color: #16a34a;
        color: #ffffff;
    }

    /* Selected Tray */
    .tray-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 18px;
    }

    .tray-title {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .tray-items-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .tray-row {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        font-size: 12.5px;
    }

    .btn-remove-tray {
        background: none;
        border: none;
        color: #dc2626;
        cursor: pointer;
        font-weight: 700;
        padding: 2px 6px;
    }

    .btn-submit-loan {
        width: 100%;
        padding: 13px;
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-submit-loan:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
    }

    .btn-submit-loan:hover:not(:disabled) {
        background: #115e59;
    }

    /* Return List */
    .return-toolbar {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .return-toolbar-left {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .btn-submit-batch-return {
        padding: 9px 16px;
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s;
    }

    .btn-submit-batch-return:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
    }

    .btn-submit-batch-return:hover:not(:disabled) {
        background: #115e59;
    }

    .select-kondisi {
        padding: 5px 8px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 12px;
        background: #ffffff;
        color: #1e293b;
        font-weight: 600;
    }

    .select-kondisi:focus {
        border-color: #0f766e;
        outline: none;
    }

    .highlight-loan-row {
        background: #ecfdf5 !important;
        transition: background 0.5s ease;
    }

    .return-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .return-table th {
        text-align: left;
        padding: 10px 12px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 700;
        color: #475569;
    }

    .return-table td {
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .btn-return-action {
        padding: 7px 12px;
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
        white-space: nowrap;
    }

    .btn-return-action:hover {
        background: #115e59;
    }

    .empty-state-notice {
        text-align: center;
        padding: 48px 20px;
        color: #64748b;
    }

    @media (max-width: 900px) {
        .circ-layout {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="circ-container">
    <!-- HEADER -->
    <div class="circ-header">
        <div class="circ-breadcrumb">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            Meja Sirkulasi Perpustakaan
        </div>
        <h1 class="circ-title">Sirkulasi Meja: Scan QR Anggota</h1>
        <p class="circ-subtitle">
            Pindai QR kartu anggota untuk melayani peminjaman dan pengembalian buku langsung di meja sirkulasi tanpa booking website terlebih dahulu.
        </p>
    </div>

    <!-- FLASH MESSAGE -->
    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
            ⚠️ {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
            <strong>Gagal Memproses Transaksi:</strong>
            <ul style="margin: 6px 0 0 18px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Notifikasi Jika Barcode Tidak Ditemukan --}}
    @if (!empty($error))
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
            <strong>Gagal:</strong> {{ $error }}
        </div>
    @endif

    {{-- Tampilan Rincian Data Booking Jika Ditemukan --}}
    @if (!empty($booking))
        <div style="border: 2px solid #0f766e; background: #f0fdf4; border-radius: 16px; padding: 20px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(15, 118, 110, 0.08);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 14px;">
                <div>
                    <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; background: #ccfbf1; color: #0f766e; padding: 4px 10px; border-radius: 6px;">
                        🎫 Tiket Booking Online
                    </span>
                    <h3 style="margin: 8px 0 0 0; color: #0f172a; font-size: 20px; font-weight: 800;">{{ $booking->kode_booking }}</h3>
                </div>
                <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12.5px; font-weight: 700; background: {{ $booking->status === 'Siap Diambil' ? '#dcfce7' : '#fef3c7' }}; color: {{ $booking->status === 'Siap Diambil' ? '#166534' : '#b45309' }};">
                    {{ $booking->status }}
                </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; padding: 14px 0; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; font-size: 13px;">
                <div>
                    <span style="color: #64748b; font-size: 11.5px; font-weight: 700; display: block; text-transform: uppercase;">Anggota Pemesan</span>
                    <strong>{{ $booking->member?->name }}</strong> <span style="color: #64748b;">({{ $booking->member?->kode_anggota }})</span>
                </div>
                <div>
                    <span style="color: #64748b; font-size: 11.5px; font-weight: 700; display: block; text-transform: uppercase;">Judul Buku</span>
                    <strong style="color: #0f172a;">{{ $buku?->judul ?? 'Buku Perpustakaan' }}</strong>
                </div>
                <div>
                    <span style="color: #64748b; font-size: 11.5px; font-weight: 700; display: block; text-transform: uppercase;">Lokasi Rak Fisik</span>
                    <strong style="color: #0f766e;">📍 {{ $buku?->rak ?? 'Rak Utama' }}</strong>
                </div>
                <div>
                    <span style="color: #64748b; font-size: 11.5px; font-weight: 700; display: block; text-transform: uppercase;">Metode Pengambilan</span>
                    <strong style="color: #0f172a;">{{ $booking->opsi_pengambilan === 'siapkan_petugas' ? '📦 Disiapkan Petugas di Meja' : '🚶 Ambil Mandiri dari Rak' }}</strong>
                </div>
            </div>

            <div style="margin-top: 16px; display: flex; flex-wrap: wrap; gap: 10px;">
                <form method="POST" action="{{ route('petugas.booking.serah-terima', $booking->idPeminjaman) }}" style="flex: 2; min-width: 200px; margin: 0;" onsubmit="return confirm('Konfirmasi serah terima buku kepada {{ $booking->member?->name }}? Transaksi akan beralih menjadi Dipinjam (30 hari).');">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 12px; background: #0f766e; color: white; border: none; border-radius: 9px; font-weight: 700; font-size: 13.5px; cursor: pointer;">
                        🤝 Serah Terima Buku (Konfirmasi Selesai)
                    </button>
                </form>

                @if($booking->status === 'Booking')
                    <form method="POST" action="{{ route('petugas.booking.siapkan', $booking->idPeminjaman) }}" style="flex: 1; min-width: 160px; margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 12px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a; border-radius: 9px; font-weight: 700; font-size: 13.5px; cursor: pointer;">
                            📦 Tandai Siap Diambil
                        </button>
                    </form>
                @endif

                <a href="{{ route('peminjaman.booking.tiket', $booking->idPeminjaman) }}" target="_blank" style="flex: 1; min-width: 140px; text-align: center; background: #ffffff; color: #475569; border: 1px solid #cbd5e1; padding: 12px; border-radius: 9px; text-decoration: none; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                    🔍 Buka Tiket QR
                </a>
            </div>
        </div>
    @endif

    {{-- Tampilan Rincian Data Buku Jika Ditemukan via Barcode Fisik --}}
    @if (!empty($buku) && empty($booking))
        <div style="border: 1px solid #cbd5e1; background: #ffffff; border-radius: 16px; padding: 20px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                <div>
                    <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px;">
                        📖 Detail Barcode Buku Fisik
                    </span>
                    <h3 style="margin: 8px 0 0 0; color: #0f172a; font-size: 19px; font-weight: 800;">{{ $buku->judul }}</h3>
                </div>
                <span style="padding: 4px 12px; border-radius: 20px; font-size: 12.5px; font-weight: 700; background: {{ $buku->stok > 0 ? '#dcfce7' : '#fee2e2' }}; color: {{ $buku->stok > 0 ? '#166534' : '#991b1b' }};">
                    {{ $buku->stok > 0 ? 'Tersedia (' . $buku->stok . ' eks)' : 'Stok Habis' }}
                </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; padding: 12px 0; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; font-size: 13px;">
                <div><span style="color: #64748b;">Kode Barcode:</span> <code>{{ $buku->barcode?->kodeBarcode ?? ('BK-'.$buku->idBuku) }}</code></div>
                <div><span style="color: #64748b;">Penulis:</span> <strong>{{ $buku->penulis }}</strong></div>
                <div><span style="color: #64748b;">Kategori:</span> <strong>{{ $buku->kategori?->namaKategori ?? 'Umum' }}</strong></div>
                <div><span style="color: #64748b;">Rak Fisik:</span> <strong style="color: #0f766e;">{{ $buku->rak ?? '-' }}</strong></div>
                <div><span style="color: #64748b;">Harga Penggantian:</span> <strong>Rp {{ number_format($buku->harga, 0, ',', '.') }}</strong></div>
            </div>

            <div style="margin-top: 14px; display: flex; gap: 10px;">
                <a href="{{ route('peminjaman.create') }}" style="flex: 1; text-align: center; background: #2563eb; color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 700;">
                    Proses Pinjam
                </a>
                <a href="{{ route('pengembalian.create', ['barcode' => $buku->barcode?->kodeBarcode ?? '']) }}" style="flex: 1; text-align: center; background: #16a34a; color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 700;">
                    Proses Kembali
                </a>
            </div>
        </div>
    @endif

    <div class="circ-layout">
        <!-- KOLOM KIRI: SCANNER & PROFIL ANGGOTA -->
        <div>
            <div class="panel-card">
                <h3 class="panel-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Pindai QR Kartu Anggota
                </h3>

                <!-- Viewfinder Scanner -->
                <div class="scanner-viewfinder">
                    <div id="qrReader" style="display: none;"></div>
                    <div class="scanner-placeholder" id="scannerPlaceholder">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                        <span style="font-size: 13px;" id="scannerStatusText">Arahkan QR anggota ke kamera</span>
                    </div>
                </div>

                <button type="button" class="btn-toggle-scan" id="btnToggleScan" onclick="toggleScanner()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg>
                    <span>Mulai Kamera Scanner</span>
                </button>

                <!-- Input Manual Alternatif -->
                <div class="input-manual-wrap">
                    <input type="text" id="inputManualMember" class="input-manual" placeholder="Ketik No. Anggota / Email / ID..." onkeydown="if(event.key==='Enter'){event.preventDefault();submitManualMember();}">
                    <button type="button" class="btn-submit-manual" onclick="submitManualMember()">Cari</button>
                </div>

                <!-- CARD PROFIL ANGGOTA (DITAMPILKAN KETIKA DISCAN) -->
                <div class="member-profile-card" id="memberProfileCard" style="display: {{ $selectedMember ? 'block' : 'none' }};">
                    <div class="member-head">
                        <div class="member-avatar" id="dispMemberAvatar">
                            {{ $selectedMember?->inisial ?? 'AG' }}
                        </div>
                        <div class="member-meta">
                            <h4 id="dispMemberName">{{ $selectedMember?->name ?? '-' }}</h4>
                            <span id="dispMemberCode">{{ $selectedMember?->kode_anggota ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="member-pills">
                        <div class="stat-pill">
                            <span class="val" id="dispSedangDipinjam">{{ $activeLoans->count() }}</span>
                            <span class="lbl">Sedang Dipinjam</span>
                        </div>
                        <div class="stat-pill">
                            <span class="val" id="dispSisaKuota">{{ max(0, 3 - $activeLoans->count()) }}</span>
                            <span class="lbl">Sisa Kuota Pinjam</span>
                        </div>
                    </div>

                    <div style="font-size: 12px; color: #64748b; line-height: 1.6; margin-bottom: 12px;">
                        <div>📧 <span id="dispMemberEmail">{{ $selectedMember?->email ?? '-' }}</span></div>
                        <div>📞 <span id="dispMemberPhone">{{ $selectedMember?->noTelepon ?? '-' }}</span></div>
                    </div>

                    <button type="button" onclick="resetMemberSelection()" style="width: 100%; padding: 8px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 12px; font-weight: 700; color: #475569; cursor: pointer;">
                        🔄 Ganti / Lepas Anggota
                    </button>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: TABS SIRKULASI (PINJAM & KEMBALI) -->
        <div>
            <div class="panel-card">
                @php
                    $isKembaliTab = request('tab') === 'kembali';
                @endphp
                <!-- TABS -->
                <div class="circ-tabs">
                    <button type="button" class="circ-tab-btn {{ $isKembaliTab ? '' : 'active' }}" id="tabBtnPinjam" onclick="switchCircTab('pinjam')">
                        📚 Peminjaman Langsung
                    </button>
                    <button type="button" class="circ-tab-btn {{ $isKembaliTab ? 'active' : '' }}" id="tabBtnKembali" onclick="switchCircTab('kembali')">
                        🔄 Pengembalian Langsung (<span id="countBorrowedBadge">{{ $activeLoans->count() }}</span>)
                    </button>
                </div>

                <!-- STATE BELUM PILIH ANGGOTA -->
                <div id="unselectedMemberNotice" style="display: {{ $selectedMember ? 'none' : 'block' }};">
                    <div class="empty-state-notice">
                        <div style="width: 56px; height: 56px; border-radius: 50%; background: #f0fdfa; color: #0f766e; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                        </div>
                        <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 6px;">Pindai QR Anggota Terlebih Dahulu</h3>
                        <p style="font-size: 13.5px; color: #64748b; margin: 0 0 16px;">
                            Gunakan kamera scanner atau input nomor kartu anggota di sisi kiri untuk memuat identitas dan melayani transaksi.
                        </p>
                    </div>
                </div>

                <!-- KONTEN TAB 1: PINJAM LANGSUNG -->
                <div id="tabContentPinjam" style="display: {{ $selectedMember && ! $isKembaliTab ? 'block' : 'none' }};">
                    <!-- Search Bar Buku -->
                    <div class="search-bar-wrap">
                        <svg class="search-icon-svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text"
                               id="bookSearchInput"
                               class="search-book-input"
                               placeholder="Cari judul buku, penulis, rak, atau scan barcode buku fisik..."
                               oninput="debounceSearchBooks(this.value)">
                    </div>

                    <!-- Selected Tray (Keranjang Peminjaman Langsung) -->
                    <div class="tray-box">
                        <div class="tray-title">
                            <span>Buku yang Dipilih untuk Dipinjam:</span>
                            <span id="trayCountText" style="color: #0f766e;">0 / 3 Buku</span>
                        </div>
                        <div class="tray-items-list" id="trayItemsList">
                            <div style="font-size: 12.5px; color: #94a3b8; text-align: center; padding: 12px;">
                                Belum ada buku yang dipilih. Klik '+ Pilih' pada daftar buku di bawah atau scan barcode fisik buku.
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Konfirmasi Peminjaman -->
                    <form id="formWalkinLoan" action="{{ route('peminjaman.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="source" value="walkin_scanner">
                        <input type="hidden" name="idUserMember" id="formMemberIdInput" value="{{ $selectedMember?->id ?? '' }}">
                        <div id="hiddenBarcodeInputContainer"></div>
                        <input type="hidden" name="durasiHari" value="30">

                        <button type="submit" class="btn-submit-loan" id="btnConfirmWalkinLoan" disabled>
                            🤝 Konfirmasi Peminjaman Langsung (30 Hari)
                        </button>
                    </form>

                    <!-- Daftar Hasil Pencarian Buku -->
                    <h4 style="font-size: 13.5px; font-weight: 800; color: #0f172a; margin: 24px 0 12px;">
                        Koleksi Buku Siap Dipinjam (Tersedia):
                    </h4>
                    <div class="books-grid" id="booksResultsGrid">
                        @foreach($availableBooks as $bk)
                            @php
                                $firstEks = $bk->eksemplar->where('status', 'Tersedia')->first() ?? $bk->eksemplar->first();
                                $kode = $firstEks?->qr_token ?? $firstEks?->kode_barcode ?? $bk->barcode?->kodeBarcode ?? ('BK-'.$bk->idBuku);
                                $isBorrowed = $selectedMember && in_array($bk->idBuku, $selectedMemberBorrowedBookIds ?? []);
                            @endphp
                            <div class="book-item-card" id="bookCard-{{ $bk->idBuku }}">
                                @if($bk->sampul)
                                    <img src="{{ asset('storage/' . $bk->sampul) }}" alt="Sampul" class="book-cover-thumb">
                                @else
                                    <div class="book-cover-thumb">📖</div>
                                @endif
                                <div class="book-item-meta">
                                    <div class="book-item-title">{{ $bk->judul }}</div>
                                    <div class="book-item-author">{{ $bk->penulis ?? 'Anonim' }}</div>
                                    <div class="book-item-tags">
                                        <span class="tag-stok">Stok: {{ $bk->stok }}</span>
                                        <span class="tag-rak">Rak: {{ $bk->rak ?? '-' }}</span>
                                        @if($isBorrowed)
                                            <span style="background: #fef2f2; color: #dc2626; font-size: 10.5px; font-weight: 700; padding: 2px 6px; border-radius: 4px;">Sedang Dipinjam</span>
                                        @endif
                                    </div>
                                </div>
                                @if($isBorrowed)
                                    <button type="button" class="btn-pick-book" disabled style="opacity: 0.55; cursor: not-allowed; background: #fee2e2; color: #dc2626; border-color: #fca5a5;" title="Buku ini sedang dipinjam oleh anggota">
                                        Sedang Dipinjam
                                    </button>
                                @else
                                    <button type="button" class="btn-pick-book" onclick="togglePickBook({{ json_encode(['idBuku' => $bk->idBuku, 'judul' => $bk->judul, 'kodeBarcode' => $kode]) }})">
                                        + Pilih
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- KONTEN TAB 2: PENGEMBALIAN LANGSUNG -->
                <div id="tabContentKembali" style="display: {{ $selectedMember && $isKembaliTab ? 'block' : 'none' }};">
                    <div style="margin-bottom: 14px; font-size: 13px; color: #64748b;">
                        Daftar buku yang sedang dipinjam oleh anggota ini. Petugas dapat memverifikasi fisik buku dan menyelesaikan pengembalian langsung di meja sirkulasi tanpa beralih ke halaman lain.
                    </div>

                    <form id="formWalkinReturn" action="{{ route('sirkulasi.pengembalian-langsung') }}" method="POST">
                        @csrf
                        <input type="hidden" name="idUserMember" id="formReturnMemberId" value="{{ $selectedMember?->id ?? '' }}">

                        <div id="borrowedBooksContainer">
                            @if($activeLoans->count() > 0)
                                <!-- Toolbar Pengembalian Sekaligus -->
                                <div class="return-toolbar">
                                    <div class="return-toolbar-left">
                                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                                            <input type="checkbox" id="checkSelectAllReturns" onchange="toggleSelectAllReturns(this)" style="width: 16px; height: 16px; accent-color: #0f766e; cursor: pointer;">
                                            <span>Pilih Semua Buku</span>
                                        </label>
                                        <span id="labelSelectedReturnsCount" style="color: #64748b; font-size: 12px; font-weight: 500;">
                                            (0 dari {{ $activeLoans->count() }} buku terpilih)
                                        </span>
                                    </div>
                                    <button type="button" id="btnSubmitBatchReturn" class="btn-submit-batch-return" onclick="submitBatchReturn()" disabled>
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        <span>Selesaikan Pengembalian (<span id="countSelectedReturnText">0</span>)</span>
                                    </button>
                                </div>

                                <table class="return-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 38px; text-align: center;">#</th>
                                            <th>Buku & Kode Barcode</th>
                                            <th>Tgl Pinjam & Jatuh Tempo</th>
                                            <th>Status Denda</th>
                                            <th>Kondisi Fisik</th>
                                            <th style="text-align: right;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="returnTableBody">
                                        @foreach($activeLoans as $al)
                                            @php
                                                $today = \Carbon\Carbon::now();
                                                $batas = \Carbon\Carbon::parse($al->peminjaman->batasKembali);
                                                $isOverdue = $today->greaterThan($batas);
                                                $hariTelat = $isOverdue ? max(1, $batas->diffInDays($today)) : 0;
                                                $mingguTelat = (int) ceil($hariTelat / 7);
                                                $hargaBuku = (float) ($al->buku->harga ?? 0);
                                                $dendaTelat = $isOverdue ? ($hargaBuku * min($mingguTelat, 10) * 0.10) : 0;
                                                $kodeBarcode = $al->eksemplar?->kode_barcode ?? $al->eksemplar?->qr_token ?? $al->buku->barcode?->kodeBarcode ?? ('BK-'.$al->idBuku);
                                            @endphp
                                            <tr id="loanRow-{{ $al->id }}" data-detail-id="{{ $al->id }}" data-code="{{ strtolower($kodeBarcode) }}">
                                                <td style="text-align: center;">
                                                    <input type="checkbox" name="detail_ids[]" value="{{ $al->id }}" class="checkbox-return-item" onchange="updateReturnSelectionUI()" style="width: 16px; height: 16px; accent-color: #0f766e; cursor: pointer;">
                                                </td>
                                                <td>
                                                    <div style="font-weight: 700; color: #0f172a;">{{ $al->buku->judul }}</div>
                                                    <div style="font-size: 11.5px; color: #64748b; font-family: monospace;">{{ $kodeBarcode }}</div>
                                                </td>
                                                <td>
                                                    <div>{{ \Carbon\Carbon::parse($al->peminjaman->tanggalPinjam)->translatedFormat('d M Y') }}</div>
                                                    <div style="font-size: 11.5px; color: #64748b;">s/d {{ $batas->translatedFormat('d M Y') }}</div>
                                                </td>
                                                <td>
                                                    @if($isOverdue)
                                                        <span style="color: #dc2626; font-weight: 700; font-size: 12px;">
                                                            Telat {{ $hariTelat }} Hari (Est. Denda Rp {{ number_format($dendaTelat, 0, ',', '.') }})
                                                        </span>
                                                    @else
                                                        <span style="color: #166534; font-weight: 700; font-size: 12px;">
                                                            ✓ Bebas Denda
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <select name="kondisi[{{ $al->id }}]" class="select-kondisi">
                                                        <option value="Baik" selected>Baik</option>
                                                        <option value="Rusak">Rusak</option>
                                                        <option value="Hilang">Hilang</option>
                                                    </select>
                                                </td>
                                                <td style="text-align: right;">
                                                    <button type="button" class="btn-return-action" onclick="kembalikanSatuBuku({{ $al->id }})">
                                                        Kembalikan &rarr;
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="empty-state-notice">
                                    <div style="font-size: 24px; margin-bottom: 6px;">🎉</div>
                                    <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0 0 4px;">Tidak Ada Pinjaman Aktif</h4>
                                    <p style="font-size: 12.5px; color: #64748b; margin: 0;">Anggota ini tidak sedang meminjam buku apapun saat ini.</p>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // State Meja Sirkulasi Langsung
    let activeMember = {!! $selectedMember ? json_encode([
        'id' => $selectedMember->id,
        'name' => $selectedMember->name,
        'kodeAnggota' => $selectedMember->kode_anggota,
        'email' => $selectedMember->email,
        'noTelepon' => $selectedMember->noTelepon ?? '-',
        'inisial' => $selectedMember->inisial ?? 'AG',
        'sedangDipinjam' => $activeLoans->count(),
        'sisaKuota' => max(0, 3 - $activeLoans->count()),
        'borrowedBookIds' => $selectedMemberBorrowedBookIds ?? [],
    ]) : 'null' !!};

    let activeMemberBorrowedBookIds = @json($selectedMemberBorrowedBookIds ?? []);

    let selectedTrayBooks = [];
    let html5QrScanner = null;
    let isCameraRunning = false;
    let isScanThrottled = false;
    let lastScannedText = '';
    let lastScannedTime = 0;
    let searchDebounceTimer = null;

    let currentCircTab = '{{ request("tab") === "kembali" ? "kembali" : "pinjam" }}';

    // Switch Tabs
    function switchCircTab(tab) {
        currentCircTab = tab;
        const tabBtnPinjam = document.getElementById('tabBtnPinjam');
        const tabBtnKembali = document.getElementById('tabBtnKembali');
        const tabContentPinjam = document.getElementById('tabContentPinjam');
        const tabContentKembali = document.getElementById('tabContentKembali');
        const unselectedNotice = document.getElementById('unselectedMemberNotice');

        if (!activeMember) {
            unselectedNotice.style.display = 'block';
            tabContentPinjam.style.display = 'none';
            tabContentKembali.style.display = 'none';
            return;
        }

        unselectedNotice.style.display = 'none';

        if (tab === 'pinjam') {
            tabBtnPinjam.classList.add('active');
            tabBtnKembali.classList.remove('active');
            tabContentPinjam.style.display = 'block';
            tabContentKembali.style.display = 'none';
        } else {
            tabBtnKembali.classList.add('active');
            tabBtnPinjam.classList.remove('active');
            tabContentKembali.style.display = 'block';
            tabContentPinjam.style.display = 'none';
        }
    }

    // Toggle Camera Scanner
    async function toggleScanner() {
        if (isCameraRunning) {
            await stopScanner();
        } else {
            await startScanner();
        }
    }

    async function startScanner() {
        const btnToggle = document.getElementById('btnToggleScan');
        const qrDiv = document.getElementById('qrReader');
        const placeholder = document.getElementById('scannerPlaceholder');

        try {
            html5QrScanner = new Html5Qrcode("qrReader");
            placeholder.style.display = 'none';
            qrDiv.style.display = 'block';

            await html5QrScanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 180, height: 180 } },
                (decodedText) => {
                    const now = Date.now();
                    if (isScanThrottled || (decodedText === lastScannedText && now - lastScannedTime < 3000)) {
                        return;
                    }
                    isScanThrottled = true;
                    lastScannedText = decodedText;
                    lastScannedTime = now;
                    setTimeout(() => { isScanThrottled = false; }, 2500);

                    handleScannedCode(decodedText);
                },
                (error) => {}
            );

            isCameraRunning = true;
            btnToggle.classList.add('active');
            btnToggle.innerHTML = `<span>Hentikan Kamera</span>`;
        } catch (err) {
            placeholder.style.display = 'flex';
            qrDiv.style.display = 'none';
            alert('Gagal mengakses kamera: ' + err);
        }
    }

    async function stopScanner() {
        const btnToggle = document.getElementById('btnToggleScan');
        const qrDiv = document.getElementById('qrReader');
        const placeholder = document.getElementById('scannerPlaceholder');

        if (html5QrScanner && isCameraRunning) {
            try {
                await html5QrScanner.stop();
                html5QrScanner.clear();
            } catch (e) {}
        }

        isCameraRunning = false;
        qrDiv.style.display = 'none';
        placeholder.style.display = 'flex';
        btnToggle.classList.remove('active');
        btnToggle.innerHTML = `<span>Mulai Kamera Scanner</span>`;
    }

    // Handle Scanned Code: Anggota atau Buku
    async function handleScannedCode(code) {
        const cleanCode = (code || '').trim();
        if (!cleanCode) return;

        // Jika anggota sudah aktif dan berada di tab Pengembalian Langsung
        if (activeMember && currentCircTab === 'kembali') {
            const cleanLower = cleanCode.toLowerCase();
            const rows = document.querySelectorAll('#returnTableBody tr');
            let matchedRow = null;
            rows.forEach(r => {
                const rowCode = (r.getAttribute('data-code') || '').toLowerCase();
                if (rowCode === cleanLower || cleanLower.includes(rowCode) || (rowCode && rowCode.includes(cleanLower))) {
                    matchedRow = r;
                }
            });

            if (matchedRow) {
                const cb = matchedRow.querySelector('.checkbox-return-item');
                if (cb) {
                    cb.checked = true;
                    updateReturnSelectionUI();
                    matchedRow.classList.add('highlight-loan-row');
                    setTimeout(() => matchedRow.classList.remove('highlight-loan-row'), 2000);
                    return;
                }
            } else if (cleanCode.startsWith('BK-') || cleanCode.startsWith('bk_') || cleanCode.startsWith('eks_')) {
                alert(`Buku dengan kode "${cleanCode}" tidak ditemukan dalam daftar pinjaman aktif anggota ini.`);
                return;
            }
        }

        // Cek apakah kode barcode buku saat anggota sudah aktif di tab pinjam
        if (activeMember && currentCircTab === 'pinjam' && (cleanCode.startsWith('BK-') || cleanCode.startsWith('bk_') || cleanCode.startsWith('eks_'))) {
            addBookByBarcode(cleanCode);
            return;
        }

        // Default: Scan Anggota
        await fetchMember(cleanCode);
    }

    // Tambah buku langsung via barcode fisik yang discan
    async function addBookByBarcode(code) {
        if (!activeMember) {
            alert('Pindai kartu anggota terlebih dahulu sebelum memindai barcode buku.');
            return;
        }

        try {
            const memberParam = activeMember ? `&member_id=${activeMember.id}` : '';
            const res = await fetch(`{{ route('api.sirkulasi.buku-tersedia') }}?q=${encodeURIComponent(code)}${memberParam}`);
            const data = await res.json();
            if (data.success && data.data && data.data.length > 0) {
                const matched = data.data[0];
                if (matched.isBorrowedByMember || activeMemberBorrowedBookIds.includes(matched.idBuku)) {
                    alert(`Buku "${matched.judul}" sedang dipinjam oleh ${activeMember.name}. Anggota tidak diperbolehkan meminjam buku dengan judul yang sama.`);
                    return;
                }
                togglePickBook({ idBuku: matched.idBuku, judul: matched.judul, kodeBarcode: matched.kodeBarcode });
            } else {
                alert(`Buku dengan barcode/token "${code}" tidak ditemukan atau stok sedang habis.`);
            }
        } catch (e) {
            alert('Gagal memproses barcode buku: ' + e);
        }
    }

    // Manual Member Submit
    function submitManualMember() {
        const val = document.getElementById('inputManualMember').value.trim();
        if (val) {
            fetchMember(val);
        }
    }

    // Fetch Member Profile & Active Loans
    async function fetchMember(code) {
        try {
            const res = await fetch("{{ route('api.sirkulasi.scan-member') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ code: code })
            });

            const data = await res.json();
            if (data.success && data.data) {
                renderMemberProfile(data.data.member, data.data.pinjamanAktif);
            } else {
                alert(data.message || 'Anggota tidak ditemukan.');
            }
        } catch (e) {
            alert('Gagal memuat data anggota: ' + e);
        }
    }

    function renderMemberProfile(m, activeLoans) {
        activeMember = m;
        activeMemberBorrowedBookIds = m.borrowedBookIds || [];
        document.getElementById('dispMemberName').textContent = m.name;
        document.getElementById('dispMemberCode').textContent = m.kodeAnggota;
        document.getElementById('dispMemberAvatar').textContent = m.inisial;
        document.getElementById('dispMemberEmail').textContent = m.email;
        document.getElementById('dispMemberPhone').textContent = m.noTelepon;
        document.getElementById('dispSedangDipinjam').textContent = m.sedangDipinjam;
        document.getElementById('dispSisaKuota').textContent = m.sisaKuota;
        document.getElementById('formMemberIdInput').value = m.id;
        const formReturnMemberId = document.getElementById('formReturnMemberId');
        if (formReturnMemberId) formReturnMemberId.value = m.id;
        document.getElementById('countBorrowedBadge').textContent = m.sedangDipinjam;

        document.getElementById('memberProfileCard').style.display = 'block';
        document.getElementById('unselectedMemberNotice').style.display = 'none';

        if (currentCircTab === 'kembali') {
            document.getElementById('tabContentKembali').style.display = 'block';
            document.getElementById('tabContentPinjam').style.display = 'none';
        } else {
            document.getElementById('tabContentPinjam').style.display = 'block';
            document.getElementById('tabContentKembali').style.display = 'none';
        }

        renderBorrowedTable(activeLoans || []);
        updateTrayUI();

        // Refresh ketersediaan buku sesuai daftar pinjaman anggota aktif
        const currentQ = document.getElementById('bookSearchInput')?.value || '';
        fetchBooks(currentQ);
    }

    function resetMemberSelection() {
        activeMember = null;
        activeMemberBorrowedBookIds = [];
        selectedTrayBooks = [];
        document.getElementById('memberProfileCard').style.display = 'none';
        document.getElementById('unselectedMemberNotice').style.display = 'block';
        document.getElementById('tabContentPinjam').style.display = 'none';
        document.getElementById('tabContentKembali').style.display = 'none';
        document.getElementById('formMemberIdInput').value = '';
        const formReturnMemberId = document.getElementById('formReturnMemberId');
        if (formReturnMemberId) formReturnMemberId.value = '';
        updateTrayUI();

        // Refresh ketersediaan buku saat anggota di-reset
        const currentQ = document.getElementById('bookSearchInput')?.value || '';
        fetchBooks(currentQ);
    }

    // Render Pinjaman Aktif Table
    function renderBorrowedTable(loans) {
        const container = document.getElementById('borrowedBooksContainer');
        if (!loans || loans.length === 0) {
            container.innerHTML = `
                <div class="empty-state-notice">
                    <div style="font-size: 24px; margin-bottom: 6px;">🎉</div>
                    <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0 0 4px;">Tidak Ada Pinjaman Aktif</h4>
                    <p style="font-size: 12.5px; color: #64748b; margin: 0;">Anggota ini tidak sedang meminjam buku apapun saat ini.</p>
                </div>
            `;
            const countBadge = document.getElementById('countBorrowedBadge');
            if (countBadge) countBadge.textContent = '0';
            return;
        }

        let html = `
            <div class="return-toolbar">
                <div class="return-toolbar-left">
                    <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                        <input type="checkbox" id="checkSelectAllReturns" onchange="toggleSelectAllReturns(this)" style="width: 16px; height: 16px; accent-color: #0f766e; cursor: pointer;">
                        <span>Pilih Semua Buku</span>
                    </label>
                    <span id="labelSelectedReturnsCount" style="color: #64748b; font-size: 12px; font-weight: 500;">
                        (0 dari ${loans.length} buku terpilih)
                    </span>
                </div>
                <button type="button" id="btnSubmitBatchReturn" class="btn-submit-batch-return" onclick="submitBatchReturn()" disabled>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Selesaikan Pengembalian (<span id="countSelectedReturnText">0</span>)</span>
                </button>
            </div>

            <table class="return-table">
                <thead>
                    <tr>
                        <th style="width: 38px; text-align: center;">#</th>
                        <th>Buku & Kode Barcode</th>
                        <th>Tgl Pinjam & Jatuh Tempo</th>
                        <th>Status Denda</th>
                        <th>Kondisi Fisik</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="returnTableBody">
        `;

        loans.forEach(l => {
            const overdueText = l.isOverdue 
                ? `<span style="color: #dc2626; font-weight: 700; font-size: 12px;">Telat ${l.hariTerlambat} Hari (Est. Denda Rp ${Number(l.estDenda || 0).toLocaleString('id-ID')})</span>`
                : `<span style="color: #166534; font-weight: 700; font-size: 12px;">✓ Bebas Denda</span>`;

            const codeClean = (l.kodeBuku || '').toLowerCase();

            html += `
                <tr id="loanRow-${l.idDetail}" data-detail-id="${l.idDetail}" data-code="${codeClean}">
                    <td style="text-align: center;">
                        <input type="checkbox" name="detail_ids[]" value="${l.idDetail}" class="checkbox-return-item" onchange="updateReturnSelectionUI()" style="width: 16px; height: 16px; accent-color: #0f766e; cursor: pointer;">
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #0f172a;">${l.judul}</div>
                        <div style="font-size: 11.5px; color: #64748b; font-family: monospace;">${l.kodeBuku}</div>
                    </td>
                    <td>
                        <div>${l.tanggalPinjam}</div>
                        <div style="font-size: 11.5px; color: #64748b;">s/d ${l.batasKembali}</div>
                    </td>
                    <td>${overdueText}</td>
                    <td>
                        <select name="kondisi[${l.idDetail}]" class="select-kondisi">
                            <option value="Baik" selected>Baik</option>
                            <option value="Rusak">Rusak</option>
                            <option value="Hilang">Hilang</option>
                        </select>
                    </td>
                    <td style="text-align: right;">
                        <button type="button" class="btn-return-action" onclick="kembalikanSatuBuku(${l.idDetail})">
                            Kembalikan &rarr;
                        </button>
                    </td>
                </tr>
            `;
        });

        html += `</tbody></table>`;
        container.innerHTML = html;
        updateReturnSelectionUI();
    }

    function toggleSelectAllReturns(masterCb) {
        const checkboxes = document.querySelectorAll('.checkbox-return-item');
        checkboxes.forEach(cb => { cb.checked = masterCb.checked; });
        updateReturnSelectionUI();
    }

    function updateReturnSelectionUI() {
        const checkboxes = document.querySelectorAll('.checkbox-return-item');
        const checkedBoxes = document.querySelectorAll('.checkbox-return-item:checked');
        const total = checkboxes.length;
        const count = checkedBoxes.length;

        const masterCb = document.getElementById('checkSelectAllReturns');
        if (masterCb) {
            masterCb.checked = (total > 0 && count === total);
            masterCb.indeterminate = (count > 0 && count < total);
        }

        const countText = document.getElementById('countSelectedReturnText');
        if (countText) countText.textContent = count;

        const labelCount = document.getElementById('labelSelectedReturnsCount');
        if (labelCount) labelCount.textContent = `(${count} dari ${total} buku terpilih)`;

        const btnSubmit = document.getElementById('btnSubmitBatchReturn');
        if (btnSubmit) {
            btnSubmit.disabled = (count === 0);
        }
    }

    function kembalikanSatuBuku(idDetail) {
        if (!activeMember) {
            alert('Pilih anggota terlebih dahulu.');
            return;
        }

        // Uncheck all, check only this one
        document.querySelectorAll('.checkbox-return-item').forEach(cb => {
            cb.checked = (parseInt(cb.value, 10) === parseInt(idDetail, 10));
        });
        updateReturnSelectionUI();

        if (confirm(`Konfirmasi penyelesaian pengembalian 1 buku fisik ini langsung di meja sirkulasi?`)) {
            document.getElementById('formWalkinReturn').submit();
        }
    }

    function submitBatchReturn() {
        if (!activeMember) {
            alert('Pilih anggota terlebih dahulu.');
            return;
        }

        const checkedBoxes = document.querySelectorAll('.checkbox-return-item:checked');
        if (checkedBoxes.length === 0) {
            alert('Pilih minimal 1 buku yang ingin diselesaikan pengembaliannya.');
            return;
        }

        if (confirm(`Konfirmasi penyelesaian pengembalian ${checkedBoxes.length} buku fisik yang dipilih langsung di meja sirkulasi?`)) {
            document.getElementById('formWalkinReturn').submit();
        }
    }

    // Tray Peminjaman Langsung Selection Logic
    function togglePickBook(book) {
        if (!activeMember) {
            alert('Pindai atau pilih anggota terlebih dahulu.');
            return;
        }

        // Cek apakah anggota sedang meminjam buku dengan judul/idBuku yang sama
        if (activeMemberBorrowedBookIds.includes(book.idBuku)) {
            alert(`Anggota "${activeMember.name}" saat ini sedang meminjam buku "${book.judul}". Anggota tidak diperbolehkan meminjam buku dengan judul yang sama sebelum buku tersebut dikembalikan.`);
            return;
        }

        const maxQuota = activeMember.sisaKuota || 3;
        const exists = selectedTrayBooks.some(b => b.idBuku === book.idBuku);

        if (exists) {
            selectedTrayBooks = selectedTrayBooks.filter(b => b.idBuku !== book.idBuku);
        } else {
            if (selectedTrayBooks.length >= maxQuota) {
                alert(`Batas maksimal pinjam untuk anggota ini tersisa ${maxQuota} buku.`);
                return;
            }
            selectedTrayBooks.push(book);
        }

        updateTrayUI();
    }

    function removeTrayItem(idBuku) {
        selectedTrayBooks = selectedTrayBooks.filter(b => b.idBuku !== idBuku);
        updateTrayUI();
    }

    function updateTrayUI() {
        const trayList = document.getElementById('trayItemsList');
        const trayCountText = document.getElementById('trayCountText');
        const btnConfirm = document.getElementById('btnConfirmWalkinLoan');
        const hiddenInputs = document.getElementById('hiddenBarcodeInputContainer');

        const maxQuota = activeMember ? (activeMember.sisaKuota || 3) : 3;
        trayCountText.textContent = `${selectedTrayBooks.length} / ${maxQuota} Buku`;

        hiddenInputs.innerHTML = '';
        if (selectedTrayBooks.length === 0) {
            trayList.innerHTML = `
                <div style="font-size: 12.5px; color: #94a3b8; text-align: center; padding: 12px;">
                    Belum ada buku yang dipilih. Klik '+ Pilih' pada daftar buku di bawah atau scan barcode fisik buku.
                </div>
            `;
            btnConfirm.disabled = true;
        } else {
            let html = '';
            selectedTrayBooks.forEach(b => {
                html += `
                    <div class="tray-row">
                        <div>
                            <strong>${b.judul}</strong>
                            <span style="font-size: 11px; color: #64748b; margin-left: 6px; font-family: monospace;">(${b.kodeBarcode})</span>
                        </div>
                        <button type="button" class="btn-remove-tray" onclick="removeTrayItem(${b.idBuku})">&times; Hapus</button>
                    </div>
                `;
                // Add hidden input array barcodes[]
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'barcodes[]';
                input.value = b.kodeBarcode;
                hiddenInputs.appendChild(input);
            });
            trayList.innerHTML = html;
            btnConfirm.disabled = !activeMember;
        }

        // Update button active state on grid cards
        document.querySelectorAll('.book-item-card').forEach(card => {
            const btn = card.querySelector('.btn-pick-book');
            if (!btn || btn.disabled) return;
            const bookId = parseInt(card.id.replace('bookCard-', ''), 10);
            if (selectedTrayBooks.some(b => b.idBuku === bookId)) {
                btn.classList.add('picked');
                btn.textContent = '✓ Dipilih';
            } else {
                btn.classList.remove('picked');
                btn.textContent = '+ Pilih';
            }
        });
    }

    // Search Books Debounce
    function debounceSearchBooks(val) {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => {
            fetchBooks(val);
        }, 300);
    }

    async function fetchBooks(query) {
        try {
            const memberParam = activeMember ? `&member_id=${activeMember.id}` : '';
            const res = await fetch(`{{ route('api.sirkulasi.buku-tersedia') }}?q=${encodeURIComponent(query || '')}${memberParam}`);
            const data = await res.json();
            if (data.success) {
                renderBooksGrid(data.data || []);
            }
        } catch (e) {
            console.error(e);
        }
    }

    function renderBooksGrid(books) {
        const grid = document.getElementById('booksResultsGrid');
        if (books.length === 0) {
            grid.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 24px; color: #94a3b8; font-size: 13px;">
                    Tidak ditemukan buku yang cocok dan memiliki stok tersedia.
                </div>
            `;
            return;
        }

        let html = '';
        books.forEach(b => {
            const isPicked = selectedTrayBooks.some(tb => tb.idBuku === b.idBuku);
            const isBorrowed = Boolean(b.isBorrowedByMember || activeMemberBorrowedBookIds.includes(b.idBuku));
            const coverHtml = b.sampul 
                ? `<img src="${b.sampul}" alt="Sampul" class="book-cover-thumb">`
                : `<div class="book-cover-thumb">📖</div>`;

            let actionBtnHtml = '';
            if (isBorrowed) {
                actionBtnHtml = `
                    <button type="button" class="btn-pick-book" disabled style="opacity: 0.55; cursor: not-allowed; background: #fee2e2; color: #dc2626; border-color: #fca5a5;" title="Buku ini sedang dipinjam oleh anggota">
                        Sedang Dipinjam
                    </button>
                `;
            } else {
                const bookDataEscaped = JSON.stringify({ idBuku: b.idBuku, judul: b.judul, kodeBarcode: b.kodeBarcode }).replace(/"/g, '&quot;');
                actionBtnHtml = `
                    <button type="button" class="btn-pick-book ${isPicked ? 'picked' : ''}" onclick="togglePickBook(${bookDataEscaped})">
                        ${isPicked ? '✓ Dipilih' : '+ Pilih'}
                    </button>
                `;
            }

            html += `
                <div class="book-item-card" id="bookCard-${b.idBuku}">
                    ${coverHtml}
                    <div class="book-item-meta">
                        <div class="book-item-title">${b.judul}</div>
                        <div class="book-item-author">${b.penulis}</div>
                        <div class="book-item-tags">
                            <span class="tag-stok">Stok: ${b.stok}</span>
                            <span class="tag-rak">Rak: ${b.rak}</span>
                            ${isBorrowed ? '<span style="background: #fef2f2; color: #dc2626; font-size: 10.5px; font-weight: 700; padding: 2px 6px; border-radius: 4px;">Sedang Dipinjam</span>' : ''}
                        </div>
                    </div>
                    ${actionBtnHtml}
                </div>
            `;
        });
        grid.innerHTML = html;
    }

    // Aktifkan tab pengembalian jika URL memiliki query parameter ?tab=kembali
    @if(request('tab') === 'kembali')
        if (activeMember) {
            switchCircTab('kembali');
        }
    @endif
</script>
@endsection