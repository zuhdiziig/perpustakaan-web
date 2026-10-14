<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Panduan Peminjaman Buku - BOOKNEST</title>

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
            margin-bottom: 24px;
        }

        .hero-stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-top: 10px;
            position: relative;
            z-index: 2;
        }

        .hero-stat-box {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 16px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .hero-stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            flex-shrink: 0;
        }

        .hero-stat-num {
            font-size: 18px;
            font-weight: 800;
            line-height: 1.2;
        }

        .hero-stat-label {
            font-size: 11.5px;
            color: #ccfbf1;
            font-weight: 500;
        }

        /* SECTION STYLES */
        .guide-section {
            margin-bottom: 40px;
        }

        .section-header-wrap {
            margin-bottom: 22px;
        }

        .section-eyebrow {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #0f766e;
            margin-bottom: 4px;
            display: block;
        }

        .section-heading-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.4px;
        }

        .section-heading-desc {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* STEP CARDS (PROSES PEMINJAMAN) */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .step-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px 22px;
            position: relative;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
        }

        .step-card:hover {
            transform: translateY(-3px);
            border-color: #99f6e4;
            box-shadow: 0 14px 30px rgba(15, 118, 110, 0.08);
        }

        .step-card-num {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: #ccfbf1;
            color: #0f766e;
            font-size: 13px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .step-card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
            margin-bottom: 8px;
            line-height: 1.35;
        }

        .step-card-text {
            font-size: 13px;
            color: var(--text-body);
            line-height: 1.6;
            margin-bottom: 14px;
            flex-grow: 1;
        }

        .step-card-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            font-weight: 700;
            color: #0f766e;
            background: #f0fdfa;
            padding: 4px 10px;
            border-radius: 8px;
            align-self: flex-start;
        }

        /* DUAL COLUMN CARD LAYOUT */
        .dual-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }

        .feature-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 22px;
            padding: 28px 26px;
            transition: all 0.2s ease;
        }

        .feature-card:hover {
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
        }

        .feature-card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
        }

        .feature-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #f0fdfa;
            color: #0f766e;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid #ccfbf1;
        }

        .feature-card-icon.amber {
            background: #fffbeb;
            color: #d97706;
            border-color: #fef3c7;
        }

        .feature-card-icon.blue {
            background: #eff6ff;
            color: #2563eb;
            border-color: #dbeafe;
        }

        .feature-card-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.3px;
        }

        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 14px;
        }

        .feature-list li {
            font-size: 13.5px;
            color: var(--text-body);
            line-height: 1.55;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .feature-list li svg {
            flex-shrink: 0;
            margin-top: 3px;
            color: #0f766e;
        }

        /* CALLOUT BANNER */
        .callout-box {
            background: #f0fdfa;
            border: 1px solid #a7f3d0;
            border-radius: 18px;
            padding: 22px 24px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-top: 24px;
        }

        .callout-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #0f766e;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .callout-content h4 {
            font-size: 15px;
            font-weight: 800;
            color: #065f46;
            margin-bottom: 4px;
        }

        .callout-content p {
            font-size: 13px;
            color: #047857;
            line-height: 1.6;
        }

        /* FAQ ACCORDION LIST */
        .faq-accordion-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .faq-item {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .faq-item:hover {
            border-color: #cbd5e1;
        }

        .faq-question {
            width: 100%;
            padding: 18px 22px;
            background: none;
            border: none;
            text-align: left;
            font-family: inherit;
            font-size: 14.5px;
            font-weight: 700;
            color: var(--text-heading);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .faq-question svg {
            color: #64748b;
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }

        .faq-item.open .faq-question svg {
            transform: rotate(180deg);
            color: #0f766e;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0.16, 1, 0.3, 1), padding 0.2s ease;
            padding: 0 22px;
            font-size: 13.5px;
            color: var(--text-body);
            line-height: 1.65;
            background: #fafafc;
        }

        .faq-item.open .faq-answer {
            max-height: 300px;
            padding: 0 22px 18px;
            border-top: 1px solid #f1f5f9;
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
            <span style="color: var(--text-heading); font-weight: 700;">Panduan Peminjaman</span>
        </nav>

        {{-- HERO SECTION --}}
        <section class="page-hero-card">
            <div class="hero-badge-pill">
                <span class="dot"></span>
                <span>Panduan & Sirkulasi BOOKNEST</span>
            </div>
            <h1 class="hero-main-title">
                Tata Cara Peminjaman & Pengembalian Buku
            </h1>
            <p class="hero-main-desc">
                Pelajari alur mudah sirkulasi koleksi buku fisik di perpustakaan BOOKNEST. Mulai dari reservasi online via keranjang booking, verifikasi cepat dengan kartu QR anggota di meja sirkulasi, hingga pengembalian mandiri bebas antrean.
            </p>

            <div class="hero-stats-row">
                <div class="hero-stat-box">
                    <div class="hero-stat-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                            <line x1="8" y1="6" x2="16" y2="6"></line>
                            <line x1="8" y1="10" x2="16" y2="10"></line>
                        </svg>
                    </div>
                    <div>
                        <div class="hero-stat-num">{{ \App\Models\Peminjaman::BATAS_MAKSIMAL_BUKU }} Buku</div>
                        <div class="hero-stat-label">Batas Kuota Pinjam Aktif</div>
                    </div>
                </div>

                <div class="hero-stat-box">
                    <div class="hero-stat-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div>
                        <div class="hero-stat-num">{{ \App\Models\Peminjaman::MASA_PINJAM_HARI }} Hari</div>
                        <div class="hero-stat-label">Masa Peminjaman Buku</div>
                    </div>
                </div>

                <div class="hero-stat-box">
                    <div class="hero-stat-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </div>
                    <div>
                        <div class="hero-stat-num">QR Digital</div>
                        <div class="hero-stat-label">Verifikasi Cepat Tanpa Kertas</div>
                    </div>
                </div>

                <div class="hero-stat-box">
                    <div class="hero-stat-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div>
                        <div class="hero-stat-num">Bebas Denda</div>
                        <div class="hero-stat-label">Bila Tepat Waktu</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ALUR PEMINJAMAN (5 LANGKAH) --}}
        <section class="guide-section">
            <div class="section-header-wrap">
                <span class="section-eyebrow">Alur Langkah Demi Langkah</span>
                <h2 class="section-heading-title">Cara Meminjam Buku Fisik</h2>
                <p class="section-heading-desc">Ikuti 5 langkah sederhana berikut untuk mendapatkan buku bacaan favoritmu di BOOKNEST.</p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-card-num">01</div>
                    <h3 class="step-card-title">Cari Buku di Katalog</h3>
                    <p class="step-card-text">
                        Buka menu Katalog Buku. Gunakan fitur pencarian cepat berdasarkan judul, nama penulis, atau kategori seperti Fiksi, Sejarah, Sains, hingga Romance.
                    </p>
                    <span class="step-card-tag">🔎 Cek Posisi Rak Fisik</span>
                </div>

                <div class="step-card">
                    <div class="step-card-num">02</div>
                    <h3 class="step-card-title">Gunakan Keranjang Booking</h3>
                    <p class="step-card-text">
                        Ingin meminjam beberapa buku sekaligus? Masukkan ke Keranjang Booking. Anda dapat mengumpulkan hingga {{ \App\Models\Peminjaman::BATAS_MAKSIMAL_BUKU }} buku dalam satu transaksi.
                    </p>
                    <span class="step-card-tag">🛒 Booking Sekaligus</span>
                </div>

                <div class="step-card">
                    <div class="step-card-num">03</div>
                    <h3 class="step-card-title">Konfirmasi Peminjaman</h3>
                    <p class="step-card-text">
                        Tinjau ringkasan buku dan tanggal jatuh tempo. Tekan tombol konfirmasi peminjaman untuk menerbitkan nomor transaksi dan tiket reservasi digital.
                    </p>
                    <span class="step-card-tag">📋 Tiket Otomatis</span>
                </div>

                <div class="step-card">
                    <div class="step-card-num">04</div>
                    <h3 class="step-card-title">Tunjukkan QR ke Petugas</h3>
                    <p class="step-card-text">
                        Kunjungi meja layanan sirkulasi perpustakaan. Tunjukkan Kartu / QR Anggota dari smartphone Anda kepada petugas untuk diverifikasi langsung.
                    </p>
                    <span class="step-card-tag">⚡ Scan Meja Sirkulasi</span>
                </div>

                <div class="step-card">
                    <div class="step-card-num">05</div>
                    <h3 class="step-card-title">Serah Terima & Bawa Pulang</h3>
                    <p class="step-card-text">
                        Petugas memindai buku dan menyerahkan koleksi fisik. Notifikasi status "Dipinjam" langsung terbit real-time di layar akun anggota Anda.
                    </p>
                    <span class="step-card-tag">📖 Selamat Membaca!</span>
                </div>
            </div>
        </section>

        {{-- PANDUAN PENGEMBALIAN & KONDISI BUKU --}}
        <section class="guide-section">
            <div class="section-header-wrap">
                <span class="section-eyebrow">Pengembalian Mandiri</span>
                <h2 class="section-heading-title">Tata Cara Pengembalian Buku</h2>
                <p class="section-heading-desc">Kembalikan buku dengan mudah dalam satu kesatuan batch peminjaman sebelum batas jatuh tempo berakhir.</p>
            </div>

            <div class="dual-card-grid">
                <div class="feature-card">
                    <div class="feature-card-header">
                        <div class="feature-card-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                        </div>
                        <h3 class="feature-card-title">Langkah Pengembalian</h3>
                    </div>
                    <ul class="feature-list">
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Buka menu <strong>Pengembalian</strong> di Dasbor Anggota untuk melihat daftar peminjaman aktif Anda.</span>
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Klik <strong>Kembalikan</strong> pada batch transaksi peminjaman untuk menerbitkan tiket verifikasi pengembalian.</span>
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Bawa buku fisik ke meja sirkulasi perpustakaan lalu klik <strong>Tunjukkan QR Anggota</strong>.</span>
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Saat QR dipindai petugas, pop-up konfirmasi keberhasilan pengembalian akan langsung muncul di layar Anda.</span>
                        </li>
                    </ul>
                </div>

                <div class="feature-card">
                    <div class="feature-card-header">
                        <div class="feature-card-icon amber">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <h3 class="feature-card-title">Ketentuan Kondisi & Denda</h3>
                    </div>
                    <ul class="feature-list">
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span><strong>Kondisi Baik:</strong> Bebas biaya ganti rugi jika buku dikembalikan dalam keadaan utuh, bersih, dan rapi.</span>
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span><strong>Kondisi Rusak / Hilang:</strong> Anggota wajib mengganti rugi sebesar 100% dari harga katalog buku fisik.</span>
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span><strong>Keterlambatan:</strong> Dihitung tarif denda progresif per kelipatan minggu keterlambatan.</span>
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span><strong>Pembayaran Digital via QRIS:</strong> Seluruh denda dapat dibayar instan melalui QRIS di sistem dengan bukti tanda terima digital.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="callout-box">
                <div class="callout-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <div class="callout-content">
                    <h4>Tips Merawat Buku Perpustakaan</h4>
                    <p>
                        Gunakan pembatas buku resmi dan hindari melipat ujung halaman (dog-ear), mencoret, atau membasahi buku. Menjaga buku tetap prima memastikan anggota lain dapat menikmati manfaat yang sama.
                    </p>
                </div>
            </div>
        </section>

        {{-- FAQ (PERTANYAAN UMUM) --}}
        <section class="guide-section">
            <div class="section-header-wrap">
                <span class="section-eyebrow">Tanya Jawab</span>
                <h2 class="section-heading-title">Pertanyaan yang Sering Diajukan (FAQ)</h2>
                <p class="section-heading-desc">Jawaban ringkas seputar hal teknis peminjaman dan pengelolaan akun anggota.</p>
            </div>

            <div class="faq-accordion-list">
                <div class="faq-item open">
                    <button type="button" class="faq-question" onclick="toggleFaq(this)">
                        <span>Berapa batas maksimal buku yang dapat dipinjam bersamaan?</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="faq-answer">
                        Setiap anggota berstatus aktif memiliki batas kuota maksimal sebanyak <strong>{{ \App\Models\Peminjaman::BATAS_MAKSIMAL_BUKU }} buku fisik</strong> dalam waktu bersamaan. Jika kuota telah penuh, Anda perlu mengembalikan salah satu buku terlebih dahulu untuk meminjam judul baru.
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question" onclick="toggleFaq(this)">
                        <span>Berapa lama masa peminjaman untuk setiap buku?</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="faq-answer">
                        Masa peminjaman standar adalah <strong>{{ \App\Models\Peminjaman::MASA_PINJAM_HARI }} hari kalender</strong> terhitung sejak buku diserahkan oleh petugas sirkulasi. Anda dapat melihat hitung mundur sisa hari pinjaman secara realtime di dasbor akun Anda.
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question" onclick="toggleFaq(this)">
                        <span>Bagaimana jika buku yang dipinjam terlambat dikembalikan?</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="faq-answer">
                        Keterlambatan akan dikenai estimasi denda progresif secara otomatis oleh sistem. Tagihan denda dapat diselesaikan dengan memindai kode QRIS di halaman pembayaran denda sebelum tiket pengembalian diterbitkan atau saat verifikasi di meja sirkulasi.
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question" onclick="toggleFaq(this)">
                        <span>Apakah saya bisa meminjam lebih dari 1 buku sekaligus dalam satu transaksi?</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="faq-answer">
                        Tentu saja! Anda dapat menggunakan fitur <strong>Keranjang Booking</strong> untuk mengumpulkan beberapa buku pilihan dari berbagai rak, lalu menekan tombol checkout untuk membuat satu nomor transaksi peminjaman (batch peminjaman).
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question" onclick="toggleFaq(this)">
                        <span>Bagaimana jika saya kehilangan Kartu Anggota fisik saya?</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="faq-answer">
                        BOOKNEST menggunakan sistem identitas digital terpadu. Anda tidak perlu khawatir karena <strong>Kartu Digital & QR Anggota</strong> selalu tersedia di menu akun Anda dan dapat dibuka melalui browser smartphone kapan saja saat mengunjungi perpustakaan.
                    </div>
                </div>
            </div>
        </section>

        {{-- CALL TO ACTION BAR --}}
        <section style="background: #ffffff; border: 1.5px solid var(--border-color); border-radius: 22px; padding: 32px 30px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
            <div>
                <h3 style="font-size: 20px; font-weight: 800; color: var(--text-heading); margin-bottom: 4px;">Siap Memulai Petualangan Membacamu?</h3>
                <p style="font-size: 13.5px; color: var(--text-muted);">Jelajahi ribuan koleksi buku fisik terbaik di katalog BOOKNEST hari ini.</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('katalog.index') }}" class="btn-auth-register" style="padding: 10px 22px; font-size: 14px;">
                    Jelajahi Katalog Buku
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-auth-login" style="padding: 10px 22px; font-size: 14px;">
                        Buka Dasbor
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-auth-login" style="padding: 10px 22px; font-size: 14px;">
                        Daftar Anggota
                    </a>
                @endauth
            </div>
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
                        <a href="{{ route('panduan-peminjaman') }}" style="color: #0f766e; font-weight: 700;">Panduan peminjaman</a>
                        <a href="{{ route('kebijakan-privasi') }}">Kebijakan privasi</a>
                        <a href="{{ route('syarat-layanan') }}">Syarat layanan</a>
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

    <script>
        function toggleFaq(button) {
            const item = button.closest('.faq-item');
            if (!item) return;
            const isOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('open'));
            if (!isOpen) {
                item.classList.add('open');
            }
        }
    </script>
</body>
</html>
