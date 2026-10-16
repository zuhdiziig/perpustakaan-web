<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tentang BOOKNEST</title>

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

        /* ========================================
           TOPBAR HEADER (STANDAR 1400PX KONSISTEN)
        ======================================== */

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

        .mobile-toggle-btn {
            display: none;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            color: var(--text-heading);
            border: 1px solid var(--border-color);
            background: var(--bg-card, #ffffff);
            cursor: pointer;
            transition: background 0.15s;
        }

        .mobile-toggle-btn:hover {
            background: #f1f5f9;
            color: var(--text-heading);
        }

        .btn-auth-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            background: var(--bg-card, #ffffff);
            border: 1px solid var(--border-color);
            color: var(--brand-primary);
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

            background: var(--bg-card, #ffffff);
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

            background: var(--bg-card, #ffffff);

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
            background: var(--theme-accent-light, #f0fdfa);
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
            background: var(--bg-card, #ffffff);
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

            border-top: 1px solid var(--border-color);

            color: var(--text-muted);
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

        @media (max-width: 992px) {
            .mobile-toggle-btn {
                display: flex !important;
            }

            .topbar-nav {
                display: none;
            }
        }

        @guest
            @media (max-width: 992px) {
                .topbar-nav {
                    display: flex !important;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                    scrollbar-width: none;
                    gap: 4px;
                }
                .topbar-nav::-webkit-scrollbar {
                    display: none;
                }
                .topbar-nav-link {
                    padding: 6px 12px;
                    font-size: 12.5px;
                    white-space: nowrap;
                }
            }
        @endguest

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

            .about-page {
                padding: 14px 12px 36px;
            }

            .about-hero-content {
                padding: 22px 16px 24px;
            }

            .about-hero-title {
                font-size: 24px;
                line-height: 1.25;
            }

            .about-value-card,
            .about-info-card,
            .about-membership {
                padding: 18px 16px;
            }
        }

        /* ========================================
           SCOPED THEME OVERRIDES FOR TENTANG
        ======================================== */
        /* 1. VAN GOGH (STARRY NIGHT) */
        [data-theme="van-gogh"] .about-hero,
        [data-theme="van-gogh"] .about-values,
        [data-theme="van-gogh"] .about-info-card,
        [data-theme="van-gogh"] .about-membership {
            background: linear-gradient(180deg, #14243b 0%, #101c2e 100%) !important;
            border: 1px solid #1e3a5f !important;
            border-top: 2px solid rgba(245, 158, 11, 0.4) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
            color: #e2e8f0 !important;
        }

        [data-theme="van-gogh"] .about-value-card {
            border-color: #1e3a5f !important;
        }

        [data-theme="van-gogh"] .about-value-icon {
            background: radial-gradient(circle at 35% 35%, #2a639b 0%, #16365c 100%) !important;
            color: #fcd34d !important;
            border: 1px solid rgba(245, 158, 11, 0.45) !important;
            box-shadow: 0 2px 10px rgba(245, 158, 11, 0.25) !important;
        }

        [data-theme="van-gogh"] .about-hero-title,
        [data-theme="van-gogh"] .about-value-title,
        [data-theme="van-gogh"] .about-section-title,
        [data-theme="van-gogh"] .about-membership-title {
            color: #f6f0df !important;
        }

        [data-theme="van-gogh"] .about-eyebrow {
            color: #fcd34d !important;
        }

        [data-theme="van-gogh"] .about-hero-description,
        [data-theme="van-gogh"] .about-value-description,
        [data-theme="van-gogh"] .about-service-list li,
        [data-theme="van-gogh"] .about-contact-row,
        [data-theme="van-gogh"] .about-contact-label,
        [data-theme="van-gogh"] .about-info-note,
        [data-theme="van-gogh"] .about-membership-text {
            color: #9fb3c8 !important;
        }

        [data-theme="van-gogh"] .about-hero-tagline,
        [data-theme="van-gogh"] .about-contact-value {
            color: #fcd34d !important;
        }

        [data-theme="van-gogh"] .about-info-card.highlight {
            background: linear-gradient(135deg, #182b42 0%, #101c2e 100%) !important;
            border: 1px solid #2e4a6e !important;
            border-left: 4px solid #f59e0b !important;
        }

        [data-theme="van-gogh"] .about-primary-button {
            background: #245b8f !important;
            color: #ffffff !important;
            border: 1px solid #1e3a5f !important;
            font-weight: 700 !important;
        }

        [data-theme="van-gogh"] .about-primary-button:hover {
            background: #1b456e !important;
            color: #fcd34d !important;
            border-color: #f59e0b !important;
        }

        [data-theme="van-gogh"] .about-footer {
            background: #0d1b2a !important;
            border-top-color: #1e3a5f !important;
        }

        [data-theme="van-gogh"] .about-footer-bottom {
            border-top-color: #1e3a5f !important;
            color: #7892ab !important;
        }

        [data-theme="van-gogh"] .footer-brand-title,
        [data-theme="van-gogh"] .footer-column-title {
            color: #f6f0df !important;
        }

        [data-theme="van-gogh"] .footer-brand-text,
        [data-theme="van-gogh"] .footer-links a,
        [data-theme="van-gogh"] .footer-address {
            color: #9fb3c8 !important;
        }

        [data-theme="van-gogh"] .footer-links a:hover {
            color: #fcd34d !important;
        }

        /* 2. MIDNIGHT SLATE */
        [data-theme="midnight-slate"] .about-hero,
        [data-theme="midnight-slate"] .about-values,
        [data-theme="midnight-slate"] .about-info-card,
        [data-theme="midnight-slate"] .about-membership {
            background: #141f33 !important;
            border-color: #24354e !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3) !important;
            color: #e2e8f0 !important;
        }

        [data-theme="midnight-slate"] .about-value-card {
            border-color: #24354e !important;
        }

        [data-theme="midnight-slate"] .about-value-icon {
            background: #1e293b !important;
            color: #38bdf8 !important;
            border: 1px solid #38bdf8 !important;
        }

        [data-theme="midnight-slate"] .about-hero-title,
        [data-theme="midnight-slate"] .about-value-title,
        [data-theme="midnight-slate"] .about-section-title,
        [data-theme="midnight-slate"] .about-membership-title {
            color: #f8fafc !important;
        }

        [data-theme="midnight-slate"] .about-eyebrow {
            color: #38bdf8 !important;
        }

        [data-theme="midnight-slate"] .about-hero-description,
        [data-theme="midnight-slate"] .about-value-description,
        [data-theme="midnight-slate"] .about-service-list li,
        [data-theme="midnight-slate"] .about-contact-row,
        [data-theme="midnight-slate"] .about-contact-label,
        [data-theme="midnight-slate"] .about-info-note,
        [data-theme="midnight-slate"] .about-membership-text {
            color: #94a3b8 !important;
        }

        [data-theme="midnight-slate"] .about-info-card.highlight {
            background: #141f33 !important;
            border-color: #24354e !important;
            border-left: 4px solid #38bdf8 !important;
        }

        [data-theme="midnight-slate"] .about-footer {
            background: #0f172a !important;
            border-top-color: #24354e !important;
        }

        [data-theme="midnight-slate"] .about-footer-bottom {
            border-top-color: #24354e !important;
            color: #64748b !important;
        }

        [data-theme="midnight-slate"] .footer-brand-title,
        [data-theme="midnight-slate"] .footer-column-title {
            color: #f8fafc !important;
        }

        [data-theme="midnight-slate"] .footer-brand-text,
        [data-theme="midnight-slate"] .footer-links a,
        [data-theme="midnight-slate"] .footer-address {
            color: #94a3b8 !important;
        }

        /* 3. DARK MODE */
        [data-theme="dark"] .about-hero,
        [data-theme="dark"] .about-values,
        [data-theme="dark"] .about-info-card,
        [data-theme="dark"] .about-membership {
            background: #131d31 !important;
            border-color: #23314d !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
            color: #cbd5e1 !important;
        }

        [data-theme="dark"] .about-value-card {
            border-color: #23314d !important;
        }

        [data-theme="dark"] .about-value-icon {
            background: #042f2e !important;
            color: #2dd4bf !important;
            border: 1px solid #115e59 !important;
        }

        [data-theme="dark"] .about-hero-title,
        [data-theme="dark"] .about-value-title,
        [data-theme="dark"] .about-section-title,
        [data-theme="dark"] .about-membership-title {
            color: #f8fafc !important;
        }

        [data-theme="dark"] .about-eyebrow {
            color: #2dd4bf !important;
        }

        [data-theme="dark"] .about-hero-description,
        [data-theme="dark"] .about-value-description,
        [data-theme="dark"] .about-service-list li,
        [data-theme="dark"] .about-contact-row,
        [data-theme="dark"] .about-contact-label,
        [data-theme="dark"] .about-info-note,
        [data-theme="dark"] .about-membership-text {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .about-info-card.highlight {
            background: #042f2e !important;
            border-color: #115e59 !important;
            border-left: 4px solid #2dd4bf !important;
        }

        [data-theme="dark"] .about-footer {
            background: #0d1726 !important;
            border-top-color: #23314d !important;
        }

        [data-theme="dark"] .about-footer-bottom {
            border-top-color: #23314d !important;
            color: #64748b !important;
        }

        [data-theme="dark"] .footer-brand-title,
        [data-theme="dark"] .footer-column-title {
            color: #f8fafc !important;
        }

        [data-theme="dark"] .footer-brand-text,
        [data-theme="dark"] .footer-links a,
        [data-theme="dark"] .footer-address {
            color: #94a3b8 !important;
        }

        /* 4. SOFT BLUSH */
        [data-theme="soft-blush"] .about-hero,
        [data-theme="soft-blush"] .about-values,
        [data-theme="soft-blush"] .about-info-card,
        [data-theme="soft-blush"] .about-membership {
            background: #ffffff !important;
            border-color: #eedde2 !important;
            box-shadow: 0 4px 16px rgba(184, 93, 119, 0.06) !important;
        }

        [data-theme="soft-blush"] .about-value-card {
            border-color: #eedde2 !important;
        }

        [data-theme="soft-blush"] .about-hero-title,
        [data-theme="soft-blush"] .about-value-title,
        [data-theme="soft-blush"] .about-section-title,
        [data-theme="soft-blush"] .about-membership-title {
            color: #4a2835 !important;
        }

        [data-theme="soft-blush"] .about-eyebrow {
            color: #b85d77 !important;
        }

        [data-theme="soft-blush"] .about-info-card.highlight {
            background: #fdf2f5 !important;
            border-color: #eedde2 !important;
            border-left: 4px solid #b85d77 !important;
        }
    </style>
</head>

<body>

    {{-- ========================================
         TOPBAR HEADER (KONSISTEN DENGAN DASHBOARD & KATALOG)
    ======================================== --}}
    <header class="topbar">
        <div class="topbar-container">
            <!-- Brand & Mobile Toggle -->
            <div style="display: flex; align-items: center; gap: 12px;">
                @auth
                    <button type="button" class="mobile-toggle-btn" id="sidebarToggle" aria-label="Buka menu navigasi" aria-controls="sidebarPanel" aria-expanded="false">
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
                <a href="{{ route('tentang') }}" class="topbar-nav-link active">
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

        <!-- PAGE WRAPPER (LAYOUT KONSISTEN DENGAN DASBOR & KATALOG) -->
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
                <div class="about-page" style="max-width: 100%; margin: 0; padding: 0 0 30px 0;">
    @else
        <main class="about-page">
    @endauth

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

                @guest
                    <a
                        href="{{ route('register') }}"
                        class="about-primary-button"
                    >
                        Jadi Anggota
                    </a>
                @else
                    @if(auth()->user()->role === 'member')
                        <a
                            href="{{ route('katalog.index') }}"
                            class="about-primary-button"
                        >
                            Jelajahi Katalog
                        </a>
                    @else
                        <a
                            href="{{ route('dashboard') }}"
                            class="about-primary-button"
                        >
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span>Buka Dasbor</span>
                        </a>
                    @endif
                @endguest

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

        @auth
            </div>
            {{-- End .about-page inside .content-area --}}
        @else
            </main>
        @endauth

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

                        <a href="{{ route('panduan-peminjaman') }}">
                            Panduan peminjaman
                        </a>

                        <a href="{{ route('kebijakan-privasi') }}">
                            Kebijakan privasi
                        </a>

                        <a href="{{ route('syarat-layanan') }}">
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

    @auth
            </main>
            <!-- END CONTENT AREA -->
        </div>
        <!-- END PAGE WRAPPER -->
    @endauth

    @include('layouts.partials.sidebar_scripts')

    @auth
        @if(auth()->user()->role === 'member')
            @include('layouts.partials.member_feedback_toasts')
            {{-- Realtime Sirkulasi Notification Modal & Polling --}}
            @include('layouts.partials.member_realtime_notification')
            {{-- Modal Pencarian Terpadu Anggota (Buku, Pinjam, Kembali, Denda) --}}
            @include('layouts.partials.member_universal_search')
            {{-- Pop Up Message Ringkas Notifikasi Booking Peminjaman & Pengembalian --}}
            @include('layouts.partials.member_booking_popup')
        @endif
    @endauth
</body>
</html>