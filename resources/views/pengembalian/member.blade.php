@extends('layouts.anggota')

@section('title', 'Pengembalian Buku - BOOKNEST')

@section('styles')
<style>
    .pengembalian-page {
        width: 100%;
        max-width: 1240px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    /* --- PAGE HEADER --- */
    .pengembalian-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .pengembalian-title-group {
        flex: 1;
        min-width: 280px;
    }

    .pengembalian-heading {
        margin: 0;
        font-size: 26px;
        line-height: 1.25;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pengembalian-subtitle {
        margin: 6px 0 0;
        color: var(--text-muted);
        font-size: 13.5px;
        line-height: 1.5;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-action-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        background: #ffffff;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .btn-action-outline:hover {
        border-color: #cbd5e1;
        color: var(--brand-primary);
        background: #f8fafc;
        transform: translateY(-1px);
    }

    /* --- ALERTS --- */
    .alert-banner {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 22px;
        font-size: 13.5px;
        font-weight: 500;
        animation: fadeIn 0.2s ease;
    }

    .alert-banner.success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }

    .alert-banner.error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    /* --- STATS SUMMARY CARDS --- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
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

    .stat-icon-amber {
        background: #fef3c7;
        color: #b45309;
    }

    .stat-icon-emerald {
        background: #dcfce7;
        color: #15803d;
    }

    .stat-icon-teal {
        background: #ccfbf1;
        color: #0f766e;
    }

    .stat-icon-rose {
        background: #ffe4e6;
        color: #e11d48;
    }

    .stat-number {
        font-size: 22px;
        font-weight: 800;
        line-height: 1.2;
        color: var(--text-heading);
        letter-spacing: -0.5px;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        margin-top: 2px;
    }

    /* --- FILTER TABS BAR --- */
    .filter-tabs-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 12px;
        width: fit-content;
        max-width: 100%;
        overflow-x: auto;
    }

    .filter-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-muted);
        text-decoration: none;
        transition: all 0.15s ease;
        white-space: nowrap;
    }

    .filter-tab-btn:hover {
        color: var(--text-heading);
        background: rgba(255, 255, 255, 0.6);
    }

    .filter-tab-btn.active {
        background: #ffffff;
        color: var(--brand-primary);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .filter-badge-count {
        padding: 2px 7px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 800;
        background: #e2e8f0;
        color: #475569;
    }

    .filter-tab-btn.active .filter-badge-count {
        background: var(--brand-primary-light);
        color: var(--brand-primary);
    }

    /* --- MAIN CONTENT CARD --- */
    .main-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .main-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .main-card-title {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--text-heading);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* --- DATA TABLE --- */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        text-align: left;
    }

    .data-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 700;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }

    .data-table td {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        color: var(--text-body);
    }

    .data-table tbody tr {
        transition: background 0.1s ease;
    }

    .data-table tbody tr:hover {
        background: #fbfcfd;
    }

    .data-table tbody tr.highlighted {
        background: #f0fdfa;
        box-shadow: inset 3px 0 0 #0f766e;
    }

    /* --- BADGES & LABELS --- */
    .trx-badge {
        font-family: monospace;
        font-size: 11.5px;
        font-weight: 700;
        color: #475569;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        display: inline-block;
        white-space: nowrap;
    }

    .book-item-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .book-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #ccfbf1;
        color: #0f766e;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .book-title-name {
        font-weight: 700;
        color: var(--text-heading);
        font-size: 13.5px;
        line-height: 1.35;
        display: block;
    }

    .book-meta-sub {
        font-size: 12px;
        color: var(--text-muted);
        display: block;
        margin-top: 2px;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-status.ontime {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-status.overdue {
        background: #ffe4e6;
        color: #e11d48;
    }

    .badge-status.returned {
        background: #ccfbf1;
        color: #0f766e;
    }

    .fine-tag-danger {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 700;
        color: #e11d48;
        background: #ffe4e6;
        padding: 2px 7px;
        border-radius: 6px;
        margin-top: 4px;
    }

    .fine-tag-free {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        color: #16a34a;
        margin-top: 3px;
    }

    .badge-status-waiting {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 700;
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    /* --- ACTION BUTTONS --- */
    .btn-return-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 13px;
        border-radius: 8px;
        background: #0f766e;
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(15, 118, 110, 0.2);
    }

    .btn-return-action:hover {
        background: #115e59;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(15, 118, 110, 0.3);
    }

    .btn-ticket-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 13px;
        border-radius: 8px;
        background: #0f766e;
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(15, 118, 110, 0.2);
    }

    .btn-ticket-action:hover {
        background: #115e59;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(15, 118, 110, 0.3);
    }

    .btn-qr-desk {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        font-size: 12.5px;
        font-weight: 700;
        border: 1px solid #cbd5e1;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-qr-desk:hover {
        background: #e2e8f0;
        color: var(--text-heading);
        border-color: #94a3b8;
    }

    .btn-view-receipt {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        background: #f0fdfa;
        color: #0f766e;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid #99f6e4;
        transition: all 0.15s;
    }

    .btn-view-receipt:hover {
        background: #ccfbf1;
        color: #115e59;
    }

    /* --- EMPTY STATE --- */
    .empty-state {
        text-align: center;
        padding: 56px 24px;
    }

    .empty-icon-wrap {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .empty-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 6px;
    }

    .empty-desc {
        font-size: 13.5px;
        color: var(--text-muted);
        max-width: 440px;
        margin: 0 auto 20px;
        line-height: 1.5;
    }

    /* --- MODAL DIALOG --- */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        z-index: 100;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-overlay.show {
        display: flex;
        animation: fadeIn 0.15s ease;
    }

    .modal-card {
        background: #ffffff;
        border-radius: 18px;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        border: 1px solid var(--border-color);
        animation: scaleUp 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text-heading);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-close-modal {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px;
        border-radius: 6px;
        transition: color 0.15s;
    }

    .btn-close-modal:hover {
        color: var(--text-heading);
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: #f8fafc;
    }

    .book-summary-box {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 6px;
    }

    .form-select {
        width: 100%;
        padding: 10px 14px;
        border-radius: 9px;
        border: 1px solid #cbd5e1;
        font-size: 13.5px;
        font-family: inherit;
        color: var(--text-body);
        background: #ffffff;
        outline: none;
    }

    .form-select:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
    }

    .checkbox-wrap {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 12.5px;
        color: #166534;
        line-height: 1.45;
    }

    .checkbox-wrap input {
        margin-top: 2px;
        cursor: pointer;
    }

    .qr-card-center {
        text-align: center;
        padding: 10px 0;
    }

    .qr-box-inner {
        width: 180px;
        height: 180px;
        margin: 0 auto 16px;
        background: #ffffff;
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qr-box-inner img, .qr-box-inner svg {
        width: 100%;
        height: 100%;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes scaleUp {
        from { transform: scale(0.96); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* --- BATCH RETURN STYLES --- */
    .bulk-action-bar {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 50;
        background: #0f172a;
        color: #ffffff;
        padding: 10px 18px;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        display: flex;
        align-items: center;
        gap: 16px;
        animation: scaleUp 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        max-width: 92%;
    }

    .bulk-bar-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        width: 100%;
    }

    .bulk-bar-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .bulk-badge-count {
        background: #0f766e;
        color: #ffffff;
        font-weight: 800;
        font-size: 13px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .bulk-bar-text {
        font-size: 13.5px;
        color: #cbd5e1;
    }

    .bulk-bar-text strong {
        color: #ffffff;
    }

    .bulk-bar-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-bulk-cancel {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        padding: 6px 10px;
        transition: color 0.15s;
    }

    .btn-bulk-cancel:hover {
        color: #ffffff;
    }

    .btn-bulk-submit {
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 9px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s;
    }

    .btn-bulk-submit:hover {
        background: #115e59;
        transform: translateY(-1px);
    }

    .modal-batch-card {
        max-width: 680px !important;
        width: 100%;
    }

    .batch-modal-item-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        transition: all 0.15s;
    }

    .batch-modal-item-card:hover {
        border-color: #cbd5e1;
    }

    .condition-selector-group {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .condition-option {
        cursor: pointer;
        position: relative;
    }

    .condition-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .condition-label-box {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        transition: all 0.15s ease;
        justify-content: center;
        text-align: center;
        height: 100%;
        box-sizing: border-box;
    }

    .condition-option input[type="radio"]:checked + .condition-label-box {
        font-weight: 800;
        transform: translateY(-1px);
    }

    .condition-option.opt-baik input[type="radio"]:checked + .condition-label-box {
        background: #ecfdf5;
        border-color: #10b981;
        color: #065f46;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.15);
    }

    .condition-option.opt-rusak input[type="radio"]:checked + .condition-label-box {
        background: #fffbeb;
        border-color: #f59e0b;
        color: #92400e;
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.15);
    }

    .condition-option.opt-hilang input[type="radio"]:checked + .condition-label-box {
        background: #fef2f2;
        border-color: #ef4444;
        color: #991b1b;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.15);
    }

    @media (max-width: 900px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 560px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .bulk-action-bar {
            width: calc(100% - 24px);
            max-width: 100%;
            bottom: 12px;
            padding: 8px 12px;
        }

        .condition-selector-group {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="pengembalian-page">

    {{-- PAGE HEADER --}}
    <div class="pengembalian-header">
        <div class="pengembalian-title-group">
            <h1 class="pengembalian-heading">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #0f766e;">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                    <path d="M21 3v5h-5"></path>
                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                    <path d="M3 21v-5h5"></path>
                </svg>
                Pengembalian Buku
            </h1>
            <p class="pengembalian-subtitle">
                Kelola pengembalian buku pinjaman, pantau jatuh tempo, denda, dan tanda bukti tanda terima pengembalian Anda.
            </p>
        </div>

        <div class="header-actions">
            <a href="{{ route('riwayat.index', ['status' => 'Dipinjam']) }}" class="btn-action-outline">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="16 3 21 3 21 8"></polyline>
                    <line x1="4" y1="20" x2="21" y2="3"></line>
                </svg>
                <span>Lihat Peminjaman Aktif</span>
            </a>
            <a href="{{ route('dashboard') }}" class="btn-action-outline">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Dasbor</span>
            </a>
        </div>
    </div>

    {{-- STATS SUMMARY ROW --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-box stat-icon-amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalPerluKembali }}</div>
                <div class="stat-label">Buku Siap Dikembalikan</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box stat-icon-emerald">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalSudahKembali }}</div>
                <div class="stat-label">Selesai Dikembalikan</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box stat-icon-teal">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalTepatWaktu }}</div>
                <div class="stat-label">Pengembalian Tepat Waktu</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box {{ $totalDendaAktif > 0 ? 'stat-icon-rose' : 'stat-icon-teal' }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div>
                <div class="stat-number" style="{{ $totalDendaAktif > 0 ? 'color: #e11d48;' : '' }}">{{ $totalDendaAktif }}</div>
                <div class="stat-label">Denda Belum Dibayar</div>
            </div>
        </div>
    </div>

    {{-- FILTER TABS BAR --}}
    <div class="filter-tabs-bar">
        <a href="{{ route('pengembalian.member', ['tab' => 'aktif']) }}" class="filter-tab-btn {{ $tab === 'aktif' ? 'active' : '' }}">
            <span>Buku Siap Dikembalikan</span>
            <span class="filter-badge-count">{{ $totalPerluKembali }}</span>
        </a>

        <a href="{{ route('pengembalian.member', ['tab' => 'riwayat']) }}" class="filter-tab-btn {{ $tab === 'riwayat' ? 'active' : '' }}">
            <span>Riwayat Pengembalian</span>
            <span class="filter-badge-count">{{ $totalSudahKembali }}</span>
        </a>

        <a href="{{ route('riwayat.index') }}" class="filter-tab-btn">
            <span>Semua Transaksi Peminjaman</span>
        </a>
    </div>

    {{-- TAB 1: BUKU SIAP DIKEMBALIKAN (AKTIF) --}}
    @if($tab === 'aktif')
        <div class="main-card">
            <div class="main-card-header" style="flex-wrap: wrap; gap: 12px;">
                <h3 class="main-card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #0f766e;">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    Daftar Buku Sedang Dipinjam yang Perlu Dikembalikan
                </h3>
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    @php
                        $bukuBisaKembali = $pinjamanAktif->where('statusBuku', 'Dipinjam')->count();
                    @endphp
                    @if($bukuBisaKembali > 1)
                        <button type="button" class="btn-return-action" onclick="openBatchReturnModalAll()" style="padding: 7px 14px; font-size: 12.5px; background: #0f766e;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            <span>Kembalikan Sekaligus ({{ $bukuBisaKembali }})</span>
                        </button>
                    @endif
                    <span style="font-size: 12.5px; color: var(--text-muted); font-weight: 600;">
                        Total: {{ $pinjamanAktif->count() }} buku aktif
                    </span>
                </div>
            </div>

            @if($pinjamanAktif->isNotEmpty())
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 44px; text-align: center;">
                                    <input type="checkbox" id="checkAllBooks" onchange="toggleSelectAllBooks(this)" title="Pilih Semua Buku" style="cursor: pointer; width: 17px; height: 17px; accent-color: #0f766e;">
                                </th>
                                <th style="width: 110px;">Kode TRX</th>
                                <th>Detail Buku</th>
                                <th style="width: 120px;">Tgl Pinjam</th>
                                <th style="width: 140px;">Jatuh Tempo</th>
                                <th style="width: 160px;">Status Keterlambatan</th>
                                <th style="width: 200px; text-align: right;">Aksi Pengembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pinjamanAktif as $item)
                                <tr class="{{ (int)$highlightId === (int)$item->idPeminjaman ? 'highlighted' : '' }}">
                                    {{-- CHECKBOX PILIHAN --}}
                                    <td style="text-align: center;">
                                        @if($item->statusBuku === 'Dipinjam')
                                            <input type="checkbox" class="book-row-check" 
                                                   value="{{ $item->id }}" 
                                                   data-id="{{ $item->id }}"
                                                   data-judul="{{ $item->buku->judul ?? 'Buku' }}"
                                                   data-penulis="{{ $item->buku->penulis ?? 'Anonim' }}"
                                                   data-eksemplar="{{ $item->eksemplar->nomor_eksemplar ?? '1' }}"
                                                   data-rak="{{ $item->buku->rak ?? 'Utama' }}"
                                                   data-harga="{{ (float)($item->buku->harga ?? 0) }}"
                                                   data-overdue="{{ $item->isOverdue ? 1 : 0 }}"
                                                   data-denda="{{ $item->isOverdue ? $item->estDenda : 0 }}"
                                                   data-haritelat="{{ $item->isOverdue ? $item->hariTerlambat : 0 }}"
                                                   onchange="handleRowCheckChange()" 
                                                   style="cursor: pointer; width: 17px; height: 17px; accent-color: #0f766e;">
                                        @else
                                            <span title="Buku sedang menunggu scan petugas di meja layanan" style="color: #cbd5e1; font-size: 16px;">—</span>
                                        @endif
                                    </td>
                                    {{-- KODE TRX --}}
                                    <td>
                                        <span class="trx-badge">
                                            #TRX-{{ str_pad($item->idPeminjaman, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>

                                    {{-- DETAIL BUKU --}}
                                    <td>
                                        <div class="book-item-row">
                                            <div class="book-icon-box">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="book-title-name">{{ $item->buku->judul ?? 'Buku' }}</span>
                                                <span class="book-meta-sub">
                                                    {{ $item->buku->penulis ?? 'Anonim' }} • 
                                                    Eksemplar: #{{ $item->eksemplar->nomor_eksemplar ?? '1' }} • 
                                                    Rak: {{ $item->buku->rak ?? 'Utama' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- TGL PINJAM --}}
                                    <td>
                                        <span style="font-weight: 600; color: #475569;">
                                            {{ \Carbon\Carbon::parse($item->peminjaman->tanggalPinjam)->translatedFormat('d M Y') }}
                                        </span>
                                    </td>

                                    {{-- JATUH TEMPO --}}
                                    <td>
                                        <span style="font-weight: 700; color: {{ $item->isOverdue ? '#dc2626' : 'var(--text-heading)' }};">
                                            {{ $item->batasKembaliCarbon->translatedFormat('d M Y') }}
                                        </span>
                                    </td>

                                    {{-- STATUS KETERLAMBATAN & PENGAJUAN --}}
                                    <td>
                                        @if($item->statusBuku === 'Diajukan Kembali')
                                            @if($item->denda && $item->denda->status === 'Belum Dibayar')
                                                <span class="badge-status-waiting" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;">
                                                    💳 Menunggu Bayar Denda
                                                </span>
                                                <div style="font-size: 11px; color: #dc2626; font-weight: 700; margin-top: 3px;">
                                                    Denda: Rp {{ number_format($item->denda->jumlah, 0, ',', '.') }}
                                                </div>
                                            @elseif($item->denda && $item->denda->status === 'Lunas')
                                                <span class="badge-status-waiting">
                                                    ⏳ Menunggu Scan Petugas
                                                </span>
                                                <div style="font-size: 11px; color: #059669; font-weight: 700; margin-top: 3px;">
                                                    ✓ Denda tercatat lunas · belum diverifikasi (Tiket: {{ $item->kode_batch_kembali ?? $item->kode_kembali ?? '-' }})
                                                </div>
                                            @else
                                                <span class="badge-status-waiting">
                                                    ⏳ Menunggu Scan Petugas
                                                </span>
                                                <div style="font-size: 11px; color: #b45309; font-weight: 700; margin-top: 3px;">
                                                    Tiket: {{ $item->kode_batch_kembali ?? $item->kode_kembali ?? '-' }}
                                                </div>
                                            @endif
                                        @elseif($item->isOverdue)
                                            <span class="badge-status overdue">
                                                ● Terlambat {{ $item->hariTerlambat }} Hari
                                            </span>
                                            <div class="fine-tag-danger">
                                                Est. Denda: Rp {{ number_format($item->estDenda, 0, ',', '.') }}
                                            </div>
                                        @else
                                            <span class="badge-status ontime">
                                                ✓ Sisa {{ $item->sisaHari }} Hari
                                            </span>
                                            <div class="fine-tag-free">
                                                Bebas Denda
                                            </div>
                                        @endif
                                    </td>

                                    {{-- AKSI PENGEMBALIAN --}}
                                    <td style="text-align: right;">
                                        @if($item->statusBuku === 'Diajukan Kembali')
                                            @if($item->denda && $item->denda->status === 'Belum Dibayar')
                                                <div style="display: inline-flex; align-items: center; gap: 8px;">
                                                    <a href="{{ route('bayar.qr', $item->denda->idDenda) }}" 
                                                       class="btn-ticket-action" 
                                                       style="background: #dc2626; border-color: #dc2626; color: #ffffff;"
                                                       title="Bayar Denda Terlebih Dahulu">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                                            <line x1="1" y1="10" x2="23" y2="10"></line>
                                                        </svg>
                                                        <span>Bayar Denda (Rp {{ number_format($item->denda->jumlah, 0, ',', '.') }})</span>
                                                    </a>
                                                </div>
                                            @else
                                                <div style="display: inline-flex; align-items: center; gap: 8px;">
                                                    <a href="{{ route('member.kartu-saya') }}" 
                                                       class="btn-ticket-action" 
                                                       target="_blank"
                                                       title="Tunjukkan Kartu / QR Anggota Anda ke Petugas Meja Sirkulasi">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                            <rect x="3" y="3" width="7" height="7"></rect>
                                                            <rect x="14" y="3" width="7" height="7"></rect>
                                                            <rect x="14" y="14" width="7" height="7"></rect>
                                                            <rect x="3" y="14" width="7" height="7"></rect>
                                                        </svg>
                                                        <span>Tunjukkan QR Anggota</span>
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <div style="display: inline-flex; align-items: center; gap: 8px;">
                                                <button type="button" 
                                                        class="btn-return-action"
                                                        onclick="openReturnModal({{ $item->id }}, '{{ addslashes($item->buku->judul ?? 'Buku') }}', '{{ $item->eksemplar->nomor_eksemplar ?? '1' }}', '{{ $item->isOverdue ? number_format($item->estDenda, 0, ',', '.') : '0' }}', {{ (float)($item->buku->harga ?? 0) }}, {{ (float)($item->estDenda ?? 0) }})">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                    <span>Kembalikan</span>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon-wrap">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="empty-title">Tidak Ada Buku yang Perlu Dikembalikan</div>
                    <div class="empty-desc">
                        Saat ini Anda tidak memiliki tanggungan peminjaman buku aktif. Seluruh buku telah selesai dikembalikan ke perpustakaan.
                    </div>
                    <a href="{{ route('katalog.index') }}" class="btn-return-action">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <span>Jelajahi Katalog Buku</span>
                    </a>
                </div>
            @endif
        </div>
    @endif

    {{-- TAB 2: RIWAYAT PENGEMBALIAN SELESAI --}}
    @if($tab === 'riwayat')
        <div class="main-card">
            <div class="main-card-header">
                <h3 class="main-card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #0f766e;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    Riwayat Pengembalian Buku yang Telah Selesai
                </h3>
                <span style="font-size: 12.5px; color: var(--text-muted); font-weight: 600;">
                    Total: {{ $riwayatPengembalian->total() }} transaksi
                </span>
            </div>

            @if($riwayatPengembalian->isNotEmpty())
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 130px;">No. Return</th>
                                <th>Detail Buku yang Dikembalikan</th>
                                <th style="width: 140px;">Tgl Dikembalikan</th>
                                <th style="width: 130px;">Kondisi Buku</th>
                                <th style="width: 160px;">Status Denda</th>
                                <th style="width: 160px; text-align: right;">Bukti Pengembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatPengembalian as $ret)
                                <tr>
                                    {{-- NO RETURN --}}
                                    <td>
                                        <span class="trx-badge">
                                            #RET-{{ str_pad($ret->idPengembalian, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>

                                    {{-- DETAIL BUKU --}}
                                    <td>
                                        <div class="book-item-row">
                                            <div class="book-icon-box" style="background: #dcfce7; color: #15803d;">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                @php
                                                    $firstDetail = $ret->peminjaman->details->first();
                                                @endphp
                                                <span class="book-title-name">{{ $firstDetail->buku->judul ?? 'Buku Perpustakaan' }}</span>
                                                <span class="book-meta-sub">
                                                    Penerima: {{ $ret->petugas->name ?? 'Petugas Perpustakaan' }} • 
                                                    Ref: #TRX-{{ str_pad($ret->idPeminjaman, 5, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- TGL KEMBALI --}}
                                    <td>
                                        <span style="font-weight: 700; color: #0f766e;">
                                            {{ \Carbon\Carbon::parse($ret->tanggalKembali)->translatedFormat('d M Y') }}
                                        </span>
                                    </td>

                                    {{-- KONDISI BUKU --}}
                                    <td>
                                        @if($ret->kondisiBuku === 'Baik')
                                            <span class="badge-status ontime">✓ Kondisi Baik</span>
                                        @elseif($ret->kondisiBuku === 'Rusak')
                                            <span class="badge-status overdue">⚠️ Kondisi Rusak</span>
                                        @else
                                            <span class="badge-status overdue">✕ Buku Hilang</span>
                                        @endif
                                    </td>

                                    {{-- STATUS DENDA --}}
                                    <td>
                                        @if($ret->denda)
                                            @if($ret->denda->status === 'Belum Dibayar')
                                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                                    <span style="font-size: 12px; font-weight: 800; color: #dc2626;">
                                                        Rp {{ number_format($ret->denda->jumlah, 0, ',', '.') }}
                                                    </span>
                                                    <a href="{{ route('bayar.qr', $ret->denda->idDenda) }}" style="font-size: 11px; font-weight: 700; color: #0f766e; text-decoration: underline;">
                                                        Bayar QR Denda &rarr;
                                                    </a>
                                                </div>
                                            @else
                                                <span class="badge-status ontime">✓ Denda tercatat lunas · belum diverifikasi</span>
                                            @endif
                                        @else
                                            <span class="badge-status ontime">✓ Bebas Denda</span>
                                        @endif
                                    </td>

                                    {{-- BUKTI PENGEMBALIAN --}}
                                    <td style="text-align: right;">
                                        <a href="{{ route('pengembalian.member.bukti', $ret->idPengembalian) }}" class="btn-view-receipt">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                            </svg>
                                            <span>Lihat Resi</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="padding: 18px 24px;">
                    {{ $riwayatPengembalian->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon-wrap">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="empty-title">Belum Ada Riwayat Pengembalian</div>
                    <div class="empty-desc">
                        Transaksi pengembalian buku yang telah selesai diproses akan dicatat dan diarsipkan secara otomatis di sini.
                    </div>
                </div>
            @endif
        </div>
    @endif

</div>

{{-- MODAL 1: KONFIRMASI PENGEMBALIAN BUKU MANDIRI --}}
<div class="modal-overlay" id="modalReturn">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #0f766e;">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                Ajukan Pengembalian Buku
            </h3>
            <button type="button" class="btn-close-modal" onclick="closeReturnModal()">✕</button>
        </div>

        <form id="formReturn" method="POST" action="">
            @csrf
            <div class="modal-body">
                <div class="book-summary-box">
                    <div class="book-icon-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <strong id="modalBookTitle" style="color: var(--text-heading); font-size: 13.5px; display: block;">-</strong>
                        <span id="modalBookEksemplar" style="font-size: 12px; color: var(--text-muted);">Eksemplar #1</span>
                    </div>
                </div>

                <div id="modalDendaNotice" style="display: none; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; font-size: 12.5px; color: #be123c;">
                    ⚠️ Buku ini telah melewati batas jatuh tempo. Estimasi denda keterlambatan sebesar <strong id="modalDendaAmount">Rp 0</strong> akan ditagihkan pada sistem.
                </div>

                <div class="form-group">
                    <label class="form-label">Kondisi Fisik Buku yang Dikembalikan</label>
                    <select name="kondisiBuku" id="modalKondisiBuku" class="form-select" required onchange="handleSingleConditionChange()">
                        <option value="Baik" selected>Baik (Buku utuh, bersih, dan tidak rusak)</option>
                        <option value="Rusak">Rusak (Halaman robek, basah, atau coretan parah)</option>
                        <option value="Hilang">Hilang (Buku fisik hilang / tidak dapat ditemukan)</option>
                    </select>
                </div>

                <div id="modalConditionNotice" style="display: none; background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; font-size: 12.5px; line-height: 1.5;">
                    <span id="modalConditionNoticeText">-</span>
                </div>

                <div id="modalPayFirstWarning" style="display: none; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 11px 14px; margin-bottom: 16px; font-size: 12px; color: #1e40af; line-height: 1.45;">
                    ℹ️ <strong>Ketentuan Denda:</strong> Karena terdapat denda pengembalian, Anda akan diarahkan untuk <strong>membayar denda via QRIS terlebih dahulu</strong> sebelum Tiket Pengembalian Buku Fisik diterbitkan.
                </div>

                <div class="checkbox-wrap">
                    <input type="checkbox" name="konfirmasi" id="checkConfirm" required>
                    <label for="checkConfirm">
                        Saya memastikan telah menyiapkan buku fisik ini untuk dikembalikan dan akan menunjukkan QR Code tiket ke petugas di meja sirkulasi.
                    </label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-qr-desk" onclick="closeReturnModal()">Batal</button>
                <button type="submit" class="btn-return-action" id="modalSubmitBtn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span id="modalSubmitText">Selesaikan & Terbitkan QR Code</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 2: TAMPILKAN QR PENGEMBALIAN UNTUK MEJA SIRKULASI --}}
<div class="modal-overlay" id="modalQr">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #0f766e;">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                QR Pengembalian Sirkulasi
            </h3>
            <button type="button" class="btn-close-modal" onclick="closeQrModal()">✕</button>
        </div>

        <div class="modal-body">
            <div class="qr-card-center">
                <div class="qr-box-inner" id="qrContainer">
                    <img id="qrImage" src="" alt="QR Code">
                </div>
                <div style="font-weight: 800; font-size: 14px; color: var(--text-heading); margin-bottom: 4px;" id="qrBookTitle">-</div>
                <div style="font-size: 12px; color: var(--text-muted); max-width: 340px; margin: 0 auto; line-height: 1.45;">
                    Tunjukkan QR Code ini kepada petugas perpustakaan di meja sirkulasi untuk verifikasi pengembalian buku fisik secara kilat.
                </div>
            </div>
        </div>

        <div class="modal-footer" style="justify-content: center;">
            <button type="button" class="btn-return-action" onclick="closeQrModal()">
                Tutup Tampilan QR
            </button>
        </div>
    </div>
</div>

{{-- FLOATING BULK ACTION BAR --}}
<div id="bulkActionBar" class="bulk-action-bar" style="display: none;">
    <div class="bulk-bar-content">
        <div class="bulk-bar-left">
            <span class="bulk-badge-count" id="selectedCountBadge">0</span>
            <div class="bulk-bar-text">
                <strong id="selectedCountText">0 Buku</strong> dipilih untuk dikembalikan
            </div>
        </div>
        <div class="bulk-bar-right">
            <button type="button" class="btn-bulk-cancel" onclick="clearAllSelections()">
                Batal Pilihan
            </button>
            <button type="button" class="btn-bulk-submit" onclick="openBatchReturnModalFromSelection()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                    <polyline points="9 11 12 14 22 4"></polyline>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
                <span>Proses Sekaligus</span>
            </button>
        </div>
    </div>
</div>

{{-- MODAL 3: PENGEMBALIAN BUKU SEKALIGUS (BATCH) --}}
<div class="modal-overlay" id="modalBatchReturn">
    <div class="modal-card modal-batch-card">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #0f766e;">
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    Pengembalian Sekaligus
                </h3>
                <span style="font-size: 12px; color: var(--text-muted); margin-top: 2px; display: block;">
                    Mengembalikan <strong id="batchModalItemCount">0</strong> buku secara bersamaan dalam satu tiket QR
                </span>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeBatchReturnModal()">✕</button>
        </div>

        <form id="formBatchReturn" method="POST" action="{{ route('pengembalian.member.batch-store') }}">
            @csrf
            <div class="modal-body" style="max-height: 62vh; overflow-y: auto; padding: 20px;">
                <div style="background: #f0fdfa; border: 1px solid #ccfbf1; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; font-size: 12.5px; color: #0f766e; display: flex; align-items: flex-start; gap: 10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="flex-shrink: 0; margin-top: 1px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <div>
                        <strong>Penting:</strong> Tentukan kondisi fisik untuk setiap buku di bawah ini. Jika ada buku berstatus <strong>Rusak</strong> atau <strong>Hilang</strong>, sistem akan secara otomatis menghitung tagihan ganti rugi (100% harga buku).
                    </div>
                </div>

                {{-- DAFTAR KARTU BUKU DENGAN PILIHAN KONDISI --}}
                <div id="batchItemsList" style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
                    {{-- Di-render via JavaScript --}}
                </div>

                {{-- RINGKASAN BATCH --}}
                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; margin-bottom: 18px;">
                    <div style="font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                        Ringkasan Pengembalian
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 12px;">
                        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 8px 10px; text-align: center;">
                            <span style="font-size: 11px; font-weight: 700; color: #065f46; display: block;">Kondisi Baik</span>
                            <strong id="summaryCountBaik" style="font-size: 16px; color: #047857;">0</strong>
                        </div>
                        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 8px 10px; text-align: center;">
                            <span style="font-size: 11px; font-weight: 700; color: #92400e; display: block;">Kondisi Rusak</span>
                            <strong id="summaryCountRusak" style="font-size: 16px; color: #b45309;">0</strong>
                        </div>
                        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 8px 10px; text-align: center;">
                            <span style="font-size: 11px; font-weight: 700; color: #991b1b; display: block;">Buku Hilang</span>
                            <strong id="summaryCountHilang" style="font-size: 16px; color: #dc2626;">0</strong>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px dashed #cbd5e1; font-size: 13px;">
                        <span style="font-weight: 600; color: var(--text-heading);">Total Estimasi Tagihan/Denda:</span>
                        <strong id="summaryTotalDenda" style="font-size: 15px; color: #dc2626;">Rp 0</strong>
                    </div>
                </div>

                <div class="checkbox-wrap">
                    <input type="checkbox" name="konfirmasi" id="checkBatchConfirm" required>
                    <label for="checkBatchConfirm">
                        Saya memastikan telah menyiapkan seluruh buku yang dipilih dan bersedia menunjukkan tiket QR pengembalian bersama ke petugas sirkulasi.
                    </label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-qr-desk" onclick="closeBatchReturnModal()">Batal</button>
                <button type="submit" class="btn-return-action" id="btnSubmitBatch">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Terbitkan Tiket Pengembalian Sekaligus</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let selectedBatchBooks = [];

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(Math.round(number));
    }

    function toggleSelectAllBooks(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.book-row-check');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
        });
        handleRowCheckChange();
    }

    function handleRowCheckChange() {
        const checkboxes = Array.from(document.querySelectorAll('.book-row-check'));
        const checked = checkboxes.filter(cb => cb.checked);
        const master = document.getElementById('checkAllBooks');

        if (master) {
            if (checked.length === 0) {
                master.checked = false;
                master.indeterminate = false;
            } else if (checked.length === checkboxes.length) {
                master.checked = true;
                master.indeterminate = false;
            } else {
                master.checked = false;
                master.indeterminate = true;
            }
        }

        const bulkBar = document.getElementById('bulkActionBar');
        const badge = document.getElementById('selectedCountBadge');
        const text = document.getElementById('selectedCountText');

        if (checked.length > 0) {
            if (badge) badge.innerText = checked.length;
            if (text) text.innerText = checked.length + ' Buku';
            if (bulkBar) bulkBar.style.display = 'flex';
        } else {
            if (bulkBar) bulkBar.style.display = 'none';
        }
    }

    function clearAllSelections() {
        const checkboxes = document.querySelectorAll('.book-row-check');
        checkboxes.forEach(cb => {
            cb.checked = false;
        });
        const master = document.getElementById('checkAllBooks');
        if (master) {
            master.checked = false;
            master.indeterminate = false;
        }
        const bulkBar = document.getElementById('bulkActionBar');
        if (bulkBar) bulkBar.style.display = 'none';
    }

    function openBatchReturnModalAll() {
        const checkboxes = Array.from(document.querySelectorAll('.book-row-check'));
        if (checkboxes.length === 0) return;

        checkboxes.forEach(cb => {
            cb.checked = true;
        });
        handleRowCheckChange();
        openBatchReturnModalFromSelection();
    }

    function openBatchReturnModalFromSelection() {
        const checked = Array.from(document.querySelectorAll('.book-row-check:checked'));
        if (checked.length === 0) {
            alert('Silakan pilih minimal 1 buku untuk dikembalikan.');
            return;
        }

        selectedBatchBooks = checked.map(cb => ({
            id: cb.dataset.id,
            judul: cb.dataset.judul,
            penulis: cb.dataset.penulis,
            eksemplar: cb.dataset.eksemplar,
            rak: cb.dataset.rak,
            harga: parseFloat(cb.dataset.harga) || 0,
            overdue: parseInt(cb.dataset.overdue) === 1,
            denda: parseFloat(cb.dataset.denda) || 0,
            hariTelat: parseInt(cb.dataset.haritelat) || 0,
            kondisi: 'Baik'
        }));

        renderBatchModalCards();
        document.getElementById('modalBatchReturn').classList.add('show');
    }

    function renderBatchModalCards() {
        const container = document.getElementById('batchItemsList');
        const countHeader = document.getElementById('batchModalItemCount');
        if (countHeader) countHeader.innerText = selectedBatchBooks.length;

        if (!container) return;
        container.innerHTML = '';

        selectedBatchBooks.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 'batch-modal-item-card';
            card.id = `batch-card-${item.id}`;

            const overdueBadge = item.overdue
                ? `<div style="font-size: 11px; color: #dc2626; font-weight: 700; margin-top: 2px;">Terlambat ${item.hariTelat} hr (Est. Denda: Rp ${formatRupiah(item.denda)})</div>`
                : `<div style="font-size: 11px; color: #16a34a; font-weight: 600; margin-top: 2px;">Tepat Waktu (Bebas Denda)</div>`;

            card.innerHTML = `
                <input type="hidden" name="detail_ids[]" value="${item.id}">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 11px; font-weight: 800; background: #f1f5f9; color: #475569; padding: 2px 6px; border-radius: 4px;">#${index + 1}</span>
                            <strong style="color: var(--text-heading); font-size: 13.5px; line-height: 1.35;">${item.judul}</strong>
                        </div>
                        <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 3px;">
                            ${item.penulis} • Eksemplar #${item.eksemplar} • Rak ${item.rak}
                        </div>
                    </div>
                    <div style="text-align: right; flex-shrink: 0;">
                        <span style="font-size: 11.5px; color: #475569; font-weight: 700;">Nilai Buku: Rp ${formatRupiah(item.harga)}</span>
                        ${overdueBadge}
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label style="font-size: 11.5px; font-weight: 700; color: #475569;">
                            Kondisi Fisik Buku Ini:
                        </label>
                        <span id="badge-kondisi-label-${item.id}" style="font-size: 11px; font-weight: 700; color: #059669;">
                            Baik (Normal)
                        </span>
                    </div>

                    <div class="condition-selector-group">
                        <label class="condition-option opt-baik">
                            <input type="radio" name="kondisi[${item.id}]" value="Baik" checked onchange="handleItemConditionChange('${item.id}', 'Baik')">
                            <div class="condition-label-box">
                                <span>✓ Baik</span>
                            </div>
                        </label>
                        <label class="condition-option opt-rusak">
                            <input type="radio" name="kondisi[${item.id}]" value="Rusak" onchange="handleItemConditionChange('${item.id}', 'Rusak')">
                            <div class="condition-label-box">
                                <span>⚠️ Rusak</span>
                            </div>
                        </label>
                        <label class="condition-option opt-hilang">
                            <input type="radio" name="kondisi[${item.id}]" value="Hilang" onchange="handleItemConditionChange('${item.id}', 'Hilang')">
                            <div class="condition-label-box">
                                <span>✕ Hilang</span>
                            </div>
                        </label>
                    </div>

                    <div id="item-penalty-box-${item.id}" style="display: none; margin-top: 8px; border-radius: 8px; padding: 7px 10px; font-size: 11.5px; line-height: 1.4;">
                    </div>
                </div>
            `;

            container.appendChild(card);
        });

        updateBatchSummary();
    }

    function handleItemConditionChange(detailId, condition) {
        const item = selectedBatchBooks.find(b => String(b.id) === String(detailId));
        if (item) {
            item.kondisi = condition;
        }

        const penaltyBox = document.getElementById(`item-penalty-box-${detailId}`);
        const labelBadge = document.getElementById(`badge-kondisi-label-${detailId}`);

        if (penaltyBox && item) {
            if (condition === 'Baik') {
                penaltyBox.style.display = 'none';
                if (labelBadge) {
                    labelBadge.innerText = 'Baik (Normal)';
                    labelBadge.style.color = '#059669';
                }
            } else if (condition === 'Rusak') {
                penaltyBox.style.display = 'block';
                penaltyBox.style.background = '#fffbeb';
                penaltyBox.style.color = '#92400e';
                penaltyBox.style.border = '1px solid #fde68a';
                penaltyBox.innerHTML = `⚠️ <strong>Denda Rusak (100% Harga Buku):</strong> Rp ${formatRupiah(item.harga)} wajib dibayar via QRIS terlebih dahulu sebelum tiket diterbitkan.`;
                if (labelBadge) {
                    labelBadge.innerText = 'Rusak (+ Denda Ganti Rugi)';
                    labelBadge.style.color = '#d97706';
                }
            } else if (condition === 'Hilang') {
                penaltyBox.style.display = 'block';
                penaltyBox.style.background = '#fef2f2';
                penaltyBox.style.color = '#991b1b';
                penaltyBox.style.border = '1px solid #fecaca';
                penaltyBox.innerHTML = `✕ <strong>Denda Hilang (100% Harga Buku):</strong> Rp ${formatRupiah(item.harga)} wajib dibayar via QRIS terlebih dahulu sebelum tiket diterbitkan.`;
                if (labelBadge) {
                    labelBadge.innerText = 'Hilang (+ Denda Penggantian)';
                    labelBadge.style.color = '#dc2626';
                }
            }
        }

        updateBatchSummary();
    }

    function updateBatchSummary() {
        let countBaik = 0;
        let countRusak = 0;
        let countHilang = 0;
        let totalDenda = 0;

        selectedBatchBooks.forEach(item => {
            const cond = item.kondisi || 'Baik';
            if (cond === 'Baik') {
                countBaik++;
            } else if (cond === 'Rusak') {
                countRusak++;
                totalDenda += item.harga;
            } else if (cond === 'Hilang') {
                countHilang++;
                totalDenda += item.harga;
            }

            // Keterlambatan
            if (item.overdue) {
                totalDenda += item.denda;
            }
        });

        const elBaik = document.getElementById('summaryCountBaik');
        const elRusak = document.getElementById('summaryCountRusak');
        const elHilang = document.getElementById('summaryCountHilang');
        const elTotal = document.getElementById('summaryTotalDenda');

        if (elBaik) elBaik.innerText = countBaik;
        if (elRusak) elRusak.innerText = countRusak;
        if (elHilang) elHilang.innerText = countHilang;
        if (elTotal) {
            if (totalDenda > 0) {
                elTotal.innerText = 'Rp ' + formatRupiah(totalDenda);
                elTotal.style.color = '#dc2626';
            } else {
                elTotal.innerText = 'Bebas Denda (Rp 0)';
                elTotal.style.color = '#059669';
            }
        }

        const btnSubmitBatch = document.getElementById('btnSubmitBatch');
        if (btnSubmitBatch) {
            const spanText = btnSubmitBatch.querySelector('span');
            if (spanText) {
                if (totalDenda > 0) {
                    spanText.innerText = `Bayar Denda (Rp ${formatRupiah(totalDenda)}) & Buat Tiket`;
                } else {
                    spanText.innerText = 'Terbitkan Tiket Pengembalian Sekaligus';
                }
            }
        }
    }

    function closeBatchReturnModal() {
        document.getElementById('modalBatchReturn').classList.remove('show');
    }

    let currentModalBookPrice = 0;
    let currentModalOverdueFine = 0;

    function openReturnModal(detailId, judul, eksemplar, dendaFormatted, bookPrice = 0, overdueFine = 0) {
        const form = document.getElementById('formReturn');
        form.action = `/pengembalian-saya/${detailId}/proses`;

        document.getElementById('modalBookTitle').innerText = judul;
        document.getElementById('modalBookEksemplar').innerText = 'Eksemplar #' + eksemplar;

        currentModalBookPrice = Number(bookPrice) || 0;
        currentModalOverdueFine = Number(overdueFine) || 0;

        const select = document.getElementById('modalKondisiBuku');
        if (select) {
            select.value = 'Baik';
        }

        handleSingleConditionChange();

        document.getElementById('modalReturn').classList.add('show');
    }

    function handleSingleConditionChange() {
        const select = document.getElementById('modalKondisiBuku');
        const condition = select ? select.value : 'Baik';

        const dendaBox = document.getElementById('modalDendaNotice');
        const conditionBox = document.getElementById('modalConditionNotice');
        const conditionText = document.getElementById('modalConditionNoticeText');
        const payFirstBox = document.getElementById('modalPayFirstWarning');
        const submitText = document.getElementById('modalSubmitText');

        let conditionFine = 0;
        if (condition === 'Rusak') {
            conditionFine = currentModalBookPrice;
            if (conditionBox && conditionText) {
                conditionBox.style.display = 'block';
                conditionBox.style.background = '#fffbeb';
                conditionBox.style.borderColor = '#fde68a';
                conditionBox.style.color = '#92400e';
                conditionText.innerHTML = `⚠️ <strong>Denda Kerusakan (100% Harga Buku):</strong> Rp ${formatRupiah(conditionFine)} wajib dibayar via QRIS terlebih dahulu sebelum tiket pengembalian diterbitkan.`;
            }
        } else if (condition === 'Hilang') {
            conditionFine = currentModalBookPrice;
            if (conditionBox && conditionText) {
                conditionBox.style.display = 'block';
                conditionBox.style.background = '#fef2f2';
                conditionBox.style.borderColor = '#fecaca';
                conditionBox.style.color = '#991b1b';
                conditionText.innerHTML = `✕ <strong>Denda Kehilangan (100% Harga Buku):</strong> Rp ${formatRupiah(conditionFine)} wajib dibayar via QRIS terlebih dahulu sebelum tiket pengembalian diterbitkan.`;
            }
        } else {
            if (conditionBox) conditionBox.style.display = 'none';
        }

        if (currentModalOverdueFine > 0) {
            if (dendaBox) {
                document.getElementById('modalDendaAmount').innerText = 'Rp ' + formatRupiah(currentModalOverdueFine);
                dendaBox.style.display = 'block';
            }
        } else {
            if (dendaBox) dendaBox.style.display = 'none';
        }

        const totalFine = currentModalOverdueFine + conditionFine;

        if (totalFine > 0) {
            if (payFirstBox) payFirstBox.style.display = 'block';
            if (submitText) {
                submitText.innerText = `Bayar Denda (Rp ${formatRupiah(totalFine)}) & Terbitkan QR Code`;
            }
        } else {
            if (payFirstBox) payFirstBox.style.display = 'none';
            if (submitText) {
                submitText.innerText = 'Selesaikan & Terbitkan QR Code';
            }
        }
    }

    function closeReturnModal() {
        document.getElementById('modalReturn').classList.remove('show');
    }

    function openQrModal(judul, qrToken) {
        document.getElementById('qrBookTitle').innerText = judul;
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${encodeURIComponent(qrToken)}`;
        document.getElementById('qrImage').src = qrUrl;
        document.getElementById('modalQr').classList.add('show');
    }

    function closeQrModal() {
        document.getElementById('modalQr').classList.remove('show');
    }

    // Tutup modal jika klik di luar kartu
    window.addEventListener('click', function(e) {
        const modalReturn = document.getElementById('modalReturn');
        const modalQr = document.getElementById('modalQr');
        const modalBatchReturn = document.getElementById('modalBatchReturn');
        if (e.target === modalReturn) closeReturnModal();
        if (e.target === modalQr) closeQrModal();
        if (e.target === modalBatchReturn) closeBatchReturnModal();
    });
</script>
@endsection
