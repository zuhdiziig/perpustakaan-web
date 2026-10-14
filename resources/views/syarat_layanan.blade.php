<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Syarat Layanan & Tata Tertib - BOOKNEST</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('layouts.partials.sidebar_styles')

    <style>
        :root {
            --brand-primary: #0f766e;
            --brand-primary-dark: #115e59;
            --brand-primary-light: #ccfbf1;
            --brand-primary-surface: #f0fdfa;
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

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-page);
            color: var(--text-body);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* TOPBAR HEADER */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
        }

        .topbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 14px 28px;
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
            text-decoration: none;
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
            gap: 8px;
        }

        .topbar-nav-link {
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 13.5px;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .topbar-nav-link:hover {
            color: var(--text-heading);
            background: #f1f5f9;
            transform: translateY(-1px);
        }

        .topbar-nav-link.active {
            background: var(--brand-primary-light);
            color: #0f766e;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(15, 118, 110, 0.12);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            border: 1px solid var(--border-color);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
        }

        .topbar-icon-btn:hover {
            background: #f1f5f9;
            color: var(--text-heading);
            transform: translateY(-1px);
        }

        .topbar-user-badge {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ccfbf1;
            color: #0f766e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .topbar-user-badge:hover {
            transform: translateY(-1px);
        }

        .btn-auth-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            color: #0f766e;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-auth-login:hover {
            border-color: #0f766e;
            background: #f0fdfa;
            transform: translateY(-1px);
        }

        .btn-auth-register {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            background: #0f766e;
            color: #ffffff;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-auth-register:hover {
            background: #115e59;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
            transform: translateY(-1px);
        }

        /* PAGE CONTAINER */
        .info-page-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 34px 28px 58px;
        }

        /* BREADCRUMB */
        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        .breadcrumb-nav a {
            color: var(--text-muted);
            transition: color 0.15s;
        }

        .breadcrumb-nav a:hover {
            color: #0f766e;
        }

        .breadcrumb-separator {
            font-size: 11px;
            color: #cbd5e1;
        }

        /* HERO HEADER */
        .page-hero-card {
            background: linear-gradient(135deg, #0f766e 0%, #115e59 55%, #134e4a 100%);
            border-radius: 24px;
            padding: 44px 40px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            margin-bottom: 36px;
            box-shadow: 0 16px 36px -10px rgba(15, 118, 110, 0.3);
        }

        .page-hero-card::after {
            content: '';
            position: absolute;
            right: -60px;
            bottom: -80px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #ccfbf1;
            margin-bottom: 14px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero-badge-pill .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #34d399;
        }

        .hero-main-title {
            font-size: 32px;
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -0.6px;
            margin-bottom: 12px;
            max-width: 800px;
        }

        .hero-main-desc {
            font-size: 15px;
            color: #e6fffa;
            line-height: 1.65;
            max-width: 760px;
            margin-bottom: 20px;
        }

        .hero-meta-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            color: #ccfbf1;
            background: rgba(255, 255, 255, 0.12);
            padding: 6px 14px;
            border-radius: 10px;
        }

        /* HIGHLIGHT CARDS */
        .summary-rules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .summary-rule-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px 22px;
            transition: all 0.2s ease;
        }

        .summary-rule-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
            border-color: #99f6e4;
        }

        .rule-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #f0fdfa;
            color: #0f766e;
            border: 1px solid #ccfbf1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .rule-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 8px;
        }

        .rule-text {
            font-size: 13px;
            color: var(--text-body);
            line-height: 1.6;
        }

        /* ARTICLES LIST */
        .terms-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .term-article-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 22px;
            padding: 30px 28px;
            transition: all 0.2s ease;
        }

        .term-article-card:hover {
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.04);
        }

        .term-header {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 14px;
        }

        .term-num-badge {
            font-size: 13px;
            font-weight: 800;
            background: #f1f5f9;
            color: #475569;
            padding: 4px 10px;
            border-radius: 8px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .term-title {
            font-size: 19px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.3px;
        }

        .term-body {
            font-size: 14px;
            color: var(--text-body);
            line-height: 1.75;
        }

        .term-body p {
            margin-bottom: 12px;
        }

        .term-body p:last-child {
            margin-bottom: 0;
        }

        .term-list {
            margin: 12px 0 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .term-list li {
            font-size: 13.5px;
            color: var(--text-body);
            line-height: 1.6;
        }

        .term-callout {
            background: #f8fafc;
            border-left: 4px solid #0f766e;
            padding: 14px 18px;
            border-radius: 0 12px 12px 0;
            margin-top: 14px;
            font-size: 13px;
            color: #334155;
            line-height: 1.6;
        }

        /* FOOTER */
        .about-footer {
            margin-top: 48px;
            padding-top: 36px;
            border-top: 1px solid var(--border-color);
        }

        .about-footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 36px;
        }

        .footer-brand-title {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.4px;
            color: var(--text-heading);
        }

        .footer-brand-tagline {
            margin: 8px 0 0;
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .footer-column-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-heading);
            margin-bottom: 12px;
        }

        .footer-links {
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

        .footer-address {
            margin: 0;
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .about-footer-bottom {
            margin-top: 32px;
            padding-top: 18px;
            border-top: 1px solid var(--border-color);
            font-size: 12px;
            color: var(--text-muted);
            text-align: center;
        }

        @media (max-width: 900px) {
            .about-footer-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }
            .hero-main-title {
                font-size: 26px;
            }
            .page-hero-card {
                padding: 30px 22px;
            }
        }
    </style>
</head>

<body>
    <!-- TOPBAR (STANDARD 1400PX) -->
    <header class="topbar">
        <div class="topbar-container">
            <div style="display: flex; align-items: center; gap: 12px;">
                @auth
                    <button type="button" class="mobile-toggle-btn" id="sidebarToggle" aria-label="Buka Menu Navigasi" style="display: flex;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                @endauth
                <a href="{{ route('home') }}" class="topbar-brand">
                    <div class="topbar-brand-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </div>
                    <span>BOOKNEST</span>
                </a>
            </div>

            <!-- Top Nav Links -->
            <nav class="topbar-nav">
                <a href="{{ route('home') }}" class="topbar-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    Beranda
                </a>
                @if(!auth()->check() || auth()->user()->role === 'member')
                    <a href="{{ route('katalog.index') }}" class="topbar-nav-link {{ request()->routeIs('katalog.*') ? 'active' : '' }}">
                        Katalog
                    </a>
                @endif
                @auth
                    <a href="{{ route('dashboard') }}" class="topbar-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dasbor
                    </a>
                @else
                    <a href="{{ route('login') }}" class="topbar-nav-link">
                        Dasbor
                    </a>
                @endauth
                <a href="{{ route('tentang') }}" class="topbar-nav-link">
                    Tentang
                </a>
            </nav>

            <!-- Topbar Right Actions -->
            <div class="topbar-right">
                @auth
                    @if(auth()->user()->role === 'member')
                        @php
                            $jumlahKeranjang = count(session('keranjang_booking', []));
                        @endphp
                        <a href="{{ route('keranjang.index') }}" class="topbar-icon-btn" id="topbarCartBtn" title="Keranjang Booking ({{ $jumlahKeranjang }} Buku)" style="position: relative;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <span id="topbarCartBadge" style="position: absolute; top: -4px; right: -4px; background: #0f766e; color: #ffffff; font-size: 10px; font-weight: 800; width: 18px; height: 18px; border-radius: 50%; display: {{ $jumlahKeranjang > 0 ? 'flex' : 'none' }}; align-items: center; justify-content: center; border: 2px solid #ffffff;">
                                {{ $jumlahKeranjang }}
                            </span>
                        </a>

                        <button type="button" class="topbar-icon-btn" onclick="openUniversalSearchModal()" title="Pencarian Cepat" aria-label="Buka Pencarian Cepat" style="background: none; cursor: pointer;">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('buku.index') }}" class="topbar-icon-btn" title="Kelola Master Buku">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="topbar-user-badge" title="Profil Pengguna">
                        @if(auth()->user()->foto ?? false)
                            <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        @else
                            {{ auth()->user()->inisial ?? strtoupper(substr(auth()->user()->name ?? 'US', 0, 2)) }}
                        @endif
                    </a>
                @else
                    <div class="guest-nav-actions" style="display: flex; align-items: center; gap: 8px;">
                        <a href="{{ route('login') }}" class="btn-auth-login">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-auth-register">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    @auth
        <!-- MOBILE BACKDROP -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- PAGE WRAPPER (LAYOUT KONSISTEN SEMUA ROLE) -->
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
                <div class="info-page-container" style="max-width: 100%; margin: 0; padding: 0 0 30px 0;">
    @else
        <main class="info-page-container">
    @endauth

        {{-- BREADCRUMB --}}
        <nav class="breadcrumb-nav" aria-label="Navigasi Halaman">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('tentang') }}">Tentang</a>
            <span class="breadcrumb-separator">/</span>
            <span style="color: var(--text-heading); font-weight: 700;">Syarat Layanan</span>
        </nav>

        {{-- HERO SECTION --}}
        <section class="page-hero-card">
            <div class="hero-badge-pill">
                <span class="dot"></span>
                <span>Tata Tertib & Ketentuan Resmi</span>
            </div>
            <h1 class="hero-main-title">
                Syarat dan Ketentuan Layanan BOOKNEST
            </h1>
            <p class="hero-main-desc">
                Pedoman resmi pemanfaatan koleksi buku fisik, fasilitas ruang baca, dan layanan digital perpustakaan BOOKNEST guna mewujudkan ekosistem literasi yang tertib, nyaman, dan berkeadilan bagi seluruh lapisan masyarakat.
            </p>
            <div class="hero-meta-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span>Terakhir diperbarui: 14 Oktober 2026 · Mengikat seluruh anggota terdaftar dan pengunjung</span>
            </div>
        </section>

        {{-- SUMMARY RULES GRID --}}
        <section class="summary-rules-grid">
            <div class="summary-rule-card">
                <div class="rule-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <polyline points="17 11 19 13 23 9"></polyline>
                    </svg>
                </div>
                <h3 class="rule-title">Keanggotaan Sah</h3>
                <p class="rule-text">
                    Setiap anggota bertanggung jawab penuh atas akun, QR digital, dan transaksi peminjaman buku yang dilakukan menggunakan identitasnya.
                </p>
            </div>

            <div class="summary-rule-card">
                <div class="rule-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <h3 class="rule-title">Integritas Koleksi</h3>
                <p class="rule-text">
                    Buku fisik wajib dijaga kebersihannya. Dilarang melipat halaman permanen, mencoret, mengotori, atau merusak barcode buku.
                </p>
            </div>

            <div class="summary-rule-card">
                <div class="rule-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <h3 class="rule-title">Ketepatan Waktu</h3>
                <p class="rule-text">
                    Durasi peminjaman berlaku maksimal {{ \App\Models\Peminjaman::MASA_PINJAM_HARI }} hari kalender. Keterlambatan dikenakan denda progresif sesuai ketentuan sistem.
                </p>
            </div>

            <div class="summary-rule-card">
                <div class="rule-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <h3 class="rule-title">Etika Ruang Baca</h3>
                <p class="rule-text">
                    Menjaga ketenangan, menghormati sesama pembaca, serta menjaga kebersihan fasilitas gedung perpustakaan fisik BOOKNEST.
                </p>
            </div>
        </section>

        {{-- TERMS ARTICLES CONTAINER --}}
        <section class="terms-container">
            {{-- PASAL 1 --}}
            <article class="term-article-card">
                <div class="term-header">
                    <span class="term-num-badge">Pasal 1</span>
                    <h2 class="term-title">Ketentuan Umum & Definisi</h2>
                </div>
                <div class="term-body">
                    <p>Dalam Syarat dan Ketentuan ini, yang dimaksud dengan:</p>
                    <ul class="term-list">
                        <li><strong>BOOKNEST:</strong> Sistem Informasi dan Layanan Perpustakaan Umum BOOKNEST yang berkedudukan di Jl. Merdeka No. 12, Bandung.</li>
                        <li><strong>Anggota (Member):</strong> Pengguna terdaftar yang telah memiliki akun dan hak akses resmi untuk meminjam koleksi fisik perpustakaan.</li>
                        <li><strong>Petugas Sirkulasi:</strong> Staf perpustakaan yang bertugas melayani verifikasi fisik, serah terima buku, dan pengesahan transaksi di meja sirkulasi.</li>
                        <li><strong>Eksemplar Buku:</strong> Salinan fisik buku perpustakaan yang memiliki nomor identitas unik, barcode, dan penempatan nomor rak tertentu.</li>
                    </ul>
                </div>
            </article>

            {{-- PASAL 2 --}}
            <article class="term-article-card">
                <div class="term-header">
                    <span class="term-num-badge">Pasal 2</span>
                    <h2 class="term-title">Hak dan Kewajiban Anggota</h2>
                </div>
                <div class="term-body">
                    <p>Sebagai anggota BOOKNEST, Anda berhak dan berkewajiban atas:</p>
                    <ul class="term-list">
                        <li><strong>Hak Akses Koleksi:</strong> Mengakses seluruh koleksi buku fisik yang berstatus tersedia di katalog dan mengajukan peminjaman sesuai kuota yang berlaku.</li>
                        <li><strong>Kerahasiaan Akun:</strong> Anggota wajib menjaga kerahasiaan kata sandi dan kartu QR digital miliknya. Akun tidak boleh diperjualbelikan atau dialihkan kepada pihak lain.</li>
                        <li><strong>Kebenaran Data:</strong> Anggota wajib memberikan informasi data diri (nama, NIK, kontak) yang benar dan dapat dihubungi.</li>
                    </ul>
                </div>
            </article>

            {{-- PASAL 3 --}}
            <article class="term-article-card">
                <div class="term-header">
                    <span class="term-num-badge">Pasal 3</span>
                    <h2 class="term-title">Ketentuan Peminjaman Koleksi Fisik</h2>
                </div>
                <div class="term-body">
                    <p>Peminjaman koleksi buku fisik diatur dengan ketentuan sebagai berikut:</p>
                    <ul class="term-list">
                        <li>Setiap anggota dapat meminjam hingga maksimal <strong>{{ \App\Models\Peminjaman::BATAS_MAKSIMAL_BUKU }} buku fisik</strong> dalam satu waktu bersamaan.</li>
                        <li>Masa peminjaman standar adalah <strong>{{ \App\Models\Peminjaman::MASA_PINJAM_HARI }} hari kalender</strong> terhitung sejak buku diserahterimakan oleh petugas sirkulasi.</li>
                        <li>Peminjaman dapat dilakukan melalui reservasi mandiri di web (Keranjang Booking) atau langsung di meja sirkulasi perpustakaan dengan menunjukkan QR Anggota.</li>
                        <li>Buku yang telah dipinjam menjadi tanggung jawab penuh anggota sampai buku tersebut tercatat berhasil dikembalikan di sistem.</li>
                    </ul>
                </div>
            </article>

            {{-- PASAL 4 --}}
            <article class="term-article-card">
                <div class="term-header">
                    <span class="term-num-badge">Pasal 4</span>
                    <h2 class="term-title">Keterlambatan, Denda, dan Pembayaran</h2>
                </div>
                <div class="term-body">
                    <p>Demi menjaga perputaran sirkulasi buku bagi seluruh pemustaka, ketentuan keterlambatan diterapkan secara tegas dan transparan:</p>
                    <ul class="term-list">
                        <li>Buku yang melewati batas tanggal jatuh tempo akan otomatis dikenai tarif estimasi denda progresif yang terakumulasi setiap minggu keterlambatan.</li>
                        <li>Selama memiliki tanggungan peminjaman yang terlambat atau denda yang belum dibayar, hak peminjaman buku baru anggota akan ditangguhkan sementara oleh sistem.</li>
                        <li>Seluruh denda keterlambatan diselesaikan secara non-tunai melalui QRIS terintegrasi pada sistem BOOKNEST dan diverifikasi oleh petugas.</li>
                    </ul>
                    <div class="term-callout">
                        <strong>Catatan Sirkulasi:</strong> Anggota yang mengalami kendala mendesak dalam pengembalian diharapkan menghubungi petugas perpustakaan sebelum batas jatuh tempo berakhir.
                    </div>
                </div>
            </article>

            {{-- PASAL 5 --}}
            <article class="term-article-card">
                <div class="term-header">
                    <span class="term-num-badge">Pasal 5</span>
                    <h2 class="term-title">Tanggung Jawab Kerusakan & Kehilangan Buku</h2>
                </div>
                <div class="term-body">
                    <p>Koleksi buku adalah aset bersama masyarakat. Oleh karena itu:</p>
                    <ul class="term-list">
                        <li>Setiap buku yang dikembalikan akan diperiksa kondisi fisiknya oleh petugas sirkulasi di meja layanan.</li>
                        <li>Apabila buku dikembalikan dalam keadaan <strong>Rusak Berat</strong> (robek, terkena cairan, halaman hilang, atau coretan yang merusak teks) atau dinyatakan <strong>Hilang</strong>, peminjam wajib mengganti rugi sebesar <strong>100% dari harga katalog buku fisik</strong> tersebut.</li>
                        <li>Pembayaran ganti rugi dapat diselesaikan melalui menu pengembalian via QRIS atau secara langsung di meja sirkulasi perpustakaan.</li>
                    </ul>
                </div>
            </article>

            {{-- PASAL 6 --}}
            <article class="term-article-card">
                <div class="term-header">
                    <span class="term-num-badge">Pasal 6</span>
                    <h2 class="term-title">Tata Tertib Gedung & Ruang Baca Fisik</h2>
                </div>
                <div class="term-body">
                    <p>Saat berkunjung ke Gedung Perpustakaan BOOKNEST (Jl. Merdeka No. 12, Bandung):</p>
                    <ul class="term-list">
                        <li>Pengunjung wajib menjaga ketenangan dan menyetel nada dering ponsel pada mode senyap (silent).</li>
                        <li>Dilarang merokok, membawa senjata tajam, atau membawa makanan berat ke dalam area ruang baca utama.</li>
                        <li>Buku yang telah selesai dibaca di tempat harap diletakkan di meja pengembalian atau rak sementara agar ditata kembali oleh petugas.</li>
                        <li>Jam layanan operasional berlangsung <strong>Senin hingga Sabtu, pukul 08.00–17.00 WIB</strong>. Minggu dan hari libur nasional layanan fisik tutup.</li>
                    </ul>
                </div>
            </article>

            {{-- PASAL 7 --}}
            <article class="term-article-card">
                <div class="term-header">
                    <span class="term-num-badge">Pasal 7</span>
                    <h2 class="term-title">Perubahan Ketentuan Layanan</h2>
                </div>
                <div class="term-body">
                    <p>
                        BOOKNEST berhak memperbarui atau memodifikasi Syarat dan Ketentuan Layanan ini sewaktu-waktu guna menyelaraskan dengan perkembangan teknologi, regulasi literasi nasional, dan peningkatan kualitas pelayanan. Pembaruan akan diumumkan secara terbuka pada halaman web resmi ini.
                    </p>
                </div>
            </article>
        </section>

        {{-- FOOTER --}}
        <footer class="about-footer">
            <div class="about-footer-grid">
                <div>
                    <h3 class="footer-brand-title">BOOKNEST</h3>
                    <p class="footer-brand-tagline">
                        Temukan. Baca. Berkembang.<br>
                        Ruang pengetahuan untuk semua.
                    </p>
                </div>

                <div>
                    <div class="footer-column-title">Jelajahi BOOKNEST</div>
                    <div class="footer-links">
                        <a href="{{ route('katalog.index') }}">Katalog buku</a>
                        <a href="{{ route('register') }}">Keanggotaan</a>
                        <a href="{{ route('tentang') }}">Tentang kami</a>
                        <a href="{{ route('panduan-peminjaman') }}">Panduan peminjaman</a>
                        <a href="{{ route('kebijakan-privasi') }}">Kebijakan privasi</a>
                        <a href="{{ route('syarat-layanan') }}" style="color: #0f766e; font-weight: 700;">Syarat layanan</a>
                    </div>
                </div>

                <div>
                    <div class="footer-column-title">Kunjungi perpustakaan</div>
                    <p class="footer-address">
                        Jl. Merdeka No. 12, Bandung<br>
                        Senin–Sabtu, 08.00–17.00 WIB<br>
                        halo@booknest.id · (022) 420 1234
                    </p>
                </div>
            </div>

            <div class="about-footer-bottom">
                © 2026 BOOKNEST. Bersama menumbuhkan budaya membaca.
            </div>
        </footer>

    @auth
            </div>
        </main>
        <!-- END CONTENT AREA -->
    </div>
    <!-- END PAGE WRAPPER -->
    @else
        </main>
    @endauth

    @include('layouts.partials.sidebar_scripts')

    @auth
        @if(auth()->user()->role === 'member')
            @include('layouts.partials.member_feedback_toasts')
            @include('layouts.partials.member_realtime_notification')
            @include('layouts.partials.member_universal_search')
            @include('layouts.partials.member_booking_popup')
        @endif
    @endauth
</body>
</html>
