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
    <script>
        document.documentElement.classList.add('js-ready');
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
    </script>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        html {
            overflow-x: clip;
            overflow-y: scroll;
            scrollbar-width: none; /* Hilangkan scrollbar Firefox */
            -ms-overflow-style: none; /* Hilangkan scrollbar IE & Edge */
        }

        body {
            background-color: #ffffff;
            color: #0f172a;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            min-height: 100vh;
        }

        /* Hilangkan scrollbar di sebelah kanan untuk WebKit (Chrome, Safari, Edge, Opera) */
        html::-webkit-scrollbar,
        body::-webkit-scrollbar,
        *::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
            background: transparent !important;
        }

        * {
            scrollbar-width: none;
            -ms-overflow-style: none;
            -webkit-tap-highlight-color: transparent;
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 24px;
            width: 100%;
        }

        /* --- TOP NAVBAR --- */
        header.container {
            position: relative;
            z-index: 100;
        }

        .navbar {
            position: relative;
            z-index: 100;
            padding: 24px 0 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* --- SMOOTH NAVBAR ENTRANCE FADE-IN ON LOAD (0.85s ease-out) --- */
        @keyframes navFadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-12px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            animation: navFadeInDown 1.15s cubic-bezier(0.16, 1, 0.3, 1) 0s both;
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
            transition: all 0.24s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            animation: navFadeInDown 1.15s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
        }

        .btn-nav-login:hover {
            border-color: #2e625a;
            color: #234c46;
            background: #f0fdf4;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 14px rgba(46, 98, 90, 0.15);
        }

        .btn-nav-login:active {
            transform: translateY(0) scale(0.97) !important;
            box-shadow: 0 2px 4px rgba(46, 98, 90, 0.1);
        }

        .btn-nav-register {
            background-color: #4361ee;
            color: white;
            padding: 9px 20px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.24s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(67, 97, 238, 0.25);
            animation: navFadeInDown 1.15s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
        }

        .btn-nav-register:hover {
            background-color: #3651d4;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 18px rgba(67, 97, 238, 0.38);
        }

        .btn-nav-register:active {
            transform: translateY(0) scale(0.97) !important;
            box-shadow: 0 2px 6px rgba(67, 97, 238, 0.2);
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
            animation: navFadeInDown 1.15s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
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
            transition: all 0.24s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 2px 8px rgba(46, 98, 90, 0.2);
            animation: navFadeInDown 1.15s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
        }

        .btn-nav-dashboard:hover {
            background: #234c46;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px rgba(46, 98, 90, 0.32);
        }

        .btn-nav-dashboard:active {
            transform: translateY(0) scale(0.97) !important;
            box-shadow: 0 2px 6px rgba(46, 98, 90, 0.2);
        }

        .btn-nav-dashboard svg {
            transition: transform 0.24s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .btn-nav-dashboard:hover svg {
            transform: scale(1.15);
        }

        .user-menu-wrap {
            position: relative;
            z-index: 110;
            animation: navFadeInDown 1.15s cubic-bezier(0.16, 1, 0.3, 1) 0.28s both;
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
            transition: all 0.24s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .user-menu-btn:hover,
        .user-menu-btn.active {
            border-color: #2e625a;
            background: #f8fafc;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .user-menu-btn:active {
            transform: translateY(0) scale(0.98) !important;
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
        main.container {
            position: relative;
            z-index: 1;
        }

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
            transition: all 0.24s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 2px 8px rgba(46, 98, 90, 0.2);
        }

        .btn-search:hover {
            background: #234c46;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(46, 98, 90, 0.32);
        }

        .btn-search:active {
            transform: translateY(0) scale(0.97);
            box-shadow: 0 2px 6px rgba(46, 98, 90, 0.2);
        }

        .hero-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .btn-green {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #2e625a;
            color: white;
            padding: 11px 22px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            line-height: 1;
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 3px 10px rgba(46, 98, 90, 0.22);
            cursor: pointer;
        }

        .btn-green:hover {
            background: #234c46;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 22px rgba(46, 98, 90, 0.35);
        }

        .btn-green:active {
            transform: translateY(0) scale(0.98);
            box-shadow: 0 2px 6px rgba(46, 98, 90, 0.2);
        }

        .btn-green svg {
            flex-shrink: 0;
            display: block;
            width: 16px;
            height: 16px;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .btn-green:hover svg {
            transform: scale(1.18) rotate(-4deg);
        }

        .btn-green span {
            display: inline-block;
            line-height: 1;
        }

        .btn-blue {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #4361ee;
            color: white;
            padding: 11px 22px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            line-height: 1;
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 3px 10px rgba(67, 97, 238, 0.25);
            cursor: pointer;
        }

        .btn-blue:hover {
            background: #3651d4;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 22px rgba(67, 97, 238, 0.38);
        }

        .btn-blue:active {
            transform: translateY(0) scale(0.98);
            box-shadow: 0 2px 6px rgba(67, 97, 238, 0.2);
        }

        .btn-blue svg {
            flex-shrink: 0;
            display: block;
            width: 16px;
            height: 16px;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .btn-blue:hover svg {
            transform: scale(1.18) rotate(4deg);
        }

        .btn-blue span {
            display: inline-block;
            line-height: 1;
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
            background: #183d37;
            position: relative;
            box-shadow: 0 16px 36px -10px rgba(15, 23, 42, 0.14), 0 6px 14px -4px rgba(15, 23, 42, 0.06);
        }

        /* 1. Dramatic Wipe Curtain Overlay */
        .hero-img-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #183d37 0%, #2e625a 100%);
            z-index: 2;
            transform: translateX(0%);
            animation: heroWipeCurtain 1.6s cubic-bezier(0.77, 0, 0.175, 1) 0.85s forwards;
            pointer-events: none;
        }

        @keyframes heroWipeCurtain {
            0% {
                transform: translateX(0%);
            }
            100% {
                transform: translateX(101%);
                visibility: hidden;
            }
        }

        /* 2. Light Sheen Beam Wipe Sweep */
        .hero-img-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: -150%;
            width: 75%;
            height: 100%;
            background: linear-gradient(
                90deg,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.5) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: skewX(-25deg);
            z-index: 3;
            animation: sheenWipe 1.85s cubic-bezier(0.25, 1, 0.5, 1) 1.55s forwards;
            pointer-events: none;
        }

        @keyframes sheenWipe {
            0% {
                left: -150%;
            }
            100% {
                left: 220%;
            }
        }

        /* 3. Smooth Ken Burns Zoom In on Image */
        .hero-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transform: scale(1.22);
            animation: imageZoomInAnim 2.4s cubic-bezier(0.16, 1, 0.3, 1) 0.85s forwards;
            transition: transform 0.85s cubic-bezier(0.34, 1.56, 0.64, 1);
            will-change: transform;
        }

        @keyframes imageZoomInAnim {
            0% {
                transform: scale(1.22);
                filter: brightness(0.9);
            }
            100% {
                transform: scale(1);
                filter: brightness(1);
            }
        }

        .hero-img-container:hover img {
            transform: scale(1.06);
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

        @keyframes statFadeInUp {
            0% {
                opacity: 0;
                transform: translateY(16px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-item {
            animation: statFadeInUp 1.35s cubic-bezier(0.16, 1, 0.3, 1) both;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .stat-item:nth-child(1) { animation-delay: 0.32s; }
        .stat-item:nth-child(2) { animation-delay: 0.48s; }
        .stat-item:nth-child(3) { animation-delay: 0.64s; }

        .stat-item:hover {
            transform: translateY(-2px);
        }

        .stat-item h3 {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            font-variant-numeric: tabular-nums;
            display: inline-block;
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
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #2e625a 0%, #4361ee 100%);
            border-radius: 9999px;
            margin-top: 6px;
            transition: width 1.3s cubic-bezier(0.16, 1, 0.3, 1) 0.25s;
        }

        .section-title.is-revealed::after {
            width: 42px;
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
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
        }

        .cat-card:hover {
            border-color: #2e625a;
            background: #ffffff;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
            transform: translateY(-4px) scale(1.02);
        }

        .cat-card:active {
            transform: translateY(-1px) scale(0.98);
        }

        .cat-icon {
            color: #2e625a;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .cat-card:hover .cat-icon {
            transform: scale(1.18);
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
            transition: all 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .feature-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
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
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .feature-card:hover .feature-icon-box {
            background: #f0fdf4;
            border-color: #a7f3d0;
            transform: scale(1.1);
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
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #e6f7f4 0%, #f0fdf9 100%);
            border: 1px solid #cceee8;
            border-radius: 16px;
            padding: 32px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 70px;
            transition: all 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .cta-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: -120%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.5) 50%, rgba(255, 255, 255, 0) 100%);
            transform: skewX(-25deg);
            pointer-events: none;
        }

        .cta-banner.is-revealed::before {
            animation: ctaWipeSheen 1.85s cubic-bezier(0.22, 1, 0.36, 1) 0.45s forwards;
        }

        @keyframes ctaWipeSheen {
            0% { left: -120%; }
            100% { left: 220%; }
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
            padding: 12px 26px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 3px 10px rgba(46, 98, 90, 0.25);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-cta:hover {
            background: #234c46;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 24px rgba(46, 98, 90, 0.38);
        }

        .btn-cta:active {
            transform: translateY(0) scale(0.98);
            box-shadow: 0 2px 6px rgba(46, 98, 90, 0.2);
        }

        .btn-cta svg {
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .btn-cta:hover svg {
            transform: scale(1.15);
        }

        /* =========================================================
           1. INTRO ANIMATION & CINEMATIC CURTAIN WIPE
           ========================================================= */
        .intro-curtain {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 999999;
            background: linear-gradient(145deg, #183d37 0%, #0d2320 60%, #091715 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            transform: translateY(0%);
            animation: introCurtainWipe 1.15s cubic-bezier(0.77, 0, 0.175, 1) 0.85s forwards;
            will-change: transform;
        }

        @keyframes introCurtainWipe {
            0% {
                transform: translateY(0%);
            }
            100% {
                transform: translateY(-100%);
                visibility: hidden;
            }
        }

        .intro-curtain-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
            animation: introContentZoomFade 0.85s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            will-change: transform, opacity;
        }

        @keyframes introContentZoomFade {
            0% {
                opacity: 0;
                transform: scale(0.88) translateY(14px);
            }
            50% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            100% {
                opacity: 0;
                transform: scale(1.08) translateY(-14px);
            }
        }

        .intro-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .intro-logo-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25), 0 0 20px rgba(46, 98, 90, 0.5);
        }

        .intro-logo-text {
            font-size: 24px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .intro-wipe-track {
            width: 140px;
            height: 3px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 9999px;
            overflow: hidden;
            position: relative;
        }

        .intro-wipe-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #4ade80, #38bdf8);
            transform: translateX(-100%);
            animation: introWipeBarAnim 0.85s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes introWipeBarAnim {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(0); }
        }

        .intro-tagline {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            letter-spacing: 0.3px;
            font-weight: 500;
        }

        /* =========================================================
           2. CINEMATIC REVEAL & SLIDE IN HERO ANIMATIONS
           ========================================================= */
        .cinematic-reveal-title {
            opacity: 0;
            clip-path: inset(0 0 100% 0);
            transform: translateY(34px);
            filter: blur(8px);
            animation: cinematicTitleReveal 1.65s cubic-bezier(0.16, 1, 0.3, 1) 1.05s both;
            will-change: clip-path, transform, filter, opacity;
        }

        @keyframes cinematicTitleReveal {
            0% {
                opacity: 0;
                clip-path: inset(0 0 100% 0);
                transform: translateY(34px);
                filter: blur(8px);
            }
            45% {
                filter: blur(2px);
            }
            100% {
                opacity: 1;
                clip-path: inset(0 0 0 0);
                transform: translateY(0);
                filter: blur(0);
            }
        }

        /* Directional Slide In Left */
        .slide-in-left {
            opacity: 0;
            transform: translateX(-36px);
            animation: slideInLeftAnim 1.45s cubic-bezier(0.16, 1, 0.3, 1) both;
            will-change: transform, opacity;
        }

        @keyframes slideInLeftAnim {
            0% {
                opacity: 0;
                transform: translateX(-36px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Stagger Delays for Hero Left (Coordinated with Curtain Lift) */
        .hero-item-tag { animation-delay: 0.85s; }
        .hero-item-desc { animation-delay: 1.25s; }
        .hero-item-note { animation-delay: 1.75s; }

        /* Staggered Entrance for Hero Buttons */
        .stagger-btn-1 {
            animation: btnStaggerIn 1.15s cubic-bezier(0.34, 1.56, 0.64, 1) 1.45s both;
            will-change: transform, opacity;
        }

        .stagger-btn-2 {
            animation: btnStaggerIn 1.15s cubic-bezier(0.34, 1.56, 0.64, 1) 1.62s both;
            will-change: transform, opacity;
        }

        @keyframes btnStaggerIn {
            0% {
                opacity: 0;
                transform: translateY(22px) scale(0.92);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* =========================================================
           3. STAGGERED ENTRANCE & ZOOM IN SCROLL REVEAL
           ========================================================= */
        .scroll-reveal {
            opacity: 1;
            transform: none;
        }

        .js-ready .scroll-reveal {
            opacity: 0;
            transform: translateY(26px) scale(0.95);
            transition: opacity 1.35s cubic-bezier(0.16, 1, 0.3, 1), 
                        transform 1.35s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .js-ready .scroll-reveal.is-revealed {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        /* Cascade Stagger Delays */
        .reveal-delay-1 { transition-delay: 0.10s; }
        .reveal-delay-2 { transition-delay: 0.18s; }
        .reveal-delay-3 { transition-delay: 0.26s; }
        .reveal-delay-4 { transition-delay: 0.34s; }
        .reveal-delay-5 { transition-delay: 0.42s; }
        .reveal-delay-6 { transition-delay: 0.50s; }
        .reveal-delay-7 { transition-delay: 0.58s; }

        @media (prefers-reduced-motion: reduce) {
            /* Keep animations gentle and smooth without breaking */
            .js-ready .scroll-reveal {
                transition: opacity 0.4s ease-out, transform 0.4s ease-out;
            }
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

        /* --- RESPONSIVE OPTIMIZATIONS --- */
        @media (max-width: 1024px) {
            .hero {
                grid-template-columns: 1fr;
                gap: 32px;
                padding: 32px 0 40px;
            }
            .hero-img-container {
                height: 300px;
            }
            .categories-grid {
                grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
                gap: 12px;
            }
            .features-grid {
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: 18px;
            }
            .footer-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 30px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 20px;
            }
            .navbar {
                padding: 18px 0 10px;
            }
            .hero-title {
                font-size: 32px;
                letter-spacing: -0.6px;
            }
            .stats-grid {
                padding: 24px 0 36px;
                gap: 16px;
            }
            .stat-item h3 {
                font-size: 24px;
            }
            .cta-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 18px;
                padding: 26px 28px;
                margin-bottom: 50px;
            }
        }

        @media (max-width: 640px) {
            .container {
                padding: 0 16px;
            }
            .navbar {
                padding: 14px 0 10px;
                gap: 8px;
            }
            .logo-text {
                font-size: 17px;
            }
            .logo-icon {
                width: 32px;
                height: 32px;
            }
            .nav-links {
                gap: 6px;
            }
            .nav-link-item {
                padding: 6px 9px;
                font-size: 12.5px;
                gap: 4px;
            }
            .hero {
                padding: 20px 0 32px;
                gap: 22px;
            }
            .hero-title {
                font-size: 27px;
                line-height: 1.22;
                letter-spacing: -0.5px;
                margin-bottom: 12px;
                word-break: break-word;
            }
            .hero-desc {
                font-size: 13.5px;
                line-height: 1.55;
                margin-bottom: 18px;
                max-width: 100%;
            }
            .welcome-chip {
                max-width: 100%;
                font-size: 12px;
                padding: 6px 12px;
                box-sizing: border-box;
            }
            .hero-buttons {
                flex-direction: column;
                width: 100%;
                gap: 10px;
                margin-bottom: 14px;
            }
            .btn-green,
            .btn-blue {
                width: 100%;
                justify-content: center;
                text-align: center;
                box-sizing: border-box;
                padding: 12px 18px;
                font-size: 14px;
                display: flex;
                align-items: center;
                touch-action: manipulation;
            }
            .hero-img-container {
                height: 220px;
            }
            .categories-grid {
                grid-template-columns: repeat(auto-fill, minmax(95px, 1fr));
                gap: 10px;
                margin-bottom: 40px;
            }
            .cat-card {
                padding: 16px 8px;
                touch-action: manipulation;
            }
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                padding: 18px 0 28px;
            }
            .stat-item h3 {
                font-size: 21px;
            }
            .stat-item p {
                font-size: 11.5px;
            }
            .features-section {
                padding: 36px 0 44px;
            }
            .features-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }
            .feature-card {
                padding: 22px 18px;
            }
            .cta-banner {
                padding: 22px 18px;
                border-radius: 14px;
                margin-bottom: 40px;
            }
            .btn-cta {
                width: 100%;
                text-align: center;
                justify-content: center;
                display: flex;
                box-sizing: border-box;
                touch-action: manipulation;
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
                padding: 7px 10px;
                touch-action: manipulation;
            }
            .btn-nav-login {
                padding: 7px 12px;
                font-size: 12.5px;
                touch-action: manipulation;
            }
            .btn-nav-register {
                padding: 7px 12px;
                font-size: 12.5px;
                touch-action: manipulation;
            }
            .footer {
                padding: 36px 0 24px;
            }
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 24px;
                margin-bottom: 24px;
            }
        }
    </style>
</head>
<body>

    <!-- ====================================================
         INTRO ANIMATION & CINEMATIC SPLASH CURTAIN (WIPE)
         ==================================================== -->
    <div id="introCurtain" class="intro-curtain" aria-hidden="true">
        <div class="intro-curtain-inner">
            <div class="intro-brand">
                <div class="intro-logo-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <span class="intro-logo-text">BOOKNEST</span>
            </div>
            <div class="intro-wipe-track">
                <div class="intro-wipe-bar"></div>
            </div>
            <p class="intro-tagline">Ruang Pengetahuan Terbuka</p>
        </div>
    </div>

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
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span>Buka Dasbor</span>
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
                    <div class="welcome-chip slide-in-left hero-item-tag">
                        <span class="welcome-chip-pulse"></span>
                        <span>Halo, <strong>{{ auth()->user()->name }}</strong> ({{ ucfirst(auth()->user()->role) }}) 👋</span>
                    </div>
                @else
                    <span class="hero-tag slide-in-left hero-item-tag">Temukan. Baca. Berkembang.</span>
                @endauth

                <h1 class="hero-title cinematic-reveal-title">Temukan Buku Favoritmu</h1>
                <p class="hero-desc slide-in-left hero-item-desc">
                    Setiap buku membuka dunia baru. Jelajahi ribuan koleksi, pinjam dengan mudah, dan tumbuh bersama perpustakaan umum BOOKNEST.
                </p>


                <div class="hero-buttons">
                    @if(!auth()->check() || auth()->user()->role === 'member')
                        <a href="{{ route('katalog.index') }}" class="btn-green stagger-btn-1">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            <span>Jelajahi Katalog</span>
                        </a>
                    @endif

                    @guest
                    @else
                        @if(auth()->user()->role === 'member')
                            <a href="{{ route('riwayat.index') }}" class="btn-blue stagger-btn-2">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                <span>Pinjaman & Booking Saya</span>
                            </a>
                        @elseif(auth()->user()->role === 'petugas')
                            <a href="{{ route('barcode.scan') }}" class="btn-blue stagger-btn-2">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                <span>Meja Sirkulasi (Scan QR)</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn-blue stagger-btn-2">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                <span>Panel Dasbor Admin</span>
                            </a>
                        @endif
                    @endguest
                </div>

                @guest
                    <p class="hero-note slide-in-left hero-item-note">Gratis menjadi anggota · Peminjaman hingga 30 hari</p>
                @else
                    <p class="hero-note slide-in-left hero-item-note">
                        Status akun: <strong>Aktif</strong> · Akses layanan cepat langsung dari beranda
                    </p>
                @endauth
            </div>

            <!-- Hero Image Right -->
            <div class="hero-img-box">
                <div class="hero-img-container">
                    <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=1200&auto=format&fit=crop" 
                         alt="Perpustakaan BOOKNEST"
                         loading="eager"
                         fetchpriority="high"
                         decoding="async">
                </div>
                <div class="hero-img-caption slide-in-left hero-item-note">
                    <strong>12.480+</strong> <span>buku menunggu untuk kamu temukan</span>
                </div>
            </div>
        </section>

        <!-- 2. STATS SECTION -->
        <section class="stats-grid">
            <div class="stat-item">
                <h3 class="counter-number" data-target="12480" data-format="dot">0</h3>
                <p>Koleksi buku</p>
            </div>
            <div class="stat-item">
                <h3 class="counter-number" data-target="3240" data-format="dot">0</h3>
                <p>Anggota aktif</p>
            </div>
            <div class="stat-item">
                <h3 class="counter-number" data-target="7">0</h3>
                <p>Kategori pilihan</p>
            </div>
        </section>

        <!-- 3. KATEGORI BUKU -->
        <section>
            <h2 class="section-title scroll-reveal">Apa yang ingin kamu baca?</h2>
            <div class="categories-grid">
                <a href="{{ route('katalog.index', ['q' => 'Fiksi']) }}" class="cat-card scroll-reveal reveal-delay-1">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <span class="cat-name">Fiksi</span>
                </a>

                <a href="{{ route('katalog.index', ['q' => 'Pendidikan']) }}" class="cat-card scroll-reveal reveal-delay-2">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <span class="cat-name">Pendidikan</span>
                </a>

                <a href="{{ route('katalog.index', ['q' => 'Sejarah']) }}" class="cat-card scroll-reveal reveal-delay-3">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="21" x2="21" y2="21"></line><line x1="6" y1="18" x2="6" y2="7"></line><line x1="10" y1="18" x2="10" y2="7"></line><line x1="14" y1="18" x2="14" y2="7"></line><line x1="18" y1="18" x2="18" y2="7"></line><polygon points="12 2 20 7 4 7"></polygon></svg>
                    </div>
                    <span class="cat-name">Sejarah</span>
                </a>

                <a href="{{ route('katalog.index', ['q' => 'Teknologi']) }}" class="cat-card scroll-reveal reveal-delay-4">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    </div>
                    <span class="cat-name">Teknologi</span>
                </a>

                <a href="{{ route('katalog.index', ['q' => 'Agama']) }}" class="cat-card scroll-reveal reveal-delay-5">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M12 22V12"></path></svg>
                    </div>
                    <span class="cat-name">Agama</span>
                </a>

                <a href="{{ route('katalog.index', ['q' => 'Anak']) }}" class="cat-card scroll-reveal reveal-delay-6">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                    </div>
                    <span class="cat-name">Anak</span>
                </a>

                <a href="{{ route('katalog.index', ['q' => 'Horror']) }}" class="cat-card scroll-reveal reveal-delay-7">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 10h.01"></path>
                            <path d="M15 10h.01"></path>
                            <path d="M12 2a8 8 0 0 0-8 8v12l3-3 2.5 2.5L12 19l2.5 2.5L17 19l3 3V10a8 8 0 0 0-8-8z"></path>
                        </svg>
                    </div>
                    <span class="cat-name">Horror</span>
                </a>
            </div>
        </section>

        <!-- (BAGIAN BUKU POPULER DIKOSONGKAN SEMENTARA SESUAI PERMINTAAN) -->

        <!-- 4. KEUNGGULAN / FITUR -->
        <section class="features-section">
            <div class="features-header scroll-reveal">
                <h2>Perpustakaan, kini lebih dekat</h2>
                <p>Semua kebutuhan membaca dalam satu tempat yang mudah digunakan.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card scroll-reveal reveal-delay-1">
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

                <div class="feature-card scroll-reveal reveal-delay-2">
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

                <div class="feature-card scroll-reveal reveal-delay-3">
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
        <section class="cta-banner scroll-reveal">
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
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Buka Dasbor Utama</span>
                </a>
            @endguest
        </section>
    </main>

    <!-- 6. FOOTER -->
    <footer class="footer scroll-reveal">
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

        // --- Smooth Fade-In on Scroll & Count-Up Animations ---
        (function () {
            function animateCountUp(el) {
                if (!el || el.dataset.counted === 'true') return;
                el.dataset.counted = 'true';

                const target = parseInt(el.getAttribute('data-target'), 10);
                if (isNaN(target)) return;

                const isDotFormat = el.getAttribute('data-format') === 'dot' || target >= 1000;
                const duration = 2800;
                let startTime = null;

                function easeOutCubic(x) {
                    return 1 - Math.pow(1 - x, 3);
                }

                function formatNumber(num) {
                    if (!isDotFormat) return num.toString();
                    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }

                el.textContent = '0';

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    const elapsed = timestamp - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = easeOutCubic(progress);
                    const currentCount = Math.floor(eased * target);

                    el.textContent = formatNumber(currentCount);

                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        el.textContent = formatNumber(target);
                    }
                }

                requestAnimationFrame(step);
            }

            function triggerCountUpAll() {
                document.querySelectorAll('.counter-number').forEach(function (el) {
                    animateCountUp(el);
                });
            }

            function handleScrollRevealFallback() {
                const triggerPoint = window.innerHeight * 0.92;
                const revealElements = document.querySelectorAll('.scroll-reveal:not(.is-revealed)');

                revealElements.forEach(function (el) {
                    const rect = el.getBoundingClientRect();
                    if (rect.top <= triggerPoint && rect.bottom >= 0) {
                        el.classList.add('is-revealed');
                        const counters = el.querySelectorAll('.counter-number');
                        counters.forEach(function (c) { animateCountUp(c); });
                    }
                });
            }

            function init() {
                // Jalankan animasi hitung angka secara harmonis
                triggerCountUpAll();

                if ('IntersectionObserver' in window) {
                    const observerOptions = {
                        root: null,
                        rootMargin: '60px 0px 60px 0px',
                        threshold: 0.02
                    };

                    const observer = new IntersectionObserver(function (entries, obs) {
                        entries.forEach(function (entry) {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('is-revealed');
                                obs.unobserve(entry.target);
                            }
                        });
                    }, observerOptions);

                    document.querySelectorAll('.scroll-reveal').forEach(function (el) {
                        observer.observe(el);
                    });
                } else {
                    window.addEventListener('scroll', handleScrollRevealFallback, { passive: true });
                    window.addEventListener('resize', handleScrollRevealFallback, { passive: true });
                    handleScrollRevealFallback();
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();

        // --- Smooth & Gentle Mouse Wheel Scrolling Engine ---
        (function () {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return;
            }

            let currentY = window.pageYOffset || document.documentElement.scrollTop;
            let targetY = currentY;
            let isRunning = false;

            // Sinkronkan target scroll saat user melakukan scroll via touch, panah keyboard, atau navigasi lain
            function syncScrollPos() {
                if (!isRunning) {
                    currentY = window.pageYOffset || document.documentElement.scrollTop;
                    targetY = currentY;
                }
            }

            window.addEventListener('scroll', syncScrollPos, { passive: true });

            window.addEventListener('wheel', function (e) {
                // Abaikan jika user menahan Ctrl (pinch/zoom) atau Shift (horizontal)
                if (e.ctrlKey || e.shiftKey) return;

                // Abaikan jika kursor berada dalam dropdown atau elemen modal dengan scrollbar sendiri
                let el = e.target;
                while (el && el !== document.body && el !== document.documentElement) {
                    if (el.scrollHeight > el.clientHeight && el.clientHeight > 0) {
                        const style = window.getComputedStyle(el);
                        if (style.overflowY === 'auto' || style.overflowY === 'scroll') {
                            return; // Biarkan kontainer lokal scroll secara alami
                        }
                    }
                    el = el.parentElement;
                }

                // Cegah scroll bawaan yang tersendat atau membutuhkan gerakan kursor di Windows
                e.preventDefault();

                const maxScroll = Math.max(0, document.documentElement.scrollHeight - window.innerHeight);

                // Normalisasi delta mouse wheel (dukung line mode dan pixel mode)
                let delta = e.deltaY;
                if (e.deltaMode === 1) {
                    delta *= 28;
                } else if (e.deltaMode === 2) {
                    delta *= window.innerHeight;
                }

                // Rasio kecepatan putaran roda mouse yang perlahan, stabil, dan nyaman
                const step = Math.sign(delta) * Math.min(Math.abs(delta) * 0.95, 110);
                targetY = Math.min(Math.max(0, targetY + step), maxScroll);

                if (!isRunning) {
                    isRunning = true;
                    currentY = window.pageYOffset || document.documentElement.scrollTop;
                    requestAnimationFrame(updateSmoothScroll);
                }
            }, { passive: false });

            function updateSmoothScroll() {
                if (!isRunning) return;

                const diff = targetY - currentY;
                // Easing factor 0.085: meluncur secara perlahan, sangat lembut, dan responsif
                currentY += diff * 0.085;

                if (Math.abs(diff) < 0.5) {
                    currentY = targetY;
                    window.scrollTo(0, Math.round(currentY));
                    isRunning = false;
                } else {
                    window.scrollTo(0, Math.round(currentY));
                    requestAnimationFrame(updateSmoothScroll);
                }
            }
        })();

        // --- Clean up Intro Curtain after Animation Finishes ---
        (function () {
            const curtain = document.getElementById('introCurtain');
            if (!curtain) return;

            function removeCurtain() {
                if (curtain && curtain.parentNode) {
                    curtain.parentNode.removeChild(curtain);
                }
            }

            curtain.addEventListener('animationend', function (e) {
                if (e.animationName === 'introCurtainWipe') {
                    removeCurtain();
                }
            });

            // Fallback safety timer
            setTimeout(removeCurtain, 2800);
        })();
    </script>

    {{-- Realtime Sirkulasi Notification Modal & Polling --}}
    @include('layouts.partials.member_realtime_notification')
</body>
</html>