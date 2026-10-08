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

        html, body {
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
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
            align-items: center;
            flex-wrap: wrap;
        }

        .modal-actions form {
            display: inline-block;
            margin: 0;
        }

        .btn-modal-cancel {
            padding: 10px 18px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            transition: all 0.15s;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
            justify-content: center;
            gap: 6px;
            transition: background 0.15s;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-modal-primary:hover {
            background: #115e59;
        }

        .btn-modal-secondary {
            padding: 10px 18px;
            background: #f0fdfa;
            border: 1.5px solid #0f766e;
            color: #0f766e;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }

        .btn-modal-secondary:hover {
            background: #ccfbf1;
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

        @media (max-width: 640px) {
            .topbar-container {
                padding: 10px 14px;
                gap: 8px;
            }
            .topbar-brand {
                font-size: 16px;
                gap: 8px;
            }
            .topbar-brand-icon {
                width: 32px;
                height: 32px;
            }
            .topbar-actions {
                gap: 6px;
            }
            .btn-user-badge span:not(#topbarCartBadge) {
                display: none;
            }
            .btn-user-badge {
                padding: 6px 9px;
                gap: 4px;
            }
            .btn-auth-login, .btn-auth-register {
                padding: 6px 12px;
                font-size: 12.5px;
            }
            .catalog-container {
                padding: 16px 14px 40px !important;
            }
            .catalog-title {
                font-size: 24px;
            }
            .filter-card {
                padding: 16px 14px;
            }

            /* --- MODAL MOBILE FULL-WIDTH & STACKING --- */
            .modal-backdrop {
                padding: 12px;
            }
            .modal-card {
                border-radius: 16px;
                max-width: 100%;
                width: 100%;
            }
            .modal-header {
                padding: 14px 16px;
            }
            .modal-title {
                font-size: 15px;
            }
            .modal-body {
                padding: 16px;
            }
            .modal-book-preview {
                padding: 10px 12px;
                gap: 12px;
                margin-bottom: 14px;
            }
            .modal-book-cover {
                width: 50px;
                height: 70px;
            }
            .modal-book-title {
                font-size: 14px;
            }
            .modal-location-box {
                padding: 10px 12px;
                gap: 10px;
                margin-bottom: 14px;
            }
            .modal-instructions {
                font-size: 12.5px;
                margin-bottom: 16px;
            }
            .modal-actions {
                display: flex;
                flex-direction: column;
                width: 100%;
                gap: 8px;
            }
            .modal-actions form {
                width: 100%;
                margin: 0 !important;
                display: block !important;
            }
            .modal-actions form button,
            .modal-actions .btn-modal-primary,
            .modal-actions .btn-modal-secondary,
            .modal-actions .btn-modal-cancel {
                width: 100% !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                text-align: center !important;
                box-sizing: border-box !important;
                padding: 11px 16px !important;
                font-size: 13.5px !important;
            }
            .modal-actions .btn-modal-primary {
                order: 1;
            }
            .modal-actions form {
                order: 2;
            }
            .modal-actions .btn-modal-cancel {
                order: 3;
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

        /* --- FLY-TO-CART & TOAST NOTIFICATION STYLES --- */
        .cart-toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 100000;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 390px;
            width: calc(100vw - 32px);
            pointer-events: none;
        }

        .cart-toast {
            pointer-events: auto;
            background: #ffffff;
            border-radius: 14px;
            padding: 13px 16px;
            box-shadow: 0 16px 36px -6px rgba(15, 23, 42, 0.16), 0 0 0 1px rgba(15, 23, 42, 0.06);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            transform: translateY(20px) scale(0.96);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .cart-toast.show {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .cart-toast.toast-success {
            border-left: 4px solid #0f766e;
        }

        .cart-toast.toast-info {
            border-left: 4px solid #0284c7;
        }

        .cart-toast.toast-error {
            border-left: 4px solid #e11d48;
        }

        .toast-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toast-success .toast-icon {
            background: #ccfbf1;
            color: #0f766e;
        }

        .toast-info .toast-icon {
            background: #e0f2fe;
            color: #0284c7;
        }

        .toast-error .toast-icon {
            background: #ffe4e6;
            color: #e11d48;
        }

        .toast-content {
            flex: 1;
            min-width: 0;
        }

        .toast-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 2px;
        }

        .toast-message {
            font-size: 12px;
            color: #64748b;
            line-height: 1.45;
        }

        .toast-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 5px;
            font-size: 11.5px;
            font-weight: 700;
            color: #0f766e;
            text-decoration: none;
        }

        .toast-link:hover {
            text-decoration: underline;
        }

        .toast-close-btn {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 2px;
            border-radius: 6px;
            transition: all 0.15s;
        }

        .toast-close-btn:hover {
            color: #0f172a;
            background: #f1f5f9;
        }

        /* Keyframes */
        @keyframes cartBouncePop {
            0% { transform: scale(1); }
            30% { transform: scale(1.35) rotate(-6deg); background-color: #ccfbf1; }
            60% { transform: scale(0.92) rotate(3deg); }
            85% { transform: scale(1.08) rotate(-1deg); }
            100% { transform: scale(1) rotate(0deg); }
        }

        .cart-bounce-pop {
            animation: cartBouncePop 0.65s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        }

        @keyframes badgePopBump {
            0% { transform: scale(1); }
            40% { transform: scale(1.6); background-color: #14b8a6; }
            100% { transform: scale(1); }
        }

        .badge-pop-bump {
            animation: badgePopBump 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        }

        @keyframes btnCartShake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-4px); }
            40%, 80% { transform: translateX(4px); }
        }

        .btn-cart-shake {
            animation: btnCartShake 0.4s ease !important;
        }
    </style>

    @include('layouts.partials.sidebar_styles')
</head>
<body>

    <!-- TOPBAR NAVBAR -->
    <header class="topbar">
        <div class="topbar-container">
            <!-- Brand -->
            <div style="display: flex; align-items: center; gap: 14px;">
                @auth
                    <button type="button" class="mobile-toggle-btn" id="sidebarToggle" aria-label="Buka menu navigasi">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                @endauth

                <a href="{{ route('home') }}" class="topbar-brand">
                    <div class="topbar-brand-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </div>
                    <span>BOOKNEST</span>
                </a>
            </div>

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
                    @if(auth()->user()->role === 'member')
                        @php
                            $jumlahKeranjang = count(session('keranjang_booking', []));
                        @endphp
                        <a href="{{ route('keranjang.index') }}" id="topbarCartBtn" class="btn-user-badge" title="Keranjang Booking ({{ $jumlahKeranjang }} Buku)" style="padding: 7px 12px; gap: 6px; position: relative; background: #f0fdfa; border: 1px solid #ccfbf1; color: #0f766e; text-decoration: none; transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <span style="font-size: 13px; font-weight: 700;">Keranjang</span>
                            <span id="topbarCartBadge" style="background: #0f766e; color: #ffffff; font-size: 10px; font-weight: 800; padding: 1px 6px; border-radius: 9999px; transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); {{ $jumlahKeranjang > 0 ? 'display: inline-block;' : 'display: none;' }}">{{ $jumlahKeranjang }}</span>
                        </a>
                    @endif
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
    @auth
        <!-- MOBILE BACKDROP -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- PAGE WRAPPER -->
        <div class="page-wrapper">
            @if(auth()->user()->role === 'admin')
                @include('layouts.partials.sidebar_admin')
            @elseif(auth()->user()->role === 'petugas')
                @include('layouts.partials.sidebar_petugas')
            @else
                @include('layouts.partials.sidebar_anggota')
            @endif

            <!-- MAIN CONTENT AREA -->
            <main class="content-area">
    @endauth

    <div class="catalog-container" @auth style="max-width: 100%; margin: 0; padding: 0 0 40px 0;" @endauth>

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

                        <!-- Action Button: Pinjam Buku & Keranjang -->
                        <div style="display: flex; gap: 8px; align-items: center;">
                            @if (auth()->check() && auth()->user()->role === 'admin')
                                <a href="{{ route('buku.edit', $buku->idBuku) }}"
                                   class="btn-pinjam-buku"
                                   style="flex: 1; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    Kelola Buku
                                </a>
                            @else
                                <button type="button"
                                        class="btn-pinjam-buku"
                                        style="flex: 1;"
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
                            @endif

                            @if (auth()->check() && auth()->user()->role === 'member' && $buku->stok > 0)
                                <form action="{{ route('keranjang.tambah', $buku->idBuku) }}" method="POST" style="margin: 0;" class="form-ajax-cart">
                                    @csrf
                                    <button type="submit"
                                            class="btn-cart-quick"
                                            data-id="{{ $buku->idBuku }}"
                                            data-judul="{{ $buku->judul }}"
                                            title="Tambah ke Keranjang Booking"
                                            style="width: 40px; height: 39px; border-radius: 10px; background: #f0fdfa; border: 1.5px solid #0f766e; color: #0f766e; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;">
                                        <svg class="cart-icon-default" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="9" cy="21" r="1"></circle>
                                            <circle cx="20" cy="21" r="1"></circle>
                                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                        </svg>
                                        <svg class="cart-icon-success" style="display: none;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0f766e" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
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
    </div>

    @auth
            </main>
        </div>
    @endauth

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
                            Pilih cara booking buku ini: masukkan ke <strong>Keranjang Booking</strong> untuk meminjam beberapa buku sekaligus, atau ajukan booking sekarang untuk mendapatkan tiket QR instan.
                        </p>
                        <div class="modal-actions">
                            <button type="button" class="btn-modal-cancel" onclick="hideBorrowModal()">
                                Tutup
                            </button>
                            <form id="modalFormCart" method="POST" action="" class="form-ajax-cart">
                                @csrf
                                <button type="submit" class="btn-modal-secondary">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                    + Keranjang Booking
                                </button>
                            </form>
                            <a id="modalBtnBookingDirect" href="#" class="btn-modal-primary">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                </svg>
                                Booking Sekarang
                            </a>
                        </div>
                    @elseif (auth()->user()->role === 'petugas')
                        <p class="modal-instructions">
                            Anda login sebagai <strong>Petugas Perpustakaan</strong>. Anda dapat mencatat transaksi peminjaman buku ini langsung di Meja Sirkulasi.
                        </p>
                        <div class="modal-actions">
                            <button type="button" class="btn-modal-cancel" onclick="hideBorrowModal()">
                                Tutup
                            </button>
                            <a href="{{ route('peminjaman.create') }}" class="btn-modal-primary">
                                Buka Form Sirkulasi
                            </a>
                        </div>
                    @else
                        {{-- Admin --}}
                        <p class="modal-instructions">
                            Anda login sebagai <strong>Admin Perpustakaan</strong>. Admin bertugas dalam tata kelola master data buku, bukan transaksi sirkulasi peminjaman.
                        </p>
                        <div class="modal-actions">
                            <button type="button" class="btn-modal-cancel" onclick="hideBorrowModal()">
                                Tutup
                            </button>
                            <a id="modalAdminKelolaBtn" href="{{ route('buku.index') }}" class="btn-modal-primary">
                                Kelola Master Buku
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

            const idBuku = btn.getAttribute('data-id');
            const formCart = document.getElementById('modalFormCart');
            if (formCart && idBuku) {
                formCart.action = `{{ url('/keranjang-booking/tambah') }}/${idBuku}`;
            }
            const btnDirect = document.getElementById('modalBtnBookingDirect');
            if (btnDirect && idBuku) {
                btnDirect.href = `{{ url('/peminjaman/konfirmasi') }}/${idBuku}`;
            }
            const btnAdminManage = document.getElementById('modalAdminKelolaBtn');
            if (btnAdminManage && idBuku) {
                btnAdminManage.href = `{{ url('/buku') }}/${idBuku}/edit`;
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

        /* =========================================================
           FLY-TO-CART & AJAX KERANJANG SYSTEM (TANPA RELOAD)
           ========================================================= */

        function showCartToast(options) {
            const container = document.getElementById('cartToastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `cart-toast toast-${options.type || 'success'}`;

            let iconSvg = '';
            if (options.type === 'error') {
                iconSvg = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`;
            } else if (options.type === 'info') {
                iconSvg = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`;
            } else {
                iconSvg = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
            }

            const linkHtml = options.url ? `<a href="${options.url}" class="toast-link">${options.linkText || 'Buka Keranjang Booking &rarr;'}</a>` : '';

            toast.innerHTML = `
                <div class="toast-icon">${iconSvg}</div>
                <div class="toast-content">
                    <div class="toast-title">${options.title}</div>
                    <div class="toast-message">${options.message}</div>
                    ${linkHtml}
                </div>
                <button type="button" class="toast-close-btn" aria-label="Tutup notifikasi">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            `;

            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.add('show');
            });

            const closeToast = () => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentElement) toast.remove();
                }, 350);
            };

            toast.querySelector('.toast-close-btn').addEventListener('click', closeToast);
            setTimeout(closeToast, 4000);
        }

        function flyToCartAnimation(sourceEl, onLanding) {
            const targetCart = document.getElementById('topbarCartBtn');
            if (!targetCart) {
                if (typeof onLanding === 'function') onLanding();
                return;
            }

            const startRect = sourceEl.getBoundingClientRect();
            const targetRect = targetCart.getBoundingClientRect();

            // Elemen yang akan terbang
            const flyer = document.createElement(sourceEl.tagName === 'IMG' ? 'img' : 'div');
            if (sourceEl.tagName === 'IMG' && sourceEl.src) {
                flyer.src = sourceEl.src;
            } else {
                flyer.innerHTML = `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>`;
                flyer.style.background = 'linear-gradient(135deg, #0f766e, #14b8a6)';
                flyer.style.display = 'flex';
                flyer.style.alignItems = 'center';
                flyer.style.justifyContent = 'center';
            }

            const initW = Math.min(Math.max(startRect.width, 60), 96);
            const initH = Math.min(Math.max(startRect.height, 60), 136);
            const startX = startRect.left + (startRect.width - initW) / 2;
            const startY = startRect.top + (startRect.height - initH) / 2;

            flyer.style.position = 'fixed';
            flyer.style.left = `${startX}px`;
            flyer.style.top = `${startY}px`;
            flyer.style.width = `${initW}px`;
            flyer.style.height = `${initH}px`;
            flyer.style.borderRadius = '12px';
            flyer.style.objectFit = 'cover';
            flyer.style.boxShadow = '0 18px 38px rgba(15, 118, 110, 0.4), 0 0 0 2px #0f766e';
            flyer.style.zIndex = '999999';
            flyer.style.pointerEvents = 'none';
            flyer.style.willChange = 'transform, opacity';

            document.body.appendChild(flyer);

            const targetCenterX = targetRect.left + targetRect.width / 2;
            const targetCenterY = targetRect.top + targetRect.height / 2;
            const deltaX = targetCenterX - (startX + initW / 2);
            const deltaY = targetCenterY - (startY + initH / 2);

            // Lintasan melengkung (parabolic arc) ke arah navbar
            const midX = deltaX * 0.42;
            const midY = deltaY * 0.2 - 60;

            if ('animate' in flyer) {
                const anim = flyer.animate([
                    {
                        transform: 'translate(0px, 0px) scale(1) rotate(0deg)',
                        opacity: 1
                    },
                    {
                        offset: 0.45,
                        transform: `translate(${midX}px, ${midY}px) scale(0.65) rotate(-14deg)`,
                        opacity: 0.95
                    },
                    {
                        offset: 0.85,
                        transform: `translate(${deltaX * 0.92}px, ${deltaY * 0.92}px) scale(0.3) rotate(12deg)`,
                        opacity: 0.7
                    },
                    {
                        transform: `translate(${deltaX}px, ${deltaY}px) scale(0.18) rotate(0deg)`,
                        opacity: 0.2
                    }
                ], {
                    duration: 650,
                    easing: 'cubic-bezier(0.2, 0.8, 0.25, 1)',
                    fill: 'forwards'
                });

                anim.onfinish = () => {
                    flyer.remove();
                    if (typeof onLanding === 'function') onLanding();
                };
            } else {
                flyer.remove();
                if (typeof onLanding === 'function') onLanding();
            }
        }

        function triggerCartLandingEffects(newTotal) {
            const targetCart = document.getElementById('topbarCartBtn');
            if (targetCart) {
                targetCart.classList.remove('cart-bounce-pop');
                void targetCart.offsetWidth;
                targetCart.classList.add('cart-bounce-pop');
            }

            const badge = document.getElementById('topbarCartBadge');
            if (badge) {
                badge.textContent = newTotal;
                badge.style.display = 'inline-block';
                badge.classList.remove('badge-pop-bump');
                void badge.offsetWidth;
                badge.classList.add('badge-pop-bump');
            }

            const sideBadge = document.getElementById('sidebarCartBadge');
            if (sideBadge) {
                sideBadge.textContent = newTotal;
                sideBadge.style.display = 'inline-block';
            }
        }

        // Intercept Form Submit: Tambah ke Keranjang Tanpa Reload
        document.addEventListener('submit', async function(e) {
            const form = e.target.closest('.form-ajax-cart');
            if (!form) return;

            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && submitBtn.disabled) return;

            if (submitBtn) submitBtn.disabled = true;

            // Sumber elemen untuk animasi terbang
            let sourceEl = null;
            const card = form.closest('.book-card');
            if (card) {
                sourceEl = card.querySelector('.book-cover-img') || card.querySelector('.book-cover-container') || submitBtn;
            } else if (form.id === 'modalFormCart') {
                sourceEl = document.getElementById('modalCover') || submitBtn;
            } else {
                sourceEl = submitBtn;
            }

            const actionUrl = form.action;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    if (form.id === 'modalFormCart') {
                        hideBorrowModal();
                    }

                    // Morph icon checkmark di tombol kartu
                    const iconDefault = submitBtn ? submitBtn.querySelector('.cart-icon-default') : null;
                    const iconSuccess = submitBtn ? submitBtn.querySelector('.cart-icon-success') : null;
                    if (iconDefault && iconSuccess) {
                        iconDefault.style.display = 'none';
                        iconSuccess.style.display = 'block';
                        submitBtn.style.background = '#ccfbf1';
                    }

                    // Animasi Terbang ke Keranjang
                    flyToCartAnimation(sourceEl, function() {
                        triggerCartLandingEffects(data.totalItem);

                        showCartToast({
                            type: 'success',
                            title: 'Buku Masuk Keranjang!',
                            message: `"${data.judul || 'Buku'}" berhasil ditambahkan ke keranjang booking.`,
                            url: "{{ route('keranjang.index') }}"
                        });
                    });

                    // Pulihkan tombol setelah 2.5 detik
                    setTimeout(() => {
                        if (iconDefault && iconSuccess) {
                            iconDefault.style.display = 'block';
                            iconSuccess.style.display = 'none';
                            submitBtn.style.background = '#f0fdfa';
                        }
                        if (submitBtn) submitBtn.disabled = false;
                    }, 2500);

                } else if (data.already_in_cart) {
                    if (submitBtn) {
                        submitBtn.classList.add('btn-cart-shake');
                        setTimeout(() => submitBtn.classList.remove('btn-cart-shake'), 500);
                        submitBtn.disabled = false;
                    }

                    showCartToast({
                        type: 'info',
                        title: 'Sudah di Keranjang',
                        message: data.message || 'Buku ini sudah tersimpan di keranjang booking Anda.',
                        url: "{{ route('keranjang.index') }}"
                    });

                } else {
                    if (submitBtn) {
                        submitBtn.classList.add('btn-cart-shake');
                        setTimeout(() => submitBtn.classList.remove('btn-cart-shake'), 500);
                        submitBtn.disabled = false;
                    }

                    showCartToast({
                        type: 'error',
                        title: 'Tidak Dapat Menambahkan',
                        message: data.message || 'Terjadi kendala saat menambahkan buku ke keranjang.'
                    });
                }

            } catch (err) {
                console.error('Cart Ajax Error:', err);
                if (submitBtn) {
                    submitBtn.classList.add('btn-cart-shake');
                    setTimeout(() => submitBtn.classList.remove('btn-cart-shake'), 500);
                    submitBtn.disabled = false;
                }

                showCartToast({
                    type: 'error',
                    title: 'Kesalahan Sistem',
                    message: 'Gagal menghubungi server. Silakan coba beberapa saat lagi.'
                });
            }
        });
    </script>

    <!-- TOAST NOTIFICATION CONTAINER -->
    <div id="cartToastContainer" class="cart-toast-container" aria-live="polite"></div>

    @include('layouts.partials.sidebar_scripts')
</body>
</html>