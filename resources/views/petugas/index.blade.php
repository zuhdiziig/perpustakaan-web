@extends('layouts.admin')

@section('title', 'Kelola Data Petugas - BOOKNEST')

@section('styles')
<style>
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

    .btn-add-petugas {
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
        transition: background 0.15s;
    }

    .btn-add-petugas:hover {
        background: #115e59;
    }

    /* STATS */
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
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0fdfa;
        color: #0f766e;
        flex-shrink: 0;
    }

    .stat-meta {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }

    /* SEARCH FILTER */
    .filter-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 14px 18px;
        margin-bottom: 20px;
    }

    .filter-form {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 240px;
    }

    .search-input-wrap svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input-field {
        width: 100%;
        padding: 9px 14px 9px 40px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
    }

    .search-input-field:focus {
        border-color: #0f766e;
    }

    .btn-filter-submit {
        padding: 9px 18px;
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-filter-reset {
        padding: 9px 14px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    /* CARD TABLE */
    .card-panel {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .card-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 16px;
    }

    .table-container {
        overflow-x: auto;
    }

    .petugas-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .petugas-table th {
        text-align: left;
        padding: 12px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 700;
        color: #475569;
    }

    .petugas-table td {
        padding: 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .petugas-table tr:hover {
        background: #f8fafc;
    }

    .petugas-user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .petugas-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #ccfbf1;
        color: #0f766e;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
        overflow: hidden;
    }

    .badge-role-petugas {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        background: #f0fdfa;
        color: #0f766e;
    }

    .action-group {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
    }

    .btn-edit-action {
        padding: 6px 12px;
        background: #f1f5f9;
        color: #0f766e;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        transition: all 0.15s;
    }

    .btn-edit-action:hover {
        background: #ccfbf1;
        border-color: #99f6e4;
    }

    .btn-delete-action {
        padding: 6px 12px;
        background: #fee2e2;
        color: #dc2626;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #fecaca;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-delete-action:hover {
        background: #dc2626;
        color: #ffffff;
    }

    @media (max-width: 900px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="petugas-container">
    <!-- HEADER -->
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Admin / Pengguna
            </div>
            <h1 class="page-title">Kelola Akun Petugas</h1>
            <p class="page-subtitle">
                Kelola hak akses dan staf operasional meja sirkulasi perpustakaan BOOKNEST.
            </p>
        </div>
        <a href="{{ route('petugas.create') }}" class="btn-add-petugas">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Petugas
        </a>
    </div>

    <!-- FLASH MESSAGES -->
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

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $petugas->total() }}</span>
                <span class="stat-label">Total Staf Petugas</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #dcfce7; color: #16a34a;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $petugas->where('status', 'aktif')->count() }}</span>
                <span class="stat-label">Akun Petugas Aktif</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #e0f2fe; color: #0284c7;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">Akses Penuh</span>
                <span class="stat-label">Hak Operasional Sirkulasi</span>
            </div>
        </div>
    </div>

    <!-- FILTER SEARCH -->
    <div class="filter-card">
        <form action="{{ route('petugas.index') }}" method="GET" class="filter-form">
            <div class="search-input-wrap">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text"
                       name="search"
                       class="search-input-field"
                       placeholder="Cari nama, email, telepon, atau alamat petugas..."
                       value="{{ request('search') }}">
            </div>

            <button type="submit" class="btn-filter-submit">Cari Petugas</button>

            @if(request()->filled('search'))
                <a href="{{ route('petugas.index') }}" class="btn-filter-reset">Reset</a>
            @endif
        </form>
    </div>

    <!-- TABLE -->
    <div class="card-panel">
        <h3 class="card-title">Daftar Akun Petugas Perpustakaan ({{ $petugas->total() }})</h3>
        <div class="table-container">
            <table class="petugas-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Profil Petugas</th>
                        <th>Email & Kontak</th>
                        <th>Alamat Domisili</th>
                        <th>Peran & Status</th>
                        <th style="text-align: right; width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($petugas as $p)
                        <tr>
                            <td style="color: #94a3b8; font-weight: 700;">
                                {{ ($petugas->currentPage() - 1) * $petugas->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="petugas-user-cell">
                                    <div class="petugas-avatar">
                                        @if($p->foto)
                                            <img src="{{ asset('storage/' . $p->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            {{ $p->inisial ?? 'PT' }}
                                        @endif
                                    </div>
                                    <div>
                                        <strong style="color: #0f172a; font-size: 13.5px;">{{ $p->name }}</strong>
                                        <div style="font-size: 11px; color: #64748b;">ID: #PTG-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #334155;">{{ $p->email }}</div>
                                <div style="font-size: 11.5px; color: #64748b;">{{ $p->noTelepon ?: 'Belum diatur' }}</div>
                            </td>
                            <td style="color: #64748b; max-width: 240px;">
                                {{ $p->alamat ?: '-' }}
                            </td>
                            <td>
                                <span class="badge-role-petugas">
                                    🛡️ Petugas Meja Sirkulasi
                                </span>
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('petugas.edit', $p->id) }}" class="btn-edit-action" title="Ubah Akun Petugas">
                                        Edit
                                    </a>
                                    <form action="{{ route('petugas.destroy', $p->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun petugas {{ $p->name }}?');"
                                          style="display: inline; margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete-action" title="Hapus Petugas">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 36px; color: #94a3b8;">
                                Belum ada data petugas yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 18px;">
            {{ $petugas->links() }}
        </div>
    </div>
</div>
@endsection