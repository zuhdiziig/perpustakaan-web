<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tentang BOOKNEST</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-primary: #0f766e;
            --brand-primary-dark: #115e59;
            --brand-primary-light: #ccfbf1;

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

        /* ========================================
           TOPBAR HEADER
        ======================================== */

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
            transition: all 0.15s ease;
        }

        .topbar-nav-link:hover {
            color: var(--text-heading);
            background: #f1f5f9;
        }

        .topbar-nav-link.active {
            background: var(--brand-primary-light);
            color: #0f766e;
            font-weight: 700;
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
            transition: background 0.15s;
            text-decoration: none;
        }

        .topbar-icon-btn:hover {
            background: #f1f5f9;
            color: var(--text-heading);
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
        }

        .mobile-menu-button {
            display: none;
            width: 38px;
            height: 38px;
            border: 1px solid var(--border-color);
            border-radius: 9px;
            color: #475569;
            background: #ffffff;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }

        .mobile-menu-button:hover {
            background: #f1f5f9;
            color: var(--text-heading);
        }

        .mobile-nav {
            display: none;
        }

        /* ========================================
           PAGE
        ======================================== */

        .about-page {
            max-width: 1400px;
            margin: 0 auto;
            padding: 34px 28px 58px;
        }

        /* ========================================
           HERO
        ======================================== */

        .about-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(420px, 0.9fr);

            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 18px;

            overflow: hidden;

            margin-bottom: 24px;
        }

        .about-hero-content {
            padding: 44px 44px 42px;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .about-eyebrow {
            display: inline-block;

            margin-bottom: 12px;

            color: var(--brand-primary);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.7px;
            text-transform: uppercase;
        }

        .about-hero-title {
            max-width: 620px;

            margin-bottom: 16px;

            color: var(--text-heading);
            font-size: clamp(30px, 3vw, 42px);
            font-weight: 800;
            line-height: 1.18;
            letter-spacing: -1px;
        }

        .about-hero-description {
            max-width: 600px;

            margin-bottom: 14px;

            color: var(--text-muted);
            font-size: 13.5px;
            line-height: 1.75;
        }

        .about-hero-tagline {
            margin-bottom: 22px;

            color: var(--text-body);
            font-size: 13.5px;
            font-weight: 700;
        }

        .about-primary-button {
            width: fit-content;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 10px 20px;

            background: var(--brand-primary);
            color: #ffffff;

            border-radius: 9px;

            font-size: 13px;
            font-weight: 700;

            transition:
                background-color 0.15s ease,
                transform 0.15s ease,
                box-shadow 0.15s ease;
        }

        .about-primary-button:hover {
            background: var(--brand-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(15, 118, 110, 0.16);
        }

        .about-hero-image {
            min-height: 360px;
        }

        .about-hero-image img {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;
        }

        /* ========================================
           VALUE CARDS
        ======================================== */

        .about-values {
            display: grid;
            grid-template-columns: repeat(3, 1fr);

            background: #ffffff;

            border: 1px solid var(--border-color);
            border-radius: 16px;

            overflow: hidden;

            margin-bottom: 24px;
        }

        .about-value-card {
            padding: 26px 24px;

            border-right: 1px solid var(--border-color);
        }

        .about-value-card:last-child {
            border-right: none;
        }

        .about-value-icon {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 15px;

            background: var(--brand-primary-light);
            color: var(--brand-primary);

            border-radius: 9px;
        }

        .about-value-title {
            margin-bottom: 7px;

            color: var(--text-heading);
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.2px;
        }

        .about-value-description {
            color: var(--text-muted);
            font-size: 12.5px;
            line-height: 1.7;
        }

        /* ========================================
           INFORMATION SECTION
        ======================================== */

        .about-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;

            gap: 20px;

            margin-bottom: 24px;
        }

        .about-info-card {
            background: var(--bg-card);

            border: 1px solid var(--border-color);
            border-radius: 16px;

            padding: 26px 28px;
        }

        .about-info-card.highlight {
            background: #f0fdfa;
        }

        .about-section-title {
            margin-bottom: 15px;

            color: var(--text-heading);
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.4px;
        }

        .about-service-list {
            list-style: none;
        }

        .about-service-list li {
            position: relative;

            padding-left: 15px;
            margin-bottom: 6px;

            color: var(--text-muted);
            font-size: 12.5px;
        }

        .about-service-list li::before {
            content: "•";

            position: absolute;
            left: 0;
            top: 0;

            color: var(--brand-primary);
            font-weight: 800;
        }

        .about-contact-list {
            display: grid;
            gap: 9px;

            margin-bottom: 15px;
        }

        .about-contact-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;

            color: var(--text-muted);
            font-size: 12.5px;
        }

        .about-contact-label {
            color: #64748b;
            flex-shrink: 0;
        }

        .about-contact-value {
            color: var(--text-body);
            font-weight: 600;
            text-align: right;
        }

        .about-info-note {
            color: var(--text-muted);
            font-size: 12px;
            line-height: 1.7;
        }

        /* ========================================
           MEMBERSHIP
        ======================================== */

        .about-membership {
            background: var(--bg-card);

            border: 1px solid var(--border-color);
            border-radius: 16px;

            padding: 26px 28px;

            margin-bottom: 42px;
        }

        .about-membership-title {
            margin-bottom: 9px;

            color: var(--text-heading);
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .about-membership-text {
            max-width: 1100px;

            color: var(--text-muted);
            font-size: 12.5px;
            line-height: 1.75;
        }

        /* ========================================
           FOOTER
        ======================================== */

        .about-footer {
            background: #ffffff;
            border-top: 1px solid var(--border-color);
        }

        .about-footer-inner {
            max-width: 1400px;
            margin: 0 auto;

            padding: 30px 28px 22px;
        }

        .about-footer-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr;

            gap: 50px;

            padding-bottom: 24px;
        }

        .footer-brand-title {
            margin-bottom: 7px;

            color: var(--brand-primary);
            font-size: 15px;
            font-weight: 800;
        }

        .footer-brand-text,
        .footer-links,
        .footer-address {
            color: var(--text-muted);
            font-size: 11.5px;
            line-height: 1.7;
        }

        .footer-column-title {
            margin-bottom: 8px;

            color: var(--text-heading);
            font-size: 12px;
            font-weight: 800;
        }

        .footer-links a {
            display: block;

            width: fit-content;

            transition: color 0.15s ease;
        }

        .footer-links a:hover {
            color: var(--brand-primary);
        }

        .about-footer-bottom {
            padding-top: 18px;

            border-top: 1px solid #e2e8f0;

            color: #94a3b8;
            font-size: 10.5px;
        }

        /* ========================================
           RESPONSIVE
        ======================================== */

        @media (max-width: 1000px) {
            .about-hero {
                grid-template-columns: 1fr;
            }

            .about-hero-image {
                min-height: 320px;
                order: -1;
            }

            .about-values {
                grid-template-columns: 1fr;
            }

            .about-value-card {
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }

            .about-value-card:last-child {
                border-bottom: none;
            }

            .about-info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 760px) {
            .topbar-container {
                padding: 12px 18px;
            }

            .topbar-brand {
                font-size: 16px;
            }

            .topbar-brand-icon {
                width: 34px;
                height: 34px;
            }

            .topbar-nav {
                display: none;
            }

            .mobile-menu-button {
                display: inline-flex;
            }

            .mobile-nav {
                display: none;

                padding: 8px 18px 16px;

                background: #ffffff;
                border-top: 1px solid #f1f5f9;
            }

            .mobile-nav.open {
                display: grid;
                gap: 4px;
            }

            .mobile-nav a {
                padding: 10px 12px;

                color: #475569;

                border-radius: 9px;

                font-size: 13px;
                font-weight: 600;
            }

            .mobile-nav a.active {
                background: var(--brand-primary-light);
                color: var(--brand-primary);
            }

            .about-page {
                padding: 20px 16px 40px;
            }

            .about-hero {
                border-radius: 14px;
            }

            .about-hero-content {
                padding: 28px 22px 30px;
            }

            .about-hero-title {
                font-size: 30px;
            }

            .about-hero-image {
                min-height: 240px;
            }

            .about-value-card,
            .about-info-card,
            .about-membership {
                padding: 22px 20px;
            }

            .about-contact-row {
                flex-direction: column;
                gap: 2px;
            }

            .about-contact-value {
                text-align: left;
            }

            .about-footer-inner {
                padding: 28px 18px 20px;
            }

            .about-footer-grid {
                grid-template-columns: 1fr;
                gap: 22px;
            }
        }
    </style>
