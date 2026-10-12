@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.petugas')

@section('title', 'Sirkulasi Buku - BOOKNEST')

@section('styles')
<style>
    .sirkulasi-page {
        width: 100%;
        max-width: 1240px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .sirkulasi-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .sirkulasi-title-group {
        flex: 1;
        min-width: 280px;
    }

    .sirkulasi-heading {
        margin: 0;
        font-size: 26px;
        line-height: 1.25;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
    }

    .sirkulasi-subtitle {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 13.5px;
        line-height: 1.5;
    }

    .sirkulasi-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border-radius: 10px;
        background: #0f766e;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(15, 118, 110, 0.2);
    }

    .btn-action-primary:hover {
        background: #115e59;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(15, 118, 110, 0.25);
    }

    .btn-action-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: #ffffff;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-action-secondary:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        transform: translateY(-1px);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .stat-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon-amber { background: #fef3c7; color: #b45309; }
    .stat-icon-teal { background: #ccfbf1; color: #0f766e; }
    .stat-icon-emerald { background: #dcfce7; color: #15803d; }
    .stat-icon-blue { background: #dbeafe; color: #1e40af; }

    .stat-number {
        font-size: 22px;
        font-weight: 800;
        line-height: 1.2;
        color: #0f172a;
        letter-spacing: -0.5px;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-top: 2px;
    }

    /* Nav Tabs & Filter Card */
    .filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .nav-pills-sirkulasi {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
    }

    .nav-pill-btn {
        padding: 8px 16px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        text-decoration: none;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .nav-pill-btn.active {
        background: #ffffff;
        color: #0f766e;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .search-filter-form {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-input-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        padding: 8px 14px;
        border-radius: 10px;
    }

    .search-input-wrap input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 13px;
        color: #0f172a;
        width: 220px;
    }

    .filter-select {
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        outline: none;
    }

    .btn-search {
        padding: 8px 16px;
        border-radius: 10px;
        background: #0f766e;
        color: #ffffff;
        border: none;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-reset-filter {
        font-size: 12.5px;
        font-weight: 600;
        color: #64748b;
        text-decoration: underline;
    }

    /* Table Card */
    .table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .pagination-footer {
        padding: 16px 20px;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .sirkulasi-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .sirkulasi-table th {
        background: #f8fafc;
        padding: 14px 18px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }

    .sirkulasi-table td {
        padding: 16px 18px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #334155;
    }

    .sirkulasi-table tr:hover td {
        background: #fcfdfe;
    }

    .trx-badge {
        font-family: monospace;
        font-size: 12px;
        font-weight: 700;
        color: #0f766e;
        background: #ccfbf1;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .ret-badge {
        font-family: monospace;
        font-size: 12px;
        font-weight: 700;
        color: #2563eb;
        background: #dbeafe;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .user-meta-name {
        font-weight: 700;
        color: #0f172a;
    }

    .user-meta-sub {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 2px;
    }

    .book-list-item {
        margin-bottom: 3px;
        font-weight: 600;
        color: #1e293b;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 11.5px;
    }

    .badge-dipinjam { background: #fef3c7; color: #b45309; }
    .badge-selesai { background: #dcfce7; color: #15803d; }
    .badge-booking { background: #f1f5f9; color: #475569; }
    .badge-terlambat { background: #fee2e2; color: #dc2626; }

    .btn-detail-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #0f766e;
        font-weight: 700;
        font-size: 12px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-detail-link:hover {
        background: #ccfbf1;
        color: #115e59;
    }

    .pagination-footer {
        padding: 16px 20px;
        border-top: 1px solid #f1f5f9;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: #64748b;
    }

    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 576px) {
        .stats-grid { grid-template-columns: 1fr; }
        .sirkulasi-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection

@section('content')
<div class="sirkulasi-page">

    {{-- HEADER --}}
    <div class="sirkulasi-header">
        <div class="sirkulasi-title-group">
            <h1 class="sirkulasi-heading">Sirkulasi Buku</h1>
            <p class="sirkulasi-subtitle">
                Pantau seluruh aktivitas transaksi peminjaman & pengembalian buku di meja sirkulasi perpustakaan.
            </p>
        </div>

        <div class="sirkulasi-actions">
            <a href="{{ route('barcode.scan') }}" class="btn-action-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                    <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                    <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                    <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                    <line x1="7" y1="12" x2="17" y2="12"></line>
                </svg>
                <span>Meja Sirkulasi (Scan QR)</span>
            </a>
            <a href="{{ route('peminjaman.create') }}" class="btn-action-secondary">
                <span>+ Peminjaman</span>
            </a>
            <a href="{{ route('pengembalian.create') }}" class="btn-action-secondary">
                <span>+ Pengembalian</span>
            </a>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-box stat-icon-amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalPinjamAktif }}</div>
                <div class="stat-label">Sedang Dipinjam</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box stat-icon-teal">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="16 3 21 3 21 8"></polyline>
                    <line x1="4" y1="20" x2="21" y2="3"></line>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalPinjamHariIni }}</div>
                <div class="stat-label">Peminjaman Hari Ini</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box stat-icon-emerald">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                    <path d="M21 3v5h-5"></path>
                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                    <path d="M3 21v-5h5"></path>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalKembaliHariIni }}</div>
                <div class="stat-label">Pengembalian Hari Ini</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box stat-icon-blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalAntreanBooking }}</div>
                <div class="stat-label">Antrean Booking</div>
            </div>
        </div>
    </div>

    {{-- FILTER & NAV TABS CARD --}}
    <div class="filter-card">
        <div class="nav-pills-sirkulasi">
            <a href="{{ route('sirkulasi.index', array_merge(request()->query(), ['tab' => 'peminjaman'])) }}"
               class="nav-pill-btn {{ $tab === 'peminjaman' ? 'active' : '' }}">
                <span>📋 Transaksi Peminjaman</span>
                <span style="font-size: 11px; background: rgba(0,0,0,0.06); padding: 1px 6px; border-radius: 999px;">{{ $peminjamans->total() }}</span>
            </a>
            <a href="{{ route('sirkulasi.index', array_merge(request()->query(), ['tab' => 'pengembalian'])) }}"
               class="nav-pill-btn {{ $tab === 'pengembalian' ? 'active' : '' }}">
                <span>🔄 Transaksi Pengembalian</span>
                <span style="font-size: 11px; background: rgba(0,0,0,0.06); padding: 1px 6px; border-radius: 999px;">{{ $pengembalians->total() }}</span>
            </a>
        </div>

        <form action="{{ route('sirkulasi.index') }}" method="GET" class="search-filter-form">
            <input type="hidden" name="tab" value="{{ $tab }}">

            <div class="search-input-wrap">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari #TRX, nama, judul buku...">
            </div>

            @if($tab === 'peminjaman')
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Dipinjam" {{ $status === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="Selesai" {{ $status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Booking" {{ $status === 'Booking' ? 'selected' : '' }}>Booking</option>
                    <option value="Siap Diambil" {{ $status === 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil</option>
                </select>
            @endif

            <button type="submit" class="btn-search">Cari</button>

            @if($q || $status)
                <a href="{{ route('sirkulasi.index', ['tab' => $tab]) }}" class="btn-reset-filter">Reset</a>
            @endif
        </form>
    </div>

    {{-- TAB 1: TABEL PEMINJAMAN --}}
    @if($tab === 'peminjaman')
        <div class="table-card">
            <div class="table-responsive">
                <table class="sirkulasi-table">
                    <thead>
                        <tr>
                            <th style="width: 140px;">No. Transaksi</th>
                            <th style="width: 200px;">Peminjam</th>
                            <th style="width: 150px;">Petugas</th>
                            <th>Buku yang Dipinjam</th>
                            <th style="width: 130px;">Tgl Pinjam</th>
                            <th style="width: 130px;">Jatuh Tempo</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 110px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $item)
                            <tr>
                                <td>
                                    <span class="trx-badge">
                                        #TRX-{{ str_pad($item->idPeminjaman, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                    @if($item->kode_booking)
                                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                            {{ $item->kode_booking }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="user-meta-name">{{ $item->member->name ?? 'Member' }}</div>
                                    <div class="user-meta-sub">{{ $item->member->email ?? '-' }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #475569;">{{ $item->petugas->name ?? '-' }}</div>
                                </td>
                                <td>
                                    @foreach($item->details as $d)
                                        <div class="book-list-item">
                                            • {{ $d->buku->judul ?? 'Buku' }}
                                            <span style="font-size: 11px; color: #64748b; font-weight: normal;">
                                                (#{{ $d->eksemplar->nomor_eksemplar ?? '1' }})
                                            </span>
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    <span style="font-weight: 600;">
                                        {{ \Carbon\Carbon::parse($item->tanggalPinjam)->translatedFormat('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-weight: 600; {{ $item->status === 'Dipinjam' && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($item->batasKembali)) ? 'color: #dc2626;' : '' }}">
                                        {{ \Carbon\Carbon::parse($item->batasKembali)->translatedFormat('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->status === 'Dipinjam')
                                        <span class="badge-status badge-dipinjam">Dipinjam</span>
                                    @elseif($item->status === 'Selesai')
                                        <span class="badge-status badge-selesai">Selesai</span>
                                    @elseif(in_array($item->status, ['Booking', 'Siap Diambil']))
                                        <span class="badge-status badge-booking">{{ $item->status }}</span>
                                    @else
                                        <span class="badge-status badge-booking">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('peminjaman.show', $item->idPeminjaman) }}" class="btn-detail-link">
                                        <span>Detail &rarr;</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty-state">
                                    Tidak ada data transaksi peminjaman ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($peminjamans->hasPages())
                <div class="pagination-footer">
                    <div style="font-size: 13px; color: #64748b;">
                        Menampilkan <strong>{{ $peminjamans->firstItem() ?? 0 }}</strong> - <strong>{{ $peminjamans->lastItem() ?? 0 }}</strong> dari <strong>{{ $peminjamans->total() }}</strong> transaksi
                    </div>
                    <div>
                        {{ $peminjamans->links() }}
                    </div>
                </div>
            @endif
        </div>
    @else
    {{-- TAB 2: TABEL PENGEMBALIAN --}}
        <div class="table-card">
            <div class="table-responsive">
                <table class="sirkulasi-table">
                    <thead>
                        <tr>
                            <th style="width: 140px;">No. Return</th>
                            <th style="width: 140px;">Ref. Pinjam</th>
                            <th style="width: 200px;">Peminjam</th>
                            <th style="width: 150px;">Petugas Penerima</th>
                            <th>Buku yang Dikembalikan</th>
                            <th style="width: 130px;">Tgl Kembali</th>
                            <th style="width: 110px;">Kondisi</th>
                            <th style="width: 130px;">Denda</th>
                            <th style="width: 110px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengembalians as $ret)
                            <tr>
                                <td>
                                    <span class="ret-badge">
                                        #RET-{{ str_pad($ret->idPengembalian, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="trx-badge">
                                        #TRX-{{ str_pad($ret->idPeminjaman, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="user-meta-name">{{ $ret->peminjaman->member->name ?? '-' }}</div>
                                    <div class="user-meta-sub">{{ $ret->peminjaman->member->email ?? '-' }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #475569;">{{ $ret->petugas->name ?? '-' }}</div>
                                </td>
                                <td>
                                    @php
                                        $retDetails = $ret->peminjaman->details ?? collect();
                                    @endphp
                                    @foreach($retDetails as $d)
                                        <div class="book-list-item">
                                            • {{ $d->buku->judul ?? 'Buku' }}
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    <span style="font-weight: 600;">
                                        {{ \Carbon\Carbon::parse($ret->tanggalKembali)->translatedFormat('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-weight: 700; color: {{ $ret->kondisiBuku === 'Baik' ? '#15803d' : '#dc2626' }};">
                                        {{ $ret->kondisiBuku }}
                                    </span>
                                </td>
                                <td>
                                    @if($ret->denda)
                                        <strong style="color: #dc2626;">Rp {{ number_format($ret->denda->jumlah, 0, ',', '.') }}</strong>
                                        <div style="font-size: 11px; color: {{ $ret->denda->status === 'Lunas' ? '#15803d' : '#b45309' }}; font-weight: 700;">
                                            {{ $ret->denda->status }}
                                        </div>
                                    @else
                                        <span style="color: #15803d; font-weight: 600;">Rp 0 (Bebas Denda)</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('pengembalian.show', $ret->idPengembalian) }}" class="btn-detail-link">
                                        <span>Detail &rarr;</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="empty-state">
                                    Tidak ada data transaksi pengembalian ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pengembalians->hasPages())
                <div class="pagination-footer">
                    <div style="font-size: 13px; color: #64748b;">
                        Menampilkan <strong>{{ $pengembalians->firstItem() ?? 0 }}</strong> - <strong>{{ $pengembalians->lastItem() ?? 0 }}</strong> dari <strong>{{ $pengembalians->total() }}</strong> transaksi
                    </div>
                    <div>
                        {{ $pengembalians->links() }}
                    </div>
                </div>
            @endif
        </div>
    @endif

</div>
@endsection
