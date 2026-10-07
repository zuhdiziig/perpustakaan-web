<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKNEST - Temukan Buku Favoritmu</title>
    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #ffffff;
            color: #0f172a;
            line-height: 1.6;
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* --- TOP NAVBAR --- */
        .navbar {
            padding: 24px 0 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: #2e625a;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .logo-text {
            font-size: 19px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.2px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #475569;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 9px;
            transition: all 0.2s ease;
        }

        .nav-link-item:hover {
            color: #2e625a;
            background: #f1f5f9;
        }

        .btn-nav-login {
            color: #2e625a;
            border: 1px solid #cbd5e1;
            padding: 8px 18px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-nav-login:hover {
            border-color: #2e625a;
            background: #f0fdf4;
        }

        .btn-nav-register {
            background-color: #4361ee;
            color: white;
            padding: 9px 20px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-nav-register:hover {
            background-color: #3651d4;
        }

        /* --- AUTH NAVIGATION & USER DROPDOWN --- */
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            padding: 5px 11px;
            border-radius: 9999px;
            letter-spacing: 0.2px;
        }

        .role-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .role-badge--admin {
            background: #f5f3ff;
            color: #6366f1;
            border: 1px solid #e0e7ff;
        }

        .role-badge--admin .role-badge-dot {
            background: #6366f1;
            box-shadow: 0 0 6px rgba(99, 102, 241, 0.6);
        }

        .role-badge--petugas {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .role-badge--petugas .role-badge-dot {
            background: #10b981;
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.6);
        }

        .role-badge--member {
            background: #f0fdf4;
            color: #2e625a;
            border: 1px solid #cce3de;
        }

        .role-badge--member .role-badge-dot {
            background: #2e625a;
            box-shadow: 0 0 6px rgba(46, 98, 90, 0.6);
        }

        .btn-nav-dashboard {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #2e625a;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(46, 98, 90, 0.2);
        }

        .btn-nav-dashboard:hover {
            background: #234c46;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(46, 98, 90, 0.3);
        }

        .btn-nav-dashboard svg {
            transition: transform 0.2s ease;
        }

        .btn-nav-dashboard:hover svg {
            transform: translateX(3px);
        }

        .user-menu-wrap {
            position: relative;
        }

        .user-menu-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 4px 12px 4px 5px;
            border-radius: 9999px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .user-menu-btn:hover,
        .user-menu-btn.active {
            border-color: #cbd5e1;
            background: #f8fafc;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .user-avatar-mini {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #2e625a;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .user-avatar-mini img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .status-indicator {
            position: absolute;
            bottom: -1px;
            right: -1px;
            width: 9px;
            height: 9px;
            background: #22c55e;
            border: 2px solid #ffffff;
            border-radius: 50%;
        }

        .user-menu-name {
            font-size: 13.5px;
            font-weight: 700;
            color: #1e293b;
            white-space: nowrap;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-chevron {
            color: #64748b;
            transition: transform 0.2s ease;
        }

        .user-menu-btn.active .user-chevron {
            transform: rotate(180deg);
        }

        .user-dropdown-card {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 275px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.14), 0 6px 14px -2px rgba(15, 23, 42, 0.05);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px) scale(0.97);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .user-dropdown-card.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .user-dropdown-header {
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
        }

        .dropdown-avatar-lg {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #2e625a;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dropdown-avatar-lg img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .dropdown-user-info {
            overflow: hidden;
        }

        .dropdown-name {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-email {
            display: block;
            font-size: 12px;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-dropdown-role-bar {
            padding: 6px 16px;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.3px;
            border-bottom: 1px solid #f1f5f9;
        }

        .role-bar--admin {
            background: #f5f3ff;
            color: #6366f1;
        }

        .role-bar--petugas {
            background: #ecfdf5;
            color: #059669;
        }

        .role-bar--member {
            background: #f0fdf4;
            color: #2e625a;
        }

        .user-dropdown-body {
            padding: 8px;
        }

        .dropdown-section-title {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 6px 10px 4px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.15s ease;
            width: 100%;
            border: none;
            background: transparent;
            cursor: pointer;
            text-align: left;
            box-sizing: border-box;
        }

        .dropdown-item svg {
            color: #64748b;
            transition: color 0.15s ease;
            flex-shrink: 0;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .dropdown-item:hover svg {
            color: #2e625a;
        }

        .dropdown-item--danger {
            color: #dc2626;
        }

        .dropdown-item--danger svg {
            color: #dc2626;
        }

        .dropdown-item--danger:hover {
            background: #fef2f2;
            color: #b91c1c;
        }

        .dropdown-item--danger:hover svg {
            color: #b91c1c;
        }

        .dropdown-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 6px 0;
        }

        .welcome-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 14px;
        }

        .welcome-chip-pulse {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: chipPulse 2s infinite;
        }

        @keyframes chipPulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        /* --- HERO SECTION --- */
        .hero {
            padding: 40px 0 50px;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            gap: 48px;
            align-items: center;
        }

        .hero-tag {
            color: #2e625a;
            font-size: 13.5px;
            font-weight: 700;
            margin-bottom: 12px;
            display: block;
        }

        .hero-title {
            font-size: 40px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.18;
            letter-spacing: -0.8px;
            margin-bottom: 16px;
        }

        .hero-desc {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 24px;
            max-width: 480px;
        }

        .search-form-wrap {
            margin-bottom: 22px;
        }

        .search-label {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
            display: block;
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-input-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            padding: 9px 12px;
        }

        .search-input-wrap svg {
            color: #94a3b8;
            flex-shrink: 0;
        }

        .search-input-wrap input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 13px;
            color: #0f172a;
        }

        .btn-search {
            background: #2e625a;
            color: white;
            border: none;
            padding: 10px 22px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-search:hover {
            background: #234c46;
        }

        .hero-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .btn-green {
            background: #2e625a;
            color: white;
            padding: 11px 22px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-green:hover {
            background: #234c46;
        }

        .btn-blue {
            background: #4361ee;
            color: white;
            padding: 11px 22px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-blue:hover {
            background: #3651d4;
        }

        .hero-note {
            font-size: 12.5px;
            color: #64748b;
        }

        .hero-img-box {
            position: relative;
        }

        .hero-img-container {
            width: 100%;
            height: 340px;
            border-radius: 18px;
            overflow: hidden;
            background: #f1f5f9;
        }

        .hero-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .hero-img-caption {
            margin-top: 12px;
            font-size: 13px;
            color: #64748b;
        }

        .hero-img-caption strong {
            color: #2e625a;
            font-weight: 800;
        }

        /* --- STATS SECTION --- */
        .stats-grid {
            padding: 30px 0 50px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .stat-item h3 {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .stat-item p {
            font-size: 13px;
            color: #64748b;
            margin-top: 2px;
        }

        /* --- SECTION HEADING --- */
        .section-title {
            font-size: 21px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 20px;
        }

        /* --- CATEGORIES GRID --- */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 12px;
            margin-bottom: 70px;
        }

        .cat-card {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 22px 10px;
            text-align: center;
            text-decoration: none;
            color: #334155;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .cat-card:hover {
            border-color: #2e625a;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
            transform: translateY(-2px);
        }

        .cat-icon {
            color: #2e625a;
        }

        .cat-name {
            font-size: 12.5px;
            font-weight: 600;
        }

        /* --- FEATURES SECTION --- */
        .features-section {
            padding: 50px 0 60px;
            border-top: 1px solid #f1f5f9;
        }

        .features-header {
            margin-bottom: 28px;
        }

        .features-header h2 {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .features-header p {
            font-size: 13.5px;
            color: #64748b;
            margin-top: 4px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .feature-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 28px 24px;
        }

        .feature-icon-box {
            width: 40px;
            height: 40px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2e625a;
            margin-bottom: 18px;
        }

        .feature-title {
            font-size: 15.5px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .feature-desc {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
        }

        /* --- CTA BANNER --- */
        .cta-banner {
            background: #e6f7f4;
            border-radius: 16px;
            padding: 32px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 70px;
        }

        .cta-text h3 {
            font-size: 19px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .cta-text p {
            font-size: 13px;
            color: #475569;
        }

        .btn-cta {
            background: #2e625a;
            color: white;
            padding: 11px 24px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .btn-cta:hover {
            background: #234c46;
        }

        /* --- FOOTER --- */
        .footer {
            border-top: 1px solid #f1f5f9;
            padding: 50px 0 30px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr;
            gap: 36px;
            margin-bottom: 40px;
        }

        .footer-brand h4 {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .footer-brand p {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
        }

        .footer-col h5 {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .footer-col a {
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }

        .footer-col a:hover {
            color: #2e625a;
        }

        .footer-bottom {
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #f8fafc;
            padding-top: 20px;
        }

        @media (max-width: 992px) {
            .hero {
                grid-template-columns: 1fr;
                gap: 36px;
            }
            .categories-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            .features-grid, .footer-grid {
                grid-template-columns: 1fr;
            }
            .cta-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 18px;
            }
        }

        @media (max-width: 600px) {
            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .role-badge {
                display: none;
            }
            .user-menu-name {
                display: none;
            }
            .btn-nav-dashboard span {
                display: none;
            }
            .btn-nav-dashboard {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR ATAS -->
    <header class="container">
        <nav class="navbar">
            <a href="{{ route('home') }}" class="logo-area">
                <div class="logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <span class="logo-text">BOOKNEST</span>
            </a>

            <div class="nav-links">
                <a href="{{ route('katalog.index') }}" class="nav-link-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    <span>Katalog</span>
                </a>

                @guest
                    <a href="{{ route('login') }}" class="btn-nav-login">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-nav-register">Daftar Anggota</a>
                @else
                    {{-- Role Pill Badge --}}
                    @if(auth()->user()->role === 'admin')
                        <span class="role-badge role-badge--admin" title="Masuk sebagai Administrator">
                            <span class="role-badge-dot"></span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>Admin</span>
                        </span>
                    @elseif(auth()->user()->role === 'petugas')
                        <span class="role-badge role-badge--petugas" title="Masuk sebagai Petugas Perpustakaan">
                            <span class="role-badge-dot"></span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                            <span>Petugas</span>
                        </span>
                    @else
                        <span class="role-badge role-badge--member" title="Masuk sebagai Anggota Perpustakaan">
                            <span class="role-badge-dot"></span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span>Anggota</span>
                        </span>
                    @endif

                    {{-- Tombol Buka Dasbor Cepat --}}
                    <a href="{{ route('dashboard') }}" class="btn-nav-dashboard">
                        <span>Buka Dasbor</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>

                    {{-- User Profile Pill & Dropdown --}}
                    <div class="user-menu-wrap">
                        <button type="button" class="user-menu-btn" id="welcomeUserBtn" aria-expanded="false" onclick="toggleWelcomeUserDropdown(event)">
                            <div class="user-avatar-mini">
                                @if(auth()->user()->foto)
                                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}">
                                @else
                                    <span>{{ auth()->user()->inisial ?? 'U' }}</span>
                                @endif
                                <span class="status-indicator"></span>
                            </div>
                            <span class="user-menu-name">{{ Str::limit(auth()->user()->name, 12) }}</span>
                            <svg class="user-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>

                        <div class="user-dropdown-card" id="welcomeUserDropdown">
                            <div class="user-dropdown-header">
                                <div class="dropdown-avatar-lg">
                                    @if(auth()->user()->foto)
                                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}">
                                    @else
                                        <span>{{ auth()->user()->inisial ?? 'U' }}</span>
                                    @endif
                                </div>
                                <div class="dropdown-user-info">
                                    <strong class="dropdown-name">{{ auth()->user()->name }}</strong>
                                    <span class="dropdown-email">{{ auth()->user()->email }}</span>
                                </div>
                            </div>

                            <div class="user-dropdown-role-bar role-bar--{{ auth()->user()->role }}">
                                @if(auth()->user()->role === 'admin')
                                    🛡️ Administrator Utama
                                @elseif(auth()->user()->role === 'petugas')
                                    ⚡ Petugas Sirkulasi
                                @else
                                    📚 Anggota Aktif BOOKNEST
                                @endif
                            </div>

                            <div class="user-dropdown-body">
                                <div class="dropdown-section-title">Menu Utama</div>
                                <a href="{{ route('dashboard') }}" class="dropdown-item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                    <span>Dasbor Utama</span>
                                </a>

                                @if(auth()->user()->role === 'member')
                                    <a href="{{ route('riwayat.index') }}" class="dropdown-item">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <span>Riwayat & Booking Saya</span>
                                    </a>
                                    <a href="{{ route('pengembalian.member') }}" class="dropdown-item">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                        <span>Tiket Pengembalian</span>
                                    </a>
                                    <a href="{{ route('member.kartu-saya') }}" class="dropdown-item">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                        <span>Kartu Anggota Digital</span>
                                    </a>
                                @elseif(auth()->user()->role === 'petugas')
                                    <a href="{{ route('peminjaman.create') }}" class="dropdown-item">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M7 7h3v3H7z"/><path d="M14 7h3v3h-3z"/><path d="M7 14h3v3H7z"/></svg>
                                        <span>Scan QR Peminjaman</span>
                                    </a>
                                    <a href="{{ route('pengembalian.create') }}" class="dropdown-item">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                        <span>Scan QR Pengembalian</span>
                                    </a>
                                @elseif(auth()->user()->role === 'admin')
                                    <a href="{{ route('buku.index') }}" class="dropdown-item">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                        <span>Kelola Buku</span>
                                    </a>
                                    <a href="{{ route('member.index') }}" class="dropdown-item">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        <span>Data Anggota</span>
                                    </a>
                                    <a href="{{ route('laporan.index') }}" class="dropdown-item">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                                        <span>Laporan Perpustakaan</span>
                                    </a>
                                @endif

                                <div class="dropdown-divider"></div>

                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                    <span>Pengaturan Akun</span>
                                </a>

                                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="dropdown-item dropdown-item--danger">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                        <span>Keluar dari Akun</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </nav>
    </header>

    <main class="container">
        <!-- 1. HERO SECTION -->
        <section class="hero">
            <div class="hero-left">
                @auth
                    <div class="welcome-chip">
                        <span class="welcome-chip-pulse"></span>
                        <span>Halo, <strong>{{ auth()->user()->name }}</strong> ({{ ucfirst(auth()->user()->role) }}) 👋</span>
                    </div>
                @else
                    <span class="hero-tag">Temukan. Baca. Berkembang.</span>
                @endauth

                <h1 class="hero-title">Temukan Buku Favoritmu</h1>
                <p class="hero-desc">
                    Setiap buku membuka dunia baru. Jelajahi ribuan koleksi, pinjam dengan mudah, dan tumbuh bersama perpustakaan umum BOOKNEST.
                </p>

                <!-- Search Input Form -->
                <form action="{{ route('katalog.index') }}" method="GET" class="search-form-wrap">
                    <label class="search-label" for="heroCatalogSearch">Cari koleksi buku</label>
                    <div class="search-box">
                        <div class="search-input-wrap">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" id="heroCatalogSearch" name="q" placeholder="Cari judul, penulis, atau kategori...">
                        </div>
                        <button type="submit" class="btn-search">Cari</button>
                    </div>
                </form>

                <div class="hero-buttons">
                    <a href="{{ route('katalog.index') }}" class="btn-green">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        <span>Jelajahi Katalog</span>
                    </a>

                    @guest
                        <a href="{{ route('register') }}" class="btn-blue">
                            <span>Daftar Anggota</span>
                        </a>
                    @else
                        @if(auth()->user()->role === 'member')
                            <a href="{{ route('riwayat.index') }}" class="btn-blue">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                <span>Pinjaman & Booking Saya</span>
                            </a>
                        @elseif(auth()->user()->role === 'petugas')
                            <a href="{{ route('peminjaman.create') }}" class="btn-blue">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                <span>Meja Sirkulasi (Scan QR)</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn-blue">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                <span>Panel Dasbor Admin</span>
                            </a>
                        @endif
                    @endguest
                </div>

                @guest
                    <p class="hero-note">Gratis menjadi anggota · Peminjaman hingga 30 hari</p>
                @else
                    <p class="hero-note">
                        Status akun: <strong>Aktif</strong> · Akses layanan cepat langsung dari beranda
                    </p>
                @endauth
            </div>

            <!-- Hero Image Right -->
            <div class="hero-img-box">
                <div class="hero-img-container">
                    <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=1200&auto=format&fit=crop" 
                         alt="Perpustakaan BOOKNEST">
                </div>
                <div class="hero-img-caption">
                    <strong>12.480+</strong> <span>buku menunggu untuk kamu temukan</span>
                </div>
            </div>
        </section>

        <!-- 2. STATS SECTION -->
        <section class="stats-grid">
            <div class="stat-item">
                <h3>12.480</h3>
                <p>Koleksi buku</p>
            </div>
            <div class="stat-item">
                <h3>3.240</h3>
                <p>Anggota aktif</p>
            </div>
            <div class="stat-item">
                <h3>7</h3>
                <p>Kategori pilihan</p>
            </div>
        </section>

        <!-- 3. KATEGORI BUKU -->
        <section>
            <h2 class="section-title">Apa yang ingin kamu baca?</h2>
            <div class="categories-grid">
                <a href="{{ route('katalog.index', ['q' => 'Fiksi']) }}" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <span class="cat-name">Fiksi</span>
                </a>

                <a href="{{ route('katalog.index', ['q' => 'Pendidikan']) }}" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <span class="cat-name">Pendidikan</span>
                </a>

                <a href="{{ route('katalog.index', ['q' => 'Sejarah']) }}" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="21" x2="21" y2="21"></line><line x1="6" y1="18" x2="6" y2="7"></line><line x1="10" y1="18" x2="10" y2="7"></line><line x1="14" y1="18" x2="14" y2="7"></line><line x1="18" y1="18" x2="18" y2="7"></line><polygon points="12 2 20 7 4 7"></polygon></svg>
                    </div>
                    <span class="cat-name">Sejarah</span>
                </a>

                <a href="{{ route('katalog.index', ['q' => 'Teknologi']) }}" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    </div>
                    <span class="cat-name">Teknologi</span>
                </a>

                <a href="{{ route('katalog.index', ['q' => 'Agama']) }}" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M12 22V12"></path></svg>
                    </div>
                    <span class="cat-name">Agama</span>
                </a>

                <a href="{{ route('katalog.index', ['q' => 'Anak']) }}" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                    </div>
                    <span class="cat-name">Anak</span>
                </a>

                <a href="{{ route('katalog.index', ['q' => 'Umum']) }}" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"></line><line x1="4" y1="12" x2="20" y2="12"></line><line x1="4" y1="18" x2="20" y2="18"></line></svg>
                    </div>
                    <span class="cat-name">Umum</span>
                </a>
            </div>
        </section>

        <!-- (BAGIAN BUKU POPULER DIKOSONGKAN SEMENTARA SESUAI PERMINTAAN) -->

        <!-- 4. KEUNGGULAN / FITUR -->
        <section class="features-section">
            <div class="features-header">
                <h2>Perpustakaan, kini lebih dekat</h2>
                <p>Semua kebutuhan membaca dalam satu tempat yang mudah digunakan.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <h3 class="feature-title">Temukan dengan cepat</h3>
                    <p class="feature-desc">
                        Cari judul, penulis, dan kategori. Lihat lokasi rak serta ketersediaan buku secara langsung.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <line x1="7" y1="7" x2="7" y2="17"></line>
                            <line x1="11" y1="7" x2="11" y2="17"></line>
                            <line x1="15" y1="7" x2="15" y2="17"></line>
                            <line x1="17" y1="7" x2="17" y2="17"></line>
                        </svg>
                    </div>
                    <h3 class="feature-title">Pinjam tanpa ribet</h3>
                    <p class="feature-desc">
                        Ajukan peminjaman dari akunmu. Tunjukkan barcode kepada petugas saat mengambil buku.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                        </svg>
                    </div>
                    <h3 class="feature-title">Kelola dengan tenang</h3>
                    <p class="feature-desc">
                        Pantau jatuh tempo, riwayat, dan denda. Bayar melalui QRIS dengan bukti pembayaran digital.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. CTA BANNER -->
        <section class="cta-banner">
            @guest
                <div class="cta-text">
                    <h3>Mulai perjalanan membacamu hari ini</h3>
                    <p>Daftar gratis dan jadilah bagian dari komunitas yang terus belajar. Satu akun, ribuan kesempatan untuk berkembang.</p>
                </div>
                <a href="{{ route('register') }}" class="btn-cta">Daftar Sekarang</a>
            @else
                <div class="cta-text">
                    <h3>Senang melihatmu kembali, {{ explode(' ', auth()->user()->name)[0] }}!</h3>
                    <p>Siap melanjutkan petualangan membacamu? Akses ribuan koleksi buku fisik atau pantau aktivitasmu langsung dari dasbor.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn-cta" style="display: inline-flex; align-items: center; gap: 8px;">
                    <span>Buka Dasbor Utama</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            @endguest
        </section>
    </main>

    <!-- 6. FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h4>BOOKNEST</h4>
                    <p>Temukan. Baca. Berkembang.<br>Ruang pengetahuan untuk semua.</p>
                </div>

                <div class="footer-col">
                    <h5>Jelajahi BOOKNEST</h5>
                    <ul>
                        <li><a href="{{ route('katalog.index') }}">Katalog buku</a></li>
                        <li><a href="{{ route('register') }}">Keanggotaan</a></li>
                        <li><a href="{{ route('tentang') }}">Tentang kami</a></li>
                        <li><a href="javascript:void(0)">Panduan peminjaman</a></li>
                        <li><a href="javascript:void(0)">Kebijakan privasi</a></li>
                        <li><a href="javascript:void(0)">Syarat layanan</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h5>Kunjungi perpustakaan</h5>
                    <ul>
                        <li><span style="font-size: 13px; color: #64748b;">Jl. Merdeka No. 12, Bandung</span></li>
                        <li><span style="font-size: 13px; color: #64748b;">Senin–Sabtu, 08.00–17.00 WIB</span></li>
                        <li><span style="font-size: 13px; color: #64748b;">halo@booknest.id · (022) 420 1234</span></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                © 2026 BOOKNEST. Bersama menumbuhkan budaya membaca.
            </div>
        </div>
    </footer>

    <script>
        function toggleWelcomeUserDropdown(event) {
            event.stopPropagation();
            const btn = document.getElementById('welcomeUserBtn');
            const dropdown = document.getElementById('welcomeUserDropdown');
            if (!dropdown) return;

            const isOpen = dropdown.classList.contains('show');
            if (isOpen) {
                dropdown.classList.remove('show');
                btn.classList.remove('active');
                btn.setAttribute('aria-expanded', 'false');
            } else {
                dropdown.classList.add('show');
                btn.classList.add('active');
                btn.setAttribute('aria-expanded', 'true');
            }
        }

        document.addEventListener('click', function (e) {
            const dropdown = document.getElementById('welcomeUserDropdown');
            const btn = document.getElementById('welcomeUserBtn');
            if (dropdown && dropdown.classList.contains('show')) {
                if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
                    dropdown.classList.remove('show');
                    btn.classList.remove('active');
                    btn.setAttribute('aria-expanded', 'false');
                }
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const dropdown = document.getElementById('welcomeUserDropdown');
                const btn = document.getElementById('welcomeUserBtn');
                if (dropdown && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    btn.classList.remove('active');
                    btn.setAttribute('aria-expanded', 'false');
                }
            }
        });
    </script>
</body>
</html>