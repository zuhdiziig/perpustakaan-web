<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ Str::limit($buku->sinopsis ?? 'Detail buku '.$buku->judul.' karya '.$buku->penulis.' di katalog BOOKNEST.', 155) }}">

    <title>{{ $buku->judul }} - Detail Buku BOOKNEST</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-primary: #0f766e;
            --brand-primary-dark: #115e59;
            --brand-primary-light: #ccfbf1;
            --brand-mint: #f0fdfa;
            --brand-blue: #2563eb;
            --brand-blue-dark: #1d4ed8;
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --radius-lg: 14px;
            --shadow-soft: 0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 16px rgba(15, 23, 42, 0.04);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-body);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        a { color: inherit; text-decoration: none; }

        button { font-family: inherit; cursor: pointer; border: none; background: none; }

        /* --- NAVBAR --- */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
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
            background: var(--brand-primary);
            color: #ffffff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .topbar-nav { display: flex; align-items: center; gap: 10px; }

        .topbar-nav-link {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-muted);
            padding: 6px 14px;
            border-radius: 8px;
            transition: all 0.15s;
        }

        .topbar-nav-link:hover { color: var(--brand-primary); }

        .topbar-nav-link.active {
            background: var(--brand-primary-light);
            color: var(--brand-primary);
            font-weight: 700;
        }

        .topbar-actions { display: flex; align-items: center; gap: 10px; }

        .btn-auth-login,
        .btn-auth-register {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            color: #ffffff;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
            transition: background 0.15s, transform 0.15s;
        }

        .btn-auth-login { background: var(--brand-blue); }
        .btn-auth-login:hover { background: var(--brand-blue-dark); }
        .btn-auth-register { background: var(--brand-primary); }
        .btn-auth-register:hover { background: var(--brand-primary-dark); }

        .btn-user-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: #f1f5f9;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .user-initials {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: var(--brand-primary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
        }

        /* --- PAGE HEADER --- */
        .detail-container {
            max-width: 1240px;
            width: 100%;
            margin: 0 auto;
            padding: 28px 24px 72px;
        }

        .page-eyebrow {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--brand-primary);
            margin-bottom: 6px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.6px;
            line-height: 1.2;
        }

        .breadcrumb {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
            margin: 8px 0 26px;
            font-size: 12.5px;
            font-weight: 500;
            color: var(--text-muted);
        }

        .breadcrumb a:hover { color: var(--brand-primary); }

        .breadcrumb li + li::before {
            content: '/';
            margin-right: 6px;
            color: #94a3b8;
        }

        .breadcrumb [aria-current="page"] { color: var(--text-heading); font-weight: 600; }

        /* --- DETAIL GRID --- */
        .detail-grid {
            display: grid;
            grid-template-columns: minmax(260px, 340px) 1fr;
            gap: 28px;
            align-items: start;
            animation: fadeUp 0.45s ease both;
        }

        .cover-card {
            position: relative;
            aspect-ratio: 4 / 3.3;
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: linear-gradient(135deg, #e7e5e4 0%, #d6d3d1 100%);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-soft);
        }

        .cover-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(.2, .7, .2, 1);
        }

        .cover-card:hover img { transform: scale(1.04); }

        .cover-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 24px;
            text-align: center;
            color: #ffffff;
            background: linear-gradient(135deg, var(--brand-primary) 0%, #134e4a 100%);
        }

        .cover-fallback h2 { font-size: 18px; font-weight: 800; }
        .cover-fallback p { font-size: 13px; color: var(--brand-primary-light); }

        .location-card {
            margin-top: 14px;
            background: var(--brand-mint);
            border: 1px solid #99f6e4;
            border-radius: var(--radius-lg);
            padding: 18px 20px;
        }

        .location-card h3 {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 10px;
        }

        .location-card h3 svg { color: var(--brand-primary); }

        .location-card p {
            font-size: 12.5px;
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* Info column */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 11px;
            border-radius: 999px;
        }

        .badge-status.tersedia { background: #dcfce7; color: #15803d; }
        .badge-status.habis { background: #fee2e2; color: #b91c1c; }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .badge-status.tersedia .badge-dot { animation: pulseDot 2s ease-in-out infinite; }

        .book-title-main {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-heading);
            line-height: 1.25;
            letter-spacing: -0.5px;
            margin: 16px 0 14px;
        }

        .book-author-main {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-heading);
            margin-bottom: 18px;
        }

        .spec-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 10px 16px;
            box-shadow: var(--shadow-soft);
        }

        .spec-list { display: grid; }

        .spec-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 16px;
            padding: 8.5px 0;
            font-size: 13px;
        }

        .spec-row + .spec-row { border-top: 1px dashed #f1f5f9; }
        .spec-row dt { color: var(--text-muted); font-weight: 500; }
        .spec-row dd { color: var(--text-heading); font-weight: 600; text-align: right; }
        .spec-row dd.is-low { color: #b45309; }
        .spec-row dd.is-empty { color: #b91c1c; }

        .action-row {
            display: flex;
            gap: 10px;
            margin-top: 14px;
        }

        .btn-pinjam,
        .btn-kembali {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 20px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
            color: #ffffff;
            transition: background 0.15s, transform 0.15s, box-shadow 0.15s;
        }

        .btn-pinjam {
            flex: 1;
            background: var(--brand-primary);
            box-shadow: 0 6px 16px -6px rgba(15, 118, 110, 0.55);
        }

        .btn-pinjam:hover { background: var(--brand-primary-dark); transform: translateY(-1px); }
        .btn-pinjam:active { transform: translateY(0); }

        .btn-pinjam:disabled {
            background: #cbd5e1;
            color: #475569;
            box-shadow: none;
            cursor: not-allowed;
            transform: none;
        }

        .btn-kembali { min-width: 96px; background: var(--brand-blue); }
        .btn-kembali:hover { background: var(--brand-blue-dark); transform: translateY(-1px); }

        .loan-note {
            margin-top: 12px;
            font-size: 12.5px;
            color: var(--text-muted);
        }

        /* --- ABOUT --- */
        .about-card {
            margin-top: 28px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px 26px;
            box-shadow: var(--shadow-soft);
            animation: fadeUp 0.45s 0.08s ease both;
        }

        .about-card h2 {
            font-size: 19px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 14px;
        }

        .about-card p {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.75;
        }

        .about-card p + p { margin-top: 12px; }

        /* --- RELATED --- */
        .section-related { margin-top: 36px; animation: fadeUp 0.45s 0.16s ease both; }

        .section-title {
            font-size: 19px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 18px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .related-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-soft);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .related-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px -12px rgba(15, 23, 42, 0.18);
        }

        .related-cover {
            display: block;
            aspect-ratio: 16 / 11;
            background: #f1f5f9;
            overflow: hidden;
        }

        .related-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .related-card:hover .related-cover img { transform: scale(1.06); }

        .related-cover-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            background: linear-gradient(135deg, var(--brand-primary) 0%, #134e4a 100%);
        }

        .related-body {
            padding: 12px 12px 14px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .related-badges {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .badge-rating {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-heading);
        }

        .badge-rating svg { fill: none; stroke: #c2410c; stroke-width: 2; }

        .related-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-heading);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .related-title a:hover { color: var(--brand-primary); }

        .related-author {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .related-meta {
            display: flex;
            gap: 12px;
            margin: 10px 0 12px;
            font-size: 11.5px;
            color: var(--text-muted);
        }

        .related-meta span { display: inline-flex; align-items: center; gap: 4px; }

        .btn-related {
            margin-top: auto;
            display: flex;
            justify-content: center;
            padding: 7px 12px;
            background: var(--brand-primary);
            color: #ffffff;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            transition: background 0.15s;
        }

        .btn-related:hover { background: var(--brand-primary-dark); }

        /* --- BORROW DIALOG --- */
        .borrow-dialog {
            margin: auto;
            width: min(440px, calc(100% - 32px));
            border: none;
            border-radius: 18px;
            padding: 0;
            box-shadow: 0 30px 60px -20px rgba(15, 23, 42, 0.45);
            color: var(--text-body);
        }

        .borrow-dialog::backdrop {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
        }

        .borrow-dialog[open] { animation: dialogIn 0.22s ease both; }

        .dialog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 22px;
            border-bottom: 1px solid #f1f5f9;
        }

        .dialog-header h2 { font-size: 16px; font-weight: 800; color: var(--text-heading); }

        .dialog-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
        }

        .dialog-close:hover { background: #f1f5f9; color: var(--text-heading); }

        .dialog-body { padding: 20px 22px 22px; }

        .dialog-location {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 14px;
            background: var(--brand-mint);
            border: 1px solid #99f6e4;
            border-radius: 12px;
            margin-bottom: 14px;
        }

        .dialog-location-icon {
            flex-shrink: 0;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--brand-primary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dialog-location strong { display: block; font-size: 14px; color: var(--text-heading); }
        .dialog-location span { font-size: 12.5px; color: var(--text-muted); }

        .dialog-steps {
            padding-left: 18px;
            font-size: 13px;
            line-height: 1.7;
            color: var(--text-body);
        }

        .dialog-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-dialog-cancel {
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-body);
            background: #f1f5f9;
        }

        .btn-dialog-primary {
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            background: var(--brand-primary);
        }

        .btn-dialog-primary:hover { background: var(--brand-primary-dark); }

        /* --- FOOTER --- */
        .footer {
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            padding: 48px 24px 32px;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1240px;
            margin: 0 auto 40px;
            display: grid;
            grid-template-columns: 1.3fr 1fr 1.2fr;
            gap: 40px;
        }

        .footer-brand h3 { font-size: 18px; font-weight: 800; color: var(--text-heading); margin-bottom: 8px; }
        .footer-brand p { font-size: 13.5px; color: var(--text-muted); line-height: 1.6; }
        .footer-heading { font-size: 14px; font-weight: 800; color: var(--text-heading); margin-bottom: 14px; }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 8px; }
        .footer-links a { font-size: 13px; color: var(--text-muted); transition: color 0.15s; }
        .footer-links a:hover { color: var(--brand-primary); }
        .footer-contact-item { font-size: 13px; color: var(--text-muted); margin-bottom: 6px; }

        .footer-copyright {
            max-width: 1240px;
            margin: 0 auto;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
            font-size: 12.5px;
            color: #94a3b8;
        }

        /* --- ANIMATIONS --- */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes dialogIn {
            from { opacity: 0; transform: translateY(12px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes pulseDot {
            0%, 100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.45); }
            50% { box-shadow: 0 0 0 4px rgba(22, 163, 74, 0); }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 960px) {
            .related-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 760px) {
            .topbar-nav { display: none; }
            .detail-grid { grid-template-columns: 1fr; }
            .footer-container { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .action-row { flex-direction: column; }
            .related-grid { grid-template-columns: 1fr; }
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

        .cart-toast.toast-success { border-left: 4px solid #0f766e; }
        .cart-toast.toast-info { border-left: 4px solid #0284c7; }
        .cart-toast.toast-error { border-left: 4px solid #e11d48; }

        .toast-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toast-success .toast-icon { background: #ccfbf1; color: #0f766e; }
        .toast-info .toast-icon { background: #e0f2fe; color: #0284c7; }
        .toast-error .toast-icon { background: #ffe4e6; color: #e11d48; }

        .toast-content { flex: 1; min-width: 0; }
        .toast-title { font-size: 13.5px; font-weight: 700; color: #0f172a; line-height: 1.3; margin-bottom: 2px; }
        .toast-message { font-size: 12px; color: #64748b; line-height: 1.45; }
        .toast-link { display: inline-flex; align-items: center; gap: 4px; margin-top: 5px; font-size: 11.5px; font-weight: 700; color: #0f766e; text-decoration: none; }
        .toast-link:hover { text-decoration: underline; }
        .toast-close-btn { background: none; border: none; color: #94a3b8; cursor: pointer; padding: 2px; border-radius: 6px; transition: all 0.15s; }
        .toast-close-btn:hover { color: #0f172a; background: #f1f5f9; }

        @keyframes cartBouncePop {
            0% { transform: scale(1); }
            30% { transform: scale(1.35) rotate(-6deg); background-color: #ccfbf1; }
            60% { transform: scale(0.92) rotate(3deg); }
            85% { transform: scale(1.08) rotate(-1deg); }
            100% { transform: scale(1) rotate(0deg); }
        }
        .cart-bounce-pop { animation: cartBouncePop 0.65s cubic-bezier(0.34, 1.56, 0.64, 1) !important; }

        @keyframes badgePopBump {
            0% { transform: scale(1); }
            40% { transform: scale(1.6); background-color: #14b8a6; }
            100% { transform: scale(1); }
        }
        .badge-pop-bump { animation: badgePopBump 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) !important; }

        @keyframes btnCartShake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-4px); }
            40%, 80% { transform: translateX(4px); }
        }
        .btn-cart-shake { animation: btnCartShake 0.4s ease !important; }
    </style>

    @include('layouts.partials.sidebar_styles')
</head>
<body>

    @php
        $namaKategori = $buku->kategori?->namaKategori ?? 'Umum';
        $stokTersedia = (int) $buku->stok;
        $isTersedia = $stokTersedia > 0;
        $rakLengkap = $buku->rak ?? 'Rak A-01';
        $kodeRak = Str::of($rakLengkap)->replaceStart('Rak ', '')->toString();
        $fallbackCover = 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=800&auto=format&fit=crop';
    @endphp

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-container">
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

            <nav class="topbar-nav" aria-label="Navigasi utama">
                <a href="{{ route('home') }}" class="topbar-nav-link">Beranda</a>
                <a href="{{ route('katalog.index') }}" class="topbar-nav-link active">Katalog</a>
                <a href="{{ route('dashboard') }}" class="topbar-nav-link">Dasbor</a>
                <a href="{{ route('tentang') }}" class="topbar-nav-link">Tentang</a>
            </nav>

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
                    <a href="{{ route('dashboard') }}" class="btn-user-badge">
                        <div class="user-initials">{{ auth()->user()->inisial ?? 'US' }}</div>
                        <span>Dasbor</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-auth-login">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-auth-register">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- MAIN DETAIL CONTENT -->
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

    <div class="detail-container" @auth style="max-width: 100%; margin: 0; padding: 0 0 40px 0;" @endauth>
        <p class="page-eyebrow">BOOKNEST / Perpustakaan umum</p>
        <h1 class="page-title">Detail Buku</h1>

        <nav aria-label="Breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('katalog.index') }}">Katalog</a></li>
                <li aria-current="page">{{ $buku->judul }}</li>
            </ol>
        </nav>

        <section class="detail-grid" aria-labelledby="judulBuku">
            <!-- Kolom kiri: cover & lokasi -->
            <div>
                <div class="cover-card">
                    @if (! empty($buku->cover))
                        <img src="{{ $buku->cover }}"
                             alt="Sampul buku {{ $buku->judul }}"
                             fetchpriority="high"
                             onerror="this.onerror=null; this.src='{{ $fallbackCover }}';">
                    @else
                        <div class="cover-fallback">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <h2>{{ $buku->judul }}</h2>
                            <p>{{ $buku->penulis }}</p>
                        </div>
                    @endif
                </div>

                <aside class="location-card" aria-label="Lokasi buku">
                    <h3>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        Perpustakaan Pusat
                    </h3>
                    <p>{{ $rakLengkap }} · Area {{ $namaKategori }}</p>
                    <p>Senin–Sabtu, 08.00–17.00 WIB</p>
                </aside>
            </div>

            <!-- Kolom kanan: informasi -->
            <div>
                @if ($isTersedia)
                    <span class="badge-status tersedia"><span class="badge-dot"></span>Tersedia</span>
                @else
                    <span class="badge-status habis"><span class="badge-dot"></span>Sedang dipinjam</span>
                @endif

                <h2 class="book-title-main" id="judulBuku">{{ $buku->judul }}</h2>
                <p class="book-author-main">{{ $buku->penulis }}</p>

                <div class="spec-card">
                    <dl class="spec-list">
                        <div class="spec-row"><dt>Kategori</dt><dd>{{ $namaKategori }}</dd></div>
                        <div class="spec-row"><dt>ISBN</dt><dd>{{ $buku->isbn ?? '-' }}</dd></div>
                        <div class="spec-row"><dt>Penerbit</dt><dd>{{ $buku->penerbit }}</dd></div>
                        <div class="spec-row"><dt>Tahun terbit</dt><dd>{{ $buku->tahunTerbit }}</dd></div>
                        <div class="spec-row"><dt>Jumlah halaman</dt><dd>{{ $buku->jumlahHalaman ? $buku->jumlahHalaman.' halaman' : '-' }}</dd></div>
                        <div class="spec-row"><dt>Rak / kode buku</dt><dd>{{ $kodeRak }} / {{ $kodeBuku }}</dd></div>
                        <div class="spec-row">
                            <dt>Stok tersedia</dt>
                            <dd id="stokTersedia" @class(['is-empty' => ! $isTersedia, 'is-low' => $isTersedia && $stokTersedia <= 1])>
                                {{ $stokTersedia }} dari {{ $totalEksemplar }} eksemplar
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="action-row" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('buku.edit', $buku->idBuku) }}" id="btnKelolaBuku" class="btn-pinjam" style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Kelola Data Buku
                        </a>
                    @elseif ($isTersedia)
                        <a href="{{ route('peminjaman.konfirmasi', $buku->idBuku) }}" id="btnPinjamBuku" class="btn-pinjam">
                            Pinjam Buku
                        </a>

                        @if (auth()->check() && auth()->user()->role === 'member')
                            @php
                                $cart = session('keranjang_booking', []);
                                $inCart = isset($cart[$buku->idBuku]);
                            @endphp
                            @if ($inCart)
                                <a href="{{ route('keranjang.index') }}" class="btn-cart-added" style="display: inline-flex; align-items: center; gap: 6px; padding: 11px 18px; background: #ccfbf1; color: #0f766e; border: 1px solid #99f6e4; border-radius: 8px; font-size: 13.5px; font-weight: 700; text-decoration: none;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    Sudah di Keranjang
                                </a>
                            @else
                                <form action="{{ route('keranjang.tambah', $buku->idBuku) }}" method="POST" style="display: inline;" class="form-ajax-cart" id="formCartDetail">
                                    @csrf
                                    <button type="submit" class="btn-add-cart" style="display: inline-flex; align-items: center; gap: 6px; padding: 11px 18px; background: #ffffff; color: #0f766e; border: 1.5px solid #0f766e; border-radius: 8px; font-size: 13.5px; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                        + Masukkan Keranjang
                                    </button>
                                </form>
                            @endif
                        @endif
                    @else
                        <button type="button" class="btn-pinjam" disabled>
                            Semua eksemplar sedang dipinjam
                        </button>
                    @endif
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('buku.index') }}" id="btnKembali" class="btn-kembali">Kembali ke Kelola Buku</a>
                    @else
                        <a href="{{ $urlKembali }}" id="btnKembali" class="btn-kembali">Kembali</a>
                    @endif
                </div>

                <p class="loan-note">
                    Masa pinjam {{ $masaPinjamBulan }} bulan · Maksimal {{ $batasMaksimalBuku }} buku per anggota. Pengambilan dilakukan di meja layanan.
                </p>
            </div>
        </section>

        <!-- Tentang buku -->
        <section class="about-card" aria-labelledby="tentangBuku">
            <h2 id="tentangBuku">Tentang buku ini</h2>
            @forelse (preg_split('/\R{2,}/', trim((string) $buku->sinopsis), -1, PREG_SPLIT_NO_EMPTY) as $paragraf)
                <p>{{ $paragraf }}</p>
            @empty
                <p>{{ $buku->judul }} karya {{ $buku->penulis }} diterbitkan oleh {{ $buku->penerbit }} pada tahun {{ $buku->tahunTerbit }}. Sinopsis buku ini belum tersedia.</p>
            @endforelse
        </section>

        <!-- Bacaan lain -->
        @if ($bukuTerkait->isNotEmpty())
            <section class="section-related" aria-labelledby="bacaanLain">
                <h2 class="section-title" id="bacaanLain">Bacaan lain yang mungkin kamu suka</h2>

                <div class="related-grid">
                    @foreach ($bukuTerkait as $terkait)
                        <article class="related-card">
                            <a href="{{ route('katalog.show', $terkait->idBuku) }}" class="related-cover" tabindex="-1" aria-hidden="true">
                                @if (! empty($terkait->cover))
                                    <img src="{{ $terkait->cover }}" alt="" loading="lazy" onerror="this.onerror=null; this.src='{{ $fallbackCover }}';">
                                @else
                                    <div class="related-cover-fallback">
                                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            <div class="related-body">
                                <div class="related-badges">
                                    @if ($terkait->stok > 0)
                                        <span class="badge-status tersedia"><span class="badge-dot"></span>Tersedia</span>
                                    @else
                                        <span class="badge-status habis"><span class="badge-dot"></span>Habis</span>
                                    @endif

                                    <span class="badge-rating">
                                        <svg width="13" height="13" viewBox="0 0 24 24" aria-hidden="true">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                        4,8
                                    </span>
                                </div>

                                <h3 class="related-title">
                                    <a href="{{ route('katalog.show', $terkait->idBuku) }}">{{ $terkait->judul }}</a>
                                </h3>
                                <p class="related-author">{{ $terkait->penulis }} · {{ $terkait->kategori?->namaKategori ?? 'Umum' }}</p>

                                <div class="related-meta">
                                    <span>
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                        </svg>
                                        {{ $terkait->jumlahHalaman ?? '-' }} halaman
                                    </span>
                                    <span>
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        {{ $terkait->rak ?? '-' }}
                                    </span>
                                </div>

                                <a href="{{ route('katalog.show', $terkait->idBuku) }}" class="btn-related">Pinjam Buku</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    @auth
            </main>
        </div>
    @endauth

    <!-- DIALOG PANDUAN PEMINJAMAN -->
    <dialog id="borrowDialog" class="borrow-dialog" aria-labelledby="borrowDialogTitle">
        <div class="dialog-header">
            <h2 id="borrowDialogTitle">Pinjam “{{ $buku->judul }}”</h2>
            <button type="button" class="dialog-close" data-close-dialog aria-label="Tutup">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="dialog-body">
            <div class="dialog-location">
                <div class="dialog-location-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div>
                    <strong>{{ $rakLengkap }} · Perpustakaan Pusat</strong>
                    <span>{{ $stokTersedia }} dari {{ $totalEksemplar }} eksemplar tersedia</span>
                </div>
            </div>

            @guest
                <ol class="dialog-steps">
                    <li>Masuk atau daftar sebagai anggota BOOKNEST.</li>
                    <li>Ambil buku di {{ $rakLengkap }}.</li>
                    <li>Tunjukkan kartu QR anggota ke petugas di meja layanan.</li>
                </ol>
                <div class="dialog-actions">
                    <a href="{{ route('register') }}" class="btn-dialog-cancel">Daftar</a>
                    <a href="{{ route('login') }}" class="btn-dialog-primary">Masuk untuk pinjam</a>
                </div>
            @else
                @if (auth()->user()->role === 'member')
                    <ol class="dialog-steps">
                        <li>Ajukan peminjaman buku secara online melalui sistem perpustakaan.</li>
                        <li>Ambil buku di {{ $rakLengkap }}, Perpustakaan Pusat dengan kartu anggota.</li>
                        <li>Buku wajib dikembalikan sebelum batas waktu jatuh tempo ({{ \App\Models\Peminjaman::MASA_PINJAM_HARI }} hari).</li>
                    </ol>
                    <div class="dialog-actions">
                        <button type="button" class="btn-dialog-cancel" data-close-dialog>Tutup</button>
                        <a href="{{ route('member.kartu-saya') }}" class="btn-dialog-cancel">Buka Kartu / QR Saya</a>
                        <a href="{{ route('peminjaman.konfirmasi', $buku->idBuku) }}" class="btn-dialog-primary">Lanjutkan ke Peminjaman</a>
                    </div>
                @elseif (auth()->user()->role === 'petugas')
                    <ol class="dialog-steps">
                        <li>Anda masuk sebagai <strong>Petugas Perpustakaan</strong>.</li>
                        <li>Pindai QR anggota dan barcode <strong>{{ $kodeBuku }}</strong> di form sirkulasi.</li>
                    </ol>
                    <div class="dialog-actions">
                        <button type="button" class="btn-dialog-cancel" data-close-dialog>Tutup</button>
                        <a href="{{ route('peminjaman.create') }}" class="btn-dialog-primary">Buka Form Sirkulasi</a>
                    </div>
                @else
                    {{-- Admin --}}
                    <ol class="dialog-steps">
                        <li>Anda masuk sebagai <strong>Admin Perpustakaan</strong>.</li>
                        <li>Admin berwenang dalam pembaruan data dan manajemen eksemplar buku ini.</li>
                    </ol>
                    <div class="dialog-actions">
                        <button type="button" class="btn-dialog-cancel" data-close-dialog>Tutup</button>
                        <a href="{{ route('buku.edit', $buku->idBuku) }}" class="btn-dialog-primary">Kelola Data Buku</a>
                    </div>
                @endif
            @endguest
        </div>
    </dialog>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-brand">
                <h3>BOOKNEST</h3>
                <p>Temukan. Baca. Berkembang.<br>Ruang pengetahuan untuk semua.</p>
            </div>

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

    <script>
        (() => {
            const dialog = document.getElementById('borrowDialog');
            const tombolPinjam = document.getElementById('btnPinjamBuku');

            if (tombolPinjam && tombolPinjam.tagName === 'BUTTON') {
                tombolPinjam.addEventListener('click', () => dialog.showModal());
            }

            dialog.querySelectorAll('[data-close-dialog]').forEach((tombol) => {
                tombol.addEventListener('click', () => dialog.close());
            });

            // Tutup dialog saat area backdrop diklik
            dialog.addEventListener('click', (event) => {
                if (event.target === dialog) {
                    dialog.close();
                }
            });
        })();

        /* =========================================================
           FLY-TO-CART & AJAX KERANJANG SYSTEM (TANPA RELOAD)
           ========================================================= */

        function showCartToast(options) {
            const container = document.getElementById('cartToastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `cart-toast toast-${options.type || 'success'}`;

            let iconSvg = '';
            if (options.type === 'error' || options.type === 'info') {
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
            requestAnimationFrame(() => toast.classList.add('show'));

            const closeToast = () => {
                toast.classList.remove('show');
                setTimeout(() => { if (toast.parentElement) toast.remove(); }, 350);
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

            const midX = deltaX * 0.42;
            const midY = deltaY * 0.2 - 60;

            if ('animate' in flyer) {
                const anim = flyer.animate([
                    { transform: 'translate(0px, 0px) scale(1) rotate(0deg)', opacity: 1 },
                    { offset: 0.45, transform: `translate(${midX}px, ${midY}px) scale(0.65) rotate(-14deg)`, opacity: 0.95 },
                    { offset: 0.85, transform: `translate(${deltaX * 0.92}px, ${deltaY * 0.92}px) scale(0.3) rotate(12deg)`, opacity: 0.7 },
                    { transform: `translate(${deltaX}px, ${deltaY}px) scale(0.18) rotate(0deg)`, opacity: 0.2 }
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

        document.addEventListener('submit', async function(e) {
            const form = e.target.closest('.form-ajax-cart');
            if (!form) return;

            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && submitBtn.disabled) return;
            if (submitBtn) submitBtn.disabled = true;

            const coverImg = document.querySelector('.cover-card img') || submitBtn;
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
                    flyToCartAnimation(coverImg, function() {
                        triggerCartLandingEffects(data.totalItem);

                        showCartToast({
                            type: 'success',
                            title: 'Buku Masuk Keranjang!',
                            message: `"${data.judul || 'Buku'}" berhasil ditambahkan ke keranjang booking.`,
                            url: "{{ route('keranjang.index') }}"
                        });
                    });

                    // Ganti form tombol dengan state "Sudah di Keranjang"
                    const cartAddedLink = document.createElement('a');
                    cartAddedLink.href = "{{ route('keranjang.index') }}";
                    cartAddedLink.className = 'btn-cart-added';
                    cartAddedLink.style.cssText = 'display: inline-flex; align-items: center; gap: 6px; padding: 11px 18px; background: #ccfbf1; color: #0f766e; border: 1px solid #99f6e4; border-radius: 8px; font-size: 13.5px; font-weight: 700; text-decoration: none;';
                    cartAddedLink.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Sudah di Keranjang`;
                    form.replaceWith(cartAddedLink);

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