<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dasbor Anggota - BOOKNEST')</title>

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
            --sidebar-width: 256px;
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

        /* --- TOPBAR NAVBAR --- */
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
        }

        .mobile-toggle-btn {
            display: none;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            color: #475569;
            border: 1px solid var(--border-color);
        }

        /* --- MAIN LAYOUT WRAPPER --- */
        .page-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px 28px 48px;
            display: flex;
            gap: 28px;
            align-items: flex-start;
        }

        /* --- SIDEBAR PANEL ANGGOTA --- */
        .sidebar {
            width: var(--sidebar-width);
            flex-shrink: 0;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: sticky;
            top: 86px;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .sidebar-header-icon {
            width: 36px;
            height: 36px;
            background: #0f766e;
            color: #ffffff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-header-text h3 {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--text-heading);
            line-height: 1.2;
            letter-spacing: -0.2px;
        }

        .sidebar-header-text span {
            font-size: 11.5px;
            color: var(--text-muted);
        }

        .sidebar-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            padding: 0 10px;
            margin-bottom: 6px;
        }

        .sidebar-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            color: #475569;
            transition: all 0.15s ease;
            position: relative;
        }

        .sidebar-link-content {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .sidebar-link-icon {
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
        }

        .sidebar-link:hover {
            background: #f8fafc;
            color: var(--text-heading);
        }

        .sidebar-link:hover .sidebar-link-icon {
            color: var(--brand-primary);
        }

        .sidebar-link.active {
            background: #ccfbf1;
            color: #0f766e;
            font-weight: 700;
        }

        .sidebar-link.active .sidebar-link-icon {
            color: #0f766e;
        }

        .sidebar-link.active::after {
            content: '';
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 18px;
            background: #0f766e;
            border-radius: 2px;
        }

        /* --- USER CARD AT SIDEBAR BOTTOM --- */
        .sidebar-user {
            margin-top: 10px;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .sidebar-user-left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .sidebar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ccfbf1;
            color: #0f766e;
            font-size: 12.5px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .sidebar-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-user-details {
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-heading);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        .sidebar-user-role {
            font-size: 11px;
            color: var(--text-muted);
            display: block;
        }

        .sidebar-user-menu-btn {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }

        .sidebar-user-menu-btn:hover {
            color: var(--text-heading);
            background: #f1f5f9;
        }

        .user-dropdown {
            position: absolute;
            bottom: calc(100% + 8px);
            right: 0;
            width: 190px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 6px;
            display: none;
            z-index: 50;
        }

        .user-dropdown.show {
            display: block;
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 12px;
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            border-radius: 8px;
            transition: all 0.15s;
            width: 100%;
            text-align: left;
        }

        .user-dropdown-item:hover {
            background: #f8fafc;
            color: var(--text-heading);
        }

        .user-dropdown-item.logout {
            color: #dc2626;
        }

        .user-dropdown-item.logout:hover {
            background: #fee2e2;
            color: #b91c1c;
        }

        .user-dropdown-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 4px 0;
        }

        /* --- CONTENT WRAPPER --- */
        .content-area {
            flex: 1;
            min-width: 0;
        }

        /* --- MOBILE OVERLAY --- */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            z-index: 45;
            backdrop-filter: blur(2px);
        }

        /* --- RESPONSIVE BREAKPOINTS --- */
        @media (max-width: 992px) {
            .mobile-toggle-btn {
                display: flex;
            }

            .topbar-nav {
                display: none;
            }

            .page-wrapper {
                padding: 16px;
                gap: 0;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                height: 100vh;
                z-index: 50;
                border-radius: 0;
                transform: translateX(-100%);
                transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
                overflow-y: auto;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>

    @yield('styles')
</head>
<body>

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-container">
            <div style="display: flex; align-items: center; gap: 14px;">
                <button type="button" class="mobile-toggle-btn" id="sidebarToggle" aria-label="Buka menu navigasi">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>

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

            <!-- Top Nav Links -->
            <nav class="topbar-nav">
                <a href="{{ route('home') }}" class="topbar-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('katalog.index') }}" class="topbar-nav-link {{ request()->routeIs('katalog.*') ? 'active' : '' }}">
                    Katalog
                </a>
                <a href="{{ route('dashboard') }}" class="topbar-nav-link {{ request()->routeIs('dashboard', 'profile.*') ? 'active' : '' }}">
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

                <a href="{{ route('profile.edit') }}" class="topbar-user-badge" title="Profil Anggota">
                    @if(auth()->user()->foto ?? false)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                    @else
                        {{ auth()->user()->inisial ?? 'RP' }}
                    @endif
                </a>
            </div>
        </div>
    </header>

    <!-- MOBILE BACKDROP -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- PAGE WRAPPER -->
    <div class="page-wrapper">

        <!-- SIDEBAR PANEL ANGGOTA -->
        <aside class="sidebar" id="sidebarPanel">
            <div class="sidebar-header">
                <div class="sidebar-header-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <div class="sidebar-header-text">
                    <h3>BOOKNEST</h3>
                    <span>Panel Anggota</span>
                </div>
            </div>

            <!-- MENU UTAMA -->
            <div>
                <div class="sidebar-section-title">Utama</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <div class="sidebar-link-content">
                                <span class="sidebar-link-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="14" width="7" height="7"></rect>
                                        <rect x="3" y="14" width="7" height="7"></rect>
                                    </svg>
                                </span>
                                <span>Dasbor</span>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('katalog.index') }}" class="sidebar-link {{ request()->routeIs('katalog.*') ? 'active' : '' }}">
                            <div class="sidebar-link-content">
                                <span class="sidebar-link-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        <line x1="9" y1="6" x2="15" y2="6"></line>
                                        <line x1="9" y1="10" x2="15" y2="10"></line>
                                    </svg>
                                </span>
                                <span>Katalog Buku</span>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('riwayat.index', ['status' => 'Dipinjam']) }}" class="sidebar-link {{ request()->routeIs('riwayat.*') && request('status') === 'Dipinjam' ? 'active' : '' }}">
                            <div class="sidebar-link-content">
                                <span class="sidebar-link-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="16 3 21 3 21 8"></polyline>
                                        <line x1="4" y1="20" x2="21" y2="3"></line>
                                        <polyline points="21 16 21 21 16 21"></polyline>
                                        <line x1="15" y1="15" x2="21" y2="21"></line>
                                        <line x1="4" y1="4" x2="9" y2="9"></line>
                                    </svg>
                                </span>
                                <span>Peminjaman</span>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('riwayat.index') }}" class="sidebar-link {{ request()->routeIs('riwayat.*') && request('status') !== 'Dipinjam' ? 'active' : '' }}">
                            <div class="sidebar-link-content">
                                <span class="sidebar-link-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </span>
                                <span>Riwayat</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- MENU LAINNYA -->
            <div>
                <div class="sidebar-section-title">Lainnya</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('denda.saya') }}" class="sidebar-link {{ request()->routeIs('denda.saya') ? 'active' : '' }}">
                            <div class="sidebar-link-content">
                                <span class="sidebar-link-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="20" x2="18" y2="10"></line>
                                        <line x1="12" y1="20" x2="12" y2="4"></line>
                                        <line x1="6" y1="20" x2="6" y2="14"></line>
                                    </svg>
                                </span>
                                <span>Denda</span>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <div class="sidebar-link-content">
                                <span class="sidebar-link-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <span>Profil</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- USER CARD BOTTOM -->
            <div class="sidebar-user">
                <div class="sidebar-user-left">
                    <div class="sidebar-avatar">
                        @if(auth()->user()->foto ?? false)
                            <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Avatar">
                        @else
                            {{ auth()->user()->inisial ?? 'RP' }}
                        @endif
                    </div>
                    <div class="sidebar-user-details">
                        <span class="sidebar-user-name" title="{{ auth()->user()->name }}">
                            {{ auth()->user()->name }}
                        </span>
                        <span class="sidebar-user-role">Anggota</span>
                    </div>
                </div>

                <button type="button" class="sidebar-user-menu-btn" id="userMenuBtn" aria-label="Menu akun anggota" aria-expanded="false">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="5" cy="12" r="2"></circle>
                        <circle cx="12" cy="12" r="2"></circle>
                        <circle cx="19" cy="12" r="2"></circle>
                    </svg>
                </button>

                <!-- USER POPUP DROPDOWN -->
                <div class="user-dropdown" id="userDropdown">
                    <a href="{{ route('profile.edit') }}" class="user-dropdown-item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Edit Profil</span>
                    </a>

                    <a href="{{ route('member.kartu-saya') }}" class="user-dropdown-item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span>QR Code Saya</span>
                    </a>

                    <div class="user-dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="user-dropdown-item logout">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <!-- SCRIPTS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar Mobile Toggle
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebarPanel');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (toggleBtn && sidebar && backdrop) {
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('mobile-open');
                    backdrop.classList.toggle('show');
                });

                backdrop.addEventListener('click', function () {
                    sidebar.classList.remove('mobile-open');
                    backdrop.classList.remove('show');
                });
            }

            // User dropdown in sidebar
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userDropdown = document.getElementById('userDropdown');

            if (userMenuBtn && userDropdown) {
                userMenuBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isOpen = userDropdown.classList.contains('show');
                    userDropdown.classList.toggle('show');
                    userMenuBtn.setAttribute('aria-expanded', !isOpen);
                });

                document.addEventListener('click', function (e) {
                    if (!userDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                        userDropdown.classList.remove('show');
                        userMenuBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
