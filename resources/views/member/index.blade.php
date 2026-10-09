@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.petugas')

@section('title', 'Data Anggota Perpustakaan - BOOKNEST')

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
        max-width: 650px;
    }

    /* --- STATS SUMMARY GRID --- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
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

    .stat-icon.primary { background: #f0fdfa; color: #0f766e; }
    .stat-icon.success { background: #dcfce7; color: #166534; }
    .stat-icon.danger { background: #fee2e2; color: #991b1b; }
    .stat-icon.purple { background: #f3e8ff; color: #7e22ce; }

    .stat-meta {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        line-height: 1.1;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted, #64748b);
        margin-top: 2px;
    }

    /* --- TOOLBAR FILTER & SEARCH --- */
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
        flex: 1;
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
        min-width: 240px;
        max-width: 420px;
    }

    .search-input-wrapper svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border-radius: 8px;
        border: 1px solid var(--border-color, #e2e8f0);
        font-size: 13px;
        font-family: inherit;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .search-input:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
    }

    .filter-select {
        padding: 9px 12px;
        border-radius: 8px;
        border: 1px solid var(--border-color, #e2e8f0);
        font-size: 13px;
        font-family: inherit;
        background: #ffffff;
        color: #334155;
        outline: none;
        cursor: pointer;
    }

    .filter-select:focus {
        border-color: #0f766e;
    }

    .btn-filter-submit {
        padding: 9px 16px;
        background: #0f766e;
        color: #ffffff;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s;
    }

    .btn-filter-submit:hover {
        background: #115e59;
    }

    .btn-filter-reset {
        padding: 9px 14px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.15s;
    }

    .btn-filter-reset:hover {
        background: #e2e8f0;
    }

    /* --- DATA TABLE CARD --- */
    .table-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .member-table {
        width: 100%;
        min-width: 820px;
        border-collapse: collapse;
        text-align: left;
    }

    .member-table th {
        background: #f8fafc;
        padding: 13px 18px;
        font-size: 11.5px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-color, #e2e8f0);
    }

    .member-table td {
        padding: 15px 18px;
        font-size: 13.5px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .member-table tr:hover td {
        background-color: #f8fafc;
    }

    /* Member Identity Cell */
    .member-identity {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .member-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
        color: #ffffff;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 5px rgba(15, 118, 110, 0.15);
    }

    .member-info-name {
        font-weight: 700;
        color: var(--text-heading, #0f172a);
        font-size: 14px;
        line-height: 1.3;
    }

    .member-info-email {
        font-size: 12px;
        color: var(--text-muted, #64748b);
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 1px;
    }

    /* Badges */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-status.aktif {
        background: #dcfce7;
        color: #166534;
    }

    .badge-status.nonaktif {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }

    .status-dot.aktif { background: #16a34a; box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2); }
    .status-dot.nonaktif { background: #dc2626; box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.2); }

    /* Action Buttons */
    .action-group {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-action-qr {
        background: #f0fdfa;
        color: #0f766e;
        border: 1px solid #99f6e4;
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

    .btn-action-qr:hover {
        background: #0f766e;
        color: #ffffff;
        border-color: #0f766e;
    }

    .btn-action-edit {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
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

    .btn-action-edit:hover {
        background: #d97706;
        color: #ffffff;
        border-color: #d97706;
    }

    .btn-action-toggle {
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: none;
        transition: all 0.15s;
    }

    .btn-action-toggle.deactivate {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .btn-action-toggle.deactivate:hover {
        background: #dc2626;
        color: #ffffff;
    }

    .btn-action-toggle.activate {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .btn-action-toggle.activate:hover {
        background: #16a34a;
        color: #ffffff;
    }

    .badge-staff-readonly {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        background: #f1f5f9;
        padding: 5px 8px;
        border-radius: 6px;
    }

    .btn-create-member {
        padding: 10px 18px;
        background: #0f766e;
        color: #ffffff;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 4px rgba(15, 118, 110, 0.2);
        transition: background 0.15s, transform 0.1s;
    }

    .btn-create-member:hover {
        background: #115e59;
        transform: translateY(-1px);
    }

    .badge-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        background: #f0fdfa;
        color: #0f766e;
        border: 1px solid #ccfbf1;
    }

    /* Flash Alerts */
    .flash-alert {
        padding: 14px 18px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .flash-alert.success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
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
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-card {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input-wrapper {
            max-width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="member-page">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span>Direktori Keanggotaan</span>
            </div>
            <h1 class="page-title">Data Anggota Perpustakaan</h1>
            <p class="page-subtitle">
                @if (auth()->user()->role === 'admin')
                    Kelola master data anggota perpustakaan, buat akun anggota baru, serta pengawasan status hak akses peminjaman.
                @else
                    Daftar anggota resmi perpustakaan untuk verifikasi identitas di meja sirkulasi serta pencetakan Kartu Anggota QR.
                @endif
            </p>
        </div>

        <div>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('member.create') }}" class="btn-create-member">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Anggota Baru
                </a>
            @else
                <div class="badge-role-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    Akses Petugas: Verifikasi & Cetak QR
                </div>
            @endif
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    @if (session('success'))
        <div class="flash-alert success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- SUMMARY STATS GRID -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $totalMember ?? $members->total() }}</span>
                <span class="stat-label">Total Anggota</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $memberAktif ?? $members->where('status', 'aktif')->count() }}</span>
                <span class="stat-label">Anggota Aktif</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon danger">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $memberNonaktif ?? $members->where('status', 'nonaktif')->count() }}</span>
                <span class="stat-label">Nonaktif / Suspend</span>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="filter-card">
        <form action="{{ route('member.index') }}" method="GET" class="filter-form">
            <div class="search-input-wrapper">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text"
                       name="search"
                       class="search-input"
                       placeholder="Cari nama, email, telepon, alamat..."
                       value="{{ request('search') }}">
            </div>

            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif Saja</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif Saja</option>
            </select>

            <button type="submit" class="btn-filter-submit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                Cari & Filter
            </button>

            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('member.index') }}" class="btn-filter-reset">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- DATA TABLE CARD -->
    <div class="table-card">
        <div class="table-container">
            <table class="member-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Anggota</th>
                        <th>Kontak & Alamat</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th style="text-align: right; width: 260px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $m)
                        <tr>
                            <td style="color: #94a3b8; font-weight: 700;">
                                {{ ($members->currentPage() - 1) * $members->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="member-identity">
                                    <div class="member-avatar">
                                        {{ strtoupper(substr($m->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="member-info-name">{{ $m->name }}</div>
                                        <div class="member-info-email">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                                <polyline points="22,6 12,13 2,6"></polyline>
                                            </svg>
                                            {{ $m->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 3px;">
                                    <div style="font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 5px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #0f766e;">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                        </svg>
                                        {{ $m->noTelepon ?? '-' }}
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $m->alamat }}">
                                        {{ $m->alamat ?? '-' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status {{ $m->status === 'aktif' ? 'aktif' : 'nonaktif' }}">
                                    <span class="status-dot {{ $m->status === 'aktif' ? 'aktif' : 'nonaktif' }}"></span>
                                    {{ ucfirst($m->status) }}
                                </span>
                            </td>
                            <td style="color: #64748b; font-size: 12.5px;">
                                {{ $m->created_at ? $m->created_at->format('d M Y') : '-' }}
                            </td>
                            <td>
                                <div class="action-group" style="justify-content: flex-end;">
                                    {{-- Cetak Kartu QR: Bisa diakses Petugas & Admin --}}
                                    <a href="{{ route('member.cetak-qr', $m->id) }}"
                                       target="_blank"
                                       class="btn-action-qr"
                                       title="Cetak Kartu QR Anggota">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="7" height="7"></rect>
                                            <rect x="14" y="3" width="7" height="7"></rect>
                                            <rect x="14" y="14" width="7" height="7"></rect>
                                            <rect x="3" y="14" width="7" height="7"></rect>
                                        </svg>
                                        Kartu QR
                                    </a>

                                    @if (auth()->user()->role === 'admin')
                                        {{-- Tombol Ubah: Khusus Admin --}}
                                        <a href="{{ route('member.edit', $m->id) }}"
                                           class="btn-action-edit"
                                           title="Edit Profil Anggota">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Ubah
                                        </a>

                                        {{-- Toggle Status: Khusus Admin --}}
                                        <form action="{{ route('member.toggle-status', $m->id) }}"
                                              method="POST"
                                              style="display: inline; margin: 0;"
                                              onsubmit="return confirm('Apakah Anda yakin ingin mengubah status keanggotaan {{ $m->name }}?');">
                                            @csrf
                                            @method('PATCH')
                                            @if ($m->status === 'aktif')
                                                <button type="submit" class="btn-action-toggle deactivate" title="Nonaktifkan Keanggotaan">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                                                    </svg>
                                                    Nonaktifkan
                                                </button>
                                            @else
                                                <button type="submit" class="btn-action-toggle activate" title="Aktifkan Kembali Keanggotaan">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                    Aktifkan
                                                </button>
                                            @endif
                                        </form>
                                    @else
                                        {{-- Badge Petugas Read-Only Info --}}
                                        <span class="badge-staff-readonly" title="Pengubahan data hanya dapat dilakukan oleh Administrator">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                            </svg>
                                            Verifikasi OK
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 48px 20px;">
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px;">
                                    <div style="width: 52px; height: 52px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                    </div>
                                    <div style="font-weight: 700; color: #334155; font-size: 15px;">Tidak Ada Anggota Ditemukan</div>
                                    <div style="font-size: 13px; color: #64748b; max-width: 380px;">
                                        @if(request()->filled('search') || request()->filled('status'))
                                            Kriteria pencarian tidak cocok dengan data anggota yang tersimpan. Coba reset filter.
                                        @else
                                            Belum ada data anggota yang terdaftar di sistem perpustakaan.
                                        @endif
                                    </div>
                                    @if(request()->filled('search') || request()->filled('status'))
                                        <a href="{{ route('member.index') }}" class="btn-filter-reset" style="margin-top: 6px;">
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
                Menampilkan <strong>{{ $members->firstItem() ?? 0 }}</strong> - <strong>{{ $members->lastItem() ?? 0 }}</strong> dari <strong>{{ $members->total() }}</strong> anggota
            </div>
            <div>
                {{ $members->links() }}
            </div>
        </div>
    </div>
</div>
@endsection