<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kebijakan Privasi - BOOKNEST</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Lato:wght@400;700&family=Open+Sans:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function () {
            try {
                var prefs = null;
                try {
                    var raw = localStorage.getItem('booknest_preferences');
                    if (raw) prefs = JSON.parse(raw);
                } catch (e) {}

                var themeChoice = (prefs && prefs.theme) ? prefs.theme : (localStorage.getItem('booknest_theme') || 'light');
                var effectiveTheme = themeChoice;
                if (themeChoice === 'system') {
                    effectiveTheme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
                }
                document.documentElement.setAttribute('data-theme', effectiveTheme);
                document.documentElement.setAttribute('data-theme-choice', themeChoice);

                var savedFont = (prefs && prefs.font) ? prefs.font : (localStorage.getItem('booknest_font') || 'Inter');
                document.documentElement.setAttribute('data-font', savedFont);

                var savedSize = (prefs && prefs.fontSize) ? prefs.fontSize : (localStorage.getItem('booknest_font_size') || 'normal');
                document.documentElement.setAttribute('data-font-size', savedSize);

                var savedOpening = (prefs && prefs.opening) ? prefs.opening : (localStorage.getItem('booknest_opening') || 'soft-mint-glow');
                document.documentElement.setAttribute('data-opening', savedOpening);

                var savedLang = (prefs && prefs.lang) ? prefs.lang : (localStorage.getItem('booknest_lang') || 'id');
                document.documentElement.setAttribute('data-lang', savedLang);
            } catch (e) {}
        })();
    </script>
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
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
            background: var(--bg-card, #ffffff);
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

        /* PRINCIPLES CARDS */
        .principles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .principle-card {
            background: var(--bg-card, #ffffff);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px 22px;
            transition: all 0.2s ease;
        }

        .principle-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
            border-color: #99f6e4;
        }

        .principle-icon-wrap {
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

        .principle-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 8px;
        }

        .principle-text {
            font-size: 13px;
            color: var(--text-body);
            line-height: 1.6;
        }

        /* POLICY ARTICLES */
        .policy-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .policy-card {
            background: var(--bg-card, #ffffff);
            border: 1px solid var(--border-color);
            border-radius: 22px;
            padding: 30px 28px;
            transition: all 0.2s ease;
        }

        .policy-card:hover {
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.04);
        }

        .policy-article-header {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 14px;
        }

        .policy-number-badge {
            font-size: 13px;
            font-weight: 800;
            background: #f1f5f9;
            color: #475569;
            padding: 4px 10px;
            border-radius: 8px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .policy-article-title {
            font-size: 19px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.3px;
        }

        .policy-content-body {
            font-size: 14px;
            color: var(--text-body);
            line-height: 1.75;
        }

        .policy-content-body p {
            margin-bottom: 12px;
        }

        .policy-content-body p:last-child {
            margin-bottom: 0;
        }

        .policy-point-list {
            margin: 12px 0 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .policy-point-list li {
            font-size: 13.5px;
            color: var(--text-body);
            line-height: 1.6;
        }

        .policy-highlight-note {
            background: var(--theme-border-subtle, #f8fafc);
            border-left: 4px solid var(--brand-primary, #0f766e);
            padding: 14px 18px;
            border-radius: 0 12px 12px 0;
            margin-top: 14px;
            font-size: 13px;
            color: var(--text-body, #334155);
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

        /* ========================================
           SCOPED THEME OVERRIDES FOR KEBIJAKAN PRIVASI
        ======================================== */
        /* 1. VAN GOGH (STARRY NIGHT) */
        [data-theme="van-gogh"] .page-hero-card {
            background: linear-gradient(135deg, #102a43 0%, #16365c 55%, #0b1726 100%) !important;
            border: 1px solid #1e3a5f !important;
            border-top: 2px solid rgba(245, 158, 11, 0.45) !important;
            box-shadow: 0 16px 36px -10px rgba(0, 0, 0, 0.6) !important;
        }

        [data-theme="van-gogh"] .principle-card,
        [data-theme="van-gogh"] .policy-card {
            background: linear-gradient(180deg, #14243b 0%, #101c2e 100%) !important;
            border: 1px solid #1e3a5f !important;
            border-top: 2px solid rgba(245, 158, 11, 0.4) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
            color: #e2e8f0 !important;
        }

        [data-theme="van-gogh"] .principle-icon-wrap {
            background: radial-gradient(circle at 35% 35%, #2a639b 0%, #16365c 100%) !important;
            color: #fcd34d !important;
            border: 1px solid rgba(245, 158, 11, 0.45) !important;
            box-shadow: 0 2px 10px rgba(245, 158, 11, 0.25) !important;
        }

        [data-theme="van-gogh"] .policy-article-header {
            border-color: #1e3a5f !important;
        }

        [data-theme="van-gogh"] .policy-number-badge {
            background: #1e3a5f !important;
            color: #fcd34d !important;
            border: 1px solid rgba(245, 158, 11, 0.3) !important;
        }

        [data-theme="van-gogh"] .principle-title,
        [data-theme="van-gogh"] .policy-article-title,
        [data-theme="van-gogh"] .footer-brand-title,
        [data-theme="van-gogh"] .footer-column-title {
            color: #f6f0df !important;
        }

        [data-theme="van-gogh"] .principle-text,
        [data-theme="van-gogh"] .policy-content-body,
        [data-theme="van-gogh"] .policy-point-list li,
        [data-theme="van-gogh"] .breadcrumb-nav,
        [data-theme="van-gogh"] .breadcrumb-nav a,
        [data-theme="van-gogh"] .footer-brand-tagline,
        [data-theme="van-gogh"] .footer-links a,
        [data-theme="van-gogh"] .footer-address {
            color: #9fb3c8 !important;
        }

        [data-theme="van-gogh"] .policy-highlight-note {
            background: #0d1b2a !important;
            border-left-color: #f59e0b !important;
            color: #e2e8f0 !important;
            border-top: 1px solid #1e3a5f !important;
            border-right: 1px solid #1e3a5f !important;
            border-bottom: 1px solid #1e3a5f !important;
        }

        /* 2. MIDNIGHT SLATE */
        [data-theme="midnight-slate"] .page-hero-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
            border: 1px solid #334155 !important;
        }

        [data-theme="midnight-slate"] .principle-card,
        [data-theme="midnight-slate"] .policy-card {
            background: #141f33 !important;
            border-color: #24354e !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3) !important;
            color: #e2e8f0 !important;
        }

        [data-theme="midnight-slate"] .principle-icon-wrap {
            background: #1e293b !important;
            color: #38bdf8 !important;
            border: 1px solid #38bdf8 !important;
        }

        [data-theme="midnight-slate"] .policy-article-header {
            border-color: #24354e !important;
        }

        [data-theme="midnight-slate"] .policy-number-badge {
            background: #1e293b !important;
            color: #38bdf8 !important;
        }

        [data-theme="midnight-slate"] .principle-title,
        [data-theme="midnight-slate"] .policy-article-title,
        [data-theme="midnight-slate"] .footer-brand-title,
        [data-theme="midnight-slate"] .footer-column-title {
            color: #f8fafc !important;
        }

        [data-theme="midnight-slate"] .principle-text,
        [data-theme="midnight-slate"] .policy-content-body,
        [data-theme="midnight-slate"] .policy-point-list li,
        [data-theme="midnight-slate"] .footer-brand-tagline,
        [data-theme="midnight-slate"] .footer-links a,
        [data-theme="midnight-slate"] .footer-address {
            color: #94a3b8 !important;
        }

        [data-theme="midnight-slate"] .policy-highlight-note {
            background: #1e293b !important;
            border-left-color: #38bdf8 !important;
            color: #e2e8f0 !important;
            border: 1px solid #24354e !important;
            border-left: 4px solid #38bdf8 !important;
        }

        /* 3. DARK MODE */
        [data-theme="dark"] .page-hero-card {
            background: linear-gradient(135deg, #0f172a 0%, #042f2e 100%) !important;
            border: 1px solid #115e59 !important;
        }

        [data-theme="dark"] .principle-card,
        [data-theme="dark"] .policy-card {
            background: #131d31 !important;
            border-color: #23314d !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
            color: #cbd5e1 !important;
        }

        [data-theme="dark"] .principle-icon-wrap {
            background: #042f2e !important;
            color: #2dd4bf !important;
            border: 1px solid #115e59 !important;
        }

        [data-theme="dark"] .policy-article-header {
            border-color: #23314d !important;
        }

        [data-theme="dark"] .policy-number-badge {
            background: #1e293b !important;
            color: #2dd4bf !important;
        }

        [data-theme="dark"] .principle-title,
        [data-theme="dark"] .policy-article-title,
        [data-theme="dark"] .footer-brand-title,
        [data-theme="dark"] .footer-column-title {
            color: #f8fafc !important;
        }

        [data-theme="dark"] .principle-text,
        [data-theme="dark"] .policy-content-body,
        [data-theme="dark"] .policy-point-list li,
        [data-theme="dark"] .footer-brand-tagline,
        [data-theme="dark"] .footer-links a,
        [data-theme="dark"] .footer-address {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .policy-highlight-note {
            background: #0f1d2e !important;
            border-left-color: #2dd4bf !important;
            color: #cbd5e1 !important;
            border: 1px solid #23314d !important;
            border-left: 4px solid #2dd4bf !important;
        }

        /* 4. SOFT BLUSH */
        [data-theme="soft-blush"] .page-hero-card {
            background: linear-gradient(135deg, #b85d77 0%, #8c3f55 100%) !important;
            box-shadow: 0 16px 36px -10px rgba(184, 93, 119, 0.3) !important;
        }

        [data-theme="soft-blush"] .principle-card,
        [data-theme="soft-blush"] .policy-card {
            background: #ffffff !important;
            border-color: #eedde2 !important;
            box-shadow: 0 4px 16px rgba(184, 93, 119, 0.06) !important;
        }

        [data-theme="soft-blush"] .principle-icon-wrap {
            background: #fdf2f5 !important;
            color: #b85d77 !important;
            border-color: #f7d5df !important;
        }

        [data-theme="soft-blush"] .principle-title,
        [data-theme="soft-blush"] .policy-article-title {
            color: #4a2835 !important;
        }

        [data-theme="soft-blush"] .policy-highlight-note {
            background: #fdf2f5 !important;
            border-left-color: #b85d77 !important;
            color: #4a2835 !important;
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

                    @include('layouts.partials.topbar_user_dropdown')
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
            <span style="color: var(--text-heading); font-weight: 700;">Kebijakan Privasi</span>
        </nav>

        {{-- HERO SECTION --}}
        <section class="page-hero-card">
            <div class="hero-badge-pill">
                <span class="dot"></span>
                <span>Transparansi & Keamanan Data</span>
            </div>
            <h1 class="hero-main-title">
                Kebijakan Privasi Anggota BOOKNEST
            </h1>
            <p class="hero-main-desc">
                Perpustakaan BOOKNEST berkomitmen melindungi privasi, integritas, dan keamanan informasi pribadi seluruh anggota, pengunjung, dan staf kami sesuai standar perlindungan data yang bertanggung jawab.
            </p>
            <div class="hero-meta-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span>Terakhir diperbarui: 14 Oktober 2026 · Berlaku untuk seluruh ekosistem layanan BOOKNEST</span>
            </div>
        </section>

        {{-- 4 PRINCIPLES --}}
        <section class="principles-grid">
            <div class="principle-card">
                <div class="principle-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <h3 class="principle-title">Perlindungan Identitas</h3>
                <p class="principle-text">
                    Informasi akun seperti Nama, NIK, dan Nomor Kontak Anda tersimpan aman dan hanya digunakan untuk keperluan verifikasi sirkulasi perpustakaan.
                </p>
            </div>

            <div class="principle-card">
                <div class="principle-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M2 12h20"></path>
                        <path d="M20 12v8H4v-8"></path>
                        <path d="M6 4h12l2 8H4l2-8z"></path>
                    </svg>
                </div>
                <h3 class="principle-title">Kerahasiaan Riwayat Baca</h3>
                <p class="principle-text">
                    Daftar buku yang Anda baca dan minjam bersifat personal. Kami tidak pernah mempublikasikan kebiasaan literasi Anda kepada publik tanpa izin.
                </p>
            </div>

            <div class="principle-card">
                <div class="principle-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>
                <h3 class="principle-title">Tanpa Penjualan Data</h3>
                <p class="principle-text">
                    BOOKNEST tidak menjual, menyewakan, atau memperdagangkan basis data anggota kepada pihak ketiga komersial mana pun untuk keperluan iklan.
                </p>
            </div>

            <div class="principle-card">
                <div class="principle-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h3 class="principle-title">Keamanan Akses Berlapis</h3>
                <p class="principle-text">
                    Akses data anggota diatur dengan enkripsi password modern dan token QR digital yang terhubung dengan akun terverifikasi.
                </p>
            </div>
        </section>

        {{-- POLICY ARTICLES CONTAINER --}}
        <section class="policy-container">
            {{-- PASAL 1 --}}
            <article class="policy-card">
                <div class="policy-article-header">
                    <span class="policy-number-badge">Pasal 1</span>
                    <h2 class="policy-article-title">Informasi yang Kami Kumpulkan</h2>
                </div>
                <div class="policy-content-body">
                    <p>
                        Untuk menyelenggarakan layanan sirkulasi dan keanggotaan perpustakaan yang tertib, BOOKNEST mengumpulkan informasi yang Anda berikan secara langsung saat pendaftaran dan penggunaan sistem:
                    </p>
                    <ul class="policy-point-list">
                        <li><strong>Data Akun Pribadi:</strong> Nama lengkap, Nomor Induk Kependudukan (NIK), alamat email aktif, nomor telepon/WhatsApp, dan foto profil digital.</li>
                        <li><strong>Data Transaksi Sirkulasi:</strong> Riwayat buku yang dipesan (booking), status peminjaman aktif, tanggal jatuh tempo, riwayat pengembalian, dan catatan kondisi fisik buku.</li>
                        <li><strong>Data Finansial Sirkulasi:</strong> Riwayat tagihan denda keterlambatan atau penggantian buku serta bukti konfirmasi pembayaran QRIS digital.</li>
                        <li><strong>Data Teknis Perangkat:</strong> Alamat IP, identitas sesi login, dan token QR anggota unik yang digunakan untuk validasi di meja layanan sirkulasi.</li>
                    </ul>
                </div>
            </article>

            {{-- PASAL 2 --}}
            <article class="policy-card">
                <div class="policy-article-header">
                    <span class="policy-number-badge">Pasal 2</span>
                    <h2 class="policy-article-title">Tujuan Penggunaan Data Anggota</h2>
                </div>
                <div class="policy-content-body">
                    <p>Informasi yang kami kumpulkan dipergunakan semata-mata untuk tujuan operasional perpustakaan:</p>
                    <ul class="policy-point-list">
                        <li>Memverifikasi identitas anggota saat proses pengambilan dan penyerahan buku fisik di meja layanan sirkulasi.</li>
                        <li>Menerbitkan Kartu Anggota Digital dan Tiket QR Pengembalian Mandiri.</li>
                        <li>Mengirimkan pemberitahuan penting seperti persetujuan booking buku dan pengingat tanggal jatuh tempo pengembalian.</li>
                        <li>Menyusun laporan statistik internal perpustakaan (seperti kategori buku terpopuler dan tren peminjaman) yang dianonimkan tanpa menampilkan identitas individu.</li>
                    </ul>
                </div>
            </article>

            {{-- PASAL 3 --}}
            <article class="policy-card">
                <div class="policy-article-header">
                    <span class="policy-number-badge">Pasal 3</span>
                    <h2 class="policy-article-title">Keamanan & Penyimpanan Informasi</h2>
                </div>
                <div class="policy-content-body">
                    <p>
                        Kami menerapkan langkah-langkah keamanan teknologi terkini untuk melindungi data Anda dari akses, pengubahan, atau pengungkapan tanpa izin:
                    </p>
                    <ul class="policy-point-list">
                        <li>Kata sandi akun Anda dienkripsi dengan algoritma hashing satu arah yang kuat (Bcrypt). Petugas perpustakaan maupun administrator tidak dapat membaca kata sandi asli Anda.</li>
                        <li>Token QR anggota dirancang secara aman untuk mencegah pemalsuan identitas oleh pihak yang tidak bertanggung jawab.</li>
                        <li>Akses terhadap basis data dibatasi ketat hanya kepada staf perpustakaan yang memiliki wewenang administratif.</li>
                    </ul>
                    <div class="policy-highlight-note">
                        <strong>Perhatian:</strong> Jangan pernah membagikan kredensial login Anda kepada siapa pun. Petugas BOOKNEST tidak akan pernah meminta kata sandi akun Anda.
                    </div>
                </div>
            </article>

            {{-- PASAL 4 --}}
            <article class="policy-card">
                <div class="policy-article-header">
                    <span class="policy-number-badge">Pasal 4</span>
                    <h2 class="policy-article-title">Pembayaran Digital & Data Finansial</h2>
                </div>
                <div class="policy-content-body">
                    <p>
                        Dalam transaksi penyelesaian denda keterlambatan atau ganti rugi fisik, BOOKNEST memfasilitasi pembayaran non-tunai melalui Quick Response Code Indonesian Standard (QRIS):
                    </p>
                    <ul class="policy-point-list">
                        <li>BOOKNEST tidak menyimpan informasi rekening bank, kartu kredit, atau PIN dompet digital Anda.</li>
                        <li>Seluruh proses verifikasi pembayaran dilakukan secara aman melalui gateway sistem perbankan nasional yang berizin resmi.</li>
                        <li>Sistem kami hanya mencatat status keberhasilan pembayaran, nominal transaksi, dan kode resi digital sebagai bukti tanda terima sah bagi anggota.</li>
                    </ul>
                </div>
            </article>

            {{-- PASAL 5 --}}
            <article class="policy-card">
                <div class="policy-article-header">
                    <span class="policy-number-badge">Pasal 5</span>
                    <h2 class="policy-article-title">Hak Anggota atas Data Pribadi</h2>
                </div>
                <div class="policy-content-body">
                    <p>Sebagai anggota BOOKNEST, Anda memiliki hak penuh terhadap data pribadi Anda:</p>
                    <ul class="policy-point-list">
                        <li><strong>Hak Akses & Pembaruan:</strong> Anda dapat meninjau dan memperbarui nama, nomor telepon, dan foto profil kapan saja melalui halaman Profil Saya.</li>
                        <li><strong>Hak Meninjau Riwayat:</strong> Anda dapat memantau seluruh riwayat peminjaman, pengembalian, dan pembayaran denda melalui dasbor akun.</li>
                        <li><strong>Hak Penonaktifan Akun:</strong> Anda dapat mengajukan permohonan penutupan akun kepada petugas perpustakaan setelah memastikan tidak memiliki tanggungan peminjaman buku aktif atau denda tertunda.</li>
                    </ul>
                </div>
            </article>

            {{-- PASAL 6 --}}
            <article class="policy-card">
                <div class="policy-article-header">
                    <span class="policy-number-badge">Pasal 6</span>
                    <h2 class="policy-article-title">Hubungi Kami Mengenai Privasi</h2>
                </div>
                <div class="policy-content-body">
                    <p>
                        Jika Anda memiliki pertanyaan, saran, atau kekhawatiran terkait pengelolaan data pribadi dan privasi di BOOKNEST, silakan hubungi tim pengelola kami melalui:
                    </p>
                    <ul class="policy-point-list">
                        <li><strong>Alamat Kantor:</strong> Perpustakaan BOOKNEST, Jl. Merdeka No. 12, Bandung, Jawa Barat</li>
                        <li><strong>Email Layanan Privasi:</strong> halo@booknest.id / privasi@booknest.id</li>
                        <li><strong>Telepon Layanan:</strong> (022) 420 1234 (Senin–Sabtu, 08.00–17.00 WIB)</li>
                    </ul>
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
                        <a href="{{ route('kebijakan-privasi') }}" style="color: #0f766e; font-weight: 700;">Kebijakan privasi</a>
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
</body>
</html>
