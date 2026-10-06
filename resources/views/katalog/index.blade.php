<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Katalog Buku - BOOKNEST Perpustakaan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-primary: #0f766e;
            --brand-primary-dark: #115e59;
            --brand-primary-light: #ccfbf1;
            --brand-accent: #14b8a6;
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-body);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font-family: inherit;
            cursor: pointer;
            border: none;
            background: none;
        }

        /* --- NAVBAR --- */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
        }

        .topbar-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text-heading);
        }

        .topbar-brand-icon {
            width: 36px;
            height: 36px;
            background: #0f766e;
            color: #ffffff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .topbar-nav {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-nav-link {
            font-size: 13.5px;
            font-weight: 600;
            color: #64748b;
            padding: 6px 14px;
            border-radius: 8px;
            transition: all 0.15s;
        }

        .topbar-nav-link:hover {
            color: #0f766e;
        }

        .topbar-nav-link.active {
            background: #ccfbf1;
            color: #0f766e;
            font-weight: 700;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-auth-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            background: #2563eb;
            color: #ffffff;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
            transition: all 0.15s;
        }

        .btn-auth-login:hover {
            background: #1d4ed8;
        }

        .btn-auth-register {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            background: #0f766e;
            color: #ffffff;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
            transition: all 0.15s;
        }

        .btn-auth-register:hover {
            background: #115e59;
        }

        .btn-user-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .btn-user-badge:hover {
            background: #e2e8f0;
        }

        .user-initials {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: #0f766e;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
        }

        /* --- MAIN WRAPPER --- */
        .catalog-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 32px 24px 70px;
        }

        /* --- BREADCRUMB & HEADER --- */
        .breadcrumb {
            font-size: 12px;
            font-weight: 700;
            color: #0f766e;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .catalog-title {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.6px;
            line-height: 1.2;
            margin-bottom: 6px;
        }

        .catalog-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 26px;
        }

        /* --- SEARCH & FILTER CARD --- */
        .filter-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.02);
            margin-bottom: 24px;
        }

        .filter-section-label {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 10px;
        }

        .search-bar-row {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
        }

        .search-input-wrap {
            position: relative;
            flex: 1;
        }

        .search-input-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            font-size: 13.5px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            color: #0f172a;
            background: #ffffff;
            transition: all 0.2s;
        }

        .search-input:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
        }

        .btn-search {
            padding: 12px 26px;
            background: #0f766e;
            color: #ffffff;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .btn-search:hover {
            background: #115e59;
        }

        .filter-dropdowns-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .dropdown-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .dropdown-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }

        .dropdown-select-wrap {
            position: relative;
        }

        .dropdown-select {
            width: 100%;
            padding: 10px 34px 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13px;
            color: #0f172a;
            background: #ffffff;
            outline: none;
            cursor: pointer;
            appearance: none;
            font-family: inherit;
            transition: all 0.2s;
        }

        .dropdown-select:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
        }

        .dropdown-select-wrap svg {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
        }

        /* --- RESULTS SUMMARY BAR --- */
        .results-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .results-count-text {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .results-count-text span {
            color: #64748b;
            font-weight: 500;
        }

        .sort-select-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #64748b;
        }

        .sort-select {
            padding: 6px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 12.5px;
            color: #0f172a;
            background: #ffffff;
            outline: none;
            cursor: pointer;
            font-family: inherit;
        }

        /* --- BOOK GRID (4 COLUMNS) --- */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .book-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.07);
        }

        /* Book Cover Visual */
        .book-cover-container {
            width: 100%;
            height: 220px;
            background: #f1f5f9;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .book-cover-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .book-card:hover .book-cover-img {
            transform: scale(1.03);
        }

        .book-cover-fallback {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .book-cover-fallback h4 {
            font-size: 15px;
            font-weight: 800;
            line-height: 1.2;
            margin-top: 10px;
        }

        .book-cover-fallback p {
            font-size: 11px;
            color: #ccfbf1;
            margin-top: 4px;
        }

        /* Book Body */
        .book-body {
            padding: 16px 18px 18px;
            display: flex;
            flex-direction: column;
            flex: 1;
            justify-content: space-between;
        }

        .book-badges-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
        }

        .badge-status.tersedia {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-status.habis {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .badge-status.tersedia .badge-dot {
            background: #16a34a;
        }

        .badge-status.habis .badge-dot {
            background: #dc2626;
        }

        .badge-rating {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
        }

        .badge-rating svg {
            color: #f59e0b;
            fill: #f59e0b;
        }

        .book-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-heading);
            line-height: 1.3;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .book-title a {
            transition: color 0.15s;
        }

        .book-title a:hover {
            color: #0f766e;
        }

        .book-author-cat {
            font-size: 12.5px;
            color: var(--text-muted);
            margin-bottom: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .book-meta-row {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 11.5px;
            color: #64748b;
            padding-top: 10px;
            border-top: 1px solid #f1f5f9;
            margin-bottom: 14px;
        }

        .book-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-pinjam-buku {
            width: 100%;
            padding: 10px;
            background: #0f766e;
            color: #ffffff;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background 0.15s, transform 0.1s;
        }

        .btn-pinjam-buku:hover {
            background: #115e59;
        }

        .btn-pinjam-buku:active {
            transform: scale(0.98);
        }

        /* --- EMPTY STATE --- */
        .empty-katalog {
            grid-column: 1 / -1;
            background: #ffffff;
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            padding: 56px 24px;
            text-align: center;
        }

        .empty-katalog-icon {
            width: 52px;
            height: 52px;
            background: #f1f5f9;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            margin-bottom: 16px;
        }

        .empty-katalog h3 {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 6px;
        }

        .empty-katalog p {
            font-size: 13.5px;
            color: var(--text-muted);
            max-width: 440px;
            margin: 0 auto 18px;
        }

        .btn-reset-filter {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #0f766e;
            color: #ffffff;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
        }

        /* --- PAGINATION --- */
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 10px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .pagination-info {
            font-size: 13px;
            color: var(--text-muted);
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .page-btn {
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: #ffffff;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.15s;
        }

        .page-btn:hover:not(:disabled):not(.active) {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .page-btn.active {
            background: #0f766e;
            color: #ffffff;
            border-color: #0f766e;
            font-weight: 700;
        }

        .page-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .page-dots {
            padding: 0 4px;
            color: #94a3b8;
            font-weight: 700;
        }

        /* --- MODAL PINJAM BUKU --- */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(3px);
            z-index: 60;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-backdrop.show {
            display: flex;
        }

        .modal-card {
            background: #ffffff;
            border-radius: 20px;
            max-width: 500px;
            width: 100%;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
            animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(8px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-title-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #ccfbf1;
            color: #0f766e;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .btn-modal-close {
            color: #94a3b8;
            padding: 4px;
            border-radius: 6px;
            transition: color 0.15s;
        }

        .btn-modal-close:hover {
            color: #0f172a;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-book-preview {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
            background: #f8fafc;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .modal-book-cover {
            width: 60px;
            height: 84px;
            border-radius: 6px;
            object-fit: cover;
            background: #e2e8f0;
            flex-shrink: 0;
        }

        .modal-book-details {
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
        }

        .modal-book-title {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 4px;
        }

        .modal-book-author {
            font-size: 12.5px;
            color: #64748b;
        }

        .modal-location-box {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 18px;
        }

        .modal-location-icon {
            color: #059669;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .modal-location-text h4 {
            font-size: 13.5px;
            font-weight: 800;
            color: #065f46;
            margin-bottom: 2px;
        }

        .modal-location-text p {
            font-size: 12.5px;
            color: #047857;
            line-height: 1.4;
        }

        .modal-instructions {
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 22px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-modal-cancel {
            padding: 10px 18px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            transition: all 0.15s;
        }

        .btn-modal-cancel:hover {
            background: #f1f5f9;
        }

        .btn-modal-primary {
            padding: 10px 20px;
            background: #0f766e;
            color: #ffffff;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
        }

        .btn-modal-primary:hover {
            background: #115e59;
        }

        /* --- FOOTER --- */
        .footer {
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            padding: 48px 24px 32px;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1240px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.3fr 1fr 1.2fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand h3 {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 8px;
        }

        .footer-brand p {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .footer-heading {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 14px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .footer-links a {
            font-size: 13px;
            color: var(--text-muted);
            transition: color 0.15s;
        }

        .footer-links a:hover {
            color: #0f766e;
        }

        .footer-contact-item {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 6px;
            line-height: 1.5;
        }

        .footer-copyright {
            max-width: 1240px;
            margin: 0 auto;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
            font-size: 12.5px;
            color: #94a3b8;
            text-align: left;
        }

        /* --- RESPONSIVE BREAKPOINTS --- */
        @media (max-width: 1024px) {
            .book-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            .footer-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .topbar-nav {
                display: none;
            }
            .filter-dropdowns-grid {
                grid-template-columns: 1fr;
            }
            .book-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .footer-container {
                grid-template-columns: 1fr;
            }
            .results-bar {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .book-grid {
                grid-template-columns: 1fr;
            }
            .search-bar-row {
                flex-direction: column;
            }
            .pagination-container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

    <!-- TOPBAR NAVBAR -->
    <header class="topbar">
        <div class="topbar-container">
            <!-- Brand -->
            <a href="{{ route('home') }}" class="topbar-brand">
                <div class="topbar-brand-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <span>BOOKNEST</span>
            </a>

            <!-- Nav Links -->
            <nav class="topbar-nav">
                <a href="{{ route('home') }}" class="topbar-nav-link">
                    Beranda
                </a>
                <a href="{{ route('katalog.index') }}" class="topbar-nav-link active">
                    Katalog
                </a>
                <a href="{{ route('dashboard') }}" class="topbar-nav-link">
                    Dasbor
                </a>
                <a href="{{ route('tentang') }}" class="topbar-nav-link">
                    Tentang
                </a>
            </nav>

            <!-- Actions (Guest / Auth) -->
            <div class="topbar-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-user-badge" title="Buka Dasbor Saya">
                        <div class="user-initials">{{ auth()->user()->inisial ?? 'US' }}</div>
                        <span>Dasbor</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-auth-login">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn-auth-register">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT WRAPPER -->
    <main class="catalog-container">

        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <span>BOOKNEST</span>
            <span>/</span>
            <span>Perpustakaan umum</span>
        </div>

        <!-- Header Titles -->
        <h1 class="catalog-title">Katalog Buku</h1>
        <p class="catalog-subtitle">Temukan cerita, pengetahuan, dan inspirasi untuk setiap hari.</p>

        <!-- SEARCH & FILTERS FORM -->
        <form action="{{ route('katalog.index') }}" method="GET" id="catalogFilterForm" class="filter-card">
            <div class="filter-section-label">Pencarian buku</div>

            <!-- Big Search Bar -->
            <div class="search-bar-row">
                <div class="search-input-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text"
                           name="q"
                           value="{{ $keyword }}"
                           placeholder="Cari judul, penulis, atau kategori..."
                           class="search-input">
                </div>
                <button type="submit" class="btn-search">
                    Cari
                </button>
            </div>

            <!-- 3 Dropdown Filters Grid -->
            <div class="filter-dropdowns-grid">
                <!-- 1. Kategori -->
                <div class="dropdown-field">
                    <label for="filterKategori" class="dropdown-label">Kategori</label>
                    <div class="dropdown-select-wrap">
                        <select name="kategori" id="filterKategori" class="dropdown-select" onchange="document.getElementById('catalogFilterForm').submit()">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->idKategori }}" {{ (string)$kategoriId === (string)$kat->idKategori ? 'selected' : '' }}>
                                    {{ $kat->namaKategori }} ({{ $kat->buku_count ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>

                <!-- 2. Lokasi -->
                <div class="dropdown-field">
                    <label for="filterLokasi" class="dropdown-label">Lokasi</label>
                    <div class="dropdown-select-wrap">
                        <select name="lokasi" id="filterLokasi" class="dropdown-select" onchange="document.getElementById('catalogFilterForm').submit()">
                            <option value="Perpustakaan Pusat" {{ $lokasi === 'Perpustakaan Pusat' ? 'selected' : '' }}>Perpustakaan Pusat</option>
                            <option value="Semua Lokasi" {{ $lokasi === 'Semua Lokasi' ? 'selected' : '' }}>Semua Cabang Layanan</option>
                        </select>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>

                <!-- 3. Status -->
                <div class="dropdown-field">
                    <label for="filterStatus" class="dropdown-label">Status</label>
                    <div class="dropdown-select-wrap">
                        <select name="status" id="filterStatus" class="dropdown-select" onchange="document.getElementById('catalogFilterForm').submit()">
                            <option value="" {{ empty($status) ? 'selected' : '' }}>Semua Status</option>
                            <option value="tersedia" {{ $status === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="habis" {{ $status === 'habis' ? 'selected' : '' }}>Habis / Dipinjam</option>
                        </select>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Preserve sorting -->
            <input type="hidden" name="sort" value="{{ $sort }}">
        </form>

        <!-- RESULTS INFO BAR -->
        <div class="results-bar">
            <div class="results-count-text">
                {{ $bukus->total() }} buku ditemukan
                <span>· Diurutkan berdasarkan {{ $sort === 'terbaru' ? 'terbaru' : ($sort === 'judul_asc' ? 'judul (A-Z)' : ($sort === 'judul_desc' ? 'judul (Z-A)' : 'popularitas')) }}</span>
            </div>

            <!-- Sorting Dropdown -->
            <div class="sort-select-wrap">
                <label for="catalogSort">Urutkan:</label>
                <select id="catalogSort" class="sort-select" onchange="handleSortChange(this.value)">
                    <option value="popularitas" {{ $sort === 'popularitas' ? 'selected' : '' }}>Popularitas</option>
                    <option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>Buku Terbaru</option>
                    <option value="judul_asc" {{ $sort === 'judul_asc' ? 'selected' : '' }}>Judul (A-Z)</option>
                    <option value="judul_desc" {{ $sort === 'judul_desc' ? 'selected' : '' }}>Judul (Z-A)</option>
                </select>
            </div>
        </div>

        <!-- 4-COLUMN BOOK GRID -->
        <div class="book-grid">
            @forelse ($bukus as $buku)
                <div class="book-card">
                    <!-- Cover Container -->
                    <a href="{{ route('katalog.show', $buku->idBuku) }}" class="book-cover-container" aria-label="Lihat detail {{ $buku->judul }}">
                        @if (!empty($buku->cover))
                            <img src="{{ $buku->cover }}"
                                 alt="{{ $buku->judul }}"
                                 class="book-cover-img"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=800&auto=format&fit=crop';">
                        @else
                            <div class="book-cover-fallback">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                <h4>{{ $buku->judul }}</h4>
                                <p>{{ $buku->kategori?->namaKategori ?? 'Umum' }}</p>
                            </div>
                        @endif
                    </a>

                    <!-- Card Body -->
                    <div class="book-body">
                        <div>
                            <!-- Badges: Availability & Rating -->
                            <div class="book-badges-row">
                                @if ($buku->stok > 0)
                                    <span class="badge-status tersedia">
                                        <span class="badge-dot"></span>
                                        Tersedia
                                    </span>
                                @else
                                    <span class="badge-status habis">
                                        <span class="badge-dot"></span>
                                        Habis
                                    </span>
                                @endif

                                <div class="badge-rating">
                                    <svg width="13" height="13" viewBox="0 0 24 24">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                    <span>4,8</span>
                                </div>
                            </div>

                            <!-- Book Title -->
                            <h3 class="book-title" title="{{ $buku->judul }}">
                                <a href="{{ route('katalog.show', $buku->idBuku) }}">{{ $buku->judul }}</a>
                            </h3>

                            <!-- Author & Category -->
                            <p class="book-author-cat" title="{{ $buku->penulis }} · {{ $buku->kategori?->namaKategori ?? 'Umum' }}">
                                {{ $buku->penulis }} · {{ $buku->kategori?->namaKategori ?? 'Umum' }}
                            </p>

                            <!-- Meta Specifications -->
                            <div class="book-meta-row">
                                <div class="book-meta-item">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg>
                                    <span>{{ $buku->jumlahHalaman ?? 320 }} halaman</span>
                                </div>
                                <div class="book-meta-item">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>{{ $buku->rak ?? 'Rak A-01' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button: Pinjam Buku -->
                        <button type="button"
                                class="btn-pinjam-buku"
                                data-id="{{ $buku->idBuku }}"
                                data-judul="{{ $buku->judul }}"
                                data-penulis="{{ $buku->penulis }}"
                                data-kategori="{{ $buku->kategori?->namaKategori ?? 'Umum' }}"
                                data-rak="{{ $buku->rak ?? 'Rak F-12' }}"
                                data-stok="{{ $buku->stok }}"
                                data-halaman="{{ $buku->jumlahHalaman ?? 320 }}"
                                data-cover="{{ $buku->cover ?? '' }}"
                                onclick="openBorrowModal(this)">
                            Pinjam Buku
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-katalog">
                    <div class="empty-katalog-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <h3>Buku Tidak Ditemukan</h3>
                    <p>Maaf, tidak ada buku yang sesuai dengan kriteria pencarian atau filter yang Anda pilih.</p>
                    <a href="{{ route('katalog.index') }}" class="btn-reset-filter">
                        Reset Semua Filter
                    </a>
                </div>
            @endforelse
        </div>

        <!-- PAGINATION BAR -->
        @if ($bukus->hasPages())
            <div class="pagination-container">
                <div class="pagination-info">
                    Menampilkan {{ $bukus->firstItem() ?? 0 }}-{{ $bukus->lastItem() ?? 0 }} dari {{ $bukus->total() }} buku
                </div>

                <div class="pagination-controls">
                    {{-- Previous Page Link --}}
                    @if ($bukus->onFirstPage())
                        <button class="page-btn" disabled aria-label="Halaman Sebelumnya">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                    @else
                        <a href="{{ $bukus->previousPageUrl() }}" class="page-btn" aria-label="Halaman Sebelumnya">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($bukus->links()->elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="page-dots">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $bukus->currentPage())
                                    <button class="page-btn active">{{ $page }}</button>
                                @else
                                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($bukus->hasMorePages())
                        <a href="{{ $bukus->nextPageUrl() }}" class="page-btn" aria-label="Halaman Selanjutnya">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    @else
                        <button class="page-btn" disabled aria-label="Halaman Selanjutnya">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @else
            <div class="pagination-container">
                <div class="pagination-info">
                    Menampilkan {{ $bukus->firstItem() ?? 0 }}-{{ $bukus->lastItem() ?? 0 }} dari {{ $bukus->total() }} buku
                </div>
            </div>
        @endif

    </main>

    <!-- MODAL INFORMASI PEMINJAMAN BUKU -->
    <div class="modal-backdrop" id="borrowModal" onclick="closeBorrowModal(event)">
        <div class="modal-card" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="modal-header">
                <div class="modal-title-group">
                    <div class="modal-title-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </div>
                    <h3 class="modal-title">Panduan Peminjaman Buku</h3>
                </div>
                <button type="button" class="btn-modal-close" onclick="hideBorrowModal()" aria-label="Tutup">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <!-- Book Snippet -->
                <div class="modal-book-preview">
                    <img id="modalCover" src="" alt="Cover" class="modal-book-cover" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=800&auto=format&fit=crop';">
                    <div class="modal-book-details">
                        <h4 id="modalJudul" class="modal-book-title">Judul Buku</h4>
                        <p id="modalPenulis" class="modal-book-author">Penulis · Kategori</p>
                    </div>
                </div>

                <!-- Physical Rack Location -->
                <div class="modal-location-box">
                    <div class="modal-location-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <div class="modal-location-text">
                        <h4 id="modalRak">Lokasi: Rak F-12</h4>
                        <p>Perpustakaan Pusat · Stok tersedia: <strong id="modalStok">5 eksemplar</strong></p>
                    </div>
                </div>

                <!-- Instructions based on Role / Guest -->
                @guest
                    <p class="modal-instructions">
                        Buku ini tersedia di rak perpustakaan fisik. Untuk meminjam, silakan <strong>masuk</strong> atau <strong>daftar keanggotaan</strong> BOOKNEST terlebih dahulu agar Anda mendapatkan kartu anggota digital.
                    </p>
                    <div class="modal-actions">
                        <button type="button" class="btn-modal-cancel" onclick="hideBorrowModal()">
                            Tutup
                        </button>
                        <a href="{{ route('login') }}" class="btn-modal-primary">
                            Masuk Akun
                        </a>
                    </div>
                @else
                    @if (auth()->user()->role === 'member')
                        <p class="modal-instructions">
                            Silakan kunjungi meja sirkulasi Perpustakaan Pusat, ambil buku di rak di atas, lalu tunjukkan <strong>Kartu Anggota / QR Member</strong> Anda ke Petugas untuk dicatat peminjamannya.
                        </p>
                        <div class="modal-actions">
                            <button type="button" class="btn-modal-cancel" onclick="hideBorrowModal()">
                                Tutup
                            </button>
                            <a href="{{ route('member.kartu-saya') }}" class="btn-modal-primary">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                </svg>
                                Buka Kartu / QR Saya
                            </a>
                        </div>
                    @else
                        {{-- Petugas / Admin --}}
                        <p class="modal-instructions">
                            Anda login sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong>. Anda dapat mencatat transaksi peminjaman buku ini langsung di Meja Sirkulasi.
                        </p>
                        <div class="modal-actions">
                            <button type="button" class="btn-modal-cancel" onclick="hideBorrowModal()">
                                Tutup
                            </button>
                            <a href="{{ route('peminjaman.create') }}" class="btn-modal-primary">
                                Buka Form Sirkulasi
                            </a>
                        </div>
                    @endif
                @endguest
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Brand & Tagline -->
            <div class="footer-brand">
                <h3>BOOKNEST</h3>
                <p>Temukan. Baca. Berkembang.<br>Ruang pengetahuan untuk semua.</p>
            </div>

            <!-- Jelajahi -->
            <div>
                <h4 class="footer-heading">Jelajahi BOOKNEST</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('katalog.index') }}">Katalog buku</a></li>
                    <li><a href="{{ route('register') }}">Keanggotaan</a></li>
                    <li><a href="{{ route('tentang') }}">Tentang kami</a></li>
                    <li><a href="{{ route('katalog.index') }}">Panduan peminjaman</a></li>
                    <li><a href="{{ route('home') }}">Kebijakan privasi · Syarat layanan</a></li>
                </ul>
            </div>

            <!-- Kunjungi -->
            <div>
                <h4 class="footer-heading">Kunjungi perpustakaan</h4>
                <div class="footer-contact-item">Jl. Merdeka No. 12, Bandung</div>
                <div class="footer-contact-item">Senin–Sabtu, 08.00–17.00 WIB</div>
                <div class="footer-contact-item">halo@booknest.id · (022) 420 1234</div>
            </div>
        </div>

        <div class="footer-copyright">
            &copy; 2026 BOOKNEST. Bersama menumbuhkan budaya membaca.
        </div>
    </footer>

    <!-- SCRIPT LOGIC -->
    <script>
        function handleSortChange(sortValue) {
            const form = document.getElementById('catalogFilterForm');
            const sortInput = form.querySelector('input[name="sort"]');
            if (sortInput) {
                sortInput.value = sortValue;
            }
            form.submit();
        }

        function openBorrowModal(btn) {
            const modal = document.getElementById('borrowModal');
            const judul = btn.getAttribute('data-judul');
            const penulis = btn.getAttribute('data-penulis');
            const kategori = btn.getAttribute('data-kategori');
            const rak = btn.getAttribute('data-rak');
            const stok = parseInt(btn.getAttribute('data-stok') || '0', 10);
            const cover = btn.getAttribute('data-cover') || '';

            document.getElementById('modalJudul').textContent = judul;
            document.getElementById('modalPenulis').textContent = `${penulis} · ${kategori}`;
            document.getElementById('modalRak').textContent = `Lokasi: ${rak}`;
            document.getElementById('modalStok').textContent = stok > 0 ? `${stok} eksemplar` : 'Stok habis';

            const coverImg = document.getElementById('modalCover');
            if (cover) {
                coverImg.src = cover;
            } else {
                coverImg.src = 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=800&auto=format&fit=crop';
            }

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function hideBorrowModal() {
            const modal = document.getElementById('borrowModal');
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }

        function closeBorrowModal(e) {
            if (e.target.id === 'borrowModal') {
                hideBorrowModal();
            }
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideBorrowModal();
            }
        });
    </script>
</body>
</html>