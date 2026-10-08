@extends('layouts.anggota')

@section('title', 'Keranjang Booking Buku - BOOKNEST')

@section('styles')
<style>
    :root {
        --cart-teal: #0f766e;
        --cart-teal-dark: #115e59;
        --cart-teal-light: #ccfbf1;
    }

    .cart-page {
        max-width: 1240px;
        margin: 0 auto;
        padding: 10px 0 60px;
    }

    /* --- BREADCRUMB & HEADER --- */
    .cart-breadcrumb {
        font-size: 12px;
        font-weight: 700;
        color: #0f766e;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .cart-breadcrumb a {
        color: #64748b;
        text-decoration: none;
    }

    .cart-breadcrumb a:hover {
        color: #0f766e;
    }

    .cart-header-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .cart-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.6px;
        line-height: 1.2;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cart-badge-count {
        font-size: 14px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 9999px;
        background: #ccfbf1;
        color: #0f766e;
    }

    .cart-subtitle {
        font-size: 14px;
        color: var(--text-muted);
    }

    /* --- FLASH MESSAGES --- */
    .cart-alert {
        padding: 14px 18px;
        border-radius: 12px;
        font-size: 13.5px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        line-height: 1.5;
    }

    .cart-alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .cart-alert-danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .cart-alert-info {
        background: #f0fdfa;
        border: 1px solid #99f6e4;
        color: #0f766e;
    }

    /* --- GRID 2 KOLOM --- */
    .cart-grid {
        display: grid;
        grid-template-columns: 1fr 390px;
        gap: 28px;
        align-items: start;
    }

    /* --- ITEM LIST (KOLOM KIRI) --- */
    .cart-items-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .cart-items-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 16px;
    }

    .cart-toolbar-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--text-heading);
    }

    .btn-clear-cart {
        font-size: 12.5px;
        font-weight: 700;
        color: #ef4444;
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        transition: background 0.15s;
    }

    .btn-clear-cart:hover {
        background: #fee2e2;
    }

    .cart-item-row {
        display: flex;
        gap: 18px;
        padding: 18px 0;
        border-bottom: 1px solid #f1f5f9;
        align-items: center;
        position: relative;
    }

    .cart-item-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .cart-item-cover {
        width: 74px;
        height: 104px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
    }

    .cart-item-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-info {
        flex: 1;
        min-width: 0;
    }

    .cart-item-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 4px;
        line-height: 1.35;
    }

    .cart-item-author {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .cart-item-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .badge-category {
        font-size: 11px;
        font-weight: 700;
        color: #0f766e;
        background: #f0fdfa;
        border: 1px solid #ccfbf1;
        padding: 2px 8px;
        border-radius: 6px;
    }

    .badge-rak {
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-remove-item {
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.15s, transform 0.1s;
    }

    .btn-remove-item:hover {
        background: #ffe4e6;
        transform: scale(1.05);
    }

    .cart-empty-box {
        text-align: center;
        padding: 56px 20px;
    }

    .cart-empty-icon {
        width: 64px;
        height: 64px;
        background: #f0fdfa;
        color: #0f766e;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    .cart-empty-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 6px;
    }

    .cart-empty-desc {
        font-size: 13.5px;
        color: var(--text-muted);
        margin-bottom: 22px;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    /* --- CHECKOUT SIDEBAR (KOLOM KANAN) --- */
    .checkout-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        position: sticky;
        top: 90px;
    }

    .checkout-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Quota Meter Box */
    .quota-meter-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .quota-meter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12.5px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .quota-meter-bar {
        width: 100%;
        height: 8px;
        background: #e2e8f0;
        border-radius: 9999px;
        overflow: hidden;
        display: flex;
    }

    .quota-bar-fill-borrowed {
        background: #2563eb;
        height: 100%;
        transition: width 0.3s;
    }

    .quota-bar-fill-cart {
        background: #0f766e;
        height: 100%;
        transition: width 0.3s;
    }

    .quota-meter-legend {
        display: flex;
        gap: 12px;
        margin-top: 10px;
        font-size: 11.5px;
        color: #64748b;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    /* Ringkasan list */
    .summary-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-bottom: 18px;
        border-bottom: 1px dashed #e2e8f0;
        margin-bottom: 18px;
        font-size: 13.5px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .summary-key {
        color: var(--text-muted);
        font-weight: 500;
    }

    .summary-val {
        color: var(--text-heading);
        font-weight: 700;
    }

    /* Radio Opsi Pengambilan */
    .pickup-option-title {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 10px;
    }

    .pickup-radio-card {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        padding: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.15s;
    }

    .pickup-radio-card:hover {
        border-color: #0f766e;
        background: #f0fdfa;
    }

    .pickup-radio-card input[type="radio"]:checked + .pickup-radio-label {
        font-weight: 700;
        color: #0f766e;
    }

    .pickup-radio-label {
        font-size: 13px;
        color: #334155;
        line-height: 1.4;
    }

    .pickup-radio-desc {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 2px;
    }

    /* Button Checkout */
    .btn-checkout-booking {
        width: 100%;
        padding: 14px 20px;
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 14.5px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(15, 118, 110, 0.25);
        transition: background 0.15s, transform 0.1s;
        margin-top: 14px;
    }

    .btn-checkout-booking:hover:not(:disabled) {
        background: #115e59;
        transform: translateY(-1px);
    }

    .btn-checkout-booking:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    .btn-back-katalog {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        text-decoration: none;
        transition: all 0.15s;
    }

    .btn-back-katalog:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    @media (max-width: 900px) {
        .cart-grid {
            grid-template-columns: 1fr;
        }

        .checkout-card {
            position: static;
        }
    }
</style>
@endsection

@section('content')
<div class="cart-page">

    <!-- 1. BREADCRUMB -->
    <div class="cart-breadcrumb">
        <a href="{{ route('dashboard') }}">Dasbor</a>
        <span>/</span>
        <a href="{{ route('katalog.index') }}">Katalog</a>
        <span>/</span>
        <span>Keranjang Booking</span>
    </div>

    <!-- 2. HEADER -->
    <div class="cart-header-row">
        <div>
            <h1 class="cart-title">
                Keranjang Booking Buku
                <span class="cart-badge-count">{{ $totalItemKeranjang }} Buku</span>
            </h1>
            <p class="cart-subtitle">
                Kumpulkan beberapa buku yang ingin Anda pinjam sekaligus dalam satu tiket QR Code.
            </p>
        </div>

        <a href="{{ route('katalog.index') }}" class="btn-back-katalog">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Tambah Buku Lain dari Katalog
        </a>
    </div>

    <!-- 3. FLASH MESSAGES -->
    @if (session('success'))
        <div class="cart-alert cart-alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="cart-alert cart-alert-danger">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if (session('info'))
        <div class="cart-alert cart-alert-info">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <div>{{ session('info') }}</div>
        </div>
    @endif

    <!-- 4. GRID KONTEN -->
    @if ($totalItemKeranjang > 0)
        <div class="cart-grid">

            <!-- KOLOM KIRI: DAFTAR BUKU DI KERANJANG -->
            <div class="cart-items-card">
                <div class="cart-items-toolbar">
                    <span class="cart-toolbar-title">Daftar Buku Pilihan Anda ({{ $totalItemKeranjang }})</span>
                    <form action="{{ route('keranjang.kosongkan') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang booking?');">
                        @csrf
                        <button type="submit" class="btn-clear-cart">
                            Hapus Semua
                        </button>
                    </form>
                </div>

                <div class="cart-items-list">
                    @foreach ($items as $item)
                        @php
                            $b = $item['buku'];
                        @endphp
                        <div class="cart-item-row">
                            <div class="cart-item-cover">
                                @if (! empty($b->cover))
                                    <img src="{{ $b->cover }}" alt="{{ $b->judul }}" onerror="this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=400&auto=format&fit=crop';">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #64748b;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                    </div>
                                @endif
                            </div>

                            <div class="cart-item-info">
                                <h3 class="cart-item-title">{{ $b->judul }}</h3>
                                <p class="cart-item-author">{{ $b->penulis }} · {{ $b->penerbit ?? 'Penerbit' }} ({{ $b->tahunTerbit }})</p>
                                <div class="cart-item-meta">
                                    <span class="badge-category">{{ $b->kategori?->namaKategori ?? 'Umum' }}</span>
                                    <span class="badge-rak">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                        {{ $b->rak ?? 'Rak Perpustakaan' }}
                                    </span>
                                    @if ($item['isTersedia'])
                                        <span style="font-size: 11px; font-weight: 700; color: #16a34a; background: #dcfce7; padding: 2px 8px; border-radius: 6px;">
                                            Tersedia ({{ $b->stok }} eks)
                                        </span>
                                    @else
                                        <span style="font-size: 11px; font-weight: 700; color: #dc2626; background: #fee2e2; padding: 2px 8px; border-radius: 6px;">
                                            Stok Habis
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <form action="{{ route('keranjang.hapus', $b->idBuku) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-remove-item" title="Hapus buku ini dari keranjang">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- KOLOM KANAN: CHECKOUT & KUOTA SUMMARY -->
            <div class="checkout-card">
                <h2 class="checkout-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--cart-teal)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    Ringkasan Booking
                </h2>

                <!-- Quota Meter Box -->
                <div class="quota-meter-box">
                    <div class="quota-meter-header">
                        <span>Status Kuota Peminjaman</span>
                        <span>{{ $bukuSedangDipinjam + $totalItemKeranjang }} / {{ $batasMaksimalBuku }} Buku</span>
                    </div>

                    @php
                        $pctBorrowed = min(100, ($bukuSedangDipinjam / $batasMaksimalBuku) * 100);
                        $pctCart = min(100 - $pctBorrowed, ($totalItemKeranjang / $batasMaksimalBuku) * 100);
                    @endphp

                    <div class="quota-meter-bar">
                        <div class="quota-bar-fill-borrowed" style="width: {{ $pctBorrowed }}%;"></div>
                        <div class="quota-bar-fill-cart" style="width: {{ $pctCart }}%;"></div>
                    </div>

                    <div class="quota-meter-legend">
                        <div class="legend-item">
                            <span class="legend-dot" style="background: #2563eb;"></span>
                            <span>Sedang dipinjam: {{ $bukuSedangDipinjam }}</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot" style="background: #0f766e;"></span>
                            <span>Di keranjang: {{ $totalItemKeranjang }}</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot" style="background: #cbd5e1;"></span>
                            <span>Sisa kuota: {{ $sisaKuotaSetelahBooking }}</span>
                        </div>
                    </div>
                </div>

                @if ($kuotaMelebihiBatas)
                    <div class="cart-alert cart-alert-danger" style="margin-bottom: 16px; padding: 12px 14px; font-size: 12.5px;">
                        ⛔ <strong>Melebihi Kuota!</strong> Total buku ({{ $bukuSedangDipinjam + $totalItemKeranjang }}) melebihi batas maksimal {{ $batasMaksimalBuku }} buku. Silakan hapus {{ ($bukuSedangDipinjam + $totalItemKeranjang) - $batasMaksimalBuku }} buku dari keranjang untuk melanjutkan.
                    </div>
                @endif

                <!-- Form Checkout -->
                <form action="{{ route('keranjang.checkout') }}" method="POST">
                    @csrf

                    <!-- Ringkasan Rincian -->
                    <div class="summary-list">
                        <div class="summary-row">
                            <span class="summary-key">Anggota</span>
                            <span class="summary-val">{{ $user->name }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-key">Jumlah Buku Booking</span>
                            <span class="summary-val">{{ $totalItemKeranjang }} Buku</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-key">Masa Peminjaman</span>
                            <span class="summary-val">{{ $durasiHari }} Hari Aktif</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-key">Batas Pengambilan Tiket</span>
                            <span class="summary-val">{{ $batasAmbilJam }} Jam sejak dibuat</span>
                        </div>
                    </div>

                    <!-- Pilihan Pengambilan Buku -->
                    <div class="pickup-option-title">Metode Pengambilan Buku:</div>

                    <label class="pickup-radio-card">
                        <input type="radio" name="opsi_pengambilan" value="siapkan_petugas" checked>
                        <div>
                            <div class="pickup-radio-label">📦 Disiapkan oleh Petugas di Meja</div>
                            <div class="pickup-radio-desc">Petugas sirkulasi akan menyiapkan seluruh buku ini di meja layanan. Anda tinggal datang dan scan QR.</div>
                        </div>
                    </label>

                    <label class="pickup-radio-card">
                        <input type="radio" name="opsi_pengambilan" value="ambil_mandiri">
                        <div>
                            <div class="pickup-radio-label">🚶 Ambil Mandiri dari Rak</div>
                            <div class="pickup-radio-desc">Anda mencari sendiri buku di rak koleksi perpustakaan, lalu membawanya ke meja sirkulasi untuk scan serah terima.</div>
                        </div>
                    </label>

                    <!-- Tombol Konfirmasi Booking -->
                    <button
                        type="submit"
                        class="btn-checkout-booking"
                        @disabled($kuotaMelebihiBatas)
                    >
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        Booking Sekaligus ({{ $totalItemKeranjang }} Buku) & Dapatkan QR
                    </button>
                </form>
            </div>

        </div>
    @else
        <!-- EMPTY STATE -->
        <div class="cart-items-card">
            <div class="cart-empty-box">
                <div class="cart-empty-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
                <h3 class="cart-empty-title">Keranjang Booking Anda Masih Kosong</h3>
                <p class="cart-empty-desc">
                    Anda belum memasukkan buku apa pun ke dalam keranjang. Silakan jelajahi katalog untuk memilih beberapa buku sekaligus.
                </p>
                <a href="{{ route('katalog.index') }}" class="btn-checkout-booking" style="max-width: 260px; margin: 0 auto; text-decoration: none;">
                    Jelajahi Katalog Buku &rarr;
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
