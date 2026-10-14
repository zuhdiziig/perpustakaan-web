@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.petugas')

@section('title', 'Kelola Tagihan & Kas Denda - BOOKNEST')

@section('styles')
<style>
    /* --- BREADCRUMB & HEADER --- */
    .page-header {
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
    }

    .breadcrumb {
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

    .page-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        letter-spacing: -0.5px;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .page-subtitle {
        font-size: 13.5px;
        color: var(--text-muted, #64748b);
        max-width: 680px;
    }

    .badge-header-info {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f0fdfa;
        color: #0f766e;
        border: 1px solid #ccfbf1;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
    }

    /* --- STATS SUMMARY GRID --- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon.danger { background: #fee2e2; color: #dc2626; }
    .stat-icon.success { background: #dcfce7; color: #16a34a; }
    .stat-icon.warning { background: #fef3c7; color: #d97706; }
    .stat-icon.primary { background: #f0fdfa; color: #0f766e; }

    .stat-meta {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted, #64748b);
        margin-top: 2px;
    }

    /* --- FLASH MESSAGES --- */
    .flash-alert {
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 13.5px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .flash-alert.success {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .flash-alert.info {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    /* --- FILTER & SEARCH BAR --- */
    .filter-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .filter-form {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        width: 100%;
    }

    .search-input-wrapper {
        position: relative;
        flex: 0 0 320px;
        width: 320px;
        min-width: 320px;
        max-width: 320px;
    }

    .search-input {
        width: 100%;
        height: 38px;
        padding: 0 36px 0 12px;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        background: #ffffff;
        color: #0f172a;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .search-input:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
    }

    .search-icon-btn {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        color: #0f766e;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }

    .search-icon-btn:hover {
        background: #f0fdfa;
        color: #115e59;
        transform: translateY(-50%) scale(1.08);
    }

    .search-icon-btn:active {
        transform: translateY(-50%) scale(0.95);
    }

    .filter-select {
        height: 38px;
        padding: 0 14px;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        font-family: inherit;
        background: #ffffff;
        color: #334155;
        cursor: pointer;
        box-sizing: border-box;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .filter-select:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
    }

    .btn-filter-submit {
        height: 38px;
        padding: 0 16px;
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
        box-sizing: border-box;
        transition: background 0.15s;
    }

    .btn-filter-submit:hover {
        background: #115e59;
    }

    .btn-filter-reset {
        height: 38px;
        padding: 0 14px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-sizing: border-box;
        transition: background 0.15s, color 0.15s;
        white-space: nowrap;
    }

    .btn-filter-reset:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* --- DATA TABLE CARD --- */
    .table-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    .denda-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .denda-table th {
        background: #f8fafc;
        padding: 13px 18px;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-color, #e2e8f0);
        white-space: nowrap;
    }

    .denda-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13.5px;
        color: #334155;
        vertical-align: middle;
    }

    .denda-table tr:last-child td {
        border-bottom: none;
    }

    .denda-table tbody tr:hover td {
        background: #f8fafc;
    }

    /* Badges & Pills */
    .invoice-tag {
        font-family: monospace;
        font-size: 12px;
        font-weight: 700;
        background: #f1f5f9;
        color: #0f172a;
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        display: inline-block;
    }

    .member-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .member-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #e0f2fe;
        color: #0369a1;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .member-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 13.5px;
        line-height: 1.2;
    }

    .member-email {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    .badge-status-denda {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-status-denda.lunas {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .badge-status-denda.belum {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .status-subtext {
        font-size: 11px;
        color: #64748b;
        margin-top: 3px;
    }

    .price-value {
        font-weight: 800;
        font-size: 14.5px;
    }

    .price-value.danger { color: #dc2626; }
    .price-value.success { color: #16a34a; }

    /* Action buttons */
    .action-group {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-action-cash {
        background: #16a34a;
        color: #ffffff;
        border: none;
        padding: 6px 12px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.15s;
    }

    .btn-action-cash:hover {
        background: #15803d;
    }

    .btn-action-qris {
        background: #2563eb;
        color: #ffffff;
        padding: 6px 11px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.15s;
    }

    .btn-action-qris:hover {
        background: #1d4ed8;
    }

    .btn-action-nota {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        padding: 6px 11px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s;
    }

    .btn-action-nota:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .table-footer {
        padding: 16px 20px;
        background: #ffffff;
        border-top: 1px solid var(--border-color, #e2e8f0);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .search-input-wrapper {
            flex: 1 1 240px;
            width: auto;
            min-width: 200px;
            max-width: 100%;
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-card {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-form {
            width: 100%;
        }

        .search-input-wrapper {
            flex: 1 1 100%;
            width: 100%;
            min-width: 100%;
            max-width: 100%;
        }

        .filter-select {
            flex: 1 1 100%;
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="denda-page">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <span>Sirkulasi & Keuangan</span>
            </div>
            <h1 class="page-title">Kelola Tagihan & Kas Denda</h1>
            <p class="page-subtitle">
                Monitoring tagihan denda keterlambatan/kerusakan, verifikasi penerimaan pembayaran tunai di meja sirkulasi, serta rekapitulasi kas masuk perpustakaan.
            </p>
        </div>

        <div>
            <div class="badge-header-info">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                <span>Metode Pembayaran: QRIS Otomatis</span>
            </div>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if (session('success'))
        <div class="flash-alert success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('info'))
        <div class="flash-alert info">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <!-- SUMMARY STATS GRID -->
    <div class="stats-grid" id="dendaStatsGrid">
        <div class="stat-card">
            <div class="stat-icon danger">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</span>
                <span class="stat-label">Total Tunggakan Belum Bayar</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</span>
                <span class="stat-label">Total Kas Denda Masuk</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $countBelumLunas }}</span>
                <span class="stat-label">Tagihan Belum Lunas</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon primary">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $countLunas }}</span>
                <span class="stat-label">Tagihan Lunas (Selesai)</span>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="filter-card">
        <form action="{{ route('denda.index') }}" method="GET" class="filter-form" id="dendaFilterForm">
            <div class="search-input-wrapper">
                <input type="text"
                       name="search"
                       id="dendaSearchInput"
                       class="search-input"
                       placeholder="Cari no tagihan, nama anggota, judul buku..."
                       value="{{ request('search') }}"
                       autocomplete="off">
                <button type="submit"
                        id="dendaSearchBtn"
                        class="search-icon-btn"
                        title="Cari tagihan denda"
                        aria-label="Cari tagihan denda">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </div>

            <select name="status" id="dendaStatusSelect" class="filter-select">
                <option value="">Semua Status Tagihan</option>
                <option value="Belum Dibayar" {{ request('status') === 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar (Tunggakan)</option>
                <option value="Lunas" {{ request('status') === 'Lunas' ? 'selected' : '' }}>Lunas Saja</option>
            </select>

            <div id="dendaResetWrapper" style="{{ (request()->filled('search') || request()->filled('status')) ? 'display: inline-flex;' : 'display: none;' }}">
                <a href="{{ route('denda.index') }}" id="dendaResetBtn" class="btn-filter-reset">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- DATA TABLE CARD -->
    <div class="table-card" id="dendaTableCard" style="transition: opacity 0.2s ease;">
        <div class="table-container">
            <table class="denda-table">
                <thead>
                    <tr>
                        <th style="width: 45px;">#</th>
                        <th>No. Tagihan</th>
                        <th>Anggota Peminjam</th>
                        <th>Buku Terkait</th>
                        <th>Rincian Pelanggaran</th>
                        <th>Nominal Denda</th>
                        <th>Status & Pembayaran</th>
                        <th style="text-align: right; width: 190px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dendas as $d)
                        @php
                            $member = $d->pengembalian?->peminjaman?->member ?? $d->details->first()?->peminjaman?->member;
                            $buku = $d->pengembalian?->peminjaman?->details?->first()?->buku ?? $d->details->first()?->buku;
                            $pembayaranSukses = $d->pembayaran->firstWhere('status', 'Sukses') ?? $d->pembayaran->last();
                        @endphp
                        <tr>
                            <td style="color: #94a3b8; font-weight: 700;">
                                {{ ($dendas->currentPage() - 1) * $dendas->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <span class="invoice-tag">
                                    #DND-{{ str_pad($d->idDenda, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td>
                                <div class="member-cell">
                                    <div class="member-avatar">
                                        {{ strtoupper(substr($member->name ?? 'M', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="member-name">{{ $member->name ?? 'Anggota Perpustakaan' }}</div>
                                        <div class="member-email">{{ $member->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #1e293b; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $buku->judul ?? '-' }}">
                                    {{ $buku->judul ?? 'Buku Perpustakaan' }}
                                </div>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">
                                    Tgl Kembali: {{ $d->pengembalian?->tanggalKembali ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 12.5px; font-weight: 600; color: #334155; max-width: 220px;">
                                    {{ $d->jenisDenda }}
                                </div>
                            </td>
                            <td>
                                <span class="price-value {{ $d->status === 'Lunas' ? 'success' : 'danger' }}">
                                    Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @if ($d->status === 'Lunas')
                                    <span class="badge-status-denda lunas">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Lunas
                                    </span>
                                    <div class="status-subtext">
                                        Metode: {{ $pembayaranSukses->metode ?? 'Online/QRIS' }}
                                    </div>
                                @else
                                    <span class="badge-status-denda belum">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                        </svg>
                                        Belum Dibayar
                                    </span>
                                    <div class="status-subtext">
                                        Menunggu Pembayaran
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="action-group">
                                    @if ($d->status !== 'Lunas')
                                        {{-- Link Buka / Generate QRIS --}}
                                        <a href="{{ route('bayar.qr', $d->idDenda) }}"
                                           target="_blank"
                                           class="btn-action-qris"
                                           title="Generate & Tampilkan QRIS Pembayaran Online">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                                <rect x="3" y="3" width="7" height="7"></rect>
                                                <rect x="14" y="3" width="7" height="7"></rect>
                                                <rect x="14" y="14" width="7" height="7"></rect>
                                                <rect x="3" y="14" width="7" height="7"></rect>
                                            </svg>
                                            Generate QRIS
                                        </a>
                                    @else
                                        @php
                                            $notaTargetId = $pembayaranSukses?->idPembayaran ?? $d->idDenda;
                                        @endphp
                                        <a href="{{ route('pembayaran.nota', $notaTargetId) }}"
                                           target="_blank"
                                           class="btn-action-nota"
                                           title="Cetak Bukti Nota Pembayaran Resmi">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                                <rect x="6" y="14" width="12" height="8"></rect>
                                            </svg>
                                            Nota
                                        </a>

                                        <a href="{{ route('denda.show', $d->idDenda) }}"
                                           target="_blank"
                                           class="btn-action-nota"
                                           title="Lihat Tagihan Resmi">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                            </svg>
                                            Detail
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 48px 20px;">
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px;">
                                    <div style="width: 52px; height: 52px; border-radius: 50%; background: #f0fdfa; display: flex; align-items: center; justify-content: center; color: #0f766e;">
                                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                    </div>
                                    <div style="font-weight: 700; color: #334155; font-size: 15px;">Tidak Ada Tagihan Denda</div>
                                    <div style="font-size: 13px; color: #64748b; max-width: 400px;">
                                        @if(request()->filled('search') || request()->filled('status'))
                                            Tidak ada data tagihan denda yang cocok dengan kriteria pencarian Anda.
                                        @else
                                            Semua pengembalian buku tertib tanpa denda, atau belum ada tagihan denda yang tercatat.
                                        @endif
                                    </div>
                                    @if(request()->filled('search') || request()->filled('status'))
                                        <a href="{{ route('denda.index') }}" class="btn-filter-reset" style="margin-top: 6px;">
                                            Reset Filter Pencarian
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div style="font-size: 13px; color: #64748b;">
                Menampilkan <strong>{{ $dendas->firstItem() ?? 0 }}</strong> - <strong>{{ $dendas->lastItem() ?? 0 }}</strong> dari <strong>{{ $dendas->total() }}</strong> tagihan denda
            </div>
            <div>
                {{ $dendas->links() }}
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('dendaFilterForm');
        const searchInput = document.getElementById('dendaSearchInput');
        const statusSelect = document.getElementById('dendaStatusSelect');
        const resetWrapper = document.getElementById('dendaResetWrapper');
        const tableCard = document.getElementById('dendaTableCard');
        const statsGrid = document.getElementById('dendaStatsGrid');

        let searchDebounceTimer = null;
        let currentAbortController = null;

        function buildFilterUrl() {
            const url = new URL(form.action, window.location.origin);
            const searchVal = searchInput ? searchInput.value.trim() : '';
            const statusVal = statusSelect ? statusSelect.value : '';

            if (searchVal) {
                url.searchParams.set('search', searchVal);
            }
            if (statusVal) {
                url.searchParams.set('status', statusVal);
            }
            return url.toString();
        }

        function updateResetButtonVisibility() {
            const searchVal = searchInput ? searchInput.value.trim() : '';
            const statusVal = statusSelect ? statusSelect.value : '';
            if (resetWrapper) {
                resetWrapper.style.display = (searchVal || statusVal) ? 'inline-flex' : 'none';
            }
        }

        function fetchDendaData(url) {
            if (currentAbortController) {
                currentAbortController.abort();
            }
            currentAbortController = new AbortController();

            if (tableCard) {
                tableCard.style.opacity = '0.35';
                tableCard.style.pointerEvents = 'none';
            }
            if (statsGrid) {
                statsGrid.style.opacity = '0.7';
            }

            fetch(url, {
                signal: currentAbortController.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network error: ' + response.status);
                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newTableCard = doc.getElementById('dendaTableCard');
                if (newTableCard && tableCard) {
                    tableCard.innerHTML = newTableCard.innerHTML;
                }

                const newStatsGrid = doc.getElementById('dendaStatsGrid');
                if (newStatsGrid && statsGrid) {
                    statsGrid.innerHTML = newStatsGrid.innerHTML;
                }

                updateResetButtonVisibility();
                window.history.pushState({ path: url }, '', url);
                bindPaginationLinks();
            })
            .catch(err => {
                if (err.name === 'AbortError') return;
                console.warn('AJAX refresh gagal, memuat via submit form biasa:', err);
                form.submit();
            })
            .finally(() => {
                if (tableCard) {
                    tableCard.style.opacity = '1';
                    tableCard.style.pointerEvents = 'auto';
                }
                if (statsGrid) {
                    statsGrid.style.opacity = '1';
                }
            });
        }

        function bindPaginationLinks() {
            if (!tableCard) return;
            const paginationLinks = tableCard.querySelectorAll('.pagination a, .table-footer a');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = link.getAttribute('href');
                    if (href && !href.startsWith('#')) {
                        e.preventDefault();
                        fetchDendaData(href);
                    }
                });
            });
        }

        // Auto refresh saat opsi dropdown status dipilih
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                clearTimeout(searchDebounceTimer);
                updateResetButtonVisibility();
                fetchDendaData(buildFilterUrl());
            });
        }

        // Live search otomatis refresh tabel (debounce 350ms)
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchDebounceTimer);
                updateResetButtonVisibility();
                searchDebounceTimer = setTimeout(() => {
                    fetchDendaData(buildFilterUrl());
                }, 350);
            });
        }

        // Cegah full-page submit saat tekan Enter
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                clearTimeout(searchDebounceTimer);
                fetchDendaData(buildFilterUrl());
            });
        }

        // Event listener klik tombol reset
        document.addEventListener('click', function(e) {
            const resetBtn = e.target.closest('.btn-filter-reset');
            if (resetBtn && resetBtn.getAttribute('href')) {
                e.preventDefault();
                if (searchInput) searchInput.value = '';
                if (statusSelect) statusSelect.value = '';
                updateResetButtonVisibility();
                fetchDendaData(resetBtn.getAttribute('href'));
            }
        });

        window.addEventListener('popstate', function() {
            window.location.reload();
        });

        bindPaginationLinks();
        updateResetButtonVisibility();
    });
</script>
@endsection