</head>

<body>

    {{-- ========================================
         TOPBAR HEADER (KONSISTEN DENGAN DASHBOARD)
    ======================================== --}}
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

            <!-- Top Nav Links -->
            <nav class="topbar-nav">
                <a href="{{ route('home') }}" class="topbar-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('katalog.index') }}" class="topbar-nav-link {{ request()->routeIs('katalog.*') ? 'active' : '' }}">
                    Katalog
                </a>
                <a href="{{ route('dashboard') }}" class="topbar-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dasbor
                </a>
                <a href="{{ route('tentang') }}" class="topbar-nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}">
                    Tentang
                </a>
            </nav>

            <!-- Top Right -->
            <div class="topbar-right">
                <a href="{{ route('katalog.index') }}" class="topbar-icon-btn" title="Cari Katalog">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </a>

                @auth
                    <a href="{{ route('profile.edit') }}" class="topbar-user-badge" title="Profil {{ auth()->user()->name ?? 'Pengguna' }}">
                        {{ auth()->user()->inisial ?? strtoupper(substr(auth()->user()->name ?? 'US', 0, 2)) }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="topbar-user-badge" title="Masuk" style="color: #64748b; background: #f1f5f9;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </a>
                @endauth

                <button
                    type="button"
                    class="mobile-menu-button"
                    id="mobileMenuButton"
                    aria-label="Buka menu"
                    aria-expanded="false"
                >
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <nav class="mobile-nav" id="mobileNav">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                Beranda
            </a>
            <a href="{{ route('katalog.index') }}" class="{{ request()->routeIs('katalog.*') ? 'active' : '' }}">
                Katalog
            </a>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Dasbor
            </a>
            <a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'active' : '' }}">
                Tentang
            </a>
        </nav>
    </header>


    {{-- ========================================
         MAIN
    ======================================== --}}
    <main class="about-page">

        {{-- HERO --}}
        <section class="about-hero">

            <div class="about-hero-content">

                <span class="about-eyebrow">
                    Tentang BOOKNEST
                </span>

                <h1 class="about-hero-title">
                    Ruang untuk membaca, belajar, dan bertumbuh
                </h1>

                <p class="about-hero-description">
                    BOOKNEST adalah sistem perpustakaan umum yang menghubungkan
                    masyarakat dengan pengetahuan. Kami percaya setiap orang
                    berhak menemukan bacaan yang bermakna, tanpa batas usia
                    atau latar belakang.
                </p>

                <p class="about-hero-tagline">
                    Temukan. Baca. Berkembang.
                </p>

                <a
                    href="{{ route('register') }}"
                    class="about-primary-button"
                >
                    Jadi Anggota
                </a>

            </div>


            <div class="about-hero-image">
                <img
                    src="{{ asset('images/library-table.jpg') }}"
                    alt="Suasana perpustakaan BOOKNEST"
                >
            </div>

        </section>


        {{-- VALUE CARDS --}}
        <section class="about-values">

            <article class="about-value-card">

                <div class="about-value-icon">
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M16 21V19C16 16.7909 14.2091 15 12 15H7C4.79086 15 3 16.7909 3 19V21"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                        <circle
                            cx="9.5"
                            cy="7"
                            r="4"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />
                        <path
                            d="M19 8V14"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                        <path
                            d="M16 11H22"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <h2 class="about-value-title">
                    Akses untuk semua
                </h2>

                <p class="about-value-description">
                    Keanggotaan gratis dan koleksi lintas bidang untuk anak,
                    pelajar, keluarga, dan masyarakat umum.
                </p>

            </article>


            <article class="about-value-card">

                <div class="about-value-icon">
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M4 5.5C4 4.672 4.672 4 5.5 4H19C19.5523 4 20 4.44772 20 5V19C20 19.5523 19.5523 20 19 20H5.5C4.672 20 4 19.328 4 18.5V5.5Z"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />
                        <path
                            d="M8 4V20"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />
                        <path
                            d="M12 8H17"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                        <path
                            d="M12 12H17"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <h2 class="about-value-title">
                    Pengetahuan yang dekat
                </h2>

                <p class="about-value-description">
                    Pencarian koleksi dan layanan digital memudahkanmu belajar
                    dari mana saja.
                </p>

            </article>


            <article class="about-value-card">

                <div class="about-value-icon">
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M12 20V11"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                        <path
                            d="M12 14C8.5 14 6 11.5 6 8"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                        <path
                            d="M12 12C15.5 12 18 9.5 18 6"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                        <path
                            d="M12 20H7"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                        <path
                            d="M12 20H17"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <h2 class="about-value-title">
                    Komunitas yang tumbuh
                </h2>

                <p class="about-value-description">
                    Kegiatan literasi, diskusi buku, dan ruang baca mendukung
                    kebiasaan belajar sepanjang hayat.
                </p>

            </article>

        </section>


        {{-- LIBRARY INFORMATION --}}
        <section class="about-info-grid">

            <article class="about-info-card">

                <h2 class="about-section-title">
                    Layanan perpustakaan
                </h2>

                <ul class="about-service-list">
                    <li>Peminjaman hingga {{ \App\Models\Peminjaman::BATAS_MAKSIMAL_BUKU }} buku selama {{ \App\Models\Peminjaman::MASA_PINJAM_HARI }} hari</li>
                    <li>Ruang baca dan area belajar bersama</li>
                    <li>Koleksi anak dan bacaan keluarga</li>
                    <li>Bantuan pencarian referensi oleh petugas</li>
                    <li>Pembayaran denda melalui QRIS</li>
                    <li>Diskusi buku setiap Sabtu minggu pertama</li>
                </ul>

            </article>


            <article class="about-info-card highlight">

                <h2 class="about-section-title">
                    Datang dan temukan inspirasimu
                </h2>

                <div class="about-contact-list">

                    <div class="about-contact-row">
                        <span class="about-contact-label">
                            Alamat
                        </span>

                        <span class="about-contact-value">
                            Jl. Merdeka No. 12, Bandung
                        </span>
                    </div>

                    <div class="about-contact-row">
                        <span class="about-contact-label">
                            Jam layanan
                        </span>

                        <span class="about-contact-value">
                            Senin–Sabtu, 08.00–17.00 WIB
                        </span>
                    </div>

                    <div class="about-contact-row">
                        <span class="about-contact-label">
                            Telepon
                        </span>

                        <span class="about-contact-value">
                            (022) 420 1234
                        </span>
                    </div>

                    <div class="about-contact-row">
                        <span class="about-contact-label">
                            Email
                        </span>

                        <span class="about-contact-value">
                            halo@booknest.id
                        </span>
                    </div>

                </div>

                <p class="about-info-note">
                    Minggu dan hari libur nasional tutup. Petugas siap
                    membantu di meja informasi lantai 1.
                </p>

            </article>

        </section>


        {{-- HOW TO BECOME MEMBER --}}
        <section class="about-membership">

            <h2 class="about-membership-title">
                Bagaimana cara menjadi anggota?
            </h2>

            <p class="about-membership-text">
                Daftar dengan nama lengkap, NIK, dan email aktif. Setelah akun
                berhasil dibuat, kamu dapat menjelajahi katalog dan mengajukan
                peminjaman. Bawa kartu identitas saat kunjungan pertama.
            </p>

        </section>

    </main>


    {{-- ========================================
         FOOTER
    ======================================== --}}
    <footer class="about-footer">

        <div class="about-footer-inner">

            <div class="about-footer-grid">

                <div>
                    <div class="footer-brand-title">
                        BOOKNEST
                    </div>

                    <p class="footer-brand-text">
                        Temukan. Baca. Berkembang.<br>
                        Ruang pengetahuan untuk semua.
                    </p>
                </div>


                <div>

                    <div class="footer-column-title">
                        Jelajahi BOOKNEST
                    </div>

                    <div class="footer-links">
                        <a href="{{ route('katalog.index') }}">
                            Katalog buku
                        </a>

                        <a href="{{ route('register') }}">
                            Keanggotaan
                        </a>

                        <a href="{{ route('tentang') }}">
                            Tentang kami
                        </a>

                        <a href="#">
                            Panduan peminjaman
                        </a>

                        <a href="#">
                            Kebijakan privasi
                        </a>

                        <a href="#">
                            Syarat layanan
                        </a>
                    </div>

                </div>


                <div>

                    <div class="footer-column-title">
                        Kunjungi perpustakaan
                    </div>

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

        </div>

    </footer>


    <script>
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileNav = document.getElementById('mobileNav');

        if (mobileMenuButton && mobileNav) {
            mobileMenuButton.addEventListener('click', () => {
                const isOpen = mobileNav.classList.toggle('open');

                mobileMenuButton.setAttribute(
                    'aria-expanded',
                    isOpen ? 'true' : 'false'
                );
            });
        }
    </script>

</body>

</html>