<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'BookNest')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
</head>

<body>

    {{-- =========================
         HEADER
    ========================== --}}
    <header class="member-header">
        <div class="member-header-inner">

            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="member-brand">
                <div class="member-brand-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 5.5C4 4.672 4.672 4 5.5 4H19C19.5523 4 20 4.44772 20 5V19C20 19.5523 19.5523 20 19 20H5.5C4.672 20 4 19.328 4 18.5V5.5Z"
                            stroke="currentColor"
                            stroke-width="1.8" />
                        <path d="M8 4V20"
                            stroke="currentColor"
                            stroke-width="1.8" />
                        <path d="M12 8H17"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round" />
                        <path d="M12 12H17"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round" />
                    </svg>
                </div>

                <span>BOOKNEST</span>
            </a>


            {{-- Desktop Navigation --}}
            <nav class="member-nav member-nav-desktop">

                <a
                    href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                >
                    Beranda
                </a>

                <a
                    href="{{ route('katalog.index') }}"
                    class="{{ request()->routeIs('katalog.*') ? 'active' : '' }}"
                >
                    Katalog Buku
                </a>

                <a
                    href="{{ route('riwayat.index') }}"
                    class="{{ request()->routeIs('riwayat.*') ? 'active' : '' }}"
                >
                    Riwayat Peminjaman
                </a>

            </nav>


            {{-- Account --}}
            <div class="member-account-wrapper">

                <button
                    type="button"
                    class="member-account"
                    id="memberAccountButton"
                    aria-expanded="false"
                    aria-controls="memberAccountDropdown"
                >

                    <div class="member-avatar">
                        @if(auth()->user()->foto ?? false)
                            <img
                                src="{{ asset('storage/' . auth()->user()->foto) }}"
                                alt="Foto Profil"
                            >
                        @else
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>

                    <div class="member-account-info">
                        <span class="member-account-name">
                            {{ auth()->user()->name ?? 'Member' }}
                        </span>

                        <span class="member-account-status">
                            Anggota
                        </span>
                    </div>

                    <svg
                        class="member-account-chevron"
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M6 9L12 15L18 9"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                </button>


                {{-- Profile Dropdown --}}
                <div
                    class="member-account-dropdown"
                    id="memberAccountDropdown"
                >

                    <div class="dropdown-user-info">

                        <div class="dropdown-avatar">
                            @if(auth()->user()->foto ?? false)
                                <img
                                    src="{{ asset('storage/' . auth()->user()->foto) }}"
                                    alt="Foto Profil"
                                >
                            @else
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            @endif
                        </div>

                        <div>
                            <strong>
                                {{ auth()->user()->name ?? 'Member' }}
                            </strong>

                            <span>
                                {{ auth()->user()->email ?? '' }}
                            </span>
                        </div>

                    </div>


                    <div class="dropdown-divider"></div>


                    {{-- Profile --}}
                    <a
                        href="{{ route('profile.edit') }}"
                        class="dropdown-item"
                    >
                        <span class="dropdown-item-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                                <circle
                                    cx="12"
                                    cy="7"
                                    r="4"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />
                            </svg>
                        </span>

                        <span>Profile</span>
                    </a>


                    {{-- QR Code --}}
                    <a
                        href="{{ route('member.kartu-saya') }}"
                        class="dropdown-item"
                    >
                        <span class="dropdown-item-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect
                                    x="4"
                                    y="4"
                                    width="6"
                                    height="6"
                                    rx="1"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />
                                <rect
                                    x="14"
                                    y="4"
                                    width="6"
                                    height="6"
                                    rx="1"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />
                                <rect
                                    x="4"
                                    y="14"
                                    width="6"
                                    height="6"
                                    rx="1"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />
                                <path
                                    d="M14 14H16V16H14V14ZM18 14H20V16H18V14ZM14 18H16V20H14V18ZM18 18H20V20H18V18Z"
                                    fill="currentColor"
                                />
                            </svg>
                        </span>

                        <span>QR Code Saya</span>
                    </a>


                    {{-- Edit Profile --}}
                    <a
                        href="{{ route('profile.edit') }}"
                        class="dropdown-item"
                    >
                        <span class="dropdown-item-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 20H21"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M16.5 3.5C16.8978 3.10218 17.4374 2.87868 18 2.87868C18.5626 2.87868 19.1022 3.10218 19.5 3.5C19.8978 3.89782 20.1213 4.43739 20.1213 5C20.1213 5.56261 19.8978 6.10218 19.5 6.5L8 18L3 19L4 14L16.5 3.5Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>

                        <span>Edit Profil</span>
                    </a>


                    {{-- Genre Favorit --}}
                    <a
                        href="{{ route('profile.edit') }}#genre-favorit"
                        class="dropdown-item"
                    >
                        <span class="dropdown-item-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 21C12 21 4 16.5 4 10.5C4 7.46243 6.23858 5 9 5C10.6569 5 12.1051 5.89543 13 7.24167C13.8949 5.89543 15.3431 5 17 5C19.7614 5 22 7.46243 22 10.5C22 16.5 14 21 14 21H12Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>

                        <span>Genre Favorit</span>
                    </a>


                    {{-- Riwayat --}}
                    <a
                        href="{{ route('riwayat.index') }}"
                        class="dropdown-item"
                    >
                        <span class="dropdown-item-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3 12A9 9 0 1 0 6 5.3"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M3 4V10H9"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M12 7V12L15 14"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>

                        <span>Riwayat Peminjaman</span>
                    </a>


                    <div class="dropdown-divider"></div>


                    {{-- Logout --}}
                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="dropdown-item dropdown-item-logout"
                        >
                            <span class="dropdown-item-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10 17L15 12L10 7"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                    <path
                                        d="M15 12H3"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />
                                    <path
                                        d="M20 4V20"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />
                                </svg>
                            </span>

                            <span>Logout</span>
                        </button>
                    </form>

                </div>

            </div>


            {{-- Mobile Menu Button --}}
            <button
                type="button"
                class="member-mobile-menu-button"
                id="memberMobileMenuButton"
                aria-expanded="false"
                aria-controls="memberMobileNav"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>


        {{-- Mobile Navigation --}}
        <nav
            class="member-mobile-nav"
            id="memberMobileNav"
        >

            <a
                href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >
                Beranda
            </a>

            <a
                href="{{ route('katalog.index') }}"
                class="{{ request()->routeIs('katalog.*') ? 'active' : '' }}"
            >
                Katalog Buku
            </a>

            <a
                href="{{ route('riwayat.index') }}"
                class="{{ request()->routeIs('riwayat.*') ? 'active' : '' }}"
            >
                Riwayat Peminjaman
            </a>

        </nav>

    </header>


    {{-- =========================
         MAIN CONTENT
    ========================== --}}
    <main class="member-main">
        @yield('content')
    </main>


    {{-- =========================
         FOOTER
    ========================== --}}
    <footer class="member-footer">

        <div class="member-footer-inner">

            <div class="member-footer-brand">

                <a
                    href="{{ route('dashboard') }}"
                    class="member-brand"
                >
                    <div class="member-brand-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4 5.5C4 4.672 4.672 4 5.5 4H19C19.5523 4 20 4.44772 20 5V19C20 19.5523 19.5523 20 19 20H5.5C4.672 20 4 19.328 4 18.5V5.5Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />
                            <path
                                d="M8 4V20"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />
                            <path
                                d="M12 8H17"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                            <path
                                d="M12 12H17"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>

                    <span>BOOKNEST</span>
                </a>

                <p>
                    Sistem perpustakaan digital untuk memudahkan
                    anggota dalam mencari dan meminjam buku.
                </p>

            </div>


            <div class="member-footer-column">

                <h4>Navigasi</h4>

                <a href="{{ route('dashboard') }}">
                    Beranda
                </a>

                <a href="{{ route('katalog.index') }}">
                    Katalog Buku
                </a>

                <a href="{{ route('riwayat.index') }}">
                    Riwayat Peminjaman
                </a>

            </div>


            <div class="member-footer-column">

                <h4>Akun</h4>

                <a href="{{ route('profile.edit') }}">
                    Profile
                </a>

                <a href="{{ route('member.kartu-saya') }}">
                    QR Code Saya
                </a>

                <a href="{{ route('profile.edit') }}">
                    Edit Profil
                </a>

            </div>

        </div>


        <div class="member-footer-bottom">

            <span>
                © {{ date('Y') }} BOOKNEST. All rights reserved.
            </span>

            <span>
                Digital Library System
            </span>

        </div>

    </footer>


    {{-- =========================
         JAVASCRIPT
    ========================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | Profile Dropdown
            |--------------------------------------------------------------------------
            */

            const accountButton =
                document.getElementById('memberAccountButton');

            const accountDropdown =
                document.getElementById('memberAccountDropdown');

            if (accountButton && accountDropdown) {

                accountButton.addEventListener('click', function (event) {

                    event.stopPropagation();

                    const isOpen =
                        accountButton.getAttribute('aria-expanded') === 'true';

                    accountButton.setAttribute(
                        'aria-expanded',
                        String(!isOpen)
                    );

                    accountDropdown.classList.toggle(
                        'show',
                        !isOpen
                    );

                });


                document.addEventListener('click', function (event) {

                    if (
                        !accountDropdown.contains(event.target) &&
                        !accountButton.contains(event.target)
                    ) {

                        accountButton.setAttribute(
                            'aria-expanded',
                            'false'
                        );

                        accountDropdown.classList.remove('show');

                    }

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Mobile Navigation
            |--------------------------------------------------------------------------
            */

            const mobileMenuButton =
                document.getElementById('memberMobileMenuButton');

            const mobileNav =
                document.getElementById('memberMobileNav');

            if (mobileMenuButton && mobileNav) {

                mobileMenuButton.addEventListener('click', function () {

                    const isOpen =
                        mobileMenuButton.getAttribute('aria-expanded') === 'true';

                    mobileMenuButton.setAttribute(
                        'aria-expanded',
                        String(!isOpen)
                    );

                    mobileNav.classList.toggle(
                        'show',
                        !isOpen
                    );

                });


                mobileNav
                    .querySelectorAll('a')
                    .forEach(function (link) {

                        link.addEventListener('click', function () {

                            mobileMenuButton.setAttribute(
                                'aria-expanded',
                                'false'
                            );

                            mobileNav.classList.remove('show');

                        });

                    });

            }

        });
    </script>


    {{-- =========================
         MEMBER LAYOUT STYLES
    ========================== --}}
    <style>

        :root {
            --member-primary: #2563eb;
            --member-primary-dark: #1d4ed8;
            --member-text: #111827;
            --member-muted: #6b7280;
            --member-border: #e5e7eb;
            --member-bg: #f8fafc;
            --member-white: #ffffff;
        }


        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--member-text);
            background: var(--member-bg);
        }


        a {
            text-decoration: none;
            color: inherit;
        }


        button,
        input,
        select,
        textarea {
            font: inherit;
        }


        /* =========================================================
           HEADER
        ========================================================== */

        .member-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid var(--member-border);
            backdrop-filter: blur(12px);
        }


        .member-header-inner {
            max-width: 1280px;
            min-height: 76px;
            margin: 0 auto;
            padding: 0 32px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
        }


        /* =========================================================
           BRAND
        ========================================================== */

        .member-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;

            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.4px;
        }


        .member-brand-icon {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 11px;

            color: white;
            background: var(--member-primary);
        }


        /* =========================================================
           DESKTOP NAV
        ========================================================== */

        .member-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }


        .member-nav a {
            position: relative;

            padding: 10px 14px;

            color: #64748b;
            font-size: 14px;
            font-weight: 600;

            border-radius: 9px;

            transition:
                color 0.2s ease,
                background 0.2s ease;
        }


        .member-nav a:hover {
            color: var(--member-text);
            background: #f1f5f9;
        }


        .member-nav a.active {
            color: var(--member-primary);
            background: #eff6ff;
        }


        /* =========================================================
           ACCOUNT
        ========================================================== */

        .member-account-wrapper {
            position: relative;
        }


        .member-account {
            display: flex;
            align-items: center;
            gap: 10px;

            padding: 6px 8px;

            border: 0;
            border-radius: 12px;

            background: transparent;

            cursor: pointer;

            transition: background 0.2s ease;
        }


        .member-account:hover {
            background: #f8fafc;
        }


        .member-avatar,
        .dropdown-avatar {
            overflow: hidden;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            color: white;
            background: var(--member-primary);

            font-weight: 700;
        }


        .member-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 14px;
        }


        .dropdown-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            font-size: 15px;
        }


        .member-avatar img,
        .dropdown-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


        .member-account-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            min-width: 0;
        }


        .member-account-name {
            max-width: 150px;

            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;

            font-size: 13px;
            font-weight: 700;
        }


        .member-account-status {
            color: var(--member-muted);
            font-size: 11px;
            font-weight: 500;
        }


        .member-account-chevron {
            color: #94a3b8;

            transition: transform 0.2s ease;
        }


        .member-account[aria-expanded="true"]
        .member-account-chevron {
            transform: rotate(180deg);
        }


        /* =========================================================
           DROPDOWN
        ========================================================== */

        .member-account-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;

            width: 270px;

            padding: 10px;

            background: white;
            border: 1px solid var(--member-border);
            border-radius: 16px;

            box-shadow:
                0 20px 40px rgba(15, 23, 42, 0.12);

            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);

            transition:
                opacity 0.2s ease,
                transform 0.2s ease,
                visibility 0.2s ease;
        }


        .member-account-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }


        .dropdown-user-info {
            display: flex;
            align-items: center;
            gap: 11px;

            padding: 8px 8px 12px;
        }


        .dropdown-user-info > div:last-child {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }


        .dropdown-user-info strong {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;

            font-size: 13px;
            font-weight: 700;
        }


        .dropdown-user-info span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;

            color: var(--member-muted);
            font-size: 11px;
        }


        .dropdown-divider {
            height: 1px;
            margin: 6px 0;
            background: #eef2f7;
        }


        .dropdown-item {
            width: 100%;

            display: flex;
            align-items: center;
            gap: 11px;

            padding: 10px;

            border: 0;
            border-radius: 9px;

            background: transparent;

            color: #475569;

            font-size: 13px;
            font-weight: 600;

            cursor: pointer;

            transition:
                color 0.2s ease,
                background 0.2s ease;
        }


        .dropdown-item:hover {
            color: var(--member-text);
            background: #f8fafc;
        }


        .dropdown-item-icon {
            width: 20px;
            height: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #64748b;
        }


        .dropdown-item-logout {
            color: #dc2626;
        }


        .dropdown-item-logout:hover {
            color: #b91c1c;
            background: #fef2f2;
        }


        /* =========================================================
           MOBILE BUTTON
        ========================================================== */

        .member-mobile-menu-button {
            display: none;

            width: 42px;
            height: 42px;

            padding: 9px;

            border: 1px solid var(--member-border);
            border-radius: 10px;

            background: white;

            cursor: pointer;
        }


        .member-mobile-menu-button span {
            display: block;

            width: 100%;
            height: 2px;

            margin: 4px 0;

            border-radius: 2px;

            background: #334155;

            transition: 0.2s ease;
        }


        /* =========================================================
           MOBILE NAV
        ========================================================== */

        .member-mobile-nav {
            display: none;

            padding: 12px 20px 18px;

            border-top: 1px solid #f1f5f9;
            background: white;
        }


        .member-mobile-nav.show {
            display: block;
        }


        .member-mobile-nav a {
            display: block;

            padding: 12px 14px;

            border-radius: 9px;

            color: #64748b;

            font-size: 14px;
            font-weight: 600;
        }


        .member-mobile-nav a:hover {
            color: var(--member-text);
            background: #f8fafc;
        }


        .member-mobile-nav a.active {
            color: var(--member-primary);
            background: #eff6ff;
        }


        /* =========================================================
           MAIN
        ========================================================== */

        .member-main {
            min-height: calc(100vh - 76px);
        }


        /* =========================================================
           FOOTER
        ========================================================== */

        .member-footer {
            margin-top: 60px;

            color: #cbd5e1;
            background: #0f172a;
        }


        .member-footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 48px 32px;

            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 60px;
        }


        .member-footer-brand .member-brand {
            color: white;
        }


        .member-footer-brand p {
            max-width: 420px;

            margin: 16px 0 0;

            color: #94a3b8;

            font-size: 13px;
            line-height: 1.8;
        }


        .member-footer-column {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }


        .member-footer-column h4 {
            margin: 0 0 8px;

            color: white;

            font-size: 13px;
            font-weight: 700;
        }


        .member-footer-column a {
            color: #94a3b8;

            font-size: 13px;

            transition: color 0.2s ease;
        }


        .member-footer-column a:hover {
            color: white;
        }


        .member-footer-bottom {
            max-width: 1280px;
            margin: 0 auto;

            padding: 18px 32px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            border-top: 1px solid rgba(148, 163, 184, 0.15);

            color: #64748b;

            font-size: 11px;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 992px) {

            .member-header-inner {
                padding: 0 20px;
            }


            .member-nav-desktop {
                display: none;
            }


            .member-mobile-menu-button {
                display: block;
            }


            .member-account {
                margin-left: auto;
            }


            .member-footer-inner {
                grid-template-columns: 1.5fr 1fr 1fr;
                gap: 30px;
                padding: 40px 20px;
            }


            .member-footer-bottom {
                padding: 18px 20px;
            }

        }


        @media (max-width: 640px) {

            .member-header-inner {
                min-height: 68px;
                padding: 0 16px;
                gap: 10px;
            }


            .member-brand span {
                font-size: 16px;
            }


            .member-brand-icon {
                width: 34px;
                height: 34px;
                border-radius: 9px;
            }


            .member-account-info,
            .member-account-chevron {
                display: none;
            }


            .member-account {
                padding: 4px;
            }


            .member-avatar {
                width: 38px;
                height: 38px;
            }


            .member-account-dropdown {
                position: fixed;

                top: 72px;
                left: 16px;
                right: 16px;

                width: auto;
            }


            .member-mobile-nav {
                padding-left: 16px;
                padding-right: 16px;
            }


            .member-main {
                min-height: calc(100vh - 68px);
            }


            .member-footer {
                margin-top: 40px;
            }


            .member-footer-inner {
                grid-template-columns: 1fr;

                gap: 30px;

                padding: 36px 20px;
            }


            .member-footer-bottom {
                flex-direction: column;
                align-items: flex-start;

                padding: 16px 20px;
            }

        }

    </style>

</body>
</html>