@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.petugas')

@section('title', 'Koleksi & Inventaris Buku - BOOKNEST')

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

    /* --- RESTOCK ALERT BANNER --- */
    .restock-alert-banner {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1.5px solid #fde68a;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.08);
    }

    .restock-alert-content {
        display: flex;
        align-items: center;
        gap: 14px;
        flex: 1;
        min-width: 280px;
    }

    .restock-alert-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #f59e0b;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.3);
    }

    .restock-alert-title {
        font-size: 14.5px;
        font-weight: 800;
        color: #92400e;
        margin: 0 0 2px;
    }

    .restock-alert-desc {
        font-size: 12.5px;
        color: #b45309;
        margin: 0;
    }

    .btn-restock-view {
        padding: 8px 16px;
        background: #b45309;
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 700;
        border-radius: 9px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s, transform 0.1s;
        white-space: nowrap;
    }

    .btn-restock-view:hover {
        background: #92400e;
        transform: translateY(-1px);
    }

    .btn-restock-view.active {
        background: #475569;
    }

    .btn-restock-view.active:hover {
        background: #334155;
    }

    /* --- STATS SUMMARY GRID --- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon.primary { background: #f0fdfa; color: #0f766e; }
    .stat-icon.blue { background: #eff6ff; color: #2563eb; }
    .stat-icon.emerald { background: #ecfdf5; color: #059669; }
    .stat-icon.purple { background: #f5f3ff; color: #7c3aed; }
    .stat-icon.amber { background: #fffbeb; color: #d97706; }

    .stat-meta {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        line-height: 1.1;
    }

    .stat-label {
        font-size: 11.5px;
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
        min-width: 220px;
        max-width: 320px;
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
        border: none;
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

    .table-toolbar-header {
        padding: 14px 20px;
        background: #fafbfc;
        border-bottom: 1px solid var(--border-color, #e2e8f0);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .table-toolbar-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-toolbar-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-toggle-all {
        padding: 6px 12px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s;
    }

    .btn-toggle-all:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .buku-table {
        width: 100%;
        min-width: 1020px;
        border-collapse: collapse;
        text-align: left;
    }

    .buku-table th {
        background: #f8fafc;
        padding: 13px 16px;
        font-size: 11.5px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-color, #e2e8f0);
    }

    .buku-table td {
        padding: 14px 16px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    /* Main Book Row styling */
    .main-book-row {
        transition: background-color 0.15s;
    }

    .main-book-row:hover td {
        background-color: #f8fafc;
    }

    .main-book-row.expanded-parent td {
        background-color: #f0fdfa !important;
        border-bottom: 1px solid #ccfbf1;
    }

    /* Book Info Cell */
    .book-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .book-thumb {
        width: 44px;
        height: 58px;
        border-radius: 7px;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
        overflow: hidden;
    }

    .book-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-meta-title {
        font-weight: 700;
        color: var(--text-heading, #0f172a);
        font-size: 14px;
        line-height: 1.3;
        margin-bottom: 3px;
    }

    .book-meta-author {
        font-size: 12px;
        color: var(--text-muted, #64748b);
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
    }

    /* Category Pill */
    .badge-category {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        background: #f0fdfa;
        color: #0f766e;
        border: 1px solid #ccfbf1;
    }

    /* Summary Pills in Main Row */
    .copies-summary-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .copies-breakdown-subtext {
        font-size: 11px;
        color: #64748b;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .copies-breakdown-subtext span {
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    /* Dropdown / Expand Button */
    .btn-toggle-eksemplar {
        background: #ffffff;
        color: #0f766e;
        border: 1.5px solid #0f766e;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(15, 118, 110, 0.08);
    }

    .btn-toggle-eksemplar:hover {
        background: #0f766e;
        color: #ffffff;
    }

    .btn-toggle-eksemplar.active {
        background: #0f766e;
        color: #ffffff;
    }

    /* Lokasi Badges: Rak vs Gudang */
    .lokasi-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .lokasi-badge.rak {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .lokasi-badge.gudang {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .tag-perlu-restock {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 7px;
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
        border-radius: 5px;
        font-size: 10.5px;
        font-weight: 700;
        margin-top: 4px;
    }

    /* Status Ketersediaan Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 8px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .status-badge.tersedia {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .status-badge.dipinjam {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .status-badge.dibooking {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .status-badge.hilang {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .borrower-subtext {
        font-size: 11px;
        color: #475569;
        margin-top: 3px;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .borrower-subtext span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Condition Badge */
    .condition-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .condition-badge.baik { background: #dcfce7; color: #166534; }
    .condition-badge.rusak { background: #fef3c7; color: #b45309; }
    .condition-badge.hilang { background: #fee2e2; color: #991b1b; }

    /* Eksemplar Identity Pill */
    .copy-id-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        background: #0f172a;
        color: #ffffff;
        border-radius: 6px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .copy-number-tag {
        display: inline-block;
        padding: 2px 7px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 700;
    }

    /* EXPANDED PANEL & SUBTABLE STYLING */
    .eksemplar-detail-row td {
        padding: 0 !important;
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }

    .eksemplar-panel {
        padding: 16px 20px 20px 20px;
        background: #f8fafc;
        border-left: 4px solid #0f766e;
    }

    .eksemplar-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .eksemplar-panel-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .eksemplar-panel-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .meta-tag {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 5px;
        background: #e2e8f0;
        color: #334155;
    }

    .meta-tag.rak { background: #d1fae5; color: #065f46; }
    .meta-tag.gudang { background: #e2e8f0; color: #475569; }

    .btn-panel-add-copy {
        background: #0f766e;
        color: #ffffff;
        border: none;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: background 0.15s;
    }

    .btn-panel-add-copy:hover {
        background: #115e59;
    }

    .eksemplar-subtable-wrapper {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 10px;
        overflow-x: auto;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .eksemplar-subtable {
        width: 100%;
        min-width: 880px;
        border-collapse: collapse;
        text-align: left;
    }

    .eksemplar-subtable th {
        background: #f1f5f9;
        padding: 9px 14px;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-color, #e2e8f0);
    }

    .eksemplar-subtable td {
        padding: 10px 14px;
        font-size: 12.5px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        background: #ffffff;
    }

    .eksemplar-subtable tr:hover td {
        background: #f8fafc;
    }

    /* Action Buttons */
    .action-group {
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn-action-manage {
        background: #f0fdfa;
        color: #0f766e;
        border: 1.5px solid #99f6e4;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }

    .btn-action-manage:hover {
        background: #ccfbf1;
        border-color: #5eead4;
    }

    .btn-action-pajang {
        background: #ecfdf5;
        color: #047857;
        border: 1.5px solid #a7f3d0;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }

    .btn-action-pajang:hover {
        background: #047857;
        color: #ffffff;
        border-color: #047857;
    }

    .btn-action-simpan {
        background: #f8fafc;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }

    .btn-action-simpan:hover {
        background: #475569;
        color: #ffffff;
        border-color: #475569;
    }

    .btn-action-edit {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
        padding: 5px 9px;
        border-radius: 7px;
        font-size: 11.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }

    .btn-action-edit:hover {
        background: #d97706;
        color: #ffffff;
    }

    .btn-action-delete {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        padding: 5px 8px;
        border-radius: 7px;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }

    .btn-action-delete:hover {
        background: #dc2626;
        color: #ffffff;
    }

    .btn-action-qr-link {
        background: #f8fafc;
        color: #334155;
        border: 1px solid #cbd5e1;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }

    .btn-action-qr-link:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }

    .btn-create-buku {
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

    .btn-create-buku:hover {
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

    .flash-alert.error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
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

    @media (max-width: 1100px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-card {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input-wrapper {
            max-width: 100%;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="buku-page">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                <span>Inventaris Perpustakaan</span>
            </div>
            <h1 class="page-title">
                @if (auth()->user()->role === 'admin')
                    Kelola Master Data Buku
                @else
                    Koleksi & Inventaris Buku
                @endif
            </h1>
            <p class="page-subtitle">
                @if (auth()->user()->role === 'admin')
                    Daftar koleksi buku per judul. Klik tombol dropdown untuk melihat dan mengelola setiap eksemplar fisik.
                @else
                    Daftar koleksi buku per judul. Buka dropdown eksemplar untuk memantau status fisik di rak display vs gudang cadangan.
                @endif
            </p>
        </div>

        <div>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('buku.create') }}" class="btn-create-buku">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Buku Baru
                </a>
            @else
                <div class="badge-role-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                    Mode Petugas: Monitoring Eksemplar Fisik
                </div>
            @endif
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if (session('success'))
        <div class="flash-alert success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="flash-alert error">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- RESTOCK ALERT BANNER JIKA DISPLAY RAK KOSONG DAN CADANGAN GUDANG TERSEDIA -->
    @if ($jumlahPerluRestock > 0)
        <div class="restock-alert-banner">
            <div class="restock-alert-content">
                <div class="restock-alert-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div>
                    <h4 class="restock-alert-title">Perhatian: Ada {{ $jumlahPerluRestock }} Judul Buku Perlu Restock ke Rak!</h4>
                    <p class="restock-alert-desc">Display di rak sudah habis terpinjam/kosong, namun stok fisik cadangan masih tersedia di gudang. Segera pajang eksemplar cadangan ke rak display.</p>
                </div>
            </div>
            <div>
                @if (request()->boolean('perlu_restock'))
                    <a href="{{ route('buku.index') }}" class="btn-restock-view active">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Tampilkan Semua Buku
                    </a>
                @else
                    <a href="{{ route('buku.index', array_merge(request()->query(), ['perlu_restock' => 1])) }}" class="btn-restock-view">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        Filter Perlu Restock ({{ $jumlahPerluRestock }})
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- SUMMARY STATS GRID: 5 INDIKATOR TERPADU -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $totalJudul }}</span>
                <span class="stat-label">Total Judul Buku</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                    <polyline points="2 17 12 22 22 17"></polyline>
                    <polyline points="2 12 12 17 22 12"></polyline>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $totalEksemplar ?? $totalStok }}</span>
                <span class="stat-label">Total Eksemplar Fisik</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="18" rx="2"></rect>
                    <line x1="2" y1="9" x2="22" y2="9"></line>
                    <line x1="2" y1="15" x2="22" y2="15"></line>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value" id="stat-eksemplar-rak">{{ $eksemplarDiRak ?? 0 }}</span>
                <span class="stat-label">Di Rak (Display)</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                    <path d="m3.3 7 8.7 5 8.7-5"></path>
                    <path d="M12 22V12"></path>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value" id="stat-eksemplar-gudang">{{ $eksemplarDiGudang ?? 0 }}</span>
                <span class="stat-label">Di Gudang (Cadangan)</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div class="stat-meta">
                <span class="stat-value">{{ $eksemplarDipinjam ?? 0 }}</span>
                <span class="stat-label">Dipinjam / Booking</span>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="filter-card">
        <form action="{{ route('buku.index') }}" method="GET" class="filter-form">
            @if(request()->boolean('perlu_restock'))
                <input type="hidden" name="perlu_restock" value="1">
            @endif

            <div class="search-input-wrapper">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text"
                       name="search"
                       class="search-input"
                       placeholder="Cari judul, ID eksemplar, token..."
                       value="{{ request('search') }}">
            </div>

            <select name="lokasi" class="filter-select">
                <option value="">Semua Lokasi</option>
                <option value="Rak" {{ request('lokasi') === 'Rak' ? 'selected' : '' }}>Di Rak (Display)</option>
                <option value="Gudang" {{ request('lokasi') === 'Gudang' ? 'selected' : '' }}>Di Gudang (Cadangan)</option>
            </select>

            <select name="kategori" class="filter-select">
                <option value="">Semua Kategori</option>
                @if(isset($kategoris))
                    @foreach ($kategoris as $kat)
                        <option value="{{ $kat->idKategori }}" {{ request('kategori') == $kat->idKategori ? 'selected' : '' }}>
                            {{ $kat->namaKategori }}
                        </option>
                    @endforeach
                @endif
            </select>

            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="Tersedia" {{ request('status') === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="Dipinjam" {{ request('status') === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="Dibooking" {{ request('status') === 'Dibooking' ? 'selected' : '' }}>Dibooking</option>
                <option value="Hilang" {{ request('status') === 'Hilang' ? 'selected' : '' }}>Hilang</option>
            </select>

            <select name="kondisi" class="filter-select">
                <option value="">Semua Kondisi</option>
                <option value="Baik" {{ request('kondisi') === 'Baik' ? 'selected' : '' }}>Kondisi Baik</option>
                <option value="Rusak" {{ request('kondisi') === 'Rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="Hilang" {{ request('kondisi') === 'Hilang' ? 'selected' : '' }}>Hilang</option>
            </select>

            <button type="submit" class="btn-filter-submit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                Filter Eksemplar
            </button>

            @if(request()->filled('search') || request()->filled('kategori') || request()->filled('status') || request()->filled('kondisi') || request()->filled('lokasi') || request()->boolean('perlu_restock'))
                <a href="{{ route('buku.index') }}" class="btn-filter-reset">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- DATA TABLE CARD: DIKELOMPOKKAN PER JUDUL BUKU DENGAN ACCORDION / DROPDOWN EKSEMPLAR -->
    <div class="table-card">
        <div class="table-toolbar-header">
            <div class="table-toolbar-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0f766e" stroke-width="2.2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span>Daftar Koleksi Judul Buku (1 Baris per Judul)</span>
            </div>
            <div class="table-toolbar-controls">
                <button type="button" class="btn-toggle-all" onclick="toggleAllEksemplarRows(true)" title="Buka seluruh rincian eksemplar">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                    Buka Semua
                </button>
                <button type="button" class="btn-toggle-all" onclick="toggleAllEksemplarRows(false)" title="Tutup seluruh rincian eksemplar">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <polyline points="18 15 12 9 6 15"></polyline>
                    </svg>
                    Tutup Semua
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="buku-table">
                <thead>
                    <tr>
                        <th style="width: 45px;">#</th>
                        <th style="min-width: 280px;">Informasi Buku</th>
                        <th style="width: 140px;">Kategori</th>
                        <th style="width: 180px;">Ringkasan Eksemplar</th>
                        <th style="width: 130px;">Ketersediaan</th>
                        <th style="text-align: right; min-width: 240px;">Aksi & Eksemplar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bukus as $buku)
                        @php
                            $items = $buku->eksemplar;
                            $totalCopies = $items->count();
                            $copiesInRak = $items->where('lokasi', 'Rak')->where('status', 'Tersedia')->count();
                            $copiesInGudang = $items->where('lokasi', 'Gudang')->where('status', 'Tersedia')->count();
                            $copiesBorrowed = $items->whereIn('status', ['Dipinjam', 'Dibooking'])->count();
                            $copiesAvailable = $items->where('status', 'Tersedia')->count();
                            $isPerluRestock = $buku->perluRestock();
                        @endphp

                        {{-- BARIS UTAMA (1 BARIS PER JUDUL BUKU) --}}
                        <tr class="main-book-row" id="main-row-{{ $buku->idBuku }}">
                            <td style="color: #94a3b8; font-weight: 700;">
                                {{ ($bukus->currentPage() - 1) * $bukus->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="book-cell">
                                    <div class="book-thumb">
                                        @if ($buku->cover_url)
                                            <img src="{{ $buku->cover_url }}"
                                                 alt="Cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background: #f1f5f9; color: #94a3b8;">
                                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="book-meta-title">{{ $buku->judul }}</div>
                                        <div class="book-meta-author">
                                            <span>{{ $buku->penulis ?? '-' }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $buku->penerbit ?? '-' }}</span>
                                            <span>({{ $buku->tahunTerbit ?? '-' }})</span>
                                            @if(!empty($buku->rak))
                                                <span>&bull;</span>
                                                <span style="color: #0f766e; font-weight: 600;">Rak: {{ $buku->rak }}</span>
                                            @endif
                                        </div>
                                        @if($isPerluRestock)
                                            <div>
                                                <span class="tag-perlu-restock">
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                                    Display Rak Kosong (Perlu Restock)
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-category">
                                    {{ $buku->kategori->namaKategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <span class="copies-summary-pill" title="Total eksemplar terdaftar untuk judul ini">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                                        {{ $totalCopies }} Eksemplar
                                    </span>
                                </div>
                                <div class="copies-breakdown-subtext">
                                    <span id="main-rak-{{ $buku->idBuku }}" style="color: #047857; font-weight: 600;">{{ $copiesInRak }} di Rak</span>
                                    <span>&bull;</span>
                                    <span id="main-gudang-{{ $buku->idBuku }}" style="color: #475569;">{{ $copiesInGudang }} di Gudang</span>
                                    @if($copiesBorrowed > 0)
                                        <span>&bull;</span>
                                        <span style="color: #1d4ed8;">{{ $copiesBorrowed }} Dipinjam</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if ($copiesAvailable > 0)
                                    <span class="status-badge tersedia">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Tersedia ({{ $copiesAvailable }})
                                    </span>
                                @elseif ($copiesBorrowed > 0)
                                    <span class="status-badge dipinjam">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        Semua Dipinjam
                                    </span>
                                @else
                                    <span class="status-badge hilang">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        Tidak Tersedia
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-group">
                                    {{-- Tombol Dropdown / Expand Eksemplar Fisik --}}
                                    <button type="button"
                                            id="btn-toggle-{{ $buku->idBuku }}"
                                            class="btn-toggle-eksemplar"
                                            onclick="toggleEksemplarRow({{ $buku->idBuku }})"
                                            title="Tampilkan daftar seluruh eksemplar fisik buku ini">
                                        <span>Lihat Eksemplar ({{ $totalCopies }})</span>
                                        <svg id="chevron-{{ $buku->idBuku }}" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.2s ease;">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </button>

                                    @if (auth()->user()->role === 'admin')
                                        {{-- Tombol Ubah Master Judul: Khusus Admin --}}
                                        <a href="{{ route('buku.edit', $buku->idBuku) }}"
                                           class="btn-action-edit"
                                           title="Edit Master Data Buku">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Ubah
                                        </a>

                                        {{-- Tombol Hapus: Khusus Admin --}}
                                        <form action="{{ route('buku.destroy', $buku->idBuku) }}"
                                              method="POST"
                                              style="display: inline; margin: 0;"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh data judul buku \'{{ addslashes($buku->judul) }}\' beserta eksemplarnya?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-delete" title="Hapus Data Buku">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- BARIS DETAIL YANG DAPAT DI-EXPAND / DROPDOWN (MENAMPILKAN SEMUA EKSEMPLAR FISIK) --}}
                        <tr id="eksemplar-row-{{ $buku->idBuku }}" class="eksemplar-detail-row" style="display: none;">
                            <td colspan="6" class="eksemplar-expanded-cell">
                                <div class="eksemplar-panel">
                                    <div class="eksemplar-panel-header">
                                        <div class="eksemplar-panel-title">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0f766e" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                            </svg>
                                            <span>Rincian Eksemplar Fisik: <strong>{{ $buku->judul }}</strong></span>
                                        </div>
                                        <div class="eksemplar-panel-meta">
                                            <span class="meta-tag" id="panel-total-{{ $buku->idBuku }}">Total: {{ $totalCopies }} Unit</span>
                                            <span class="meta-tag rak" id="panel-rak-{{ $buku->idBuku }}">Display Rak: {{ $copiesInRak }} Unit</span>
                                            <span class="meta-tag gudang" id="panel-gudang-{{ $buku->idBuku }}">Gudang Cadangan: {{ $copiesInGudang }} Unit</span>
                                            @if (auth()->user()->role === 'admin')
                                                <button type="button"
                                                        class="btn-panel-add-copy"
                                                        onclick="openModalTambahEksemplar({{ $buku->idBuku }}, '{{ addslashes($buku->judul) }}')">
                                                    + Tambah Eksemplar
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="eksemplar-subtable-wrapper">
                                        <table class="eksemplar-subtable">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40px;">No</th>
                                                    <th style="width: 180px;">ID & Nomor Eksemplar</th>
                                                    <th style="width: 140px;">Lokasi Fisik</th>
                                                    <th>Status Ketersediaan</th>
                                                    <th style="width: 110px;">Kondisi</th>
                                                    <th style="text-align: right; width: 260px;">Aksi Eksemplar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $item)
                                                    @php
                                                        $activeDetail = $item->activeDetail;
                                                        $activePeminjaman = $activeDetail?->peminjaman;
                                                    @endphp
                                                    <tr>
                                                        <td style="color: #94a3b8; font-weight: 700; font-size: 11.5px;">
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        <td>
                                                            <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                                                <span class="copy-id-badge" title="ID Unik Database: {{ $item->idEksemplar }}">
                                                                    #EK-{{ $item->idEksemplar }}
                                                                </span>
                                                                <span class="copy-number-tag">
                                                                    Eksemplar #{{ $item->nomor_eksemplar }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td id="lokasi-cell-{{ $item->idEksemplar }}">
                                                            @if ($item->lokasi === 'Rak')
                                                                <span class="lokasi-badge rak" title="Buku sedang dipajang di rak display">
                                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                                                    Di Rak (Display)
                                                                </span>
                                                            @else
                                                                <span class="lokasi-badge gudang" title="Buku disimpan di gudang cadangan">
                                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path></svg>
                                                                    Gudang (Cadangan)
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->status === 'Tersedia')
                                                                <span class="status-badge tersedia">
                                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                                    </svg>
                                                                    Tersedia
                                                                </span>
                                                            @elseif ($item->status === 'Dipinjam')
                                                                <span class="status-badge dipinjam">
                                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                                                        <circle cx="12" cy="12" r="10"></circle>
                                                                        <polyline points="12 6 12 12 16 14"></polyline>
                                                                    </svg>
                                                                    Dipinjam
                                                                </span>
                                                                @if ($activePeminjaman)
                                                                    <div class="borrower-subtext">
                                                                        <span title="Peminjam: {{ $activePeminjaman->member->name ?? '-' }}">
                                                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                                            {{ Str::limit($activePeminjaman->member->name ?? 'Member', 16) }}
                                                                        </span>
                                                                        <span>Tempo: {{ \Carbon\Carbon::parse($activePeminjaman->batasKembali)->translatedFormat('d M') }}</span>
                                                                    </div>
                                                                @endif
                                                            @elseif ($item->status === 'Dibooking')
                                                                <span class="status-badge dibooking">
                                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                    </svg>
                                                                    Dibooking
                                                                </span>
                                                                @if ($activePeminjaman)
                                                                    <div class="borrower-subtext">
                                                                        <span>{{ Str::limit($activePeminjaman->member->name ?? 'Member', 16) }}</span>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <span class="status-badge hilang">
                                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                                    </svg>
                                                                    Hilang
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="condition-badge {{ strtolower($item->kondisi ?? 'baik') }}">
                                                                {{ $item->kondisi ?? 'Baik' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="action-group">
                                                                @if (auth()->user()->role === 'admin')
                                                                    {{-- AKSI EKSEMPLAR UNTUK ADMIN: LABEL QR & HAPUS --}}
                                                                    {{-- Tombol Label QR Eksemplar --}}
                                                                    <a href="{{ route('eksemplar.cetak-qr', $item->idEksemplar) }}"
                                                                       class="btn-action-qr-link"
                                                                       target="_blank"
                                                                       title="Lihat Label Eksemplar #{{ $item->nomor_eksemplar }}">
                                                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                                                            <rect x="3" y="3" width="7" height="7"></rect>
                                                                            <rect x="14" y="3" width="7" height="7"></rect>
                                                                            <rect x="14" y="14" width="7" height="7"></rect>
                                                                            <rect x="3" y="14" width="7" height="7"></rect>
                                                                        </svg>
                                                                        Label QR
                                                                    </a>

                                                                    {{-- Tombol Hapus Eksemplar Fisik Individual --}}
                                                                    @if ($item->isSedangDipinjam())
                                                                        <button type="button"
                                                                                class="btn-action-delete"
                                                                                style="opacity: 0.5; cursor: not-allowed;"
                                                                                disabled
                                                                                title="Eksemplar sedang dipinjam, tidak dapat dihapus">
                                                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                            </svg>
                                                                            Hapus
                                                                        </button>
                                                                    @else
                                                                        <form action="{{ route('eksemplar.destroy', $item->idEksemplar) }}"
                                                                              method="POST"
                                                                              style="display: inline; margin: 0;"
                                                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus Eksemplar #{{ $item->nomor_eksemplar }} (ID: {{ $item->idEksemplar }}) dari buku \'{{ addslashes($buku->judul) }}\'?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                    class="btn-action-delete"
                                                                                    title="Hapus Eksemplar #{{ $item->nomor_eksemplar }}">
                                                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                                </svg>
                                                                                Hapus
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                @else
                                                                    {{-- AKSI EKSEMPLAR UNTUK PETUGAS: OPERASIONAL RAK & KELOLA KONDISI --}}
                                                                    <span id="op-btn-container-{{ $item->idEksemplar }}">
                                                                        @if ($item->status === 'Tersedia' && $item->lokasi === 'Gudang')
                                                                            <form action="{{ route('eksemplar.restock', $item->idEksemplar) }}"
                                                                                  method="POST"
                                                                                  style="display: inline; margin: 0;"
                                                                                  onsubmit="pindahkanEksemplarAjax(event, {{ $item->idEksemplar }}, '{{ route('eksemplar.restock', $item->idEksemplar) }}', 'Rak', {{ $buku->idBuku }}, {{ $item->nomor_eksemplar }}, '{{ addslashes($buku->judul) }}', '{{ $item->kondisi ?? 'Baik' }}', '{{ $item->status }}')">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <button type="submit"
                                                                                        class="btn-action-pajang"
                                                                                        title="Pajang Eksemplar #{{ $item->nomor_eksemplar }} ke Rak Display">
                                                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                                                                    Pajang ke Rak
                                                                                </button>
                                                                            </form>
                                                                        @elseif ($item->status === 'Tersedia' && $item->lokasi === 'Rak')
                                                                            <form action="{{ route('eksemplar.simpan-gudang', $item->idEksemplar) }}"
                                                                                  method="POST"
                                                                                  style="display: inline; margin: 0;"
                                                                                  onsubmit="pindahkanEksemplarAjax(event, {{ $item->idEksemplar }}, '{{ route('eksemplar.simpan-gudang', $item->idEksemplar) }}', 'Gudang', {{ $buku->idBuku }}, {{ $item->nomor_eksemplar }}, '{{ addslashes($buku->judul) }}', '{{ $item->kondisi ?? 'Baik' }}', '{{ $item->status }}')">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <button type="submit"
                                                                                        class="btn-action-simpan"
                                                                                        title="Simpan Eksemplar #{{ $item->nomor_eksemplar }} ke Gudang Cadangan">
                                                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path></svg>
                                                                                    Simpan Gudang
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    </span>

                                                                    <button type="button"
                                                                            id="btn-kelola-{{ $item->idEksemplar }}"
                                                                            class="btn-action-manage"
                                                                            onclick="openModalEditEksemplar({{ $item->idEksemplar }}, '{{ addslashes($buku->judul) }}', {{ $item->nomor_eksemplar }}, '{{ $item->kondisi ?? 'Baik' }}', '{{ $item->status }}', {{ $item->isSedangDipinjam() ? 'true' : 'false' }}, '{{ $item->lokasi ?? 'Rak' }}')"
                                                                            title="Ubah Kondisi / Lokasi Eksemplar #{{ $item->nomor_eksemplar }}">
                                                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                                        </svg>
                                                                        Kelola
                                                                    </button>

                                                                    <a href="{{ route('eksemplar.cetak-qr', $item->idEksemplar) }}"
                                                                       class="btn-action-qr-link"
                                                                       target="_blank"
                                                                       title="Lihat Label Eksemplar #{{ $item->nomor_eksemplar }}">
                                                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                                                            <rect x="3" y="3" width="7" height="7"></rect>
                                                                            <rect x="14" y="3" width="7" height="7"></rect>
                                                                            <rect x="14" y="14" width="7" height="7"></rect>
                                                                            <rect x="3" y="14" width="7" height="7"></rect>
                                                                        </svg>
                                                                        Label QR
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 6 }}" style="text-align: center; padding: 48px 20px;">
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px;">
                                    <div style="width: 52px; height: 52px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                    </div>
                                    <div style="font-weight: 700; color: #334155; font-size: 15px;">Tidak Ada Buku Ditemukan</div>
                                    <div style="font-size: 13px; color: #64748b; max-width: 380px;">
                                        @if(request()->filled('search') || request()->filled('kategori') || request()->filled('status') || request()->filled('kondisi') || request()->filled('lokasi') || request()->boolean('perlu_restock'))
                                            Tidak ada koleksi buku yang sesuai dengan filter pencarian Anda. Coba reset filter.
                                        @else
                                            Belum ada koleksi buku yang terdaftar di perpustakaan.
                                        @endif
                                    </div>
                                    @if(request()->filled('search') || request()->filled('kategori') || request()->filled('status') || request()->filled('kondisi') || request()->filled('lokasi') || request()->boolean('perlu_restock'))
                                        <a href="{{ route('buku.index') }}" class="btn-filter-reset" style="margin-top: 6px;">
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
                Menampilkan <strong>{{ $bukus->firstItem() ?? 0 }}</strong> - <strong>{{ $bukus->lastItem() ?? 0 }}</strong> dari <strong>{{ $bukus->total() }}</strong> judul buku (Total <strong>{{ $totalEksemplar }}</strong> eksemplar fisik)
            </div>
            <div>
                {{ $bukus->links() }}
            </div>
        </div>
    </div>
</div>

{{-- MODAL KELOLA KONDISI & LOKASI EKSEMPLAR INDIVIDUAL --}}
<div id="modalEditEksemplar" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.45); z-index: 100; align-items: center; justify-content: center; padding: 16px; backdrop-filter: blur(2px);">
    <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 440px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative;" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0 0 4px;">Kelola Eksemplar Fisik</h3>
                <p id="modalEksJudul" style="font-size: 13px; color: #64748b; margin: 0; word-break: break-word;">Judul Buku</p>
                <div id="modalEksMeta" style="font-size: 12px; font-weight: 700; color: #0f766e; margin-top: 4px;">Eksemplar #1 (ID: 0)</div>
            </div>
            <button type="button" onclick="closeModalEditEksemplar()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94a3b8; line-height: 1;">&times;</button>
        </div>

        <form id="formEditEksemplar" action="" method="POST">
            @csrf
            @method('PATCH')

            <div style="margin-bottom: 14px;">
                <label for="selectLokasiEksemplar" style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                    Lokasi Penempatan Fisik
                </label>
                <select name="lokasi" id="selectLokasiEksemplar" style="width: 100%; padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 9px; font-size: 13.5px; font-weight: 600; color: #0f172a; background: #fff;">
                    <option value="Rak">Rak (Display Terbuka)</option>
                    <option value="Gudang">Gudang (Cadangan / Penyimpanan)</option>
                </select>
                <span style="font-size: 11.5px; color: #64748b; margin-top: 4px; display: block;">
                    *Maksimal disarankan 1-2 buku per judul di rak display untuk menghemat kapasitas rak.
                </span>
            </div>

            <div style="margin-bottom: 14px;">
                <label for="selectKondisiEksemplar" style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                    Kondisi Fisik Eksemplar
                </label>
                <select name="kondisi" id="selectKondisiEksemplar" style="width: 100%; padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 9px; font-size: 13.5px; font-weight: 600; color: #0f172a; background: #fff;">
                    <option value="Baik">Baik (Bebas Kerusakan)</option>
                    <option value="Rusak">Rusak</option>
                    <option value="Hilang">Hilang</option>
                </select>
            </div>

            <div style="margin-bottom: 20px;" id="groupStatusEksemplar">
                <label for="selectStatusEksemplar" style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                    Status Ketersediaan
                </label>
                <select name="status" id="selectStatusEksemplar" style="width: 100%; padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 9px; font-size: 13.5px; font-weight: 600; color: #0f172a; background: #fff;">
                    <option value="Tersedia">Tersedia</option>
                    <option value="Hilang">Hilang (Tidak Tersedia)</option>
                </select>
                <span id="loanWarningText" style="display: none; font-size: 11.5px; color: #b45309; margin-top: 5px;">
                    *Eksemplar ini sedang dalam transaksi peminjaman/booking aktif. Status otomatis tersinkronisasi saat anggota menyelesaikan pengembalian.
                </span>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModalEditEksemplar()" style="padding: 10px 18px; border-radius: 9px; font-size: 13px; font-weight: 700; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 20px; border-radius: 9px; font-size: 13px; font-weight: 700; border: none; background: #0f766e; color: #ffffff; cursor: pointer;">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH EKSEMPLAR BARU (ADMIN) --}}
@if (auth()->user()->role === 'admin')
<div id="modalTambahEksemplar" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.45); z-index: 100; align-items: center; justify-content: center; padding: 16px; backdrop-filter: blur(2px);">
    <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 440px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative;" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0 0 4px;">Tambah Eksemplar Fisik</h3>
                <p id="modalTambahJudul" style="font-size: 13px; color: #64748b; margin: 0; word-break: break-word;">Judul Buku</p>
            </div>
            <button type="button" onclick="closeModalTambahEksemplar()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94a3b8; line-height: 1;">&times;</button>
        </div>

        <form id="formTambahEksemplar" action="" method="POST">
            @csrf

            <div style="margin-bottom: 14px;">
                <label for="inputJumlahTambah" style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                    Jumlah Eksemplar Fisik Ditambahkan
                </label>
                <input type="number"
                       name="jumlah"
                       id="inputJumlahTambah"
                       min="1"
                       max="50"
                       value="1"
                       class="form-control"
                       required
                       style="width: 100%; padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 9px; font-size: 14px; font-weight: 700; color: #0f172a;">
                <span style="font-size: 11.5px; color: #64748b; margin-top: 4px; display: block;">
                    *Setiap eksemplar akan mendapatkan ID dan nomor unik otomatis secara berurutan.
                </span>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="selectLokasiTambah" style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                    Penempatan Fisik
                </label>
                <select name="lokasi" id="selectLokasiTambah" style="width: 100%; padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 9px; font-size: 13.5px; font-weight: 600; color: #0f172a; background: #fff;">
                    <option value="">Otomatis (Maks. 2 di Rak Display, sisanya Gudang)</option>
                    <option value="Rak">Tempatkan di Rak (Display)</option>
                    <option value="Gudang">Simpan di Gudang (Cadangan)</option>
                </select>
            </div>

            <input type="hidden" name="kondisi" value="Baik">

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModalTambahEksemplar()" style="padding: 10px 18px; border-radius: 9px; font-size: 13px; font-weight: 700; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 20px; border-radius: 9px; font-size: 13px; font-weight: 700; border: none; background: #0f766e; color: #ffffff; cursor: pointer;">
                    + Simpan Eksemplar
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';

    function escapeQuotes(str) {
        if (!str) return '';
        return String(str).replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    function saveOpenedRow(idBuku) {
        try {
            sessionStorage.setItem('open_eksemplar_' + idBuku, '1');
        } catch(e) {}
    }

    function removeOpenedRow(idBuku) {
        try {
            sessionStorage.removeItem('open_eksemplar_' + idBuku);
        } catch(e) {}
    }

    function toggleEksemplarRow(idBuku) {
        const row = document.getElementById('eksemplar-row-' + idBuku);
        const btn = document.getElementById('btn-toggle-' + idBuku);
        const chevron = document.getElementById('chevron-' + idBuku);
        const parentRow = document.getElementById('main-row-' + idBuku);

        if (!row) return;

        if (row.style.display === 'none' || row.style.display === '') {
            row.style.display = 'table-row';
            if (btn) btn.classList.add('active');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
            if (parentRow) parentRow.classList.add('expanded-parent');
            saveOpenedRow(idBuku);
        } else {
            row.style.display = 'none';
            if (btn) btn.classList.remove('active');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
            if (parentRow) parentRow.classList.remove('expanded-parent');
            removeOpenedRow(idBuku);
        }
    }

    function toggleAllEksemplarRows(expand) {
        const rows = document.querySelectorAll('.eksemplar-detail-row');
        rows.forEach(row => {
            row.style.display = expand ? 'table-row' : 'none';
            const idBuku = row.id.replace('eksemplar-row-', '');
            const btn = document.getElementById('btn-toggle-' + idBuku);
            const chevron = document.getElementById('chevron-' + idBuku);
            const parentRow = document.getElementById('main-row-' + idBuku);

            if (btn) btn.classList.toggle('active', expand);
            if (chevron) chevron.style.transform = expand ? 'rotate(180deg)' : 'rotate(0deg)';
            if (parentRow) parentRow.classList.toggle('expanded-parent', expand);

            if (expand) {
                saveOpenedRow(idBuku);
            } else {
                removeOpenedRow(idBuku);
            }
        });
    }

    function openModalEditEksemplar(idEksemplar, judul, nomor, kondisi, status, isBorrowed, lokasi) {
        const modal = document.getElementById('modalEditEksemplar');
        const form = document.getElementById('formEditEksemplar');
        document.getElementById('modalEksJudul').textContent = judul;
        document.getElementById('modalEksMeta').textContent = 'Eksemplar #' + nomor + ' (ID: ' + idEksemplar + ')';
        document.getElementById('selectKondisiEksemplar').value = kondisi || 'Baik';
        document.getElementById('selectLokasiEksemplar').value = lokasi || 'Rak';

        const statusSelect = document.getElementById('selectStatusEksemplar');
        const lokasiSelect = document.getElementById('selectLokasiEksemplar');
        const warningText = document.getElementById('loanWarningText');

        if (isBorrowed) {
            statusSelect.disabled = true;
            lokasiSelect.disabled = true;
            warningText.style.display = 'block';
        } else {
            statusSelect.disabled = false;
            lokasiSelect.disabled = false;
            statusSelect.value = status || 'Tersedia';
            warningText.style.display = 'none';
        }

        form.action = "{{ url('/eksemplar') }}/" + idEksemplar + "/kondisi";
        modal.style.display = 'flex';
    }

    function closeModalEditEksemplar() {
        document.getElementById('modalEditEksemplar').style.display = 'none';
    }

    function openModalTambahEksemplar(idBuku, judul) {
        const modal = document.getElementById('modalTambahEksemplar');
        if (!modal) return;
        const form = document.getElementById('formTambahEksemplar');
        document.getElementById('modalTambahJudul').textContent = judul;
        form.action = "{{ url('/eksemplar/tambah') }}/" + idBuku;
        modal.style.display = 'flex';
    }

    function closeModalTambahEksemplar() {
        const modal = document.getElementById('modalTambahEksemplar');
        if (modal) modal.style.display = 'none';
    }

    // Modal click listeners
    const modalEditEl = document.getElementById('modalEditEksemplar');
    if (modalEditEl) {
        modalEditEl.addEventListener('click', function(e) {
            if (e.target === this) closeModalEditEksemplar();
        });
    }

    const modalTambah = document.getElementById('modalTambahEksemplar');
    if (modalTambah) {
        modalTambah.addEventListener('click', function(e) {
            if (e.target === this) closeModalTambahEksemplar();
        });
    }

    // Floating Toast Notification
    function showToastNotification(message, type = 'success') {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = 'position: fixed; top: 24px; right: 24px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; pointer-events: none; max-width: 420px;';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        const isSuccess = type === 'success';
        toast.style.cssText = `
            display: flex;
            align-items: center;
            gap: 12px;
            background: ${isSuccess ? '#0f766e' : '#b91c1c'};
            color: #ffffff;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25), 0 8px 10px -6px rgba(0, 0, 0, 0.2);
            pointer-events: auto;
            transform: translateX(30px);
            opacity: 0;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        `;

        const iconSvg = isSuccess
            ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="20 6 9 17 4 12"></polyline></svg>`
            : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`;

        toast.innerHTML = `${iconSvg}<span>${message}</span>`;
        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        });

        setTimeout(() => {
            toast.style.transform = 'translateX(30px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    // Realtime AJAX Operasional Eksemplar (Simpan Gudang / Pajang ke Rak)
    async function pindahkanEksemplarAjax(e, idEksemplar, url, targetLokasi, idBuku, nomorEksemplar, judulBuku, kondisi, status) {
        if (e) e.preventDefault();

        const form = e.target;
        const btn = form.querySelector('button');
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.5';
        }

        try {
            const response = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // 1. Update Lokasi Badge pada cell tabel
                const lokasiCell = document.getElementById('lokasi-cell-' + idEksemplar);
                if (lokasiCell) {
                    if (data.lokasi === 'Rak') {
                        lokasiCell.innerHTML = `
                            <span class="lokasi-badge rak" title="Buku sedang dipajang di rak display">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                Di Rak (Display)
                            </span>
                        `;
                    } else {
                        lokasiCell.innerHTML = `
                            <span class="lokasi-badge gudang" title="Buku disimpan di gudang cadangan">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path></svg>
                                Gudang (Cadangan)
                            </span>
                        `;
                    }
                }

                // 2. Toggle Tombol Aksi (Simpan Gudang <-> Pajang ke Rak)
                const opContainer = document.getElementById('op-btn-container-' + idEksemplar);
                if (opContainer) {
                    const routePajang = "{{ url('/eksemplar') }}/" + idEksemplar + "/restock";
                    const routeSimpan = "{{ url('/eksemplar') }}/" + idEksemplar + "/simpan-gudang";
                    const safeJudul = escapeQuotes(judulBuku);

                    if (data.lokasi === 'Rak') {
                        opContainer.innerHTML = `
                            <form action="${routeSimpan}" method="POST" style="display: inline; margin: 0;"
                                  onsubmit="pindahkanEksemplarAjax(event, ${idEksemplar}, '${routeSimpan}', 'Gudang', ${idBuku}, ${nomorEksemplar}, '${safeJudul}', '${kondisi}', '${status}')">
                                <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                                <input type="hidden" name="_method" value="PATCH">
                                <button type="submit" class="btn-action-simpan" title="Simpan Eksemplar #${nomorEksemplar} ke Gudang Cadangan">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path></svg>
                                    Simpan Gudang
                                </button>
                            </form>
                        `;
                    } else {
                        opContainer.innerHTML = `
                            <form action="${routePajang}" method="POST" style="display: inline; margin: 0;"
                                  onsubmit="pindahkanEksemplarAjax(event, ${idEksemplar}, '${routePajang}', 'Rak', ${idBuku}, ${nomorEksemplar}, '${safeJudul}', '${kondisi}', '${status}')">
                                <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                                <input type="hidden" name="_method" value="PATCH">
                                <button type="submit" class="btn-action-pajang" title="Pajang Eksemplar #${nomorEksemplar} ke Rak Display">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                    Pajang ke Rak
                                </button>
                            </form>
                        `;
                    }
                }

                // 3. Update lokasi parameter pada tombol Kelola
                const btnKelola = document.getElementById('btn-kelola-' + idEksemplar);
                if (btnKelola) {
                    btnKelola.setAttribute('onclick', `openModalEditEksemplar(${idEksemplar}, '${escapeQuotes(judulBuku)}', ${nomorEksemplar}, '${kondisi}', '${status}', false, '${data.lokasi}')`);
                }

                // 4. Update Panel Meta Tags di header rincian eksemplar
                const panelRak = document.getElementById('panel-rak-' + idBuku);
                const panelGudang = document.getElementById('panel-gudang-' + idBuku);
                if (panelRak && typeof data.copiesInRak !== 'undefined') {
                    panelRak.textContent = 'Display Rak: ' + data.copiesInRak + ' Unit';
                }
                if (panelGudang && typeof data.copiesInGudang !== 'undefined') {
                    panelGudang.textContent = 'Gudang Cadangan: ' + data.copiesInGudang + ' Unit';
                }

                // 5. Update Breakdown Text di baris utama tabel buku
                const mainRak = document.getElementById('main-rak-' + idBuku);
                const mainGudang = document.getElementById('main-gudang-' + idBuku);
                if (mainRak && typeof data.copiesInRak !== 'undefined') {
                    mainRak.textContent = data.copiesInRak + ' di Rak';
                }
                if (mainGudang && typeof data.copiesInGudang !== 'undefined') {
                    mainGudang.textContent = data.copiesInGudang + ' di Gudang';
                }

                // 6. Update Indikator Statistik Global di atas
                const statRak = document.getElementById('stat-eksemplar-rak');
                const statGudang = document.getElementById('stat-eksemplar-gudang');
                if (statRak && statGudang) {
                    let currentRak = parseInt(statRak.textContent.trim()) || 0;
                    let currentGudang = parseInt(statGudang.textContent.trim()) || 0;
                    if (data.lokasi === 'Rak') {
                        statRak.textContent = currentRak + 1;
                        statGudang.textContent = Math.max(0, currentGudang - 1);
                    } else {
                        statRak.textContent = Math.max(0, currentRak - 1);
                        statGudang.textContent = currentGudang + 1;
                    }
                }

                // 7. Notifikasi Toast Sukses
                showToastNotification(data.message, 'success');
            } else {
                showToastNotification(data.message || 'Gagal mengubah lokasi eksemplar.', 'error');
                if (btn) {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                }
            }
        } catch (err) {
            console.error(err);
            showToastNotification('Terjadi kesalahan koneksi saat memproses pemindahan eksemplar.', 'error');
            if (btn) {
                btn.disabled = false;
                btn.style.opacity = '1';
            }
        }
    }

    // Pulihkan accordion eksemplar yang sedang terbuka saat halaman dimuat ulang
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.eksemplar-detail-row').forEach(function(row) {
            const idBuku = row.id.replace('eksemplar-row-', '');
            if (sessionStorage.getItem('open_eksemplar_' + idBuku) === '1') {
                const btn = document.getElementById('btn-toggle-' + idBuku);
                const chevron = document.getElementById('chevron-' + idBuku);
                const parentRow = document.getElementById('main-row-' + idBuku);
                row.style.display = 'table-row';
                if (btn) btn.classList.add('active');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
                if (parentRow) parentRow.classList.add('expanded-parent');
            }
        });
    });
</script>
@endsection