<style>
    :root {
        --sidebar-width: 256px;

        /* SINGLE SOURCE OF THEME TOKENS (LIGHT / DEFAULT) */
        --theme-text: #0f172a;
        --theme-heading: #0f172a;
        --theme-muted: #64748b;
        --theme-surface: #ffffff;
        --theme-bg: #f8fafc;
        --theme-border: #e2e8f0;
        --theme-border-subtle: #f1f5f9;
        --theme-accent: #0f766e;
        --theme-accent-hover: #0d5f58;
        --theme-accent-light: rgba(15, 118, 110, 0.12);
        --theme-active-indicator: #0f766e;
        --theme-icon: #0f766e;
        --theme-icon-muted: #64748b;
        --theme-btn-primary-bg: #0f766e;
        --theme-btn-primary-text: #ffffff;
        --theme-btn-primary-hover: #0d5f58;
        --theme-btn-secondary-bg: #ffffff;
        --theme-btn-secondary-border: #cbd5e1;
        --theme-btn-secondary-text: #334155;
        --theme-btn-secondary-hover: #f1f5f9;
        --opening-photo-glow: 0 16px 36px -10px rgba(15, 23, 42, 0.14), 0 6px 14px -4px rgba(15, 23, 42, 0.06);
        --opening-photo-overlay: linear-gradient(135deg, #183d37 0%, #2e625a 100%);
        --opening-photo-border: #2e625a;
    }

    /* --- MAIN LAYOUT WRAPPER (FOR AUTHENTICATED USERS) --- */
    .page-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px 28px 48px;
        display: flex;
        gap: 28px;
        align-items: flex-start;
    }

    /* --- SIDEBAR PANEL (PERSISTENT & ROCK-SOLID CHROME) --- */
    .sidebar {
        width: var(--sidebar-width);
        flex-shrink: 0;
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 20px 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        gap: 20px;
        position: sticky;
        top: 86px;
        z-index: 30;
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
        flex-shrink: 0;
    }

    .sidebar-header-text h3 {
        font-size: 13.5px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        line-height: 1.2;
        letter-spacing: -0.2px;
    }

    .sidebar-header-text span {
        font-size: 11.5px;
        color: var(--text-muted, #64748b);
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
        gap: 4px;
        margin: 0;
        padding: 0;
    }

    /* Staggered entrance for sidebar menu items on page load */
    @keyframes sidebarItemCascade {
        0% {
            opacity: 0;
            transform: translateX(-8px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .sidebar-menu > li {
        animation: sidebarItemCascade 0.32s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    /* Once navigated internally, keep sidebar menu items rock-solid and stable */
    html.bn-navigated .sidebar-menu > li {
        animation: none !important;
        opacity: 1 !important;
        transform: none !important;
    }

    .sidebar-menu > li:nth-child(1) { animation-delay: 0.02s; }
    .sidebar-menu > li:nth-child(2) { animation-delay: 0.05s; }
    .sidebar-menu > li:nth-child(3) { animation-delay: 0.08s; }
    .sidebar-menu > li:nth-child(4) { animation-delay: 0.11s; }
    .sidebar-menu > li:nth-child(5) { animation-delay: 0.14s; }
    .sidebar-menu > li:nth-child(6) { animation-delay: 0.17s; }
    .sidebar-menu > li:nth-child(7) { animation-delay: 0.20s; }
    .sidebar-menu > li:nth-child(8) { animation-delay: 0.23s; }

    /* --- SIDEBAR LINK WITH SMOOTH HOVER & ACCURATE ACTIVE STATE --- */
    .sidebar-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 600;
        color: #475569;
        position: relative;
        text-decoration: none;
        background: transparent;
        transition: background-color 0.18s cubic-bezier(0.16, 1, 0.3, 1),
                    color 0.18s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.18s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        -webkit-tap-highlight-color: transparent;
    }

    .sidebar-link-content {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        transition: transform 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .sidebar-link-content > span:last-child {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar-link-icon {
        width: 19px;
        height: 19px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        flex-shrink: 0;
        transition: color 0.18s ease,
                    transform 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Hover state ONLY for inactive menu items - no indicator bar */
    .sidebar-link:hover:not(.active) {
        background: #f1f5f9;
        color: #0f172a;
        transform: translateX(3px);
    }

    .sidebar-link:hover:not(.active) .sidebar-link-icon {
        color: #0f766e;
        transform: scale(1.1);
    }

    .sidebar-link:active:not(.active) {
        transform: scale(0.98);
        transition: transform 0.08s ease;
    }

    .sidebar-link:focus-visible {
        outline: 2px solid #0f766e;
        outline-offset: 2px;
    }

    /* Active Menu State (Permanently determined by current route) */
    @keyframes activeIndicatorPop {
        0% {
            transform: translateY(-50%) scaleY(0);
            opacity: 0;
        }
        70% {
            transform: translateY(-50%) scaleY(1.15);
            opacity: 1;
        }
        100% {
            transform: translateY(-50%) scaleY(1);
            opacity: 1;
        }
    }

    .sidebar-link.active {
        background: var(--theme-accent-light, #ccfbf1) !important;
        color: var(--theme-accent, #0f766e) !important;
        font-weight: 700;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transform: none !important;
        cursor: default;
    }

    .sidebar-link.active:hover {
        background: var(--theme-accent-light, #ccfbf1) !important;
        color: var(--theme-accent, #0f766e) !important;
        transform: none !important;
    }

    .sidebar-link.active .sidebar-link-icon {
        color: var(--theme-accent, #0f766e) !important;
        transform: scale(1.05);
    }

    .sidebar-link.active .sidebar-link-content {
        transform: none !important;
    }

    /* Only the active route displays the right indicator bar */
    .sidebar-link.active::after {
        content: '';
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%) scaleY(1);
        width: 4px;
        height: 18px;
        background: var(--theme-active-indicator, var(--theme-accent, #0f766e)) !important;
        border-radius: 4px;
        animation: activeIndicatorPop 0.28s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    /* Immediate Click Navigating Feedback */
    .sidebar-link.navigating-active {
        background: var(--theme-accent-light, #e6fffa) !important;
        color: var(--theme-accent, #0f766e) !important;
        transform: translateX(4px) !important;
    }

    .sidebar-link.navigating-active .sidebar-link-icon {
        color: var(--theme-accent, #0f766e) !important;
        transform: scale(1.12) !important;
    }

    /* --- USER CARD AT SIDEBAR BOTTOM --- */
    .sidebar-user {
        margin-top: auto;
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
        background: var(--theme-accent-light, #ccfbf1) !important;
        color: var(--theme-accent, #0f766e) !important;
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
        color: var(--text-heading, #0f172a);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    .sidebar-user-role {
        font-size: 11px;
        color: var(--text-muted, #64748b);
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
        transition: background-color 0.15s ease, color 0.15s ease;
        cursor: pointer;
        background: none;
        border: none;
    }

    .sidebar-user-menu-btn:hover {
        color: var(--text-heading, #0f172a);
        background: #f1f5f9;
    }

    .user-dropdown {
        position: absolute;
        bottom: calc(100% + 8px);
        right: 0;
        width: 190px;
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        padding: 6px;
        z-index: 50;
        opacity: 0;
        visibility: hidden;
        transform: translateY(6px) scale(0.96);
        transform-origin: bottom right;
        transition: opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                    visibility 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }

    .user-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
        pointer-events: auto;
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
        transition: background-color 0.15s ease, color 0.15s ease;
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .user-dropdown-item:hover {
        background: #f8fafc;
        color: var(--text-heading, #0f172a);
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

    /* --- TOPBAR BRAND LOGO (ICON BOX, TEXT & SVG) --- */
    .topbar-brand-icon,
    .member-brand-icon,
    .topbar-logo-icon,
    .logo-icon {
        background: var(--theme-accent, #0f766e) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px var(--theme-accent-shadow, rgba(15, 118, 110, 0.25)) !important;
        transition: background 0.25s ease, box-shadow 0.25s ease, color 0.25s ease !important;
    }

    .topbar-brand-icon svg,
    .member-brand-icon svg,
    .topbar-logo-icon svg,
    .logo-icon svg {
        color: #ffffff !important;
        stroke: currentColor !important;
    }

    .topbar-brand,
    .logo-area {
        color: var(--text-heading, #0f172a) !important;
        transition: color 0.25s ease !important;
    }

    .topbar-brand span,
    .logo-text {
        color: inherit !important;
    }

    /* --- DASHBOARD GREETING & STAT ICONS BASE ENGINE --- */
    .breadcrumb {
        color: var(--theme-accent, #0f766e) !important;
        transition: color 0.25s ease !important;
    }

    .welcome-banner {
        background: var(--theme-accent-light, #ccfbf1);
        border: 1px solid var(--theme-border, #99f6e4);
        transition: background 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease !important;
    }

    .welcome-title {
        color: var(--theme-accent, #0f766e);
        transition: color 0.25s ease !important;
    }

    .welcome-meta {
        color: var(--theme-text, #134e4a);
        transition: color 0.25s ease !important;
    }

    .welcome-desc {
        color: var(--theme-muted, #115e59);
        transition: color 0.25s ease !important;
    }

    .stat-icon:not([style*="color"]) {
        background: var(--theme-accent-light, #ccfbf1);
        color: var(--theme-accent, #0f766e);
        transition: background 0.25s ease, color 0.25s ease !important;
    }

    /* --- TOPBAR USER DROPDOWN (PRIMARY ACCOUNT ACCESS) --- */
    .topbar-user-wrap {
        position: relative;
    }

    .topbar-user-badge {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--theme-accent-light, #ccfbf1) !important;
        color: var(--theme-accent, #0f766e) !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
        border: none;
        cursor: pointer;
        padding: 0;
        user-select: none;
    }

    .topbar-user-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
    }

    .topbar-user-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 260px;
        max-width: calc(100vw - 24px);
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 14px;
        box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.14), 0 4px 12px -2px rgba(15, 23, 42, 0.05);
        padding: 8px;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-6px) scale(0.97);
        transform-origin: top right;
        transition: opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                    visibility 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }

    .topbar-user-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }

    .topbar-dropdown-header {
        padding: 10px 10px 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .topbar-dropdown-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #ccfbf1;
        color: #0f766e;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    .topbar-dropdown-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .topbar-dropdown-user-info {
        min-width: 0;
        flex: 1;
    }

    .topbar-dropdown-user-name {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-heading, #0f172a);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.3;
    }

    .topbar-dropdown-user-email {
        display: block;
        font-size: 11.5px;
        color: var(--text-muted, #64748b);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.3;
    }

    .topbar-dropdown-role-bar {
        padding: 6px 10px;
        margin: 4px 0 6px;
    }

    .topbar-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 9999px;
        letter-spacing: 0.2px;
    }

    .topbar-role-badge--admin {
        background: #f5f3ff;
        color: #6366f1;
        border: 1px solid #e0e7ff;
    }

    .topbar-role-badge--petugas {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .topbar-role-badge--member {
        background: #f0fdf4;
        color: #0f766e;
        border: 1px solid #ccfbf1;
    }

    .topbar-dropdown-body {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .topbar-dropdown-item {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 8px 10px;
        font-size: 12.5px;
        font-weight: 600;
        color: #475569;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.15s ease;
        width: 100%;
        background: none;
        border: none;
        cursor: pointer;
        text-align: left;
        box-sizing: border-box;
    }

    .topbar-dropdown-item:hover {
        background: #f8fafc;
        color: var(--text-heading, #0f172a);
    }

    .topbar-dropdown-item.logout {
        color: #dc2626;
    }

    .topbar-dropdown-item.logout:hover {
        background: #fee2e2;
        color: #b91c1c;
    }

    .topbar-dropdown-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 4px 0;
    }

    /* --- CONTENT AREA & MOBILE OVERLAY --- */
    .content-area {
        flex: 1;
        min-width: 0;
    }

    /* Mobile Backdrop with Smooth Opacity/Visibility Transition */
    .sidebar-backdrop {
        position: fixed !important;
        inset: 0 !important;
        background: rgba(15, 23, 42, 0.45) !important;
        z-index: 9999 !important;
        backdrop-filter: blur(3px) !important;
        -webkit-backdrop-filter: blur(3px) !important;
        cursor: pointer !important;
        touch-action: none;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                    visibility 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .sidebar-backdrop.show {
        opacity: 1 !important;
        visibility: visible !important;
        pointer-events: auto !important;
    }

    .mobile-toggle-btn {
        display: none;
        width: 38px;
        height: 38px;
        border-radius: 8px;
        align-items: center;
        justify-content: center;
        color: #475569;
        border: 1px solid var(--border-color, #e2e8f0);
        background: #ffffff;
        cursor: pointer;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    .mobile-toggle-btn:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .mobile-toggle-btn svg {
        pointer-events: none;
    }

    .sidebar-close-mobile-btn {
        display: none;
        margin-left: auto;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        align-items: center;
        justify-content: center;
        color: #64748b;
        background: #f1f5f9;
        cursor: pointer;
        border: none;
        touch-action: manipulation;
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    .sidebar-close-mobile-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .sidebar-close-mobile-btn svg {
        pointer-events: none;
    }

    @media (min-width: 993px) {
        .sidebar-backdrop {
            display: none !important;
        }
    }

    @media (max-width: 992px) {
        .mobile-toggle-btn {
            display: flex !important;
        }

        .sidebar-close-mobile-btn {
            display: flex !important;
        }

        .page-wrapper {
            padding: 16px;
            gap: 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        @media (max-width: 640px) {
            .page-wrapper {
                padding: 12px 10px;
            }
        }

        .sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            height: 100vh !important;
            height: 100dvh !important;
            width: min(280px, 82vw) !important;
            max-width: 85vw !important;
            background: #ffffff !important;
            z-index: 10000 !important;
            border-radius: 0 16px 16px 0 !important;
            border: 1px solid var(--border-color, #e2e8f0) !important;
            border-left: none !important;
            box-shadow: 4px 0 24px rgba(15, 23, 42, 0.15) !important;
            transform: translateX(-100%) !important;
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1) !important;
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch;
            display: flex !important;
            visibility: visible !important;
            touch-action: pan-y;
            will-change: transform;
        }

        .sidebar.mobile-open {
            transform: translateX(0) !important;
        }
    }

    /* --- NATIVE VIEW TRANSITIONS (CROSS-DOCUMENT MPA IN MODERN CHROMIUM) --- */
    @view-transition {
        navigation: auto;
    }

    /* Keep topbar and sidebar persistent while transitioning the main content */
    .topbar,
    .sidebar,
    #sidebarPanel {
        view-transition-name: none;
    }

    .content-area,
    .page-wrapper > main,
    .catalog-container,
    .about-page {
        view-transition-name: page-main-content;
    }

    ::view-transition-old(page-main-content) {
        animation: 140ms cubic-bezier(0.4, 0, 1, 1) both pageFadeSlideOut;
    }

    ::view-transition-new(page-main-content) {
        animation: 240ms cubic-bezier(0, 0, 0.2, 1) both pageFadeSlideIn;
    }

    /* Fallback root animation if element-level transition is unavailable */
    ::view-transition-old(root) {
        animation: 140ms cubic-bezier(0.4, 0, 1, 1) both pageFadeSlideOut;
    }

    ::view-transition-new(root) {
        animation: 220ms cubic-bezier(0, 0, 0.2, 1) both pageFadeSlideIn;
    }

    /* --- PAGE CONTENT TRANSITION KEYFRAMES --- */
    @keyframes pageFadeSlideIn {
        0% {
            opacity: 0;
            transform: translateY(12px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pageFadeSlideOut {
        0% {
            opacity: 1;
            transform: translateY(0);
        }
        100% {
            opacity: 0;
            transform: translateY(-8px);
        }
    }

    .content-area,
    .page-wrapper > main,
    .catalog-container,
    .about-page {
        animation: pageFadeSlideIn 0.26s cubic-bezier(0.16, 1, 0.3, 1) both;
        will-change: transform, opacity;
    }

    /* Exit transition state during sidebar navigation */
    .page-transitioning-out {
        opacity: 0 !important;
        transform: translateY(-8px) scale(0.996) !important;
        transition: opacity 0.14s cubic-bezier(0.4, 0, 0.2, 1),
                    transform 0.14s cubic-bezier(0.4, 0, 0.2, 1) !important;
        pointer-events: none !important;
    }

    /* Top Page Navigation Progress Indicator */
    #pageProgressIndicator {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        width: 0%;
        background: linear-gradient(90deg, #0f766e, #14b8a6, #2dd4bf);
        box-shadow: 0 0 10px rgba(20, 184, 166, 0.5);
        z-index: 99999;
        pointer-events: none;
        transition: width 0.2s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.2s ease-out;
    }

    /* --- ACCESSIBILITY: PREFERS-REDUCED-MOTION --- */
    @media (prefers-reduced-motion: reduce) {
        .content-area,
        .page-wrapper > main,
        .catalog-container,
        .about-page {
            animation: none !important;
            transform: none !important;
        }

        .page-transitioning-out {
            transition: none !important;
            transform: none !important;
            opacity: 1 !important;
        }

        .sidebar {
            transition: none !important;
        }

        .sidebar-link,
        .sidebar-link-content,
        .sidebar-link-icon,
        .sidebar-backdrop,
        #pageProgressIndicator,
        .user-dropdown {
            transition: none !important;
        }
    }

    /* ========================================================
       DARK THEME ENGINE (MODERN & EYE-PLEASING SLATE PALETTE)
       ======================================================== */
    [data-theme="dark"] {
        --theme-text: #f8fafc;
        --theme-heading: #f8fafc;
        --theme-muted: #94a3b8;
        --theme-surface: #141f33;
        --theme-bg: #0b1120;
        --theme-border: #23314d;
        --theme-border-subtle: #1e293b;
        --theme-accent: #14b8a6;
        --theme-accent-hover: #2dd4bf;
        --theme-accent-light: rgba(20, 184, 166, 0.16);
        --theme-active-indicator: #14b8a6;
        --theme-icon: #2dd4bf;
        --theme-icon-muted: #94a3b8;
        --theme-btn-primary-bg: #0f766e;
        --theme-btn-primary-text: #ffffff;
        --theme-btn-primary-hover: #14b8a6;
        --theme-btn-secondary-bg: #1e293b;
        --theme-btn-secondary-border: #334155;
        --theme-btn-secondary-text: #f1f5f9;
        --theme-btn-secondary-hover: #334155;
        --opening-photo-glow: 0 16px 36px -10px rgba(0, 0, 0, 0.65), 0 0 20px rgba(20, 184, 166, 0.12);
        --opening-photo-overlay: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(4, 47, 46, 0.6) 100%);
        --opening-photo-border: #14b8a6;

        --brand-primary: #14b8a6;
        --brand-primary-dark: #0d9488;
        --brand-primary-light: rgba(20, 184, 166, 0.16);
        --brand-accent: #2dd4bf;
        --text-heading: #f8fafc;
        --text-body: #cbd5e1;
        --text-muted: #94a3b8;
        --border-color: #334155;
        --bg-page: #0b1120;
        --bg-card: #1e293b;
    }

    [data-theme="dark"] body {
        background-color: var(--bg-page) !important;
        color: var(--text-body) !important;
    }

    [data-theme="dark"] .topbar {
        background: #0f172a !important;
        border-color: #1e293b !important;
    }

    [data-theme="dark"] .topbar-brand,
    [data-theme="dark"] .logo-area,
    [data-theme="dark"] .topbar-brand span,
    [data-theme="dark"] .logo-text {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .topbar-brand-icon,
    [data-theme="dark"] .member-brand-icon,
    [data-theme="dark"] .topbar-logo-icon,
    [data-theme="dark"] .logo-icon {
        background: #10b981 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 10px rgba(16, 185, 129, 0.4) !important;
    }

    [data-theme="dark"] .topbar-brand-icon svg,
    [data-theme="dark"] .member-brand-icon svg,
    [data-theme="dark"] .topbar-logo-icon svg,
    [data-theme="dark"] .logo-icon svg {
        color: #ffffff !important;
    }

    [data-theme="dark"] .topbar-nav-link {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .topbar-nav-link:hover {
        background: #1e293b !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .topbar-nav-link.active {
        background: rgba(20, 184, 166, 0.16) !important;
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .topbar-icon-btn {
        color: #94a3b8 !important;
        border-color: #334155 !important;
    }

    [data-theme="dark"] .topbar-icon-btn:hover {
        background: #1e293b !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .topbar-user-badge {
        background: #042f2e !important;
        color: #2dd4bf !important;
        border-color: #115e59 !important;
    }

    [data-theme="dark"] .topbar-user-dropdown,
    [data-theme="dark"] .user-dropdown {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.5) !important;
    }

    [data-theme="dark"] .topbar-dropdown-header {
        background: #0f172a !important;
        border-color: #334155 !important;
    }

    [data-theme="dark"] .topbar-dropdown-user-name {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .topbar-dropdown-user-email {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .topbar-dropdown-item,
    [data-theme="dark"] .user-dropdown-item {
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .topbar-dropdown-item:hover,
    [data-theme="dark"] .user-dropdown-item:hover {
        background: #334155 !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .topbar-dropdown-divider,
    [data-theme="dark"] .user-dropdown-divider {
        background: #334155 !important;
    }

    [data-theme="dark"] .sidebar {
        background: #131d31 !important;
        border-color: #23314d !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="dark"] .sidebar-header {
        border-color: #23314d !important;
    }

    [data-theme="dark"] .sidebar-header-icon {
        background: #10b981 !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .sidebar-header-text h3 {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .sidebar-header-text span {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .sidebar-section-title {
        color: #64748b !important;
    }

    [data-theme="dark"] .sidebar-link {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .sidebar-link:hover {
        background: #1e293b !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .sidebar-link.active {
        background: rgba(20, 184, 166, 0.16) !important;
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .sidebar-link-icon {
        color: inherit !important;
    }

    [data-theme="dark"] .sidebar-user {
        background: transparent !important;
        border-top: 1px solid #23314d !important;
    }

    [data-theme="dark"] .sidebar-user-name {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .sidebar-user-role {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .sidebar-user-menu-btn {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .sidebar-user-menu-btn:hover {
        background: #1e293b !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .sidebar-avatar {
        background: rgba(20, 184, 166, 0.16) !important;
        color: #2dd4bf !important;
        border-color: rgba(20, 184, 166, 0.3) !important;
    }

    [data-theme="dark"] .stat-box,
    [data-theme="dark"] .stat-card,
    [data-theme="dark"] .summary-card,
    [data-theme="dark"] .profile-card-section,
    [data-theme="dark"] .card,
    [data-theme="dark"] .data-card,
    [data-theme="dark"] .table-card,
    [data-theme="dark"] .card-panel,
    [data-theme="dark"] .manage-card,
    [data-theme="dark"] .monthly-report-card,
    [data-theme="dark"] .report-card,
    [data-theme="dark"] .riwayat-card,
    [data-theme="dark"] .loan-card,
    [data-theme="dark"] .synopsis-card,
    [data-theme="dark"] .spec-card,
    [data-theme="dark"] .about-card,
    [data-theme="dark"] .related-card,
    [data-theme="dark"] .option-card,
    [data-theme="dark"] .edit-card,
    [data-theme="dark"] .filter-card,
    [data-theme="dark"] .book-meta-box,
    [data-theme="dark"] .tray-box,
    [data-theme="dark"] .tray-row,
    [data-theme="dark"] .rekomendasi-card,
    [data-theme="dark"] .universal-search-card,
    [data-theme="dark"] .booking-popup-card,
    [data-theme="dark"] .member-realtime-modal,
    [data-theme="dark"] .qris-card,
    [data-theme="dark"] .rincian-card-box,
    [data-theme="dark"] .sukses-card,
    [data-theme="dark"] .ticket-qr-card,
    [data-theme="dark"] .ticket-detail-card,
    [data-theme="dark"] .cart-items-card,
    [data-theme="dark"] .checkout-card,
    [data-theme="dark"] .main-card,
    [data-theme="dark"] .modal-card,
    [data-theme="dark"] .receipt-card,
    [data-theme="dark"] .summary-rule-card,
    [data-theme="dark"] .term-article-card,
    [data-theme="dark"] .step-card,
    [data-theme="dark"] .feature-card,
    [data-theme="dark"] .faq-item,
    [data-theme="dark"] .form-card,
    [data-theme="dark"] .cta-guide-card,
    [data-theme="dark"] .about-hero,
    [data-theme="dark"] .about-values,
    [data-theme="dark"] .about-value-card,
    [data-theme="dark"] .about-info-card,
    [data-theme="dark"] .about-membership,
    [data-theme="dark"] .principle-card,
    [data-theme="dark"] .policy-card,
    [data-theme="dark"] .figma-table-container,
    [data-theme="dark"] .rincian-card {
        background: #131d31 !important;
        border-color: #23314d !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
    }

    [data-theme="dark"] .section-header-title,
    [data-theme="dark"] .dashboard-title,
    [data-theme="dark"] .profile-title,
    [data-theme="dark"] .summary-name,
    [data-theme="dark"] .page-title,
    [data-theme="dark"] .section-title,
    [data-theme="dark"] .section-heading-title,
    [data-theme="dark"] .report-card-title,
    [data-theme="dark"] .manage-title,
    [data-theme="dark"] .riwayat-card-title,
    [data-theme="dark"] .welcome-title,
    [data-theme="dark"] .denda-alert-title,
    [data-theme="dark"] .loan-title,
    [data-theme="dark"] .loan-card-title,
    [data-theme="dark"] .option-title,
    [data-theme="dark"] .book-showcase-title,
    [data-theme="dark"] .meta-val,
    [data-theme="dark"] .tray-title {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .form-label,
    [data-theme="dark"] .filter-label,
    [data-theme="dark"] .table-search-label,
    [data-theme="dark"] .presets-label {
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-control-select,
    [data-theme="dark"] .form-control-date,
    [data-theme="dark"] .form-input,
    [data-theme="dark"] .form-select,
    [data-theme="dark"] .form-textarea,
    [data-theme="dark"] .filter-input,
    [data-theme="dark"] .search-input,
    [data-theme="dark"] .search-input-field,
    [data-theme="dark"] .search-book-input,
    [data-theme="dark"] .filter-select {
        background: #0b1220 !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .form-control:focus,
    [data-theme="dark"] .form-control-select:focus,
    [data-theme="dark"] .form-control-date:focus,
    [data-theme="dark"] .form-input:focus,
    [data-theme="dark"] .form-select:focus,
    [data-theme="dark"] .form-textarea:focus,
    [data-theme="dark"] .filter-input:focus,
    [data-theme="dark"] .search-input:focus,
    [data-theme="dark"] .search-input-field:focus,
    [data-theme="dark"] .search-book-input:focus,
    [data-theme="dark"] .filter-select:focus {
        border-color: #14b8a6 !important;
        box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.2) !important;
    }

    [data-theme="dark"] .search-input::placeholder,
    [data-theme="dark"] .search-input-field::placeholder,
    [data-theme="dark"] .form-input::placeholder,
    [data-theme="dark"] .filter-input::placeholder,
    [data-theme="dark"] .form-control::placeholder {
        color: #64748b !important;
        opacity: 1 !important;
    }

    [data-theme="dark"] .table-search-input-wrap,
    [data-theme="dark"] .search-input-wrapper,
    [data-theme="dark"] .search-input-wrap,
    [data-theme="dark"] .filter-input-wrap {
        background: #0b1220 !important;
        border-color: #334155 !important;
    }

    [data-theme="dark"] .table-search-input-wrap input {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .table-search-input-wrap svg,
    [data-theme="dark"] .search-input-wrap svg,
    [data-theme="dark"] .filter-input-wrap svg,
    [data-theme="dark"] .filter-icon {
        color: #64748b !important;
    }

    [data-theme="dark"] select option {
        background-color: #0b1220 !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .form-control.is-readonly {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .privacy-card,
    [data-theme="dark"] .welcome-banner,
    [data-theme="dark"] .denda-alert-banner,
    [data-theme="dark"] .notice-card,
    [data-theme="dark"] .location-card,
    [data-theme="dark"] .dialog-location {
        background: #042f2e !important;
        border-color: #115e59 !important;
    }

    [data-theme="dark"] .privacy-title,
    [data-theme="dark"] .welcome-title,
    [data-theme="dark"] .denda-alert-title,
    [data-theme="dark"] .notice-title {
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .privacy-desc,
    [data-theme="dark"] .welcome-desc,
    [data-theme="dark"] .welcome-meta,
    [data-theme="dark"] .denda-alert-text,
    [data-theme="dark"] .notice-desc,
    [data-theme="dark"] .dialog-location span {
        color: #99f6e4 !important;
    }

    [data-theme="dark"] .restock-alert-banner {
        background: rgba(120, 53, 15, 0.28) !important;
        border-color: #92400e !important;
    }

    [data-theme="dark"] .restock-alert-title {
        color: #fde68a !important;
    }

    [data-theme="dark"] .restock-alert-desc {
        color: #fcd34d !important;
    }

    [data-theme="dark"] .btn-restock-view {
        background: #b45309 !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .book-showcase {
        background: #0d1527 !important;
        border-color: #23314d !important;
    }

    [data-theme="dark"] .summary-meta-table {
        border-color: #23314d !important;
    }

    [data-theme="dark"] .summary-meta-label {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .summary-meta-value {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .notif-checkbox-label {
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .checkbox-mark {
        background-color: #0b1220 !important;
        border-color: #475569 !important;
    }

    /* Table Foundations */
    [data-theme="dark"] .table-responsive,
    [data-theme="dark"] table,
    [data-theme="dark"] .custom-table,
    [data-theme="dark"] .report-table,
    [data-theme="dark"] .riwayat-table,
    [data-theme="dark"] .sirkulasi-table,
    [data-theme="dark"] .buku-table,
    [data-theme="dark"] .denda-table,
    [data-theme="dark"] .kategori-table,
    [data-theme="dark"] .figma-table,
    [data-theme="dark"] .eksemplar-subtable,
    [data-theme="dark"] .detail-table {
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] table th,
    [data-theme="dark"] .custom-table th,
    [data-theme="dark"] .report-table th,
    [data-theme="dark"] .riwayat-table th,
    [data-theme="dark"] .sirkulasi-table th,
    [data-theme="dark"] .buku-table th,
    [data-theme="dark"] .denda-table th,
    [data-theme="dark"] .kategori-table th,
    [data-theme="dark"] .figma-table th,
    [data-theme="dark"] .eksemplar-subtable th {
        background-color: #0b1220 !important;
        color: #94a3b8 !important;
        border-color: #23314d !important;
    }

    [data-theme="dark"] table td,
    [data-theme="dark"] .custom-table td,
    [data-theme="dark"] .report-table td,
    [data-theme="dark"] .riwayat-table td,
    [data-theme="dark"] .sirkulasi-table td,
    [data-theme="dark"] .buku-table td,
    [data-theme="dark"] .denda-table td,
    [data-theme="dark"] .kategori-table td,
    [data-theme="dark"] .figma-table td,
    [data-theme="dark"] .eksemplar-subtable td {
        border-color: #23314d !important;
        color: #cbd5e1 !important;
        background-color: transparent !important;
    }

    /* Table Hover States */
    [data-theme="dark"] tr:hover td,
    [data-theme="dark"] tbody tr:hover td,
    [data-theme="dark"] .custom-table tr:hover td,
    [data-theme="dark"] .report-table tbody tr:hover td,
    [data-theme="dark"] .riwayat-table tbody tr:hover td,
    [data-theme="dark"] .sirkulasi-table tbody tr:hover td,
    [data-theme="dark"] .sirkulasi-table tr:hover td,
    [data-theme="dark"] .denda-table tbody tr:hover td,
    [data-theme="dark"] .buku-table tbody tr:hover td,
    [data-theme="dark"] .kategori-table tr:hover,
    [data-theme="dark"] .figma-table tr.clickable-row:hover,
    [data-theme="dark"] .eksemplar-subtable tr:hover td,
    [data-theme="dark"] .main-book-row:hover td {
        background-color: #172339 !important;
    }

    [data-theme="dark"] .main-book-row.expanded-parent td {
        background-color: #0d1e2e !important;
        border-bottom-color: #115e59 !important;
    }

    [data-theme="dark"] .figma-table tr.active-selected-row {
        background-color: rgba(20, 184, 166, 0.14) !important;
    }

    /* Detail Rows in Member Confirmation */
    [data-theme="dark"] .detail-row {
        background: #131d31 !important;
        border-color: #23314d !important;
    }

    [data-theme="dark"] .detail-row:nth-child(even) {
        background: #0d1527 !important;
    }

    [data-theme="dark"] .detail-key {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .detail-val {
        color: #f8fafc !important;
    }

    /* Member & Entity Identity in Tables & Pages */
    [data-theme="dark"] .member-name,
    [data-theme="dark"] .user-meta-name,
    [data-theme="dark"] .book-list-item,
    [data-theme="dark"] .sirkulasi-heading,
    [data-theme="dark"] .book-title-cell,
    [data-theme="dark"] .book-meta-title,
    [data-theme="dark"] .card-title,
    [data-theme="dark"] .table-toolbar-title,
    [data-theme="dark"] .eksemplar-panel-title,
    [data-theme="dark"] .card-block-title,
    [data-theme="dark"] .qris-title-center,
    [data-theme="dark"] .qris-main-heading,
    [data-theme="dark"] .card-heading-title,
    [data-theme="dark"] .denda-main-heading,
    [data-theme="dark"] .section-headline,
    [data-theme="dark"] .td-judul-buku,
    [data-theme="dark"] .user-name,
    [data-theme="dark"] .trx-id,
    [data-theme="dark"] .dialog-header h2,
    [data-theme="dark"] .dialog-location strong,
    [data-theme="dark"] .location-card h3,
    [data-theme="dark"] .dashboard-title,
    [data-theme="dark"] .page-title,
    [data-theme="dark"] .circ-title,
    [data-theme="dark"] .peminjaman-title,
    [data-theme="dark"] .pengembalian-title,
    [data-theme="dark"] .berhasil-title,
    [data-theme="dark"] .status-heading,
    [data-theme="dark"] .status-banner-title,
    [data-theme="dark"] .card-title-lg,
    [data-theme="dark"] .card-title-md,
    [data-theme="dark"] .card-title-xl,
    [data-theme="dark"] .panel-title,
    [data-theme="dark"] .nota-main-heading,
    [data-theme="dark"] .receipt-section-title,
    [data-theme="dark"] .receipt-brand-name,
    [data-theme="dark"] .r-val,
    [data-theme="dark"] .cara-title,
    [data-theme="dark"] .cart-title,
    [data-theme="dark"] .cart-item-title,
    [data-theme="dark"] .cart-empty-title,
    [data-theme="dark"] .checkout-title,
    [data-theme="dark"] .ticket-page-title,
    [data-theme="dark"] .detail-card-title,
    [data-theme="dark"] .book-item-title,
    [data-theme="dark"] .book-title-main,
    [data-theme="dark"] .rekomendasi-judul,
    [data-theme="dark"] .summary-name,
    [data-theme="dark"] .summary-meta-value,
    [data-theme="dark"] .welcome-title,
    [data-theme="dark"] .report-card-title,
    [data-theme="dark"] .manage-title,
    [data-theme="dark"] .section-title,
    [data-theme="dark"] .section-heading-title,
    [data-theme="dark"] .petugas-meta-name,
    [data-theme="dark"] .activity-member,
    [data-theme="dark"] .member-meta h4,
    [data-theme="dark"] .stat-pill .val,
    [data-theme="dark"] .card-bottom-title,
    [data-theme="dark"] .denda-alert-title,
    [data-theme="dark"] .booking-code-val,
    [data-theme="dark"] .info-value,
    [data-theme="dark"] .qr-title,
    [data-theme="dark"] .restock-alert-title,
    [data-theme="dark"] .member-contact-phone,
    [data-theme="dark"] .custom-table td strong {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .member-contact-phone svg {
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .member-email,
    [data-theme="dark"] .user-meta-sub,
    [data-theme="dark"] .sirkulasi-subtitle,
    [data-theme="dark"] .book-meta-author,
    [data-theme="dark"] .status-subtext,
    [data-theme="dark"] .stat-note,
    [data-theme="dark"] .stat-caption,
    [data-theme="dark"] .stat-label,
    [data-theme="dark"] .section-meta-text,
    [data-theme="dark"] .denda-main-subtitle,
    [data-theme="dark"] .qris-main-subtitle,
    [data-theme="dark"] .qris-expiry-info,
    [data-theme="dark"] .borrower-subtext,
    [data-theme="dark"] .copies-breakdown-subtext,
    [data-theme="dark"] .td-kode,
    [data-theme="dark"] .page-subtitle,
    [data-theme="dark"] .dashboard-subtitle,
    [data-theme="dark"] .section-subtitle,
    [data-theme="dark"] .manage-subtitle,
    [data-theme="dark"] .report-card-desc,
    [data-theme="dark"] .book-showcase-author,
    [data-theme="dark"] .option-desc,
    [data-theme="dark"] .synopsis-content,
    [data-theme="dark"] .loan-subtitle,
    [data-theme="dark"] .dialog-steps,
    [data-theme="dark"] .location-card p,
    [data-theme="dark"] .circ-subtitle,
    [data-theme="dark"] .berhasil-subtitle,
    [data-theme="dark"] .peminjaman-desc,
    [data-theme="dark"] .pengembalian-desc,
    [data-theme="dark"] .nota-main-subtitle,
    [data-theme="dark"] .receipt-brand-slogan,
    [data-theme="dark"] .receipt-brand-address,
    [data-theme="dark"] .receipt-brand-contact,
    [data-theme="dark"] .r-label,
    [data-theme="dark"] .kv-label,
    [data-theme="dark"] .cara-list,
    [data-theme="dark"] .cart-subtitle,
    [data-theme="dark"] .cart-item-author,
    [data-theme="dark"] .cart-empty-desc,
    [data-theme="dark"] .ticket-page-subtitle,
    [data-theme="dark"] .qr-instruction,
    [data-theme="dark"] .book-item-author,
    [data-theme="dark"] .book-author-main,
    [data-theme="dark"] .rekomendasi-author,
    [data-theme="dark"] .rekomendasi-meta,
    [data-theme="dark"] .summary-code,
    [data-theme="dark"] .summary-meta-label,
    [data-theme="dark"] .welcome-meta,
    [data-theme="dark"] .welcome-desc,
    [data-theme="dark"] .pengingat-body,
    [data-theme="dark"] .activity-right,
    [data-theme="dark"] .period-badge,
    [data-theme="dark"] .form-label,
    [data-theme="dark"] .table-search-label,
    [data-theme="dark"] .receipt-row,
    [data-theme="dark"] .scanner-placeholder,
    [data-theme="dark"] .member-meta span,
    [data-theme="dark"] .stat-pill .lbl,
    [data-theme="dark"] .circ-tab-btn,
    [data-theme="dark"] .status-desc,
    [data-theme="dark"] .status-banner-desc,
    [data-theme="dark"] .info-label,
    [data-theme="dark"] .qr-description,
    [data-theme="dark"] .restock-alert-desc,
    [data-theme="dark"] .pickup-deadline-box,
    [data-theme="dark"] .activity-left,
    [data-theme="dark"] .meta-label,
    [data-theme="dark"] .tag-rak,
    [data-theme="dark"] .btn-cancel,
    [data-theme="dark"] .btn-reset,
    [data-theme="dark"] .member-contact-address {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .meta-row {
        border-bottom-color: #23314d !important;
    }

    [data-theme="dark"] .member-avatar {
        background: #032b43 !important;
        color: #38bdf8 !important;
        border: 1px solid #075985 !important;
    }

    /* Invoice Tags & Identification Badges */
    [data-theme="dark"] .invoice-tag,
    [data-theme="dark"] .trx-code-badge,
    [data-theme="dark"] .copy-id-badge,
    [data-theme="dark"] .eks-badge,
    [data-theme="dark"] .tag-chip {
        background: #0b1220 !important;
        color: #38bdf8 !important;
        border: 1px solid #334155 !important;
    }

    [data-theme="dark"] .copy-number-tag {
        background: rgba(59, 130, 246, 0.16) !important;
        color: #93c5fd !important;
        border-color: rgba(59, 130, 246, 0.3) !important;
    }

    /* Nominal Denda & Money Values */
    [data-theme="dark"] .price-value.danger {
        color: #f87171 !important;
    }

    [data-theme="dark"] .price-value.success {
        color: #4ade80 !important;
    }

    [data-theme="dark"] .stat-number,
    [data-theme="dark"] .stat-value,
    [data-theme="dark"] .stat-val,
    [data-theme="dark"] .bayar-total-nominal,
    [data-theme="dark"] .qris-amount-large,
    [data-theme="dark"] .kv-val {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .kv-val.mono,
    [data-theme="dark"] .rincian-value.mono {
        color: #38bdf8 !important;
    }

    /* Status Badges */
    [data-theme="dark"] .badge-status-denda.lunas,
    [data-theme="dark"] .badge-lunas,
    [data-theme="dark"] .status-badge.tersedia,
    [data-theme="dark"] .condition-badge.baik,
    [data-theme="dark"] .badge-baik,
    [data-theme="dark"] .badge-status-tersedia,
    [data-theme="dark"] .badge-success,
    [data-theme="dark"] .pill-badge-green {
        background: rgba(34, 197, 94, 0.16) !important;
        color: #4ade80 !important;
        border-color: rgba(34, 197, 94, 0.3) !important;
    }

    [data-theme="dark"] .badge-status-denda.belum,
    [data-theme="dark"] .badge-belum,
    [data-theme="dark"] .badge-menunggu,
    [data-theme="dark"] .status-badge.dibooking,
    [data-theme="dark"] .condition-badge.rusak,
    [data-theme="dark"] .tag-perlu-restock,
    [data-theme="dark"] .badge-rusak,
    [data-theme="dark"] .badge-status-dibooking,
    [data-theme="dark"] .badge-warning,
    [data-theme="dark"] .pill-badge-amber {
        background: rgba(245, 158, 11, 0.16) !important;
        color: #fbbf24 !important;
        border-color: rgba(245, 158, 11, 0.3) !important;
    }

    [data-theme="dark"] .status-badge.hilang,
    [data-theme="dark"] .condition-badge.hilang,
    [data-theme="dark"] .badge-hilang,
    [data-theme="dark"] .badge-status-hilang,
    [data-theme="dark"] .badge-danger,
    [data-theme="dark"] .badge-status-pill.terlambat,
    [data-theme="dark"] .pill-badge-red {
        background: rgba(239, 68, 68, 0.16) !important;
        color: #f87171 !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
    }

    [data-theme="dark"] .status-badge.dipinjam,
    [data-theme="dark"] .copies-summary-pill,
    [data-theme="dark"] .badge-info,
    [data-theme="dark"] .badge-status-dipinjam,
    [data-theme="dark"] .badge-status-pill.dipinjam,
    [data-theme="dark"] .pill-badge-teal,
    [data-theme="dark"] .pill-badge-blue {
        background: rgba(59, 130, 246, 0.16) !important;
        color: #93c5fd !important;
        border-color: rgba(59, 130, 246, 0.3) !important;
    }

    [data-theme="dark"] .badge-category,
    [data-theme="dark"] .badge-buku-count,
    [data-theme="dark"] .count-badge {
        background: rgba(20, 184, 166, 0.16) !important;
        color: #2dd4bf !important;
        border-color: rgba(20, 184, 166, 0.3) !important;
    }

    [data-theme="dark"] .badge-neutral {
        background: #1e293b !important;
        color: #cbd5e1 !important;
        border-color: #334155 !important;
    }

    [data-theme="dark"] .lokasi-badge.rak,
    [data-theme="dark"] .badge-rak {
        background: rgba(34, 197, 94, 0.16) !important;
        color: #4ade80 !important;
        border-color: rgba(34, 197, 94, 0.3) !important;
    }

    [data-theme="dark"] .lokasi-badge.gudang,
    [data-theme="dark"] .meta-tag.gudang,
    [data-theme="dark"] .badge-gudang {
        background: rgba(148, 163, 184, 0.14) !important;
        color: #cbd5e1 !important;
        border-color: rgba(148, 163, 184, 0.25) !important;
    }

    /* Stat Box Icons */
    [data-theme="dark"] .stat-icon-amber,
    [data-theme="dark"] .stat-icon.amber,
    [data-theme="dark"] .stat-icon--amber {
        background: rgba(245, 158, 11, 0.16) !important;
        color: #fbbf24 !important;
    }

    [data-theme="dark"] .stat-icon-teal,
    [data-theme="dark"] .stat-icon.primary,
    [data-theme="dark"] .stat-icon--teal {
        background: rgba(20, 184, 166, 0.16) !important;
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .stat-icon-emerald,
    [data-theme="dark"] .stat-icon.success,
    [data-theme="dark"] .stat-icon.emerald,
    [data-theme="dark"] .stat-icon--emerald {
        background: rgba(34, 197, 94, 0.16) !important;
        color: #4ade80 !important;
    }

    [data-theme="dark"] .stat-icon-blue,
    [data-theme="dark"] .stat-icon--blue {
        background: rgba(59, 130, 246, 0.16) !important;
        color: #60a5fa !important;
    }

    [data-theme="dark"] .stat-icon-rose,
    [data-theme="dark"] .stat-icon.danger {
        background: rgba(239, 68, 68, 0.16) !important;
        color: #f87171 !important;
    }

    [data-theme="dark"] .stat-icon.purple {
        background: rgba(168, 85, 247, 0.16) !important;
        color: #c084fc !important;
    }

    /* Filter Cards, Search & Form Controls */
    [data-theme="dark"] .filter-card,
    [data-theme="dark"] .table-toolbar-header,
    [data-theme="dark"] .report-card-header,
    [data-theme="dark"] .riwayat-card-header,
    [data-theme="dark"] .loan-card-header,
    [data-theme="dark"] .dialog-header {
        background: #131d31 !important;
        border-color: #23314d !important;
    }

    [data-theme="dark"] .filter-tabs-bar,
    [data-theme="dark"] .nav-pills-sirkulasi {
        background: #0b1220 !important;
        border: 1px solid #23314d !important;
    }

    [data-theme="dark"] .nav-pill-btn,
    [data-theme="dark"] .filter-tab-btn {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .nav-pill-btn:hover,
    [data-theme="dark"] .filter-tab-btn:hover {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .nav-pill-btn.active,
    [data-theme="dark"] .filter-tab-btn.active {
        background: #1e293b !important;
        color: #2dd4bf !important;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.4) !important;
    }

    [data-theme="dark"] .filter-badge-count {
        background: #1e293b !important;
        color: #94a3b8 !important;
    }

    /* Buttons: Secondary, Reset, Outline */
    [data-theme="dark"] .btn-filter-reset,
    [data-theme="dark"] .btn-reset,
    [data-theme="dark"] .btn-reset-filter,
    [data-theme="dark"] .btn-action-outline,
    [data-theme="dark"] .btn-action-secondary,
    [data-theme="dark"] .btn-back-detail,
    [data-theme="dark"] .btn-back-dasbor,
    [data-theme="dark"] .btn-dialog-cancel {
        background: #1e293b !important;
        color: #cbd5e1 !important;
        border-color: #334155 !important;
    }

    [data-theme="dark"] .btn-filter-reset:hover,
    [data-theme="dark"] .btn-reset:hover,
    [data-theme="dark"] .btn-reset-filter:hover,
    [data-theme="dark"] .btn-action-outline:hover,
    [data-theme="dark"] .btn-action-secondary:hover,
    [data-theme="dark"] .btn-back-detail:hover,
    [data-theme="dark"] .btn-back-dasbor:hover,
    [data-theme="dark"] .btn-dialog-cancel:hover {
        background: #334155 !important;
        color: #f8fafc !important;
        border-color: #475569 !important;
    }

    [data-theme="dark"] .btn-toggle-all {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .btn-toggle-all:hover {
        background: #334155 !important;
        color: #f8fafc !important;
        border-color: #475569 !important;
    }

    [data-theme="dark"] .btn-toggle-all.active {
        background: rgba(20, 184, 166, 0.16) !important;
        border-color: #14b8a6 !important;
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .preset-btn {
        background: #0b1220 !important;
        border-color: #334155 !important;
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .preset-btn:hover {
        background: #1e293b !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .preset-btn.active {
        background: #042f2e !important;
        border-color: #115e59 !important;
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .period-badge {
        background: #0b1220 !important;
        border-color: #23314d !important;
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .btn-print-action {
        background: #1e293b !important;
        color: #f8fafc !important;
        border: 1px solid #334155 !important;
    }

    [data-theme="dark"] .btn-edit-action {
        background: rgba(20, 184, 166, 0.16) !important;
        color: #2dd4bf !important;
        border-color: rgba(20, 184, 166, 0.3) !important;
    }

    [data-theme="dark"] .btn-delete-action {
        background: rgba(239, 68, 68, 0.16) !important;
        color: #f87171 !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
    }

    /* Option Cards in Peminjaman */
    [data-theme="dark"] .option-card {
        background: #0f172a !important;
        border-color: #23314d !important;
    }

    [data-theme="dark"] .option-card:hover {
        background: #172339 !important;
        border-color: #14b8a6 !important;
    }

    [data-theme="dark"] .option-card:has(input[type="radio"]:checked) {
        background: #042f2e !important;
        border-color: #14b8a6 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25) !important;
    }

    /* Alerts and Messages */
    [data-theme="dark"] .alert-banner.success,
    [data-theme="dark"] .alert-aman {
        background: rgba(34, 197, 94, 0.14) !important;
        border-color: rgba(34, 197, 94, 0.3) !important;
        color: #86efac !important;
    }

    [data-theme="dark"] .alert-banner.error,
    [data-theme="dark"] .alert-biaya,
    [data-theme="dark"] .loan-alert-error {
        background: rgba(239, 68, 68, 0.14) !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
        color: #fca5a5 !important;
    }

    [data-theme="dark"] .loan-alert-warning {
        background: rgba(245, 158, 11, 0.14) !important;
        border-color: rgba(245, 158, 11, 0.3) !important;
        color: #fde68a !important;
    }

    [data-theme="dark"] div[style*="background: #dcfce7"],
    [data-theme="dark"] div[style*="background:#dcfce7"] {
        background: #042f2e !important;
        border-color: #115e59 !important;
        color: #5eead4 !important;
    }

    /* Subtable Eksemplar */
    [data-theme="dark"] .eksemplar-detail-row td {
        background: #0d1527 !important;
        border-color: #23314d !important;
    }

    [data-theme="dark"] .eksemplar-panel {
        background: #0d1527 !important;
        border-left-color: #14b8a6 !important;
    }

    [data-theme="dark"] .eksemplar-subtable-wrapper {
        background: #131d31 !important;
        border-color: #23314d !important;
    }

    /* Member Denda Cards & QRIS */
    [data-theme="dark"] .rincian-card,
    [data-theme="dark"] .qris-card,
    [data-theme="dark"] .rincian-card-box {
        background: #131d31 !important;
        border-color: #23314d !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
    }

    [data-theme="dark"] .rincian-item {
        border-color: #23314d !important;
    }

    [data-theme="dark"] .rincian-label,
    [data-theme="dark"] .kv-label {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .rincian-value {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .bayar-card {
        background: #042f2e !important;
        border-color: #115e59 !important;
    }

    [data-theme="dark"] .bayar-card-title,
    [data-theme="dark"] .cara-bayar-title {
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .bayar-disclaimer {
        color: #99f6e4 !important;
    }

    [data-theme="dark"] .cara-bayar-box {
        background: #042f2e !important;
        border-color: #115e59 !important;
    }

    [data-theme="dark"] .step-number {
        background: #14b8a6 !important;
        color: #0f172a !important;
    }

    [data-theme="dark"] .step-text {
        color: #ccfbf1 !important;
    }

    [data-theme="dark"] .kv-item.total-row {
        border-color: #23314d !important;
    }

    /* All Role Cards, Panels & Containers */
    [data-theme="dark"] .panel-card,
    [data-theme="dark"] .card-panel,
    [data-theme="dark"] .card-status-berhasil,
    [data-theme="dark"] .status-banner-card,
    [data-theme="dark"] .nota-receipt-card,
    [data-theme="dark"] .summary-card,
    [data-theme="dark"] .ticket-qr-card,
    [data-theme="dark"] .ticket-detail-card,
    [data-theme="dark"] .checkout-card,
    [data-theme="dark"] .card-bottom,
    [data-theme="dark"] .manage-card,
    [data-theme="dark"] .monthly-report-card {
        background: #131d31 !important;
        border-color: #23314d !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
    }

    [data-theme="dark"] .welcome-banner {
        background: #042f2e !important;
        border-color: #115e59 !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
    }

    [data-theme="dark"] .welcome-title {
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .welcome-meta {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .welcome-desc {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .breadcrumb {
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .stat-icon:not([style*="color"]) {
        background: rgba(16, 185, 129, 0.16) !important;
        color: #10b981 !important;
    }

    [data-theme="dark"] .denda-alert-banner {
        background: #1a1608 !important;
        border-color: #854d0e !important;
    }

    [data-theme="dark"] .card-pengingat {
        background: #042f2e !important;
        border-color: #115e59 !important;
    }

    [data-theme="dark"] .card-pengingat .pengingat-body {
        color: #99f6e4 !important;
    }

    [data-theme="dark"] .card-pengingat .pengingat-footer {
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .activity-item {
        border-bottom-color: #23314d !important;
    }

    [data-theme="dark"] .book-item-box,
    [data-theme="dark"] .stat-pill {
        background: #0d1527 !important;
        border-color: #23314d !important;
    }

    [data-theme="dark"] .cart-item-row {
        border-bottom-color: #23314d !important;
    }

    [data-theme="dark"] .cart-item-cover {
        background: #0b1220 !important;
        border-color: #23314d !important;
    }

    [data-theme="dark"] .pickup-deadline-box {
        background: rgba(245, 158, 11, 0.16) !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        color: #fbbf24 !important;
    }

    [data-theme="dark"] .pickup-deadline-box strong {
        color: #fef3c7 !important;
    }

    [data-theme="dark"] .restock-alert-banner {
        background: #1a1608 !important;
        border: 1.5px solid #854d0e !important;
    }

    [data-theme="dark"] .receipt-header,
    [data-theme="dark"] .receipt-divider,
    [data-theme="dark"] .summary-meta-table,
    [data-theme="dark"] .member-profile-card,
    [data-theme="dark"] .circ-tabs {
        border-color: #23314d !important;
    }

    /* Form Controls & Inputs */
    [data-theme="dark"] .search-input-field,
    [data-theme="dark"] .input-manual,
    [data-theme="dark"] .input-code-manual,
    [data-theme="dark"] .form-input,
    [data-theme="dark"] .form-input-field,
    [data-theme="dark"] .table-search-input-wrap {
        background: #0b1220 !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .table-search-input-wrap input {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .search-input-field::placeholder,
    [data-theme="dark"] .input-manual::placeholder,
    [data-theme="dark"] .input-code-manual::placeholder,
    [data-theme="dark"] .form-input::placeholder {
        color: #64748b !important;
    }

    /* Inline Colored Elements in Sirkulasi & Buku */
    [data-theme="dark"] .copies-breakdown-subtext span[style*="color: #475569"] {
        color: #94a3b8 !important;
    }
    [data-theme="dark"] .copies-breakdown-subtext span[style*="color: #047857"] {
        color: #34d399 !important;
    }
    [data-theme="dark"] .copies-breakdown-subtext span[style*="color: #1d4ed8"] {
        color: #60a5fa !important;
    }
    [data-theme="dark"] .book-meta-author span[style*="color: #0f766e"] {
        color: #2dd4bf !important;
    }

    /* Modals & Dialogs */
    [data-theme="dark"] .modal-card,
    [data-theme="dark"] .modal-content,
    [data-theme="dark"] .modal-dialog,
    [data-theme="dark"] .borrow-dialog {
        background: #131d31 !important;
        border-color: #23314d !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5) !important;
    }

    [data-theme="dark"] .modal-header {
        border-color: #23314d !important;
    }

    [data-theme="dark"] .modal-title {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .modal-footer {
        background: #0f172a !important;
        border-color: #23314d !important;
    }

    [data-theme="dark"] .book-summary-box {
        background: #0b1220 !important;
        border-color: #23314d !important;
    }

    [data-theme="dark"] #modalDendaNotice {
        background: rgba(239, 68, 68, 0.16) !important;
        border-color: rgba(239, 68, 68, 0.35) !important;
        color: #fca5a5 !important;
    }

    [data-theme="dark"] #modalConditionNotice {
        background: rgba(245, 158, 11, 0.16) !important;
        border-color: rgba(245, 158, 11, 0.35) !important;
        color: #fde68a !important;
    }

    [data-theme="dark"] #modalPayFirstWarning {
        background: rgba(59, 130, 246, 0.16) !important;
        border-color: rgba(59, 130, 246, 0.35) !important;
        color: #93c5fd !important;
    }

    [data-theme="dark"] .checkbox-wrap {
        background: rgba(20, 184, 166, 0.12) !important;
        border-color: rgba(20, 184, 166, 0.3) !important;
        color: #2dd4bf !important;
    }

    /* Table Action Buttons */
    [data-theme="dark"] .btn-action-manage {
        background: rgba(20, 184, 166, 0.16) !important;
        color: #2dd4bf !important;
        border-color: rgba(20, 184, 166, 0.3) !important;
    }

    [data-theme="dark"] .btn-action-pajang {
        background: rgba(34, 197, 94, 0.16) !important;
        color: #4ade80 !important;
        border-color: rgba(34, 197, 94, 0.3) !important;
    }

    [data-theme="dark"] .btn-action-simpan {
        background: #1e293b !important;
        color: #cbd5e1 !important;
        border-color: #334155 !important;
    }

    [data-theme="dark"] .btn-action-edit {
        background: rgba(245, 158, 11, 0.16) !important;
        color: #fbbf24 !important;
        border-color: rgba(245, 158, 11, 0.3) !important;
    }

    [data-theme="dark"] .btn-action-delete {
        background: rgba(239, 68, 68, 0.16) !important;
        color: #f87171 !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
    }

    [data-theme="dark"] .btn-action-nota {
        background: #1e293b !important;
        color: #cbd5e1 !important;
        border-color: #334155 !important;
    }

    [data-theme="dark"] .btn-action-nota:hover {
        background: #334155 !important;
        color: #f8fafc !important;
    }

    /* Table Footer & Pagination */
    [data-theme="dark"] .table-footer,
    [data-theme="dark"] .pagination-footer,
    [data-theme="dark"] .table-card > div[style*="background: #ffffff"],
    [data-theme="dark"] .table-card > div[style*="background:#ffffff"] {
        background: #131d31 !important;
        border-color: #23314d !important;
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .table-footer strong,
    [data-theme="dark"] .pagination-footer strong,
    [data-theme="dark"] .table-card > div[style*="background: #ffffff"] strong,
    [data-theme="dark"] .table-card > div[style*="background:#ffffff"] strong {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .custom-page-btn,
    [data-theme="dark"] .page-btn,
    [data-theme="dark"] .pagination a,
    [data-theme="dark"] .pagination span {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .custom-page-btn svg,
    [data-theme="dark"] .page-btn svg {
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .custom-page-btn:hover:not(.disabled):not(:disabled):not(.active),
    [data-theme="dark"] .page-btn:hover:not(.disabled):not(:disabled):not(.active),
    [data-theme="dark"] .pagination a:hover {
        background: #334155 !important;
        border-color: #475569 !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .custom-page-btn.active,
    [data-theme="dark"] .page-btn.active,
    [data-theme="dark"] .pagination .active span {
        background: #10b981 !important;
        border-color: #10b981 !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .custom-page-btn.disabled,
    [data-theme="dark"] .custom-page-btn:disabled,
    [data-theme="dark"] .page-btn.disabled,
    [data-theme="dark"] .page-btn:disabled,
    [data-theme="dark"] .pagination .disabled span {
        background: #0f172a !important;
        border-color: #1e293b !important;
        color: #475569 !important;
    }

    [data-theme="dark"] .custom-page-btn.disabled svg,
    [data-theme="dark"] .page-btn.disabled svg {
        color: #475569 !important;
    }

    /* Empty states */
    [data-theme="dark"] .empty-icon-wrap,
    [data-theme="dark"] .empty-state-icon {
        background: #1e293b !important;
        color: #64748b !important;
    }

    [data-theme="dark"] .empty-title,
    [data-theme="dark"] .empty-state-text {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .empty-desc,
    [data-theme="dark"] .empty-state {
        color: #94a3b8 !important;
    }

    /* ========================================================
       PREFERENCES TAB & CARDS STYLING
       ======================================================== */
    .preferences-tab-nav {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 12px;
        margin-bottom: 24px;
        border: 1px solid var(--border-color);
        max-width: 100%;
    }

    [data-theme="dark"] .preferences-tab-nav {
        background: #0b1220;
        border-color: #23314d;
    }

    .tab-nav-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 9px;
        font-size: 13.5px;
        font-weight: 700;
        color: #64748b;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        user-select: none;
    }

    [data-theme="dark"] .tab-nav-btn {
        color: #94a3b8;
    }

    .tab-nav-btn:hover {
        color: var(--text-heading);
    }

    .tab-nav-btn.active {
        background: #ffffff;
        color: var(--theme-accent, #0f766e);
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
    }

    [data-theme="dark"] .tab-nav-btn.active {
        background: #1e293b;
        color: #2dd4bf;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .tab-nav-btn svg {
        flex-shrink: 0;
    }

    /* Preference Section Cards */
    .pref-section-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 26px 28px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 22px;
        animation: profileContentFadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    [data-theme="dark"] .pref-section-card {
        background: #131d31;
        border-color: #23314d;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
    }

    .pref-section-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }

    .pref-section-info h2 {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 4px;
        letter-spacing: -0.3px;
    }

    .pref-section-info p {
        font-size: 13.5px;
        color: var(--text-muted);
        line-height: 1.5;
    }

    /* Preference Option Grid (Cards) */
    .pref-card-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    @media (max-width: 720px) {
        .pref-card-grid {
            grid-template-columns: 1fr;
        }
    }

    .pref-option-card {
        position: relative;
        display: flex;
        flex-direction: column;
        border: 2px solid var(--border-color);
        border-radius: 16px;
        padding: 18px;
        background: var(--bg-card);
        cursor: pointer;
        transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        user-select: none;
    }

    [data-theme="dark"] .pref-option-card {
        background: #0f172a;
        border-color: #23314d;
    }

    .pref-option-card:hover {
        border-color: #0f766e;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 118, 110, 0.08);
    }

    [data-theme="dark"] .pref-option-card:hover {
        border-color: #14b8a6;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .pref-option-card.active {
        border-color: var(--theme-accent, #0f766e) !important;
        background: var(--theme-accent-light, #f0fdfa) !important;
        box-shadow: 0 4px 14px rgba(15, 118, 110, 0.12);
    }

    [data-theme="dark"] .pref-option-card.active {
        border-color: #14b8a6 !important;
        background: #042f2e !important;
        box-shadow: 0 4px 16px rgba(20, 184, 166, 0.16) !important;
    }

    [data-theme="soft-blush"] .pref-option-card.active {
        border-color: #b85d77 !important;
        background: #fdf5f7 !important;
        box-shadow: 0 4px 14px rgba(184, 93, 119, 0.12) !important;
    }

    [data-theme="midnight-slate"] .pref-option-card.active {
        border-color: #38bdf8 !important;
        background: #14223d !important;
        box-shadow: 0 4px 16px rgba(56, 189, 248, 0.18) !important;
    }

    .pref-option-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .pref-option-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--theme-accent-light, #ccfbf1);
        color: var(--theme-accent, #0f766e);
        transition: all 0.2s;
    }

    [data-theme="dark"] .pref-option-icon-box {
        background: #115e59 !important;
        color: #2dd4bf !important;
    }

    [data-theme="soft-blush"] .pref-option-icon-box {
        background: #fceef2 !important;
        color: #b85d77 !important;
    }

    [data-theme="midnight-slate"] .pref-option-icon-box {
        background: rgba(56, 189, 248, 0.15) !important;
        color: #38bdf8 !important;
    }

    .pref-option-card.active .pref-option-icon-box {
        background: var(--theme-accent, #0f766e) !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .pref-option-card.active .pref-option-icon-box {
        background: #14b8a6 !important;
        color: #0f172a !important;
    }

    [data-theme="soft-blush"] .pref-option-card.active .pref-option-icon-box {
        background: #b85d77 !important;
        color: #ffffff !important;
    }

    [data-theme="midnight-slate"] .pref-option-card.active .pref-option-icon-box {
        background: #2563eb !important;
        color: #ffffff !important;
    }

    .pref-radio-indicator {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    [data-theme="dark"] .pref-radio-indicator {
        border-color: #475569;
    }

    [data-theme="soft-blush"] .pref-radio-indicator {
        border-color: #eedde2;
    }

    [data-theme="midnight-slate"] .pref-radio-indicator {
        border-color: #2b3b55;
    }

    .pref-option-card.active .pref-radio-indicator {
        border-color: var(--theme-accent, #0f766e) !important;
        background: var(--theme-accent, #0f766e) !important;
    }

    [data-theme="dark"] .pref-option-card.active .pref-radio-indicator {
        border-color: #14b8a6 !important;
        background: #14b8a6 !important;
    }

    [data-theme="soft-blush"] .pref-option-card.active .pref-radio-indicator {
        border-color: #b85d77 !important;
        background: #b85d77 !important;
    }

    [data-theme="midnight-slate"] .pref-option-card.active .pref-radio-indicator {
        border-color: #38bdf8 !important;
        background: #38bdf8 !important;
    }

    .pref-radio-indicator::after {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ffffff;
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.2s;
    }

    [data-theme="dark"] .pref-radio-indicator::after {
        background: #0f172a;
    }

    .pref-option-card.active .pref-radio-indicator::after {
        opacity: 1;
        transform: scale(1);
    }

    /* Mockup Theme Preview */
    .pref-mockup-wrapper {
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
        height: 100px;
        display: flex;
        flex-direction: column;
    }

    [data-theme="dark"] .pref-mockup-wrapper {
        border-color: #334155;
    }

    /* Light Mockup */
    .theme-mockup-light {
        background: #f8fafc;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .theme-mockup-light .mockup-nav {
        height: 18px;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        padding: 0 10px;
        gap: 6px;
    }

    .theme-mockup-light .mockup-body {
        flex: 1;
        display: flex;
        padding: 8px;
        gap: 8px;
    }

    .theme-mockup-light .mockup-sidebar {
        width: 32px;
        background: #ffffff;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }

    .theme-mockup-light .mockup-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .theme-mockup-light .mockup-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        flex: 1;
    }

    /* Dark Mockup */
    .theme-mockup-dark {
        background: #090d16;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .theme-mockup-dark .mockup-nav {
        height: 18px;
        background: #0f172a;
        border-bottom: 1px solid #1e293b;
        display: flex;
        align-items: center;
        padding: 0 10px;
        gap: 6px;
    }

    .theme-mockup-dark .mockup-body {
        flex: 1;
        display: flex;
        padding: 8px;
        gap: 8px;
    }

    .theme-mockup-dark .mockup-sidebar {
        width: 32px;
        background: #131d31;
        border-radius: 6px;
        border: 1px solid #23314d;
    }

    .theme-mockup-dark .mockup-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .theme-mockup-dark .mockup-card {
        background: #131d31;
        border: 1px solid #23314d;
        border-radius: 6px;
        flex: 1;
    }

    .pref-option-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 3px;
        letter-spacing: -0.2px;
    }

    .pref-option-desc {
        font-size: 12.5px;
        color: var(--text-muted);
        line-height: 1.45;
    }

    .pref-option-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        margin-top: 10px;
        align-self: flex-start;
    }

    .pref-option-card.active .pref-option-badge {
        background: var(--theme-accent-light, #ccfbf1) !important;
        color: var(--theme-accent, #0f766e) !important;
    }

    [data-theme="dark"] .pref-option-card.active .pref-option-badge {
        background: #115e59 !important;
        color: #2dd4bf !important;
    }

    [data-theme="soft-blush"] .pref-option-card.active .pref-option-badge {
        background: #fceef2 !important;
        color: #9e4761 !important;
    }

    [data-theme="midnight-slate"] .pref-option-card.active .pref-option-badge {
        background: rgba(56, 189, 248, 0.16) !important;
        color: #38bdf8 !important;
    }

    .pref-option-card:not(.active) .pref-option-badge {
        background: #f1f5f9;
        color: #64748b;
    }

    [data-theme="dark"] .pref-option-card:not(.active) .pref-option-badge {
        background: #1e293b;
        color: #94a3b8;
    }

    /* Notice Box for Language Constraints */
    .pref-notice-box {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        background: #f0fdfa;
        border: 1px solid #99f6e4;
        border-left: 4px solid #0f766e;
        border-radius: 14px;
        padding: 16px 20px;
        margin-top: 20px;
    }

    .pref-notice-icon {
        flex-shrink: 0;
        color: #0f766e;
        margin-top: 2px;
    }

    .pref-notice-title {
        font-size: 13.5px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        margin-bottom: 4px;
    }

    .pref-notice-desc {
        font-size: 12.5px;
        color: #334155;
        line-height: 1.5;
    }

    /* Dark Theme */
    [data-theme="dark"] .pref-notice-box {
        background: #042f2e !important;
        border-color: #115e59 !important;
        border-left: 4px solid #2dd4bf !important;
    }
    [data-theme="dark"] .pref-notice-icon {
        color: #2dd4bf !important;
    }
    [data-theme="dark"] .pref-notice-title {
        color: #f8fafc !important;
    }
    [data-theme="dark"] .pref-notice-desc {
        color: #99f6e4 !important;
    }

    /* Van Gogh Theme */
    [data-theme="van-gogh"] .pref-notice-box {
        background: linear-gradient(135deg, rgba(16, 36, 61, 0.95), rgba(13, 27, 42, 0.95)) !important;
        border: 1px solid #22446a !important;
        border-left: 4px solid #f59e0b !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35) !important;
    }
    [data-theme="van-gogh"] .pref-notice-icon {
        color: #f59e0b !important;
    }
    [data-theme="van-gogh"] .pref-notice-title {
        color: #f6f0df !important;
    }
    [data-theme="van-gogh"] .pref-notice-desc {
        color: #9fb3c8 !important;
    }

    /* Midnight Slate Theme */
    [data-theme="midnight-slate"] .pref-notice-box {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(11, 19, 41, 0.95)) !important;
        border: 1px solid #1e3156 !important;
        border-left: 4px solid #38bdf8 !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35) !important;
    }
    [data-theme="midnight-slate"] .pref-notice-box {
        color: #38bdf8 !important;
    }
    [data-theme="midnight-slate"] .pref-notice-title {
        color: #f8fafc !important;
    }
    [data-theme="midnight-slate"] .pref-notice-desc {
        color: #94a3b8 !important;
    }

    /* Soft Blush Theme */
    [data-theme="soft-blush"] .pref-notice-box {
        background: #fff1f2 !important;
        border: 1px solid #fbcfe8 !important;
        border-left: 4px solid #e11d48 !important;
    }
    [data-theme="soft-blush"] .pref-notice-icon {
        color: #e11d48 !important;
    }
    [data-theme="soft-blush"] .pref-notice-title {
        color: #831843 !important;
    }
    [data-theme="soft-blush"] .pref-notice-desc {
        color: #9f1239 !important;
    }

    /* --- RESULTS COUNT TEXT CONTRAST IN KATALOG --- */
    [data-theme="van-gogh"] .results-count-text {
        color: #f6f0df !important;
    }
    [data-theme="van-gogh"] .results-count-text span {
        color: #9fb3c8 !important;
    }

    [data-theme="midnight-slate"] .results-count-text {
        color: #f8fafc !important;
    }
    [data-theme="midnight-slate"] .results-count-text span {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .results-count-text {
        color: #f8fafc !important;
    }
    [data-theme="dark"] .results-count-text span {
        color: #94a3b8 !important;
    }

    [data-theme="soft-blush"] .results-count-text {
        color: #4a2835 !important;
    }
    [data-theme="soft-blush"] .results-count-text span {
        color: #826e75 !important;
    }

    /* Feedback Toast */
    .pref-feedback-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #0f766e;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(15, 118, 110, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        font-weight: 700;
        z-index: 999999;
        opacity: 0;
        transform: translateY(16px);
        pointer-events: none;
        transition: all 0.26s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .pref-feedback-toast.show {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    /* ========================================================
       BOOKNEST PERSONALIZATION SYSTEM: FONTS, SIZES & OPENING
       ======================================================== */

    /* 3-Column Preference Card Grid */
    .pref-card-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    @media (max-width: 960px) {
        .pref-card-grid-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .pref-card-grid-3 {
            grid-template-columns: 1fr;
        }
    }

    /* System Theme Mockup */
    .theme-mockup-system {
        width: 100%;
        height: 84px;
        border-radius: 8px;
        display: flex;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    .theme-mockup-system .half-light {
        flex: 1;
        background: #f8fafc;
        border-right: 1px dashed #cbd5e1;
        padding: 8px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .theme-mockup-system .half-dark {
        flex: 1;
        background: #0f172a;
        padding: 8px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    /* Font Selector Grid */
    .pref-font-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .pref-font-card {
        border: 2px solid var(--border-color);
        border-radius: 14px;
        padding: 14px 16px;
        background: var(--bg-card);
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        gap: 4px;
        user-select: none;
    }

    [data-theme="dark"] .pref-font-card {
        background: #0f172a;
        border-color: #23314d;
    }

    .pref-font-card:hover {
        border-color: #0f766e;
        transform: translateY(-2px);
    }

    [data-theme="dark"] .pref-font-card:hover {
        border-color: #14b8a6;
    }

    .pref-font-card.active {
        border-color: var(--theme-accent, #0f766e) !important;
        background: var(--theme-accent-light, #f0fdfa) !important;
        box-shadow: 0 4px 14px rgba(15, 118, 110, 0.12);
    }

    [data-theme="dark"] .pref-font-card.active {
        border-color: #14b8a6 !important;
        background: #042f2e !important;
        box-shadow: 0 4px 16px rgba(20, 184, 166, 0.16) !important;
    }

    [data-theme="soft-blush"] .pref-font-card.active {
        border-color: #b85d77 !important;
        background: #fdf5f7 !important;
        box-shadow: 0 4px 14px rgba(184, 93, 119, 0.12) !important;
    }

    [data-theme="midnight-slate"] .pref-font-card.active {
        border-color: #38bdf8 !important;
        background: #14223d !important;
        box-shadow: 0 4px 16px rgba(56, 189, 248, 0.18) !important;
    }

    .pref-font-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text-heading);
    }

    .pref-font-sample {
        font-size: 12.5px;
        color: var(--text-muted);
    }

    /* Font Size Selector Pills */
    .pref-size-pills {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .pref-size-btn {
        padding: 9px 22px;
        border-radius: 10px;
        border: 2px solid var(--border-color);
        background: var(--bg-card);
        color: var(--text-body);
        font-weight: 700;
        font-size: 13.5px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    [data-theme="dark"] .pref-size-btn {
        background: #0f172a;
        border-color: #23314d;
        color: #cbd5e1;
    }

    [data-theme="soft-blush"] .pref-size-btn {
        background: #ffffff;
        border-color: #eedde2;
        color: #54444a;
    }

    [data-theme="midnight-slate"] .pref-size-btn {
        background: #101a2f;
        border-color: #24354e;
        color: #cbd5e1;
    }

    .pref-size-btn:hover {
        border-color: var(--theme-accent, #0f766e);
    }

    .pref-size-btn.active {
        border-color: var(--theme-accent, #0f766e) !important;
        background: var(--theme-btn-primary-bg, #0f766e) !important;
        color: var(--theme-btn-primary-text, #ffffff) !important;
    }

    [data-theme="dark"] .pref-size-btn.active {
        border-color: #10b981 !important;
        background: #10b981 !important;
        color: #ffffff !important;
    }

    [data-theme="soft-blush"] .pref-size-btn.active {
        border-color: #b85d77 !important;
        background: #b85d77 !important;
        color: #ffffff !important;
    }

    [data-theme="midnight-slate"] .pref-size-btn.active {
        border-color: #38bdf8 !important;
        background: #2563eb !important;
        color: #ffffff !important;
    }

    /* Live Typography Preview Box */
    .pref-live-preview-box {
        border: 2px dashed var(--theme-accent, #0d9488) !important;
        background: var(--theme-accent-light, rgba(13, 148, 136, 0.05)) !important;
        border-radius: 14px;
        padding: 18px 22px;
        margin-top: 10px;
        transition: all 0.2s ease;
    }

    [data-theme="dark"] .pref-live-preview-box {
        border-color: #2dd4bf !important;
        background: rgba(45, 212, 191, 0.06) !important;
    }

    [data-theme="soft-blush"] .pref-live-preview-box {
        border-color: #b85d77 !important;
        background: rgba(184, 93, 119, 0.05) !important;
    }

    [data-theme="midnight-slate"] .pref-live-preview-box {
        border-color: #38bdf8 !important;
        background: rgba(56, 189, 248, 0.06) !important;
    }

    .pref-live-preview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
        gap: 12px;
    }

    .pref-live-preview-title {
        font-size: 12.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #0f766e;
    }

    [data-theme="dark"] .pref-live-preview-title {
        color: #2dd4bf;
    }

    .pref-live-preview-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 6px;
        background: #ccfbf1;
        color: #0f766e;
    }

    [data-theme="dark"] .pref-live-preview-badge {
        background: #115e59;
        color: #99f6e4;
    }

    .pref-live-preview-text {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-heading);
        line-height: 1.5;
        transition: font-size 0.2s, font-family 0.2s;
    }

    /* Opening Style Category Dropdown */
    .opening-category-dropdown-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .opening-dropdown-label {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .opening-select-custom {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .opening-select-control {
        appearance: none;
        -webkit-appearance: none;
        padding: 9px 36px 9px 14px;
        background: #ffffff;
        border: 2px solid #0f766e;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        color: #0f766e;
        cursor: pointer;
        outline: none;
        transition: all 0.2s ease;
    }

    [data-theme="dark"] .opening-select-control {
        background: #0f172a;
        border-color: #14b8a6;
        color: #2dd4bf;
    }

    .opening-select-control:focus {
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.2);
    }

    [data-theme="dark"] .opening-select-control:focus {
        box-shadow: 0 0 0 3px rgba(45, 212, 191, 0.25);
    }

    .opening-select-arrow {
        position: absolute;
        right: 12px;
        pointer-events: none;
        color: #0f766e;
    }

    [data-theme="dark"] .opening-select-arrow {
        color: #2dd4bf;
    }

    /* Horizontal Selector Track & Arrow Controls */
    .opening-selector-container {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 14px;
        width: 100%;
    }

    .opening-horizontal-track {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
        padding: 8px 4px 16px;
        width: 100%;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    [data-theme="dark"] .opening-horizontal-track {
        scrollbar-color: #334155 transparent;
    }

    .opening-horizontal-track::-webkit-scrollbar {
        height: 6px;
    }

    .opening-horizontal-track::-webkit-scrollbar-track {
        background: transparent;
    }

    .opening-horizontal-track::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    [data-theme="dark"] .opening-horizontal-track::-webkit-scrollbar-thumb {
        background: #334155;
    }

    .opening-h-card {
        flex: 0 0 280px;
        min-width: 280px;
        max-width: 280px;
        scroll-snap-align: start;
    }

    @media (max-width: 640px) {
        .opening-h-card {
            flex: 0 0 240px;
            min-width: 240px;
            max-width: 240px;
        }
    }

    .opening-nav-arrow {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid var(--border-color);
        background: var(--bg-card);
        color: var(--text-heading);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        z-index: 2;
    }

    [data-theme="dark"] .opening-nav-arrow {
        background: #1e293b;
        border-color: #334155;
        color: #e2e8f0;
    }

    .opening-nav-arrow:hover {
        border-color: #0f766e;
        color: #0f766e;
        transform: scale(1.05);
    }

    [data-theme="dark"] .opening-nav-arrow:hover {
        border-color: #2dd4bf;
        color: #2dd4bf;
    }

    /* Opening Concept Mockup Gradients (10 Distinct Concepts) */
    .opening-mockup-box {
        width: 100%;
        height: 84px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .opening-mockup-center {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Cewek Banget Concepts */
    .opening-mockup-coquette {
        background: linear-gradient(135deg, #ffd1dc 0%, #fff0f5 45%, #fce4ec 75%, #f8bbd0 100%);
    }

    .opening-mockup-fairy {
        background: linear-gradient(135deg, #e9d5ff 0%, #f5d0fe 35%, #fed7aa 70%, #ede9fe 100%);
    }

    .opening-mockup-sakura {
        background: linear-gradient(135deg, #fbcfe8 0%, #ffffff 40%, #ccfbf1 80%, #fce7f3 100%);
    }

    .opening-mockup-strawberry {
        background: linear-gradient(135deg, #f43f5e 0%, #fb7185 35%, #fed7aa 70%, #fff1f2 100%);
    }

    .opening-mockup-garden {
        background: linear-gradient(135deg, #f472b6 0%, #fbcfe8 35%, #d1fae5 70%, #fef3c7 100%);
    }

    /* Cowo Banget Concepts */
    .opening-mockup-midnight {
        background: linear-gradient(145deg, #091223 0%, #0f1d3a 50%, #1e293b 100%);
    }

    .opening-mockup-cyber {
        background: linear-gradient(145deg, #020617 0%, #082f49 50%, #0369a1 100%);
    }

    .opening-mockup-steel {
        background: linear-gradient(145deg, #0f172a 0%, #1e293b 50%, #334155 100%);
    }

    .opening-mockup-forest {
        background: linear-gradient(145deg, #052e16 0%, #14532d 50%, #166534 100%);
    }

    .opening-mockup-sunset {
        background: linear-gradient(145deg, #451a03 0%, #7c2d12 50%, #9a3412 100%);
    }

    /* Artistik Concepts (Van Gogh) */
    .opening-mockup-van-gogh {
        background: 
            radial-gradient(circle at 75% 20%, rgba(245, 158, 11, 0.55) 0%, rgba(233, 185, 73, 0.2) 30%, transparent 55%),
            radial-gradient(circle at 20% 70%, rgba(36, 91, 143, 0.45) 0%, transparent 50%),
            linear-gradient(140deg, #0a1726 0%, #102a43 45%, #1d4068 75%, #0d1b2a 100%);
        border: 1px solid rgba(245, 158, 11, 0.45);
        box-shadow: inset 0 0 16px rgba(16, 42, 67, 0.8), 0 0 12px rgba(245, 158, 11, 0.25);
    }

    .btn-preview-opening {
        margin-top: 14px;
        padding: 7px 14px;
        border-radius: 8px;
        border: 1.5px solid var(--theme-accent, #0f766e) !important;
        background: transparent !important;
        color: var(--theme-accent, #0f766e) !important;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        width: fit-content;
    }

    .btn-preview-opening svg {
        color: currentColor !important;
    }

    .btn-preview-opening:hover {
        background: var(--theme-accent, #0f766e) !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .btn-preview-opening {
        border-color: #2dd4bf !important;
        color: #2dd4bf !important;
    }

    [data-theme="dark"] .btn-preview-opening:hover {
        background: #14b8a6 !important;
        color: #042f2e !important;
    }

    [data-theme="soft-blush"] .btn-preview-opening {
        border-color: #b85d77 !important;
        color: #b85d77 !important;
    }

    [data-theme="soft-blush"] .btn-preview-opening:hover {
        background: #b85d77 !important;
        color: #ffffff !important;
    }

    [data-theme="midnight-slate"] .btn-preview-opening {
        border-color: #38bdf8 !important;
        color: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .btn-preview-opening:hover {
        background: #2563eb !important;
        color: #ffffff !important;
    }

    /* Bottom Action Bar */
    .pref-action-bar {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 14px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--border-color);
    }

    [data-theme="dark"] .pref-action-bar {
        border-top-color: #23314d;
    }

    [data-theme="soft-blush"] .pref-action-bar {
        border-top-color: #eedde2;
    }

    [data-theme="midnight-slate"] .pref-action-bar {
        border-top-color: #24354e;
    }

    .btn-pref-apply {
        padding: 12px 28px;
        background: var(--theme-btn-primary-bg, #0f766e) !important;
        color: var(--theme-btn-primary-text, #ffffff) !important;
        font-size: 14px;
        font-weight: 800;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-pref-apply:hover {
        background: var(--theme-btn-primary-hover, #115e59) !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.22);
    }

    .btn-pref-apply:active {
        transform: translateY(0);
    }

    .btn-pref-reset {
        padding: 12px 22px;
        background: var(--theme-btn-secondary-bg, transparent) !important;
        color: var(--theme-muted, #64748b) !important;
        font-size: 14px;
        font-weight: 700;
        border-radius: 12px;
        border: 1.5px solid var(--theme-border, #cbd5e1) !important;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-pref-reset svg {
        color: currentColor !important;
    }

    .btn-pref-reset:hover {
        background: var(--theme-btn-secondary-hover, #f1f5f9) !important;
        color: var(--theme-accent, #0f766e) !important;
        border-color: var(--theme-accent, #0f766e) !important;
    }

    [data-theme="dark"] .btn-pref-reset {
        border-color: #23314d !important;
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .btn-pref-reset:hover {
        background: #1e293b !important;
        color: #2dd4bf !important;
        border-color: #2dd4bf !important;
    }

    [data-theme="soft-blush"] .btn-pref-reset {
        border-color: #eedde2 !important;
        color: #826e75 !important;
    }

    [data-theme="soft-blush"] .btn-pref-reset:hover {
        background: #fdf2f5 !important;
        color: #b85d77 !important;
        border-color: #b85d77 !important;
    }

    [data-theme="midnight-slate"] .btn-pref-reset {
        border-color: #24354e !important;
        color: #94a3b8 !important;
    }

    [data-theme="midnight-slate"] .btn-pref-reset:hover {
        background: #192742 !important;
        color: #38bdf8 !important;
        border-color: #38bdf8 !important;
    }

    /* ========================================================
       HORIZONTAL THEME SELECTOR TRACK & PALETTE STYLES
       ======================================================== */
    .theme-selector-container {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 14px;
        width: 100%;
    }

    .theme-horizontal-track {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
        padding: 8px 4px 16px;
        width: 100%;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    [data-theme="dark"] .theme-horizontal-track,
    [data-theme="midnight-slate"] .theme-horizontal-track {
        scrollbar-color: #334155 transparent;
    }

    [data-theme="soft-blush"] .theme-horizontal-track {
        scrollbar-color: #eedde2 transparent;
    }

    .theme-horizontal-track::-webkit-scrollbar {
        height: 6px;
    }

    .theme-horizontal-track::-webkit-scrollbar-track {
        background: transparent;
    }

    .theme-horizontal-track::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    [data-theme="dark"] .theme-horizontal-track::-webkit-scrollbar-thumb,
    [data-theme="midnight-slate"] .theme-horizontal-track::-webkit-scrollbar-thumb {
        background: #334155;
    }

    [data-theme="soft-blush"] .theme-horizontal-track::-webkit-scrollbar-thumb {
        background: #eedde2;
    }

    .theme-h-card {
        flex: 0 0 280px;
        min-width: 280px;
        max-width: 280px;
        scroll-snap-align: start;
    }

    @media (max-width: 640px) {
        .theme-h-card {
            flex: 0 0 240px;
            min-width: 240px;
            max-width: 240px;
        }
    }

    .theme-nav-arrow {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid var(--border-color);
        background: var(--bg-card);
        color: var(--text-heading);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        z-index: 2;
    }

    [data-theme="dark"] .theme-nav-arrow {
        background: #1e293b;
        border-color: #334155;
        color: #e2e8f0;
    }

    [data-theme="soft-blush"] .theme-nav-arrow {
        background: #ffffff;
        border-color: #eedde2;
        color: #54444a;
    }

    [data-theme="midnight-slate"] .theme-nav-arrow {
        background: #141f33;
        border-color: #24354e;
        color: #cbd5e1;
    }

    .theme-nav-arrow:hover {
        border-color: #0f766e;
        color: #0f766e;
        transform: scale(1.05);
    }

    [data-theme="dark"] .theme-nav-arrow:hover {
        border-color: #2dd4bf;
        color: #2dd4bf;
    }

    [data-theme="soft-blush"] .theme-nav-arrow:hover {
        border-color: #b85d77;
        color: #b85d77;
    }

    [data-theme="midnight-slate"] .theme-nav-arrow:hover {
        border-color: #38bdf8;
        color: #38bdf8;
    }

    /* Palette swatches preview */
    .theme-palette-swatches {
        display: flex;
        gap: 6px;
        align-items: center;
        margin-top: 10px;
        margin-bottom: 12px;
    }

    .theme-swatch {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 1px solid rgba(0, 0, 0, 0.12);
        display: inline-block;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
    }

    /* Soft Blush Mockup Preview */
    .theme-mockup-soft-blush {
        width: 100%;
        height: 84px;
        border-radius: 8px;
        border: 1px solid #eedde3;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        background: #fdf8f9;
        box-shadow: 0 2px 6px rgba(184, 93, 119, 0.08);
    }

    .theme-mockup-soft-blush .mockup-nav {
        height: 18px;
        background: #ffffff;
        border-bottom: 1px solid #eedde3;
        padding: 0 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .theme-mockup-soft-blush .mockup-body {
        flex: 1;
        display: flex;
        background: #fdf8f9;
    }

    .theme-mockup-soft-blush .mockup-sidebar {
        width: 32px;
        background: #ffffff;
        border-right: 1px solid #eedde3;
    }

    .theme-mockup-soft-blush .mockup-content {
        flex: 1;
        padding: 6px;
        display: flex;
        gap: 4px;
    }

    .theme-mockup-soft-blush .mockup-card {
        flex: 1;
        height: 100%;
        background: #ffffff;
        border-radius: 4px;
        border: 1px solid #eedde3;
    }

    /* Midnight Slate Mockup Preview */
    .theme-mockup-midnight-slate {
        width: 100%;
        height: 84px;
        border-radius: 8px;
        border: 1px solid #28354b;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        background: #0b1329;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
    }

    .theme-mockup-midnight-slate .mockup-nav {
        height: 18px;
        background: #0f172a;
        border-bottom: 1px solid #28354b;
        padding: 0 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .theme-mockup-midnight-slate .mockup-body {
        flex: 1;
        display: flex;
        background: #0b1329;
    }

    .theme-mockup-midnight-slate .mockup-sidebar {
        width: 32px;
        background: #0f172a;
        border-right: 1px solid #28354b;
    }

    .theme-mockup-midnight-slate .mockup-content {
        flex: 1;
        padding: 6px;
        display: flex;
        gap: 4px;
    }

    .theme-mockup-midnight-slate .mockup-card {
        flex: 1;
        height: 100%;
        background: #162035;
        border-radius: 4px;
        border: 1px solid #28354b;
    }

    /* Van Gogh (Starry Night) Mockup Preview */
    .theme-mockup-van-gogh {
        width: 100%;
        height: 84px;
        border-radius: 8px;
        border: 1px solid rgba(245, 158, 11, 0.4);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        background: 
            radial-gradient(circle at 85% 15%, rgba(245, 158, 11, 0.55) 0%, rgba(233, 185, 73, 0.2) 35%, transparent 60%),
            radial-gradient(circle at 15% 75%, rgba(36, 91, 143, 0.45) 0%, transparent 50%),
            linear-gradient(135deg, #0d1b2a 0%, #102a43 60%, #1d4068 100%);
        box-shadow: 0 4px 14px rgba(16, 42, 67, 0.6), 0 0 10px rgba(245, 158, 11, 0.2);
    }

    .theme-mockup-van-gogh .mockup-nav {
        height: 18px;
        background: #0f243c;
        border-bottom: 1.5px solid rgba(245, 158, 11, 0.3);
        padding: 0 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .theme-mockup-van-gogh .mockup-body {
        flex: 1;
        display: flex;
        background: #0b1726;
    }

    .theme-mockup-van-gogh .mockup-sidebar {
        width: 32px;
        background: #0e2035;
        border-right: 1px solid #1e3a5f;
    }

    .theme-mockup-van-gogh .mockup-content {
        flex: 1;
        padding: 6px;
        display: flex;
        gap: 4px;
    }

    .theme-mockup-van-gogh .mockup-card {
        flex: 1;
        height: 100%;
        background: linear-gradient(180deg, #14243b 0%, #101c2e 100%);
        border-radius: 4px;
        border: 1px solid #1e3a5f;
        border-top: 1.5px solid rgba(245, 158, 11, 0.5);
    }

    /* ========================================================
       SOFT BLUSH THEME ENGINE (DUSTY ROSE & WARM IVORY)
       ======================================================== */
    [data-theme="soft-blush"] {
        --theme-text: #2e2528;
        --theme-heading: #2e2528;
        --theme-muted: #826e75;
        --theme-surface: #ffffff;
        --theme-bg: #fdf8f9;
        --theme-border: #eedde2;
        --theme-border-subtle: #f7ebee;
        --theme-accent: #b85d77;
        --theme-accent-hover: #9e4761;
        --theme-accent-light: rgba(184, 93, 119, 0.12);
        --theme-active-indicator: #b85d77;
        --theme-icon: #b85d77;
        --theme-icon-muted: #826e75;
        --theme-btn-primary-bg: #b85d77;
        --theme-btn-primary-text: #ffffff;
        --theme-btn-primary-hover: #9e4761;
        --theme-btn-secondary-bg: #ffffff;
        --theme-btn-secondary-border: #eedde2;
        --theme-btn-secondary-text: #54444a;
        --theme-btn-secondary-hover: #fdf2f5;
        --opening-photo-glow: 0 16px 36px -10px rgba(184, 93, 119, 0.22), 0 0 20px rgba(244, 143, 177, 0.15);
        --opening-photo-overlay: linear-gradient(135deg, rgba(184, 93, 119, 0.5) 0%, rgba(244, 143, 177, 0.35) 100%);
        --opening-photo-border: #b85d77;

        --brand-primary: #b85d77;
        --brand-primary-dark: #9e4761;
        --brand-primary-light: rgba(184, 93, 119, 0.12);
        --brand-accent: #d47a94;
        --text-heading: #2e2528;
        --text-body: #54444a;
        --text-muted: #826e75;
        --border-color: #eedde2;
        --bg-page: #fdf8f9;
        --bg-card: #ffffff;
    }

    [data-theme="soft-blush"] body {
        background-color: #fdf8f9 !important;
        color: #54444a !important;
    }

    [data-theme="soft-blush"] .topbar {
        background: #ffffff !important;
        border-color: #eedde2 !important;
    }

    [data-theme="soft-blush"] .topbar-brand,
    [data-theme="soft-blush"] .logo-area,
    [data-theme="soft-blush"] .topbar-brand span,
    [data-theme="soft-blush"] .logo-text {
        color: #2e2528 !important;
    }

    [data-theme="soft-blush"] .topbar-brand-icon,
    [data-theme="soft-blush"] .member-brand-icon,
    [data-theme="soft-blush"] .topbar-logo-icon,
    [data-theme="soft-blush"] .logo-icon {
        background: #b85d77 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 10px rgba(184, 93, 119, 0.35) !important;
    }

    [data-theme="soft-blush"] .topbar-brand-icon svg,
    [data-theme="soft-blush"] .member-brand-icon svg,
    [data-theme="soft-blush"] .topbar-logo-icon svg,
    [data-theme="soft-blush"] .logo-icon svg {
        color: #ffffff !important;
    }

    [data-theme="soft-blush"] .topbar-nav-link {
        color: #826e75 !important;
    }

    [data-theme="soft-blush"] .topbar-nav-link:hover {
        background: #fdf2f5 !important;
        color: #9e4761 !important;
    }

    [data-theme="soft-blush"] .topbar-nav-link.active {
        background: rgba(184, 93, 119, 0.12) !important;
        color: #b85d77 !important;
    }

    [data-theme="soft-blush"] .topbar-icon-btn {
        color: #826e75 !important;
        border-color: #eedde2 !important;
    }

    [data-theme="soft-blush"] .topbar-icon-btn:hover {
        background: #fdf2f5 !important;
        color: #9e4761 !important;
    }

    [data-theme="soft-blush"] .topbar-user-badge {
        background: #fceef2 !important;
        color: #9e4761 !important;
        border-color: #f0cfd8 !important;
    }

    [data-theme="soft-blush"] .topbar-user-dropdown,
    [data-theme="soft-blush"] .user-dropdown {
        background: #ffffff !important;
        border-color: #eedde2 !important;
        box-shadow: 0 10px 25px rgba(184, 93, 119, 0.08) !important;
    }

    [data-theme="soft-blush"] .topbar-dropdown-header {
        border-color: #f7ebee !important;
    }

    [data-theme="soft-blush"] .topbar-dropdown-user-name {
        color: #2e2528 !important;
    }

    [data-theme="soft-blush"] .topbar-dropdown-user-email {
        color: #826e75 !important;
    }

    [data-theme="soft-blush"] .topbar-dropdown-item,
    [data-theme="soft-blush"] .user-dropdown-item {
        color: #54444a !important;
    }

    [data-theme="soft-blush"] .topbar-dropdown-item:hover,
    [data-theme="soft-blush"] .user-dropdown-item:hover {
        background: #fdf2f5 !important;
        color: #9e4761 !important;
    }

    [data-theme="soft-blush"] .topbar-dropdown-divider,
    [data-theme="soft-blush"] .user-dropdown-divider {
        background: #f7ebee !important;
    }

    [data-theme="soft-blush"] .sidebar {
        background: #ffffff !important;
        border-color: #eedde2 !important;
        box-shadow: 0 1px 4px rgba(184, 93, 119, 0.04) !important;
    }

    [data-theme="soft-blush"] .sidebar-header {
        border-color: #f7ebee !important;
    }

    [data-theme="soft-blush"] .sidebar-header-icon {
        background: #b85d77 !important;
        color: #ffffff !important;
    }

    [data-theme="soft-blush"] .sidebar-header-text h3 {
        color: #2e2528 !important;
    }

    [data-theme="soft-blush"] .sidebar-header-text span {
        color: #826e75 !important;
    }

    [data-theme="soft-blush"] .sidebar-section-title {
        color: #9e4761 !important;
    }

    [data-theme="soft-blush"] .sidebar-link {
        color: #54444a !important;
    }

    [data-theme="soft-blush"] .sidebar-link:hover {
        background: #fdf2f5 !important;
        color: #9e4761 !important;
    }

    [data-theme="soft-blush"] .sidebar-link.active {
        background: #fceef2 !important;
        color: #9e4761 !important;
    }

    [data-theme="soft-blush"] .sidebar-link-icon {
        color: inherit !important;
    }

    [data-theme="soft-blush"] .sidebar-user {
        border-color: #f7ebee !important;
        background: #fffbfc !important;
    }

    [data-theme="soft-blush"] .sidebar-user-name {
        color: #2e2528 !important;
    }

    [data-theme="soft-blush"] .sidebar-user-role {
        color: #826e75 !important;
    }

    [data-theme="soft-blush"] .sidebar-avatar {
        background: #fceef2 !important;
        color: #9e4761 !important;
    }

    [data-theme="soft-blush"] .card,
    [data-theme="soft-blush"] .stat-box,
    [data-theme="soft-blush"] .stat-card,
    [data-theme="soft-blush"] .summary-card,
    [data-theme="soft-blush"] .profile-card-section,
    [data-theme="soft-blush"] .data-card,
    [data-theme="soft-blush"] .table-card,
    [data-theme="soft-blush"] .card-panel,
    [data-theme="soft-blush"] .manage-card,
    [data-theme="soft-blush"] .monthly-report-card,
    [data-theme="soft-blush"] .report-card,
    [data-theme="soft-blush"] .riwayat-card,
    [data-theme="soft-blush"] .loan-card,
    [data-theme="soft-blush"] .synopsis-card,
    [data-theme="soft-blush"] .spec-card,
    [data-theme="soft-blush"] .about-card,
    [data-theme="soft-blush"] .related-card,
    [data-theme="soft-blush"] .option-card,
    [data-theme="soft-blush"] .edit-card,
    [data-theme="soft-blush"] .filter-card,
    [data-theme="soft-blush"] .book-meta-box,
    [data-theme="soft-blush"] .tray-box,
    [data-theme="soft-blush"] .tray-row,
    [data-theme="soft-blush"] .book-item-card,
    [data-theme="soft-blush"] .pref-section-card,
    [data-theme="soft-blush"] .about-hero,
    [data-theme="soft-blush"] .about-values,
    [data-theme="soft-blush"] .about-value-card,
    [data-theme="soft-blush"] .about-info-card,
    [data-theme="soft-blush"] .about-membership,
    [data-theme="soft-blush"] .summary-rule-card,
    [data-theme="soft-blush"] .term-article-card,
    [data-theme="soft-blush"] .step-card,
    [data-theme="soft-blush"] .feature-card,
    [data-theme="soft-blush"] .faq-item,
    [data-theme="soft-blush"] .principle-card,
    [data-theme="soft-blush"] .policy-card,
    [data-theme="soft-blush"] .form-card,
    [data-theme="soft-blush"] .cta-guide-card,
    [data-theme="soft-blush"] .figma-table-container,
    [data-theme="soft-blush"] .rincian-card {
        background: #ffffff !important;
        border-color: #eedde2 !important;
        box-shadow: 0 1px 3px rgba(184, 93, 119, 0.05) !important;
    }

    [data-theme="soft-blush"] .section-header-title,
    [data-theme="soft-blush"] .dashboard-title,
    [data-theme="soft-blush"] .profile-title,
    [data-theme="soft-blush"] .page-title,
    [data-theme="soft-blush"] .section-title,
    [data-theme="soft-blush"] .section-heading-title,
    [data-theme="soft-blush"] .report-card-title,
    [data-theme="soft-blush"] .manage-title,
    [data-theme="soft-blush"] .riwayat-card-title,
    [data-theme="soft-blush"] .welcome-title,
    [data-theme="soft-blush"] .loan-title,
    [data-theme="soft-blush"] .loan-card-title,
    [data-theme="soft-blush"] .option-title,
    [data-theme="soft-blush"] .book-showcase-title,
    [data-theme="soft-blush"] .meta-val,
    [data-theme="soft-blush"] .tray-title,
    [data-theme="soft-blush"] .card-title-lg,
    [data-theme="soft-blush"] .card-title-md,
    [data-theme="soft-blush"] .card-title-xl,
    [data-theme="soft-blush"] .panel-title,
    [data-theme="soft-blush"] .cart-title,
    [data-theme="soft-blush"] .cart-item-title,
    [data-theme="soft-blush"] .detail-card-title,
    [data-theme="soft-blush"] .book-item-title,
    [data-theme="soft-blush"] .book-title-main,
    [data-theme="soft-blush"] .custom-table td strong {
        color: #2e2528 !important;
    }

    [data-theme="soft-blush"] .page-subtitle,
    [data-theme="soft-blush"] .dashboard-subtitle,
    [data-theme="soft-blush"] .section-subtitle,
    [data-theme="soft-blush"] .manage-subtitle,
    [data-theme="soft-blush"] .report-card-desc,
    [data-theme="soft-blush"] .book-showcase-author,
    [data-theme="soft-blush"] .option-desc,
    [data-theme="soft-blush"] .synopsis-content,
    [data-theme="soft-blush"] .loan-subtitle,
    [data-theme="soft-blush"] .circ-subtitle,
    [data-theme="soft-blush"] .stat-note,
    [data-theme="soft-blush"] .stat-caption,
    [data-theme="soft-blush"] .stat-label,
    [data-theme="soft-blush"] .member-email,
    [data-theme="soft-blush"] .sirkulasi-subtitle,
    [data-theme="soft-blush"] .user-meta-sub {
        color: #826e75 !important;
    }

    [data-theme="soft-blush"] .form-label,
    [data-theme="soft-blush"] .filter-label,
    [data-theme="soft-blush"] .table-search-label,
    [data-theme="soft-blush"] .presets-label {
        color: #54444a !important;
    }

    [data-theme="soft-blush"] .form-control,
    [data-theme="soft-blush"] .form-control-select,
    [data-theme="soft-blush"] .form-control-date,
    [data-theme="soft-blush"] .form-input,
    [data-theme="soft-blush"] .form-select,
    [data-theme="soft-blush"] .form-textarea,
    [data-theme="soft-blush"] .filter-input,
    [data-theme="soft-blush"] .search-input,
    [data-theme="soft-blush"] .search-input-field,
    [data-theme="soft-blush"] .search-book-input,
    [data-theme="soft-blush"] .filter-select {
        background: #ffffff !important;
        border-color: #eedde2 !important;
        color: #2e2528 !important;
    }

    [data-theme="soft-blush"] .form-control:focus,
    [data-theme="soft-blush"] .form-control-select:focus,
    [data-theme="soft-blush"] .form-control-date:focus,
    [data-theme="soft-blush"] .form-input:focus,
    [data-theme="soft-blush"] .form-select:focus,
    [data-theme="soft-blush"] .form-textarea:focus,
    [data-theme="soft-blush"] .filter-input:focus,
    [data-theme="soft-blush"] .search-input:focus,
    [data-theme="soft-blush"] .search-input-field:focus,
    [data-theme="soft-blush"] .search-book-input:focus,
    [data-theme="soft-blush"] .filter-select:focus {
        border-color: #b85d77 !important;
        box-shadow: 0 0 0 3px rgba(184, 93, 119, 0.2) !important;
    }

    [data-theme="soft-blush"] .btn-primary,
    [data-theme="soft-blush"] .btn-action-primary,
    [data-theme="soft-blush"] .btn-action-simpan,
    [data-theme="soft-blush"] .btn-cari-buku,
    [data-theme="soft-blush"] .btn-katalog {
        background: #b85d77 !important;
        border-color: #9e4761 !important;
        color: #ffffff !important;
    }

    [data-theme="soft-blush"] .btn-primary:hover,
    [data-theme="soft-blush"] .btn-action-primary:hover,
    [data-theme="soft-blush"] .btn-action-simpan:hover,
    [data-theme="soft-blush"] .btn-cari-buku:hover,
    [data-theme="soft-blush"] .btn-katalog:hover {
        background: #9e4761 !important;
        border-color: #83394e !important;
    }

    [data-theme="soft-blush"] .btn-filter-reset,
    [data-theme="soft-blush"] .btn-reset,
    [data-theme="soft-blush"] .btn-reset-filter,
    [data-theme="soft-blush"] .btn-action-outline,
    [data-theme="soft-blush"] .btn-action-secondary {
        background: #ffffff !important;
        color: #54444a !important;
        border-color: #eedde2 !important;
    }

    [data-theme="soft-blush"] .btn-filter-reset:hover,
    [data-theme="soft-blush"] .btn-reset:hover,
    [data-theme="soft-blush"] .btn-reset-filter:hover,
    [data-theme="soft-blush"] .btn-action-outline:hover,
    [data-theme="soft-blush"] .btn-action-secondary:hover {
        background: #fdf2f5 !important;
        color: #9e4761 !important;
        border-color: #e5cfd6 !important;
    }

    [data-theme="soft-blush"] .custom-table th,
    [data-theme="soft-blush"] table th {
        background: #faf2f4 !important;
        color: #54444a !important;
        border-color: #eedde2 !important;
    }

    [data-theme="soft-blush"] .custom-table td,
    [data-theme="soft-blush"] table td {
        border-color: #eedde2 !important;
        color: #54444a !important;
    }

    [data-theme="soft-blush"] .custom-table tr:hover td,
    [data-theme="soft-blush"] table tbody tr:hover td {
        background: #fdf5f7 !important;
    }

    [data-theme="soft-blush"] .pref-option-card {
        background: #ffffff;
        border-color: #eedde2;
    }

    [data-theme="soft-blush"] .pref-option-card:hover {
        border-color: #b85d77;
    }

    [data-theme="soft-blush"] .pref-option-card.active {
        border-color: #b85d77;
        background: #fdf5f7;
    }

    [data-theme="soft-blush"] .tab-nav-btn.active {
        background: #ffffff;
        color: #9e4761;
        box-shadow: 0 2px 8px rgba(184, 93, 119, 0.1);
    }

    /* Soft Blush Dashboard & Greeting Banner Overrides */
    [data-theme="soft-blush"] .welcome-banner {
        background: linear-gradient(135deg, #fff1f4 0%, #fdf2f5 100%) !important;
        border-color: #f3d4dc !important;
        box-shadow: 0 4px 20px -2px rgba(184, 93, 119, 0.12) !important;
    }

    [data-theme="soft-blush"] .welcome-title {
        color: #b85d77 !important;
    }

    [data-theme="soft-blush"] .welcome-meta {
        color: #4a2833 !important;
    }

    [data-theme="soft-blush"] .welcome-desc {
        color: #826e75 !important;
    }

    [data-theme="soft-blush"] .breadcrumb {
        color: #b85d77 !important;
    }

    [data-theme="soft-blush"] .stat-icon:not([style*="color"]) {
        background: rgba(184, 93, 119, 0.14) !important;
        color: #b85d77 !important;
    }

    [data-theme="soft-blush"] .stat-box {
        background: #ffffff !important;
        border-color: #eedde2 !important;
    }

    [data-theme="soft-blush"] .stat-box:hover {
        border-color: #e0bec7 !important;
        box-shadow: 0 10px 24px -4px rgba(184, 93, 119, 0.12) !important;
    }

    [data-theme="soft-blush"] .denda-alert-banner {
        background: #fff5f7 !important;
        border-color: #f3d4dc !important;
    }

    /* ========================================================
       MIDNIGHT SLATE THEME ENGINE (STEEL BLUE & DEEP SLATE)
       ======================================================== */
    [data-theme="midnight-slate"] {
        --theme-text: #cbd5e1;
        --theme-heading: #f8fafc;
        --theme-muted: #94a3b8;
        --theme-surface: #141f33;
        --theme-bg: #0b1329;
        --theme-border: #24354e;
        --theme-border-subtle: #1c2a42;
        --theme-accent: #38bdf8;
        --theme-accent-hover: #60a5fa;
        --theme-accent-light: rgba(56, 189, 248, 0.16);
        --theme-active-indicator: #38bdf8;
        --theme-icon: #38bdf8;
        --theme-icon-muted: #94a3b8;
        --theme-btn-primary-bg: #2563eb;
        --theme-btn-primary-text: #ffffff;
        --theme-btn-primary-hover: #1d4ed8;
        --theme-btn-secondary-bg: #141f33;
        --theme-btn-secondary-border: #2b3b55;
        --theme-btn-secondary-text: #cbd5e1;
        --theme-btn-secondary-hover: #1e2c45;
        --opening-photo-glow: 0 16px 36px -10px rgba(15, 23, 42, 0.6), 0 0 24px rgba(56, 189, 248, 0.15);
        --opening-photo-overlay: linear-gradient(135deg, rgba(15, 23, 42, 0.75) 0%, rgba(30, 41, 59, 0.5) 100%);
        --opening-photo-border: #38bdf8;

        --brand-primary: #3b82f6;
        --brand-primary-dark: #2563eb;
        --brand-primary-light: rgba(59, 130, 246, 0.16);
        --brand-accent: #38bdf8;
        --text-heading: #f8fafc;
        --text-body: #cbd5e1;
        --text-muted: #94a3b8;
        --border-color: #2b394f;
        --bg-page: #0b1329;
        --bg-card: #152033;
    }

    [data-theme="midnight-slate"] body {
        background-color: #0b1329 !important;
        color: #cbd5e1 !important;
    }

    [data-theme="midnight-slate"] .topbar {
        background: #0f1a30 !important;
        border-color: #1e2c45 !important;
    }

    [data-theme="midnight-slate"] .topbar-brand,
    [data-theme="midnight-slate"] .logo-area,
    [data-theme="midnight-slate"] .topbar-brand span,
    [data-theme="midnight-slate"] .logo-text {
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .topbar-brand-icon,
    [data-theme="midnight-slate"] .member-brand-icon,
    [data-theme="midnight-slate"] .topbar-logo-icon,
    [data-theme="midnight-slate"] .logo-icon {
        background: #2563eb !important;
        color: #ffffff !important;
        box-shadow: 0 2px 10px rgba(37, 99, 235, 0.45) !important;
    }

    [data-theme="midnight-slate"] .topbar-brand-icon svg,
    [data-theme="midnight-slate"] .member-brand-icon svg,
    [data-theme="midnight-slate"] .topbar-logo-icon svg,
    [data-theme="midnight-slate"] .logo-icon svg {
        color: #ffffff !important;
    }

    [data-theme="midnight-slate"] .topbar-nav-link {
        color: #94a3b8 !important;
    }

    [data-theme="midnight-slate"] .topbar-nav-link:hover {
        background: #192742 !important;
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .topbar-nav-link.active {
        background: rgba(56, 189, 248, 0.16) !important;
        color: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .topbar-icon-btn {
        color: #94a3b8 !important;
        border-color: #2b394f !important;
    }

    [data-theme="midnight-slate"] .topbar-icon-btn:hover {
        background: #192742 !important;
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .topbar-user-badge {
        background: rgba(56, 189, 248, 0.14) !important;
        color: #38bdf8 !important;
        border-color: rgba(56, 189, 248, 0.3) !important;
    }

    [data-theme="midnight-slate"] .topbar-user-dropdown,
    [data-theme="midnight-slate"] .user-dropdown {
        background: #101a2f !important;
        border-color: #26354d !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
    }

    [data-theme="midnight-slate"] .topbar-dropdown-header {
        border-color: #1c2a42 !important;
    }

    [data-theme="midnight-slate"] .topbar-dropdown-user-name {
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .topbar-dropdown-user-email {
        color: #94a3b8 !important;
    }

    [data-theme="midnight-slate"] .topbar-dropdown-item,
    [data-theme="midnight-slate"] .user-dropdown-item {
        color: #cbd5e1 !important;
    }

    [data-theme="midnight-slate"] .topbar-dropdown-item:hover,
    [data-theme="midnight-slate"] .user-dropdown-item:hover {
        background: #18263f !important;
        color: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .topbar-dropdown-divider,
    [data-theme="midnight-slate"] .user-dropdown-divider {
        background: #1c2a42 !important;
    }

    [data-theme="midnight-slate"] .sidebar {
        background: #101a2f !important;
        border-color: #22324b !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="midnight-slate"] .sidebar-header {
        border-color: #1c2a42 !important;
    }

    [data-theme="midnight-slate"] .sidebar-header-icon {
        background: #2563eb !important;
        color: #ffffff !important;
    }

    [data-theme="midnight-slate"] .sidebar-header-text h3 {
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .sidebar-header-text span {
        color: #94a3b8 !important;
    }

    [data-theme="midnight-slate"] .sidebar-section-title {
        color: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .sidebar-link {
        color: #cbd5e1 !important;
    }

    [data-theme="midnight-slate"] .sidebar-link:hover {
        background: #192742 !important;
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .sidebar-link.active {
        background: rgba(56, 189, 248, 0.16) !important;
        color: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .sidebar-link-icon {
        color: inherit !important;
    }

    [data-theme="midnight-slate"] .sidebar-user {
        border-color: #1c2a42 !important;
        background: #0c1424 !important;
    }

    [data-theme="midnight-slate"] .sidebar-user-name {
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .sidebar-user-role {
        color: #94a3b8 !important;
    }

    [data-theme="midnight-slate"] .sidebar-avatar {
        background: #1d2c48 !important;
        color: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .card,
    [data-theme="midnight-slate"] .stat-box,
    [data-theme="midnight-slate"] .stat-card,
    [data-theme="midnight-slate"] .summary-card,
    [data-theme="midnight-slate"] .profile-card-section,
    [data-theme="midnight-slate"] .data-card,
    [data-theme="midnight-slate"] .table-card,
    [data-theme="midnight-slate"] .card-panel,
    [data-theme="midnight-slate"] .manage-card,
    [data-theme="midnight-slate"] .monthly-report-card,
    [data-theme="midnight-slate"] .report-card,
    [data-theme="midnight-slate"] .riwayat-card,
    [data-theme="midnight-slate"] .loan-card,
    [data-theme="midnight-slate"] .synopsis-card,
    [data-theme="midnight-slate"] .spec-card,
    [data-theme="midnight-slate"] .about-card,
    [data-theme="midnight-slate"] .related-card,
    [data-theme="midnight-slate"] .option-card,
    [data-theme="midnight-slate"] .edit-card,
    [data-theme="midnight-slate"] .filter-card,
    [data-theme="midnight-slate"] .book-meta-box,
    [data-theme="midnight-slate"] .tray-box,
    [data-theme="midnight-slate"] .tray-row,
    [data-theme="midnight-slate"] .book-item-card,
    [data-theme="midnight-slate"] .pref-section-card,
    [data-theme="midnight-slate"] .rekomendasi-card,
    [data-theme="midnight-slate"] .universal-search-card,
    [data-theme="midnight-slate"] .booking-popup-card,
    [data-theme="midnight-slate"] .member-realtime-modal,
    [data-theme="midnight-slate"] .qris-card,
    [data-theme="midnight-slate"] .rincian-card-box,
    [data-theme="midnight-slate"] .sukses-card,
    [data-theme="midnight-slate"] .ticket-qr-card,
    [data-theme="midnight-slate"] .ticket-detail-card,
    [data-theme="midnight-slate"] .cart-items-card,
    [data-theme="midnight-slate"] .checkout-card,
    [data-theme="midnight-slate"] .main-card,
    [data-theme="midnight-slate"] .modal-card,
    [data-theme="midnight-slate"] .receipt-card,
    [data-theme="midnight-slate"] .summary-rule-card,
    [data-theme="midnight-slate"] .term-article-card,
    [data-theme="midnight-slate"] .step-card,
    [data-theme="midnight-slate"] .feature-card,
    [data-theme="midnight-slate"] .faq-item,
    [data-theme="midnight-slate"] .form-card,
    [data-theme="midnight-slate"] .cta-guide-card,
    [data-theme="midnight-slate"] .about-hero,
    [data-theme="midnight-slate"] .about-values,
    [data-theme="midnight-slate"] .about-value-card,
    [data-theme="midnight-slate"] .about-info-card,
    [data-theme="midnight-slate"] .about-membership,
    [data-theme="midnight-slate"] .principle-card,
    [data-theme="midnight-slate"] .policy-card,
    [data-theme="midnight-slate"] .figma-table-container,
    [data-theme="midnight-slate"] .rincian-card {
        background: #141f33 !important;
        border-color: #24354e !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="midnight-slate"] .section-header-title,
    [data-theme="midnight-slate"] .dashboard-title,
    [data-theme="midnight-slate"] .profile-title,
    [data-theme="midnight-slate"] .page-title,
    [data-theme="midnight-slate"] .section-title,
    [data-theme="midnight-slate"] .section-heading-title,
    [data-theme="midnight-slate"] .report-card-title,
    [data-theme="midnight-slate"] .manage-title,
    [data-theme="midnight-slate"] .riwayat-card-title,
    [data-theme="midnight-slate"] .welcome-title,
    [data-theme="midnight-slate"] .loan-title,
    [data-theme="midnight-slate"] .loan-card-title,
    [data-theme="midnight-slate"] .option-title,
    [data-theme="midnight-slate"] .book-showcase-title,
    [data-theme="midnight-slate"] .meta-val,
    [data-theme="midnight-slate"] .tray-title,
    [data-theme="midnight-slate"] .card-title-lg,
    [data-theme="midnight-slate"] .card-title-md,
    [data-theme="midnight-slate"] .card-title-xl,
    [data-theme="midnight-slate"] .panel-title,
    [data-theme="midnight-slate"] .cart-title,
    [data-theme="midnight-slate"] .cart-item-title,
    [data-theme="midnight-slate"] .detail-card-title,
    [data-theme="midnight-slate"] .book-item-title,
    [data-theme="midnight-slate"] .book-title-main,
    [data-theme="midnight-slate"] .custom-table td strong {
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .page-subtitle,
    [data-theme="midnight-slate"] .dashboard-subtitle,
    [data-theme="midnight-slate"] .section-subtitle,
    [data-theme="midnight-slate"] .manage-subtitle,
    [data-theme="midnight-slate"] .report-card-desc,
    [data-theme="midnight-slate"] .book-showcase-author,
    [data-theme="midnight-slate"] .option-desc,
    [data-theme="midnight-slate"] .synopsis-content,
    [data-theme="midnight-slate"] .loan-subtitle,
    [data-theme="midnight-slate"] .circ-subtitle,
    [data-theme="midnight-slate"] .stat-note,
    [data-theme="midnight-slate"] .stat-caption,
    [data-theme="midnight-slate"] .stat-label,
    [data-theme="midnight-slate"] .member-email,
    [data-theme="midnight-slate"] .sirkulasi-subtitle,
    [data-theme="midnight-slate"] .user-meta-sub {
        color: #94a3b8 !important;
    }

    [data-theme="midnight-slate"] .form-label,
    [data-theme="midnight-slate"] .filter-label,
    [data-theme="midnight-slate"] .table-search-label,
    [data-theme="midnight-slate"] .presets-label {
        color: #cbd5e1 !important;
    }

    [data-theme="midnight-slate"] .form-control,
    [data-theme="midnight-slate"] .form-control-select,
    [data-theme="midnight-slate"] .form-control-date,
    [data-theme="midnight-slate"] .form-input,
    [data-theme="midnight-slate"] .form-select,
    [data-theme="midnight-slate"] .form-textarea,
    [data-theme="midnight-slate"] .filter-input,
    [data-theme="midnight-slate"] .search-input,
    [data-theme="midnight-slate"] .search-input-field,
    [data-theme="midnight-slate"] .search-book-input,
    [data-theme="midnight-slate"] .filter-select {
        background: #0c1424 !important;
        border-color: #2b3b55 !important;
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .form-control:focus,
    [data-theme="midnight-slate"] .form-control-select:focus,
    [data-theme="midnight-slate"] .form-control-date:focus,
    [data-theme="midnight-slate"] .form-input:focus,
    [data-theme="midnight-slate"] .form-select:focus,
    [data-theme="midnight-slate"] .form-textarea:focus,
    [data-theme="midnight-slate"] .filter-input:focus,
    [data-theme="midnight-slate"] .search-input:focus,
    [data-theme="midnight-slate"] .search-input-field:focus,
    [data-theme="midnight-slate"] .search-book-input:focus,
    [data-theme="midnight-slate"] .filter-select:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.22) !important;
    }

    [data-theme="midnight-slate"] .btn-primary,
    [data-theme="midnight-slate"] .btn-action-primary,
    [data-theme="midnight-slate"] .btn-action-simpan,
    [data-theme="midnight-slate"] .btn-cari-buku,
    [data-theme="midnight-slate"] .btn-katalog {
        background: #2563eb !important;
        border-color: #1d4ed8 !important;
        color: #ffffff !important;
    }

    [data-theme="midnight-slate"] .btn-primary:hover,
    [data-theme="midnight-slate"] .btn-action-primary:hover,
    [data-theme="midnight-slate"] .btn-action-simpan:hover,
    [data-theme="midnight-slate"] .btn-cari-buku:hover,
    [data-theme="midnight-slate"] .btn-katalog:hover {
        background: #1d4ed8 !important;
        border-color: #1e40af !important;
    }

    [data-theme="midnight-slate"] .btn-filter-reset,
    [data-theme="midnight-slate"] .btn-reset,
    [data-theme="midnight-slate"] .btn-reset-filter,
    [data-theme="midnight-slate"] .btn-action-outline,
    [data-theme="midnight-slate"] .btn-action-secondary {
        background: #141f33 !important;
        color: #cbd5e1 !important;
        border-color: #2b3b55 !important;
    }

    [data-theme="midnight-slate"] .btn-filter-reset:hover,
    [data-theme="midnight-slate"] .btn-reset:hover,
    [data-theme="midnight-slate"] .btn-reset-filter:hover,
    [data-theme="midnight-slate"] .btn-action-outline:hover,
    [data-theme="midnight-slate"] .btn-action-secondary:hover {
        background: #1e2c45 !important;
        color: #f8fafc !important;
        border-color: #3b82f6 !important;
    }

    [data-theme="midnight-slate"] .custom-table th,
    [data-theme="midnight-slate"] table th {
        background: #0f192b !important;
        color: #cbd5e1 !important;
        border-color: #24354e !important;
    }

    [data-theme="midnight-slate"] .custom-table td,
    [data-theme="midnight-slate"] table td {
        border-color: #24354e !important;
        color: #cbd5e1 !important;
    }

    [data-theme="midnight-slate"] .custom-table tr:hover td,
    [data-theme="midnight-slate"] table tbody tr:hover td {
        background: #192742 !important;
    }

    [data-theme="midnight-slate"] .pref-option-card {
        background: #101a2f;
        border-color: #24354e;
    }

    [data-theme="midnight-slate"] .pref-option-card:hover {
        border-color: #38bdf8;
    }

    [data-theme="midnight-slate"] .pref-option-card.active {
        border-color: #38bdf8;
        background: #14223d;
    }

    [data-theme="midnight-slate"] .tab-nav-btn.active {
        background: #162238;
        color: #38bdf8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    /* Midnight Slate Dashboard & Greeting Banner Overrides */
    [data-theme="midnight-slate"] .welcome-banner {
        background: linear-gradient(135deg, #131f38 0%, #0f172a 100%) !important;
        border-color: #23385d !important;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.35) !important;
    }

    [data-theme="midnight-slate"] .welcome-title {
        color: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .welcome-meta {
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .welcome-desc {
        color: #94a3b8 !important;
    }

    [data-theme="midnight-slate"] .breadcrumb {
        color: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .stat-icon:not([style*="color"]) {
        background: rgba(56, 189, 248, 0.16) !important;
        color: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .stat-box {
        background: #131d31 !important;
        border-color: #23314d !important;
    }

    [data-theme="midnight-slate"] .stat-box:hover {
        border-color: #33486e !important;
        box-shadow: 0 10px 24px -4px rgba(0, 0, 0, 0.4) !important;
    }

    [data-theme="midnight-slate"] .denda-alert-banner {
        background: #141b2d !important;
        border-color: #23385d !important;
    }

    /* ========================================================
       VAN GOGH THEME ENGINE (STARRY NIGHT / POST-IMPRESSIONIST)
       Cobalt Blue, Ultramarine, Muted Teal, Warm Ivory & Golden Stars
       ======================================================== */
    [data-theme="van-gogh"] {
        --theme-text: #f6f0df;
        --theme-heading: #f6f0df;
        --theme-muted: #9fb3c8;
        --theme-surface: #132238;
        --theme-bg: #0b1726;
        --theme-border: #22446a;
        --theme-border-subtle: #1a3454;
        --theme-accent: #f59e0b;
        --theme-accent-hover: #d97706;
        --theme-accent-light: rgba(245, 158, 11, 0.16);
        --theme-active-indicator: #f59e0b;
        --theme-icon: #f59e0b;
        --theme-icon-muted: #9fb3c8;
        --theme-btn-primary-bg: #245b8f;
        --theme-btn-primary-text: #ffffff;
        --theme-btn-primary-hover: #1b456e;
        --theme-btn-secondary-bg: #132238;
        --theme-btn-secondary-border: #22446a;
        --theme-btn-secondary-text: #f6f0df;
        --theme-btn-secondary-hover: #193354;
        --opening-photo-glow: 0 16px 36px -10px rgba(11, 23, 38, 0.85), 0 0 32px rgba(245, 158, 11, 0.4);
        --opening-photo-overlay: linear-gradient(135deg, rgba(16, 42, 67, 0.88) 0%, rgba(36, 91, 143, 0.55) 100%);
        --opening-photo-border: #f59e0b;

        --brand-primary: #245b8f;
        --brand-primary-dark: #1b456e;
        --brand-primary-light: rgba(36, 91, 143, 0.25);
        --brand-accent: #f59e0b;
        --text-heading: #f6f0df;
        --text-body: #e2e8f0;
        --text-muted: #9fb3c8;
        --border-color: #22446a;
        --bg-page: #0b1726;
        --bg-card: #132238;

        /* Dedicated Van Gogh Starry Night Identity Tokens */
        --vg-deep-navy: #102a43;
        --vg-cobalt: #245b8f;
        --vg-sky-blue: #5e9bc5;
        --vg-gold: #e9b949;
        --vg-warm-cream: #f6f0df;
        --vg-dark-ink: #172b4d;

        /* High contrast & Post-Impressionist Depth Tokens */
        --vg-ultramarine: #102a43;
        --vg-navy-deep: #0b1726;
        --vg-teal-muted: #1a4d6b;
        --vg-star-gold: #f59e0b;
        --vg-star-glow: rgba(245, 158, 11, 0.38);
        --vg-text-gold: #fcd34d;       /* High contrast (11:1) on dark backgrounds */
        --vg-text-gold-dark: #92400e;  /* High contrast (6.3:1) on light amber/cream */
        --vg-brush-gradient: linear-gradient(90deg, #1b456e 0%, #245b8f 25%, #fcd34d 55%, #f59e0b 78%, #1b456e 100%);
    }

    /* 1. Body & Viewport Canvas: Swirling Post-Impressionist Starry Night Sky */
    [data-theme="van-gogh"] body {
        background-color: #0b1726 !important;
        background-image: 
            radial-gradient(circle at 82% 14%, rgba(245, 158, 11, 0.22) 0%, rgba(233, 185, 73, 0.08) 22%, transparent 45%),
            radial-gradient(ellipse at 15% 25%, rgba(36, 91, 143, 0.35) 0%, rgba(16, 42, 67, 0.22) 35%, transparent 58%),
            radial-gradient(ellipse at 85% 75%, rgba(26, 77, 107, 0.25) 0%, transparent 45%),
            radial-gradient(circle at 45% 85%, rgba(27, 69, 110, 0.28) 0%, transparent 50%),
            conic-gradient(from 215deg at 70% 30%, transparent 0deg, rgba(36, 91, 143, 0.12) 60deg, rgba(245, 158, 11, 0.07) 120deg, transparent 180deg) !important;
        background-attachment: fixed !important;
        color: #e2e8f0 !important;
    }

    /* 2. Topbar & Brand Identity */
    [data-theme="van-gogh"] .topbar {
        background: linear-gradient(90deg, #0e1e33 0%, #10253f 50%, #0d1a2c 100%) !important;
        border-color: #1e3a5f !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
    }

    [data-theme="van-gogh"] .topbar-brand,
    [data-theme="van-gogh"] .member-brand,
    [data-theme="van-gogh"] .logo-area,
    [data-theme="van-gogh"] .topbar-brand span,
    [data-theme="van-gogh"] .member-brand span,
    [data-theme="van-gogh"] .logo-text {
        color: #f6f0df !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    [data-theme="van-gogh"] .topbar-brand:hover span,
    [data-theme="van-gogh"] .member-brand:hover span {
        color: #fcd34d !important;
    }

    [data-theme="van-gogh"] .topbar-brand-icon,
    [data-theme="van-gogh"] .member-brand-icon,
    [data-theme="van-gogh"] .sidebar-header-icon,
    [data-theme="van-gogh"] .topbar-logo-icon,
    [data-theme="van-gogh"] .logo-icon {
        background: radial-gradient(circle at 35% 35%, #2d6ca8 0%, #15375d 100%) !important;
        color: #fcd34d !important;
        box-shadow: 0 0 16px rgba(245, 158, 11, 0.4), inset 0 0 8px rgba(245, 158, 11, 0.2) !important;
        border: 1px solid rgba(245, 158, 11, 0.55) !important;
    }

    [data-theme="van-gogh"] .topbar-brand-icon svg,
    [data-theme="van-gogh"] .member-brand-icon svg,
    [data-theme="van-gogh"] .sidebar-header-icon svg,
    [data-theme="van-gogh"] .topbar-logo-icon svg,
    [data-theme="van-gogh"] .logo-icon svg {
        color: #fcd34d !important;
        filter: drop-shadow(0 0 4px rgba(245, 158, 11, 0.4));
    }

    [data-theme="van-gogh"] .topbar-nav-link {
        color: #9fb3c8 !important;
    }

    [data-theme="van-gogh"] .topbar-nav-link:hover {
        background: #193354 !important;
        color: #f6f0df !important;
    }

    [data-theme="van-gogh"] .topbar-nav-link.active {
        background: rgba(245, 158, 11, 0.18) !important;
        color: #fcd34d !important;
        font-weight: 700 !important;
        border-bottom: 2px solid #f59e0b !important;
    }

    [data-theme="van-gogh"] .topbar-icon-btn {
        color: #9fb3c8 !important;
        border-color: #22446a !important;
    }

    [data-theme="van-gogh"] .topbar-icon-btn:hover {
        background: #193354 !important;
        color: #fcd34d !important;
        border-color: #f59e0b !important;
    }

    [data-theme="van-gogh"] .topbar-user-badge {
        background: rgba(245, 158, 11, 0.18) !important;
        color: #fcd34d !important;
        border-color: rgba(245, 158, 11, 0.4) !important;
        font-weight: 600 !important;
    }

    [data-theme="van-gogh"] .topbar-user-dropdown,
    [data-theme="van-gogh"] .user-dropdown {
        background: linear-gradient(180deg, #132238 0%, #0d1b2a 100%) !important;
        border: 1px solid #22446a !important;
        border-top: 2px solid #f59e0b !important;
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.7), 0 0 16px rgba(36, 91, 143, 0.3) !important;
    }

    [data-theme="van-gogh"] .topbar-dropdown-header {
        border-bottom-color: #22446a !important;
    }

    [data-theme="van-gogh"] .topbar-dropdown-user-name {
        color: #f6f0df !important;
    }

    [data-theme="van-gogh"] .topbar-dropdown-user-email {
        color: #9fb3c8 !important;
    }

    [data-theme="van-gogh"] .topbar-dropdown-item,
    [data-theme="van-gogh"] .user-dropdown-item {
        color: #e2e8f0 !important;
    }

    [data-theme="van-gogh"] .topbar-dropdown-item:hover,
    [data-theme="van-gogh"] .user-dropdown-item:hover {
        background: #193354 !important;
        color: #fcd34d !important;
    }

    [data-theme="van-gogh"] .topbar-dropdown-divider {
        background-color: #22446a !important;
    }

    /* 3. Sidebar Panel (Medium Intensity Level 2) */
    [data-theme="van-gogh"] .sidebar,
    [data-theme="van-gogh"] .main-sidebar,
    [data-theme="van-gogh"] .app-sidebar,
    [data-theme="van-gogh"] aside {
        background: linear-gradient(180deg, #10233b 0%, #0c1828 100%) !important;
        border-color: #1e3a5f !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.45) !important;
    }

    [data-theme="van-gogh"] .sidebar-header,
    [data-theme="van-gogh"] .sidebar-footer {
        border-color: #1e3a5f !important;
    }

    [data-theme="van-gogh"] .sidebar-header-icon {
        background: radial-gradient(circle at 35% 35%, #2d6ca8 0%, #15375d 100%) !important;
        color: #fcd34d !important;
        box-shadow: 0 0 14px rgba(245, 158, 11, 0.35) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
    }

    [data-theme="van-gogh"] .sidebar-header-icon svg {
        color: #fcd34d !important;
        stroke: currentColor !important;
    }

    [data-theme="van-gogh"] .sidebar-header-text h3,
    [data-theme="van-gogh"] .sidebar-brand-name,
    [data-theme="van-gogh"] .sidebar-brand-text,
    [data-theme="van-gogh"] .brand-title {
        color: #f6f0df !important;
        font-weight: 800 !important;
        letter-spacing: -0.2px;
        transition: color 0.15s ease;
    }

    [data-theme="van-gogh"] .sidebar-header-text span,
    [data-theme="van-gogh"] .sidebar-brand-desc,
    [data-theme="van-gogh"] .brand-subtitle {
        color: #9fb3c8 !important;
    }

    [data-theme="van-gogh"] .sidebar-close-mobile-btn {
        background: #162c47 !important;
        color: #f6f0df !important;
        border: 1px solid #1e3a5f !important;
    }

    [data-theme="van-gogh"] .sidebar-close-mobile-btn:hover {
        background: #1f3c60 !important;
        color: #fcd34d !important;
        border-color: #f59e0b !important;
    }

    [data-theme="van-gogh"] .sidebar-badge {
        background: rgba(245, 158, 11, 0.18) !important;
        color: #fcd34d !important;
        border-color: rgba(245, 158, 11, 0.4) !important;
    }

    [data-theme="van-gogh"] .sidebar-menu-title,
    [data-theme="van-gogh"] .sidebar-section-title,
    [data-theme="van-gogh"] .nav-header {
        color: #88a4c2 !important;
        letter-spacing: 0.8px;
    }

    [data-theme="van-gogh"] .sidebar-link,
    [data-theme="van-gogh"] .nav-link,
    [data-theme="van-gogh"] .sidebar-nav-link {
        color: #9fb3c8 !important;
        border-radius: 8px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    [data-theme="van-gogh"] .sidebar-link:hover:not(.active),
    [data-theme="van-gogh"] .nav-link:hover:not(.active),
    [data-theme="van-gogh"] .sidebar-nav-link:hover:not(.active) {
        background: #193354 !important;
        color: #f6f0df !important;
    }

    [data-theme="van-gogh"] .sidebar-link:hover:not(.active) .sidebar-link-icon,
    [data-theme="van-gogh"] .nav-link:hover:not(.active) svg,
    [data-theme="van-gogh"] .sidebar-nav-link:hover:not(.active) svg {
        color: #fcd34d !important;
    }

    [data-theme="van-gogh"] .sidebar-user-name {
        color: #f6f0df !important;
    }

    [data-theme="van-gogh"] .sidebar-user-role {
        color: #9fb3c8 !important;
    }

    [data-theme="van-gogh"] .sidebar-btn-logout {
        color: #fcd34d !important;
        border-color: #22446a !important;
    }

    [data-theme="van-gogh"] .sidebar-btn-logout:hover {
        background: rgba(245, 158, 11, 0.16) !important;
        border-color: #f59e0b !important;
    }

    /* 4. Cards & Containers (Level 2 Medium Intensity — Impasto Border & Oil Paint Depth) */
    [data-theme="van-gogh"] .content-card,
    [data-theme="van-gogh"] .stat-card,
    [data-theme="van-gogh"] .stat-box,
    [data-theme="van-gogh"] .summary-card,
    [data-theme="van-gogh"] .profile-card-section,
    [data-theme="van-gogh"] .card,
    [data-theme="van-gogh"] .data-card,
    [data-theme="van-gogh"] .table-card,
    [data-theme="van-gogh"] .card-panel,
    [data-theme="van-gogh"] .manage-card,
    [data-theme="van-gogh"] .monthly-report-card,
    [data-theme="van-gogh"] .report-card,
    [data-theme="van-gogh"] .riwayat-card,
    [data-theme="van-gogh"] .loan-card,
    [data-theme="van-gogh"] .synopsis-card,
    [data-theme="van-gogh"] .spec-card,
    [data-theme="van-gogh"] .about-card,
    [data-theme="van-gogh"] .related-card,
    [data-theme="van-gogh"] .option-card,
    [data-theme="van-gogh"] .edit-card,
    [data-theme="van-gogh"] .filter-card,
    [data-theme="van-gogh"] .book-meta-box,
    [data-theme="van-gogh"] .tray-box,
    [data-theme="van-gogh"] .tray-row,
    [data-theme="van-gogh"] .book-item-card,
    [data-theme="van-gogh"] .modal-content,
    [data-theme="van-gogh"] .katalog-card,
    [data-theme="van-gogh"] .book-card,
    [data-theme="van-gogh"] .detail-card,
    [data-theme="van-gogh"] .panel-box,
    [data-theme="van-gogh"] .profile-card,
    [data-theme="van-gogh"] .pref-section-card,
    [data-theme="van-gogh"] .rekomendasi-card,
    [data-theme="van-gogh"] .universal-search-card,
    [data-theme="van-gogh"] .booking-popup-card,
    [data-theme="van-gogh"] .member-realtime-modal,
    [data-theme="van-gogh"] .qris-card,
    [data-theme="van-gogh"] .rincian-card-box,
    [data-theme="van-gogh"] .sukses-card,
    [data-theme="van-gogh"] .ticket-qr-card,
    [data-theme="van-gogh"] .ticket-detail-card,
    [data-theme="van-gogh"] .cart-items-card,
    [data-theme="van-gogh"] .checkout-card,
    [data-theme="van-gogh"] .main-card,
    [data-theme="van-gogh"] .modal-card,
    [data-theme="van-gogh"] .receipt-card,
    [data-theme="van-gogh"] .summary-rule-card,
    [data-theme="van-gogh"] .term-article-card,
    [data-theme="van-gogh"] .step-card,
    [data-theme="van-gogh"] .feature-card,
    [data-theme="van-gogh"] .faq-item,
    [data-theme="van-gogh"] .form-card,
    [data-theme="van-gogh"] .cta-guide-card,
    [data-theme="van-gogh"] .figma-table-container,
    [data-theme="van-gogh"] .about-hero,
    [data-theme="van-gogh"] .about-values,
    [data-theme="van-gogh"] .about-value-card,
    [data-theme="van-gogh"] .about-info-card,
    [data-theme="van-gogh"] .about-membership,
    [data-theme="van-gogh"] .principle-card,
    [data-theme="van-gogh"] .policy-card,
    [data-theme="van-gogh"] .rincian-card {
        background: linear-gradient(180deg, #14243b 0%, #101c2e 100%) !important;
        border: 1px solid #1e3a5f !important;
        border-top: 2px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45), 0 0 1px rgba(245, 158, 11, 0.15) !important;
        color: #e2e8f0 !important;
    }

    [data-theme="van-gogh"] .card:hover,
    [data-theme="van-gogh"] .stat-box:hover,
    [data-theme="van-gogh"] .katalog-card:hover,
    [data-theme="van-gogh"] .book-card:hover,
    [data-theme="van-gogh"] .rekomendasi-card:hover {
        border-color: rgba(245, 158, 11, 0.6) !important;
        box-shadow: 0 10px 28px -4px rgba(0, 0, 0, 0.6), 0 0 16px rgba(245, 158, 11, 0.25) !important;
    }

    /* Van Gogh Rekomendasi Cards (High Contrast Post-Impressionist Depth & Warm Ivory Typography) */
    [data-theme="van-gogh"] .rekomendasi-card {
        background: linear-gradient(180deg, #14243b 0%, #101c2e 100%) !important;
        border: 1px solid #1e3a5f !important;
        border-top: 2px solid rgba(245, 158, 11, 0.45) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
    }

    [data-theme="van-gogh"] .rekomendasi-cover-wrap {
        background: #0d1a2c !important;
        border: 1px solid #1e3a5f !important;
    }

    [data-theme="van-gogh"] .rekomendasi-judul {
        color: #f6f0df !important;
        font-weight: 800 !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5) !important;
        transition: color 0.18s ease !important;
    }

    [data-theme="van-gogh"] .rekomendasi-card:hover .rekomendasi-judul {
        color: #fcd34d !important;
        text-shadow: 0 0 8px rgba(245, 158, 11, 0.35) !important;
    }

    [data-theme="van-gogh"] .rekomendasi-author {
        color: #9fb3c8 !important;
    }

    [data-theme="van-gogh"] .rekomendasi-meta {
        color: #cbd5e1 !important;
        border-top: 1px solid #1e3a5f !important;
    }

    [data-theme="van-gogh"] .rekomendasi-meta span {
        color: #cbd5e1 !important;
    }

    [data-theme="van-gogh"] .rekomendasi-card .badge-tersedia {
        background: rgba(34, 197, 94, 0.18) !important;
        color: #4ade80 !important;
        border: 1px solid rgba(74, 222, 128, 0.35) !important;
    }

    [data-theme="van-gogh"] .rekomendasi-card .badge-tersedia .dot {
        background: #4ade80 !important;
    }

    [data-theme="van-gogh"] .rekomendasi-card .badge-rating {
        color: #fcd34d !important;
        font-weight: 700 !important;
    }

    [data-theme="van-gogh"] .btn-pinjam-buku {
        background: #245b8f !important;
        color: #ffffff !important;
        border: 1px solid #1e3a5f !important;
        font-weight: 700 !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    [data-theme="van-gogh"] .btn-pinjam-buku:hover {
        background: #1b456e !important;
        color: #f6f0df !important;
        border-color: #f59e0b !important;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.35) !important;
        transform: translateY(-2px) !important;
    }

    /* 5. Form Controls (Level 3 Minimal — Maximum Readability & Precision) */
    [data-theme="van-gogh"] .form-control,
    [data-theme="van-gogh"] .form-select,
    [data-theme="van-gogh"] input[type="text"],
    [data-theme="van-gogh"] input[type="email"],
    [data-theme="van-gogh"] input[type="password"],
    [data-theme="van-gogh"] input[type="number"],
    [data-theme="van-gogh"] input[type="date"],
    [data-theme="van-gogh"] input[type="search"],
    [data-theme="van-gogh"] textarea,
    [data-theme="van-gogh"] select,
    [data-theme="van-gogh"] .opening-select-control {
        background-color: #0d1b2a !important;
        border: 1px solid #22446a !important;
        color: #f6f0df !important;
    }

    [data-theme="van-gogh"] input::placeholder,
    [data-theme="van-gogh"] textarea::placeholder {
        color: #7892ab !important;
    }

    [data-theme="van-gogh"] .form-control:focus,
    [data-theme="van-gogh"] .form-select:focus,
    [data-theme="van-gogh"] input:focus,
    [data-theme="van-gogh"] textarea:focus,
    [data-theme="van-gogh"] select:focus,
    [data-theme="van-gogh"] .opening-select-control:focus {
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25) !important;
    }

    [data-theme="van-gogh"] input[type="checkbox"],
    [data-theme="van-gogh"] input[type="radio"] {
        accent-color: #f59e0b !important;
    }

    /* 5b. Searchbars & Filter Wrappers (Universal Consistency Across All Pages) */
    [data-theme="van-gogh"] .table-search-label,
    [data-theme="van-gogh"] .filter-section-label,
    [data-theme="van-gogh"] .search-label,
    [data-theme="van-gogh"] .dropdown-label {
        color: #f6f0df !important;
        font-weight: 700 !important;
    }

    [data-theme="van-gogh"] .table-search-input-wrap,
    [data-theme="van-gogh"] .search-input-wrap,
    [data-theme="van-gogh"] .search-input-wrapper,
    [data-theme="van-gogh"] .search-bar-row .search-input-wrap,
    [data-theme="van-gogh"] .filter-input-wrap,
    [data-theme="van-gogh"] .filter-search-box,
    [data-theme="van-gogh"] .dropdown-select-wrap {
        background: #0d1b2a !important;
        border: 1px solid #22446a !important;
        border-radius: 9px !important;
        color: #f6f0df !important;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.35) !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
    }

    [data-theme="van-gogh"] .table-search-input-wrap:focus-within,
    [data-theme="van-gogh"] .search-input-wrap:focus-within,
    [data-theme="van-gogh"] .search-input-wrapper:focus-within,
    [data-theme="van-gogh"] .search-bar-row .search-input-wrap:focus-within,
    [data-theme="van-gogh"] .filter-input-wrap:focus-within,
    [data-theme="van-gogh"] .filter-search-box:focus-within,
    [data-theme="van-gogh"] .dropdown-select-wrap:focus-within {
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.22), 0 0 14px rgba(245, 158, 11, 0.2) !important;
    }

    /* Seamless inner inputs inside search wrappers: eliminate nested dark boxes & double borders */
    [data-theme="van-gogh"] .table-search-input-wrap input,
    [data-theme="van-gogh"] .filter-input-wrap input,
    [data-theme="van-gogh"] .filter-search-box input {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        outline: none !important;
        color: #f6f0df !important;
    }

    [data-theme="van-gogh"] .table-search-input-wrap input {
        padding-left: 0 !important;
    }

    [data-theme="van-gogh"] .search-input-wrap input,
    [data-theme="van-gogh"] .search-input-wrapper input,
    [data-theme="van-gogh"] .search-input {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        outline: none !important;
        color: #f6f0df !important;
        padding-left: 44px !important;
        padding-right: 14px !important;
    }

    /* Universal safety rule: never allow absolute search icon to overlap input text */
    .search-input-wrap input,
    .search-input-wrapper input,
    .search-input {
        padding-left: 44px !important;
    }

    [data-theme="van-gogh"] .table-search-input-wrap input::placeholder,
    [data-theme="van-gogh"] .search-input-wrap input::placeholder,
    [data-theme="van-gogh"] .search-input-wrapper input::placeholder,
    [data-theme="van-gogh"] .filter-input-wrap input::placeholder,
    [data-theme="van-gogh"] .filter-search-box input::placeholder,
    [data-theme="van-gogh"] .search-input::placeholder,
    [data-theme="van-gogh"] .search-input-field::placeholder,
    [data-theme="van-gogh"] .search-book-input::placeholder {
        color: #8da4be !important;
        opacity: 1 !important;
    }

    [data-theme="van-gogh"] .table-search-input-wrap svg,
    [data-theme="van-gogh"] .search-input-wrap svg,
    [data-theme="van-gogh"] .search-input-wrapper svg,
    [data-theme="van-gogh"] .filter-input-wrap svg,
    [data-theme="van-gogh"] .filter-search-box svg {
        color: #f59e0b !important;
        stroke: #f59e0b !important;
    }

    [data-theme="van-gogh"] .btn-table-search,
    [data-theme="van-gogh"] .btn-search,
    [data-theme="van-gogh"] .btn-search-trx {
        background: #245b8f !important;
        color: #ffffff !important;
        border: 1px solid #1e3a5f !important;
        font-weight: 700 !important;
        border-radius: 9px !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    [data-theme="van-gogh"] .btn-table-search:hover,
    [data-theme="van-gogh"] .btn-search:hover,
    [data-theme="van-gogh"] .btn-search-trx:hover {
        background: #1b456e !important;
        color: #f6f0df !important;
        border-color: #f59e0b !important;
        box-shadow: 0 0 14px rgba(245, 158, 11, 0.35) !important;
        transform: translateY(-1px) !important;
    }

    /* 6. Tables (Level 3 Minimal — Professional Clarity for Data Records) */
    [data-theme="van-gogh"] .table th,
    [data-theme="van-gogh"] .custom-table th,
    [data-theme="van-gogh"] table th {
        background: #0f2035 !important;
        border-color: #1e3a5f !important;
        color: #f6f0df !important;
        font-weight: 700 !important;
        letter-spacing: 0.3px;
    }

    [data-theme="van-gogh"] .table td,
    [data-theme="van-gogh"] .custom-table td,
    [data-theme="van-gogh"] table td {
        border-color: #172c47 !important;
        color: #e2e8f0 !important;
    }

    [data-theme="van-gogh"] .table tr:hover td,
    [data-theme="van-gogh"] .custom-table tr:hover td,
    [data-theme="van-gogh"] table tbody tr:hover td {
        background: #193354 !important;
    }

    /* 7. Settings & Preferences Panels */
    [data-theme="van-gogh"] .pref-option-card {
        background: #132238;
        border-color: #22446a;
    }

    [data-theme="van-gogh"] .pref-option-card:hover {
        border-color: #f59e0b;
        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.2);
    }

    [data-theme="van-gogh"] .pref-option-card.active {
        border-color: #f59e0b !important;
        background: linear-gradient(135deg, #183354 0%, #112338 100%);
        box-shadow: 0 0 16px rgba(245, 158, 11, 0.25) !important;
    }

    [data-theme="van-gogh"] .pref-option-card.active .pref-option-badge {
        background: #f59e0b !important;
        color: #0b1726 !important;
        font-weight: 800 !important;
    }

    [data-theme="van-gogh"] .pref-font-card {
        background: #132238;
        border-color: #22446a;
        color: #e2e8f0;
    }

    [data-theme="van-gogh"] .pref-font-card:hover {
        border-color: #f59e0b;
    }

    [data-theme="van-gogh"] .pref-font-card.active {
        border-color: #f59e0b !important;
        background: #183354 !important;
    }

    [data-theme="van-gogh"] .pref-size-btn {
        background: #132238;
        border-color: #22446a;
        color: #e2e8f0;
    }

    [data-theme="van-gogh"] .pref-size-btn:hover {
        border-color: #f59e0b;
    }

    [data-theme="van-gogh"] .pref-size-btn.active {
        background: #f59e0b !important;
        border-color: #f59e0b !important;
        color: #0b1726 !important;
        font-weight: 800 !important;
    }

    [data-theme="van-gogh"] .pref-live-preview-box {
        background: #0b1726;
        border-color: #22446a;
    }

    [data-theme="van-gogh"] .pref-live-preview-badge {
        background: rgba(245, 158, 11, 0.18) !important;
        color: #fcd34d !important;
    }

    [data-theme="van-gogh"] .btn-preview-opening {
        border-color: #f59e0b !important;
        color: #fcd34d !important;
    }

    [data-theme="van-gogh"] .btn-preview-opening:hover {
        background: #f59e0b !important;
        color: #0b1726 !important;
    }

    [data-theme="van-gogh"] .btn-pref-reset {
        border-color: #22446a !important;
        color: #9fb3c8 !important;
    }

    [data-theme="van-gogh"] .btn-pref-reset:hover {
        background: rgba(245, 158, 11, 0.15) !important;
        border-color: #f59e0b !important;
        color: #fcd34d !important;
    }

    [data-theme="van-gogh"] .tab-nav-btn.active {
        background: #183354;
        color: #fcd34d;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    }

    /* 8. Dashboard Hero Banner (Level 1 Highest Intensity Focal Point) */
    [data-theme="van-gogh"] .welcome-banner {
        position: relative;
        overflow: hidden;
        background: 
            radial-gradient(circle at 86% 22%, rgba(245, 158, 11, 0.28) 0%, rgba(233, 185, 73, 0.1) 26%, transparent 52%),
            radial-gradient(circle at 14% 78%, rgba(36, 91, 143, 0.35) 0%, transparent 48%),
            linear-gradient(135deg, #0f243d 0%, #0c1828 65%, #0a1320 100%) !important;
        border: 1px solid #22446a !important;
        border-top: 2.5px solid #f59e0b !important;
        box-shadow: 0 10px 30px -4px rgba(0, 0, 0, 0.6), 0 0 24px rgba(36, 91, 143, 0.25) !important;
    }

    [data-theme="van-gogh"] .welcome-banner::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, #1b456e 0%, #245b8f 25%, #fcd34d 55%, #f59e0b 78%, #1b456e 100%);
        pointer-events: none;
    }

    [data-theme="van-gogh"] .welcome-title {
        color: #fcd34d !important;
        text-shadow: 0 0 16px rgba(245, 158, 11, 0.35);
    }

    [data-theme="van-gogh"] .welcome-meta {
        color: #f6f0df !important;
    }

    [data-theme="van-gogh"] .welcome-desc {
        color: #cbd5e1 !important;
    }

    [data-theme="van-gogh"] .breadcrumb {
        color: #fcd34d !important;
    }

    [data-theme="van-gogh"] .stat-icon:not([style*="color"]),
    [data-theme="van-gogh"] .stat-icon-tile,
    [data-theme="van-gogh"] .stat-icon-teal,
    [data-theme="van-gogh"] .stat-icon--teal,
    [data-theme="van-gogh"] .stat-icon.primary,
    [data-theme="van-gogh"] .book-icon-box {
        background: radial-gradient(circle at 35% 35%, #2a639b 0%, #16365c 100%) !important;
        color: #fcd34d !important;
        border: 1px solid rgba(245, 158, 11, 0.45) !important;
        box-shadow: 0 2px 10px rgba(245, 158, 11, 0.25) !important;
    }

    [data-theme="van-gogh"] .stat-box {
        background: linear-gradient(180deg, #14243b 0%, #101c2e 100%) !important;
        border: 1px solid #1e3a5f !important;
        border-top: 2px solid rgba(245, 158, 11, 0.35) !important;
    }

    [data-theme="van-gogh"] .stat-box:hover {
        border-color: #f59e0b !important;
        box-shadow: 0 10px 24px -4px rgba(0, 0, 0, 0.5), 0 0 14px rgba(245, 158, 11, 0.22) !important;
    }

    [data-theme="van-gogh"] .denda-alert-banner {
        background: linear-gradient(135deg, #182b42 0%, #101c2e 100%) !important;
        border: 1px solid #2e4a6e !important;
        border-left: 4px solid #f59e0b !important;
    }

    /* 9. WCAG AA STRICT CONTRAST COMPLIANCE FOR BADGES & STATUS INDICATORS */
    /* Yellow / Amber / Warning / Pending Badges (Never washed-out pale yellow) */
    [data-theme="van-gogh"] .badge-warning,
    [data-theme="van-gogh"] .badge-amber,
    [data-theme="van-gogh"] .badge-status--warning,
    [data-theme="van-gogh"] .badge-status--pending,
    [data-theme="van-gogh"] .status-warning,
    [data-theme="van-gogh"] .status-pending,
    [data-theme="van-gogh"] .bg-warning,
    [data-theme="van-gogh"] span[class*="bg-amber-100"],
    [data-theme="van-gogh"] span[class*="bg-yellow-100"] {
        background: rgba(245, 158, 11, 0.2) !important;
        color: #fcd34d !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        font-weight: 600 !important;
    }

    /* Green / Success / Tersedia / Returned Badges */
    [data-theme="van-gogh"] .badge-success,
    [data-theme="van-gogh"] .badge-status--success,
    [data-theme="van-gogh"] .status-success,
    [data-theme="van-gogh"] .status-dikembalikan,
    [data-theme="van-gogh"] .bg-success,
    [data-theme="van-gogh"] span[class*="bg-emerald-100"],
    [data-theme="van-gogh"] span[class*="bg-green-100"] {
        background: rgba(34, 197, 94, 0.2) !important;
        color: #4ade80 !important;
        border: 1px solid rgba(34, 197, 94, 0.45) !important;
        font-weight: 600 !important;
    }

    /* Red / Danger / Terlambat / Denda Badges */
    [data-theme="van-gogh"] .badge-danger,
    [data-theme="van-gogh"] .badge-status--danger,
    [data-theme="van-gogh"] .status-danger,
    [data-theme="van-gogh"] .status-terlambat,
    [data-theme="van-gogh"] .bg-danger,
    [data-theme="van-gogh"] span[class*="bg-red-100"],
    [data-theme="van-gogh"] span[class*="bg-rose-100"] {
        background: rgba(239, 68, 68, 0.2) !important;
        color: #fca5a5 !important;
        border: 1px solid rgba(239, 68, 68, 0.45) !important;
        font-weight: 600 !important;
    }

    /* Blue / Info / Dipinjam Badges */
    [data-theme="van-gogh"] .badge-info,
    [data-theme="van-gogh"] .badge-primary,
    [data-theme="van-gogh"] .badge-status--info,
    [data-theme="van-gogh"] .status-info,
    [data-theme="van-gogh"] .status-dipinjam,
    [data-theme="van-gogh"] .bg-info,
    [data-theme="van-gogh"] span[class*="bg-blue-100"],
    [data-theme="van-gogh"] span[class*="bg-sky-100"] {
        background: rgba(36, 91, 143, 0.35) !important;
        color: #93c5fd !important;
        border: 1px solid rgba(59, 130, 246, 0.45) !important;
        font-weight: 600 !important;
    }

    /* If amber/yellow text is rendered on a light/cream container, use dark rich bronze */
    [data-theme="van-gogh"] .bg-light .badge-warning,
    [data-theme="van-gogh"] .bg-white .badge-warning,
    [data-theme="van-gogh"] [style*="background: #fef3c7"] span,
    [data-theme="van-gogh"] [style*="background:#fef3c7"] span {
        color: #92400e !important;
    }

    /* ==========================================================================
       UNIVERSAL THEME-DRIVEN BUTTON ENGINE (LIGHT, DARK, SOFT BLUSH, MIDNIGHT SLATE)
       ========================================================================== */
    /* Primary Action Buttons */
    .btn-primary,
    .btn-action-primary,
    .btn-action-simpan,
    .btn-save-primary,
    .btn-save-blue,
    .btn-add-petugas,
    .btn-add-member,
    .btn-add-buku,
    .btn-create-buku,
    .btn-create-member,
    .btn-create-submit,
    .btn-submit-update,
    .btn-submit-save,
    .btn-submit,
    .btn-submit-loan,
    .btn-submit-masuk,
    .btn-submit-manual,
    .btn-submit-batch-return,
    .btn-filter-submit,
    .btn-apply-filter,
    .btn-cari-buku,
    .btn-search,
    .btn-search-trx,
    .btn-table-search,
    .btn-katalog,
    .btn-pinjam,
    .btn-pinjam-buku,
    .btn-ubah-foto,
    .btn-print,
    .btn-print-action,
    .btn-cta,
    .btn-cta-primary,
    .btn-green,
    .btn-bayar-denda,
    .btn-confirm-loan,
    .btn-confirm-return,
    .btn-checkout-booking,
    .btn-lanjutkan,
    .btn-modal-primary,
    .btn-modal-confirm,
    .btn-modal-konfirmasi,
    .btn-dialog-primary,
    .btn-popup-primary,
    .btn-pref-apply,
    .btn-unduh-laporan,
    .btn-simpan-barcode,
    .action-pay-qr {
        background: var(--theme-btn-primary-bg, #0f766e);
        color: var(--theme-btn-primary-text, #ffffff) !important;
        border-color: var(--theme-btn-primary-bg, #0f766e);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-primary:hover,
    .btn-action-primary:hover,
    .btn-action-simpan:hover,
    .btn-save-primary:hover,
    .btn-save-blue:hover,
    .btn-add-petugas:hover,
    .btn-add-member:hover,
    .btn-add-buku:hover,
    .btn-create-buku:hover,
    .btn-create-member:hover,
    .btn-create-submit:hover,
    .btn-submit-update:hover,
    .btn-submit-save:hover,
    .btn-submit:hover,
    .btn-submit-loan:hover,
    .btn-submit-masuk:hover,
    .btn-submit-manual:hover,
    .btn-submit-batch-return:hover,
    .btn-filter-submit:hover,
    .btn-apply-filter:hover,
    .btn-cari-buku:hover,
    .btn-search:hover,
    .btn-search-trx:hover,
    .btn-table-search:hover,
    .btn-katalog:hover,
    .btn-pinjam:hover,
    .btn-pinjam-buku:hover,
    .btn-ubah-foto:hover,
    .btn-print:hover,
    .btn-print-action:hover,
    .btn-cta:hover,
    .btn-cta-primary:hover,
    .btn-green:hover,
    .btn-bayar-denda:hover,
    .btn-confirm-loan:hover,
    .btn-confirm-return:hover,
    .btn-checkout-booking:hover,
    .btn-lanjutkan:hover,
    .btn-modal-primary:hover,
    .btn-modal-confirm:hover,
    .btn-modal-konfirmasi:hover,
    .btn-dialog-primary:hover,
    .btn-popup-primary:hover,
    .btn-pref-apply:hover,
    .btn-unduh-laporan:hover,
    .btn-simpan-barcode:hover,
    .action-pay-qr:hover {
        background: var(--theme-btn-primary-hover, #115e59) !important;
        border-color: var(--theme-btn-primary-hover, #115e59) !important;
        color: var(--theme-btn-primary-text, #ffffff) !important;
    }

    /* Secondary & Outline Buttons */
    .btn-secondary,
    .btn-action-secondary,
    .btn-action-outline,
    .btn-filter-reset,
    .btn-reset,
    .btn-reset-filter,
    .btn-cancel,
    .btn-back,
    .btn-back-dasbor,
    .btn-back-denda,
    .btn-back-detail,
    .btn-back-katalog,
    .btn-kembali,
    .btn-kembali-dasbor,
    .btn-nav-register,
    .btn-auth-register,
    .btn-nav-login,
    .btn-auth-login,
    .btn-edit-action,
    .btn-detail-link,
    .btn-explore-katalog,
    .btn-blue,
    .btn-modal-cancel,
    .btn-modal-batal,
    .btn-modal-secondary,
    .btn-dialog-cancel,
    .btn-popup-secondary,
    .btn-pref-reset,
    .btn-preview-opening,
    .btn-cart-quick,
    .btn-add-cart,
    .btn-kembalikan-shortcut,
    .btn-lihat-denda,
    .btn-lihat-nota,
    .btn-cetak-nota,
    .btn-cek-status {
        background: var(--theme-btn-secondary-bg, #ffffff);
        color: var(--theme-btn-secondary-text, #334155) !important;
        border: 1px solid var(--theme-btn-secondary-border, #cbd5e1);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-secondary:hover,
    .btn-action-secondary:hover,
    .btn-action-outline:hover,
    .btn-filter-reset:hover,
    .btn-reset:hover,
    .btn-reset-filter:hover,
    .btn-cancel:hover,
    .btn-back:hover,
    .btn-back-dasbor:hover,
    .btn-back-denda:hover,
    .btn-back-detail:hover,
    .btn-back-katalog:hover,
    .btn-kembali:hover,
    .btn-kembali-dasbor:hover,
    .btn-nav-register:hover,
    .btn-auth-register:hover,
    .btn-nav-login:hover,
    .btn-auth-login:hover,
    .btn-edit-action:hover,
    .btn-detail-link:hover,
    .btn-explore-katalog:hover,
    .btn-blue:hover,
    .btn-modal-cancel:hover,
    .btn-modal-batal:hover,
    .btn-modal-secondary:hover,
    .btn-dialog-cancel:hover,
    .btn-popup-secondary:hover,
    .btn-pref-reset:hover,
    .btn-preview-opening:hover,
    .btn-cart-quick:hover,
    .btn-add-cart:hover,
    .btn-kembalikan-shortcut:hover,
    .btn-lihat-denda:hover,
    .btn-lihat-nota:hover,
    .btn-cetak-nota:hover,
    .btn-cek-status:hover {
        background: var(--theme-btn-secondary-hover, #f8fafc) !important;
        border-color: var(--theme-accent, #0f766e) !important;
        color: var(--theme-accent, #0f766e) !important;
    }

    /* Disabled States */
    button:disabled,
    .btn:disabled,
    .btn-primary:disabled,
    .btn-secondary:disabled,
    .btn-pinjam:disabled,
    .btn-book-disabled {
        opacity: 0.55 !important;
        cursor: not-allowed !important;
        box-shadow: none !important;
        transform: none !important;
    }

    /* Focus Visible Accessibility */
    button:focus-visible,
    .btn:focus-visible,
    a:focus-visible {
        outline: 2px solid var(--theme-accent, #0f766e) !important;
        outline-offset: 2px !important;
    }

    /* --- THEME SPECIFIC BUTTON OVERRIDES: SOFT BLUSH --- */
    [data-theme="soft-blush"] .btn-primary,
    [data-theme="soft-blush"] .btn-action-primary,
    [data-theme="soft-blush"] .btn-action-simpan,
    [data-theme="soft-blush"] .btn-save-primary,
    [data-theme="soft-blush"] .btn-save-blue,
    [data-theme="soft-blush"] .btn-add-petugas,
    [data-theme="soft-blush"] .btn-add-member,
    [data-theme="soft-blush"] .btn-add-buku,
    [data-theme="soft-blush"] .btn-create-buku,
    [data-theme="soft-blush"] .btn-create-member,
    [data-theme="soft-blush"] .btn-create-submit,
    [data-theme="soft-blush"] .btn-submit-update,
    [data-theme="soft-blush"] .btn-submit-save,
    [data-theme="soft-blush"] .btn-submit,
    [data-theme="soft-blush"] .btn-submit-loan,
    [data-theme="soft-blush"] .btn-submit-masuk,
    [data-theme="soft-blush"] .btn-submit-manual,
    [data-theme="soft-blush"] .btn-submit-batch-return,
    [data-theme="soft-blush"] .btn-filter-submit,
    [data-theme="soft-blush"] .btn-apply-filter,
    [data-theme="soft-blush"] .btn-cari-buku,
    [data-theme="soft-blush"] .btn-search,
    [data-theme="soft-blush"] .btn-search-trx,
    [data-theme="soft-blush"] .btn-table-search,
    [data-theme="soft-blush"] .btn-katalog,
    [data-theme="soft-blush"] .btn-pinjam,
    [data-theme="soft-blush"] .btn-pinjam-buku,
    [data-theme="soft-blush"] .btn-ubah-foto,
    [data-theme="soft-blush"] .btn-print,
    [data-theme="soft-blush"] .btn-print-action,
    [data-theme="soft-blush"] .btn-cta,
    [data-theme="soft-blush"] .btn-cta-primary,
    [data-theme="soft-blush"] .btn-green,
    [data-theme="soft-blush"] .btn-bayar-denda,
    [data-theme="soft-blush"] .btn-confirm-loan,
    [data-theme="soft-blush"] .btn-confirm-return,
    [data-theme="soft-blush"] .btn-checkout-booking,
    [data-theme="soft-blush"] .btn-lanjutkan,
    [data-theme="soft-blush"] .btn-modal-primary,
    [data-theme="soft-blush"] .btn-modal-confirm,
    [data-theme="soft-blush"] .btn-modal-konfirmasi,
    [data-theme="soft-blush"] .btn-dialog-primary,
    [data-theme="soft-blush"] .btn-popup-primary,
    [data-theme="soft-blush"] .btn-pref-apply,
    [data-theme="soft-blush"] .btn-unduh-laporan,
    [data-theme="soft-blush"] .btn-simpan-barcode,
    [data-theme="soft-blush"] .action-pay-qr {
        background: #b85d77 !important;
        border-color: #b85d77 !important;
        color: #ffffff !important;
        box-shadow: 0 3px 10px rgba(184, 93, 119, 0.22) !important;
    }

    [data-theme="soft-blush"] .btn-primary:hover,
    [data-theme="soft-blush"] .btn-action-primary:hover,
    [data-theme="soft-blush"] .btn-action-simpan:hover,
    [data-theme="soft-blush"] .btn-save-primary:hover,
    [data-theme="soft-blush"] .btn-save-blue:hover,
    [data-theme="soft-blush"] .btn-add-petugas:hover,
    [data-theme="soft-blush"] .btn-add-member:hover,
    [data-theme="soft-blush"] .btn-add-buku:hover,
    [data-theme="soft-blush"] .btn-create-buku:hover,
    [data-theme="soft-blush"] .btn-create-member:hover,
    [data-theme="soft-blush"] .btn-create-submit:hover,
    [data-theme="soft-blush"] .btn-submit-update:hover,
    [data-theme="soft-blush"] .btn-submit-save:hover,
    [data-theme="soft-blush"] .btn-submit:hover,
    [data-theme="soft-blush"] .btn-submit-loan:hover,
    [data-theme="soft-blush"] .btn-submit-masuk:hover,
    [data-theme="soft-blush"] .btn-submit-manual:hover,
    [data-theme="soft-blush"] .btn-submit-batch-return:hover,
    [data-theme="soft-blush"] .btn-filter-submit:hover,
    [data-theme="soft-blush"] .btn-apply-filter:hover,
    [data-theme="soft-blush"] .btn-cari-buku:hover,
    [data-theme="soft-blush"] .btn-search:hover,
    [data-theme="soft-blush"] .btn-search-trx:hover,
    [data-theme="soft-blush"] .btn-table-search:hover,
    [data-theme="soft-blush"] .btn-katalog:hover,
    [data-theme="soft-blush"] .btn-pinjam:hover,
    [data-theme="soft-blush"] .btn-pinjam-buku:hover,
    [data-theme="soft-blush"] .btn-ubah-foto:hover,
    [data-theme="soft-blush"] .btn-print:hover,
    [data-theme="soft-blush"] .btn-print-action:hover,
    [data-theme="soft-blush"] .btn-cta:hover,
    [data-theme="soft-blush"] .btn-cta-primary:hover,
    [data-theme="soft-blush"] .btn-green:hover,
    [data-theme="soft-blush"] .btn-bayar-denda:hover,
    [data-theme="soft-blush"] .btn-confirm-loan:hover,
    [data-theme="soft-blush"] .btn-confirm-return:hover,
    [data-theme="soft-blush"] .btn-checkout-booking:hover,
    [data-theme="soft-blush"] .btn-lanjutkan:hover,
    [data-theme="soft-blush"] .btn-modal-primary:hover,
    [data-theme="soft-blush"] .btn-modal-confirm:hover,
    [data-theme="soft-blush"] .btn-modal-konfirmasi:hover,
    [data-theme="soft-blush"] .btn-dialog-primary:hover,
    [data-theme="soft-blush"] .btn-popup-primary:hover,
    [data-theme="soft-blush"] .btn-pref-apply:hover,
    [data-theme="soft-blush"] .btn-unduh-laporan:hover,
    [data-theme="soft-blush"] .btn-simpan-barcode:hover,
    [data-theme="soft-blush"] .action-pay-qr:hover {
        background: #9e4761 !important;
        border-color: #9e4761 !important;
        color: #ffffff !important;
        box-shadow: 0 6px 16px rgba(184, 93, 119, 0.3) !important;
    }

    [data-theme="soft-blush"] .btn-secondary,
    [data-theme="soft-blush"] .btn-action-secondary,
    [data-theme="soft-blush"] .btn-action-outline,
    [data-theme="soft-blush"] .btn-filter-reset,
    [data-theme="soft-blush"] .btn-reset,
    [data-theme="soft-blush"] .btn-reset-filter,
    [data-theme="soft-blush"] .btn-cancel,
    [data-theme="soft-blush"] .btn-back,
    [data-theme="soft-blush"] .btn-back-dasbor,
    [data-theme="soft-blush"] .btn-back-denda,
    [data-theme="soft-blush"] .btn-back-detail,
    [data-theme="soft-blush"] .btn-back-katalog,
    [data-theme="soft-blush"] .btn-kembali,
    [data-theme="soft-blush"] .btn-kembali-dasbor,
    [data-theme="soft-blush"] .btn-nav-register,
    [data-theme="soft-blush"] .btn-auth-register,
    [data-theme="soft-blush"] .btn-nav-login,
    [data-theme="soft-blush"] .btn-auth-login,
    [data-theme="soft-blush"] .btn-edit-action,
    [data-theme="soft-blush"] .btn-detail-link,
    [data-theme="soft-blush"] .btn-explore-katalog,
    [data-theme="soft-blush"] .btn-blue,
    [data-theme="soft-blush"] .btn-modal-cancel,
    [data-theme="soft-blush"] .btn-modal-batal,
    [data-theme="soft-blush"] .btn-modal-secondary,
    [data-theme="soft-blush"] .btn-dialog-cancel,
    [data-theme="soft-blush"] .btn-popup-secondary,
    [data-theme="soft-blush"] .btn-pref-reset,
    [data-theme="soft-blush"] .btn-preview-opening,
    [data-theme="soft-blush"] .btn-cart-quick,
    [data-theme="soft-blush"] .btn-add-cart,
    [data-theme="soft-blush"] .btn-kembalikan-shortcut,
    [data-theme="soft-blush"] .btn-lihat-denda,
    [data-theme="soft-blush"] .btn-lihat-nota,
    [data-theme="soft-blush"] .btn-cetak-nota,
    [data-theme="soft-blush"] .btn-cek-status {
        background: #ffffff !important;
        border-color: #eedde2 !important;
        color: #54444a !important;
    }

    [data-theme="soft-blush"] .btn-secondary:hover,
    [data-theme="soft-blush"] .btn-action-secondary:hover,
    [data-theme="soft-blush"] .btn-action-outline:hover,
    [data-theme="soft-blush"] .btn-filter-reset:hover,
    [data-theme="soft-blush"] .btn-reset:hover,
    [data-theme="soft-blush"] .btn-reset-filter:hover,
    [data-theme="soft-blush"] .btn-cancel:hover,
    [data-theme="soft-blush"] .btn-back:hover,
    [data-theme="soft-blush"] .btn-back-dasbor:hover,
    [data-theme="soft-blush"] .btn-back-denda:hover,
    [data-theme="soft-blush"] .btn-back-detail:hover,
    [data-theme="soft-blush"] .btn-back-katalog:hover,
    [data-theme="soft-blush"] .btn-kembali:hover,
    [data-theme="soft-blush"] .btn-kembali-dasbor:hover,
    [data-theme="soft-blush"] .btn-nav-register:hover,
    [data-theme="soft-blush"] .btn-auth-register:hover,
    [data-theme="soft-blush"] .btn-nav-login:hover,
    [data-theme="soft-blush"] .btn-auth-login:hover,
    [data-theme="soft-blush"] .btn-edit-action:hover,
    [data-theme="soft-blush"] .btn-detail-link:hover,
    [data-theme="soft-blush"] .btn-explore-katalog:hover,
    [data-theme="soft-blush"] .btn-blue:hover,
    [data-theme="soft-blush"] .btn-modal-cancel:hover,
    [data-theme="soft-blush"] .btn-modal-batal:hover,
    [data-theme="soft-blush"] .btn-modal-secondary:hover,
    [data-theme="soft-blush"] .btn-dialog-cancel:hover,
    [data-theme="soft-blush"] .btn-popup-secondary:hover,
    [data-theme="soft-blush"] .btn-pref-reset:hover,
    [data-theme="soft-blush"] .btn-preview-opening:hover,
    [data-theme="soft-blush"] .btn-cart-quick:hover,
    [data-theme="soft-blush"] .btn-add-cart:hover,
    [data-theme="soft-blush"] .btn-kembalikan-shortcut:hover,
    [data-theme="soft-blush"] .btn-lihat-denda:hover,
    [data-theme="soft-blush"] .btn-lihat-nota:hover,
    [data-theme="soft-blush"] .btn-cetak-nota:hover,
    [data-theme="soft-blush"] .btn-cek-status:hover {
        background: #fdf2f5 !important;
        border-color: #e5cbd2 !important;
        color: #b85d77 !important;
    }

    /* --- THEME SPECIFIC BUTTON OVERRIDES: MIDNIGHT SLATE --- */
    [data-theme="midnight-slate"] .btn-primary,
    [data-theme="midnight-slate"] .btn-action-primary,
    [data-theme="midnight-slate"] .btn-action-simpan,
    [data-theme="midnight-slate"] .btn-save-primary,
    [data-theme="midnight-slate"] .btn-save-blue,
    [data-theme="midnight-slate"] .btn-add-petugas,
    [data-theme="midnight-slate"] .btn-add-member,
    [data-theme="midnight-slate"] .btn-add-buku,
    [data-theme="midnight-slate"] .btn-create-buku,
    [data-theme="midnight-slate"] .btn-create-member,
    [data-theme="midnight-slate"] .btn-create-submit,
    [data-theme="midnight-slate"] .btn-submit-update,
    [data-theme="midnight-slate"] .btn-submit-save,
    [data-theme="midnight-slate"] .btn-submit,
    [data-theme="midnight-slate"] .btn-submit-loan,
    [data-theme="midnight-slate"] .btn-submit-masuk,
    [data-theme="midnight-slate"] .btn-submit-manual,
    [data-theme="midnight-slate"] .btn-submit-batch-return,
    [data-theme="midnight-slate"] .btn-filter-submit,
    [data-theme="midnight-slate"] .btn-apply-filter,
    [data-theme="midnight-slate"] .btn-cari-buku,
    [data-theme="midnight-slate"] .btn-search,
    [data-theme="midnight-slate"] .btn-search-trx,
    [data-theme="midnight-slate"] .btn-table-search,
    [data-theme="midnight-slate"] .btn-katalog,
    [data-theme="midnight-slate"] .btn-pinjam,
    [data-theme="midnight-slate"] .btn-pinjam-buku,
    [data-theme="midnight-slate"] .btn-ubah-foto,
    [data-theme="midnight-slate"] .btn-print,
    [data-theme="midnight-slate"] .btn-print-action,
    [data-theme="midnight-slate"] .btn-cta,
    [data-theme="midnight-slate"] .btn-cta-primary,
    [data-theme="midnight-slate"] .btn-green,
    [data-theme="midnight-slate"] .btn-bayar-denda,
    [data-theme="midnight-slate"] .btn-confirm-loan,
    [data-theme="midnight-slate"] .btn-confirm-return,
    [data-theme="midnight-slate"] .btn-checkout-booking,
    [data-theme="midnight-slate"] .btn-lanjutkan,
    [data-theme="midnight-slate"] .btn-modal-primary,
    [data-theme="midnight-slate"] .btn-modal-confirm,
    [data-theme="midnight-slate"] .btn-modal-konfirmasi,
    [data-theme="midnight-slate"] .btn-dialog-primary,
    [data-theme="midnight-slate"] .btn-popup-primary,
    [data-theme="midnight-slate"] .btn-pref-apply,
    [data-theme="midnight-slate"] .btn-unduh-laporan,
    [data-theme="midnight-slate"] .btn-simpan-barcode,
    [data-theme="midnight-slate"] .action-pay-qr {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
        box-shadow: 0 3px 10px rgba(37, 99, 235, 0.28) !important;
    }

    [data-theme="midnight-slate"] .btn-primary:hover,
    [data-theme="midnight-slate"] .btn-action-primary:hover,
    [data-theme="midnight-slate"] .btn-action-simpan:hover,
    [data-theme="midnight-slate"] .btn-save-primary:hover,
    [data-theme="midnight-slate"] .btn-save-blue:hover,
    [data-theme="midnight-slate"] .btn-add-petugas:hover,
    [data-theme="midnight-slate"] .btn-add-member:hover,
    [data-theme="midnight-slate"] .btn-add-buku:hover,
    [data-theme="midnight-slate"] .btn-create-buku:hover,
    [data-theme="midnight-slate"] .btn-create-member:hover,
    [data-theme="midnight-slate"] .btn-create-submit:hover,
    [data-theme="midnight-slate"] .btn-submit-update:hover,
    [data-theme="midnight-slate"] .btn-submit-save:hover,
    [data-theme="midnight-slate"] .btn-submit:hover,
    [data-theme="midnight-slate"] .btn-submit-loan:hover,
    [data-theme="midnight-slate"] .btn-submit-masuk:hover,
    [data-theme="midnight-slate"] .btn-submit-manual:hover,
    [data-theme="midnight-slate"] .btn-submit-batch-return:hover,
    [data-theme="midnight-slate"] .btn-filter-submit:hover,
    [data-theme="midnight-slate"] .btn-apply-filter:hover,
    [data-theme="midnight-slate"] .btn-cari-buku:hover,
    [data-theme="midnight-slate"] .btn-search:hover,
    [data-theme="midnight-slate"] .btn-search-trx:hover,
    [data-theme="midnight-slate"] .btn-table-search:hover,
    [data-theme="midnight-slate"] .btn-katalog:hover,
    [data-theme="midnight-slate"] .btn-pinjam:hover,
    [data-theme="midnight-slate"] .btn-pinjam-buku:hover,
    [data-theme="midnight-slate"] .btn-ubah-foto:hover,
    [data-theme="midnight-slate"] .btn-print:hover,
    [data-theme="midnight-slate"] .btn-print-action:hover,
    [data-theme="midnight-slate"] .btn-cta:hover,
    [data-theme="midnight-slate"] .btn-cta-primary:hover,
    [data-theme="midnight-slate"] .btn-green:hover,
    [data-theme="midnight-slate"] .btn-bayar-denda:hover,
    [data-theme="midnight-slate"] .btn-confirm-loan:hover,
    [data-theme="midnight-slate"] .btn-confirm-return:hover,
    [data-theme="midnight-slate"] .btn-checkout-booking:hover,
    [data-theme="midnight-slate"] .btn-lanjutkan:hover,
    [data-theme="midnight-slate"] .btn-modal-primary:hover,
    [data-theme="midnight-slate"] .btn-modal-confirm:hover,
    [data-theme="midnight-slate"] .btn-modal-konfirmasi:hover,
    [data-theme="midnight-slate"] .btn-dialog-primary:hover,
    [data-theme="midnight-slate"] .btn-popup-primary:hover,
    [data-theme="midnight-slate"] .btn-pref-apply:hover,
    [data-theme="midnight-slate"] .btn-unduh-laporan:hover,
    [data-theme="midnight-slate"] .btn-simpan-barcode:hover,
    [data-theme="midnight-slate"] .action-pay-qr:hover {
        background: #1d4ed8 !important;
        border-color: #1d4ed8 !important;
        color: #ffffff !important;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.38) !important;
    }

    [data-theme="midnight-slate"] .btn-secondary,
    [data-theme="midnight-slate"] .btn-action-secondary,
    [data-theme="midnight-slate"] .btn-action-outline,
    [data-theme="midnight-slate"] .btn-filter-reset,
    [data-theme="midnight-slate"] .btn-reset,
    [data-theme="midnight-slate"] .btn-reset-filter,
    [data-theme="midnight-slate"] .btn-cancel,
    [data-theme="midnight-slate"] .btn-back,
    [data-theme="midnight-slate"] .btn-back-dasbor,
    [data-theme="midnight-slate"] .btn-back-denda,
    [data-theme="midnight-slate"] .btn-back-detail,
    [data-theme="midnight-slate"] .btn-back-katalog,
    [data-theme="midnight-slate"] .btn-kembali,
    [data-theme="midnight-slate"] .btn-kembali-dasbor,
    [data-theme="midnight-slate"] .btn-nav-register,
    [data-theme="midnight-slate"] .btn-auth-register,
    [data-theme="midnight-slate"] .btn-nav-login,
    [data-theme="midnight-slate"] .btn-auth-login,
    [data-theme="midnight-slate"] .btn-edit-action,
    [data-theme="midnight-slate"] .btn-detail-link,
    [data-theme="midnight-slate"] .btn-explore-katalog,
    [data-theme="midnight-slate"] .btn-blue,
    [data-theme="midnight-slate"] .btn-modal-cancel,
    [data-theme="midnight-slate"] .btn-modal-batal,
    [data-theme="midnight-slate"] .btn-modal-secondary,
    [data-theme="midnight-slate"] .btn-dialog-cancel,
    [data-theme="midnight-slate"] .btn-popup-secondary,
    [data-theme="midnight-slate"] .btn-pref-reset,
    [data-theme="midnight-slate"] .btn-preview-opening,
    [data-theme="midnight-slate"] .btn-cart-quick,
    [data-theme="midnight-slate"] .btn-add-cart,
    [data-theme="midnight-slate"] .btn-kembalikan-shortcut,
    [data-theme="midnight-slate"] .btn-lihat-denda,
    [data-theme="midnight-slate"] .btn-lihat-nota,
    [data-theme="midnight-slate"] .btn-cetak-nota,
    [data-theme="midnight-slate"] .btn-cek-status {
        background: #141f33 !important;
        border-color: #2b3b55 !important;
        color: #cbd5e1 !important;
    }

    [data-theme="midnight-slate"] .btn-secondary:hover,
    [data-theme="midnight-slate"] .btn-action-secondary:hover,
    [data-theme="midnight-slate"] .btn-action-outline:hover,
    [data-theme="midnight-slate"] .btn-filter-reset:hover,
    [data-theme="midnight-slate"] .btn-reset:hover,
    [data-theme="midnight-slate"] .btn-reset-filter:hover,
    [data-theme="midnight-slate"] .btn-cancel:hover,
    [data-theme="midnight-slate"] .btn-back:hover,
    [data-theme="midnight-slate"] .btn-back-dasbor:hover,
    [data-theme="midnight-slate"] .btn-back-denda:hover,
    [data-theme="midnight-slate"] .btn-back-detail:hover,
    [data-theme="midnight-slate"] .btn-back-katalog:hover,
    [data-theme="midnight-slate"] .btn-kembali:hover,
    [data-theme="midnight-slate"] .btn-kembali-dasbor:hover,
    [data-theme="midnight-slate"] .btn-nav-register:hover,
    [data-theme="midnight-slate"] .btn-auth-register:hover,
    [data-theme="midnight-slate"] .btn-nav-login:hover,
    [data-theme="midnight-slate"] .btn-auth-login:hover,
    [data-theme="midnight-slate"] .btn-edit-action:hover,
    [data-theme="midnight-slate"] .btn-detail-link:hover,
    [data-theme="midnight-slate"] .btn-explore-katalog:hover,
    [data-theme="midnight-slate"] .btn-blue:hover,
    [data-theme="midnight-slate"] .btn-modal-cancel:hover,
    [data-theme="midnight-slate"] .btn-modal-batal:hover,
    [data-theme="midnight-slate"] .btn-modal-secondary:hover,
    [data-theme="midnight-slate"] .btn-dialog-cancel:hover,
    [data-theme="midnight-slate"] .btn-popup-secondary:hover,
    [data-theme="midnight-slate"] .btn-pref-reset:hover,
    [data-theme="midnight-slate"] .btn-preview-opening:hover,
    [data-theme="midnight-slate"] .btn-cart-quick:hover,
    [data-theme="midnight-slate"] .btn-add-cart:hover,
    [data-theme="midnight-slate"] .btn-kembalikan-shortcut:hover,
    [data-theme="midnight-slate"] .btn-lihat-denda:hover,
    [data-theme="midnight-slate"] .btn-lihat-nota:hover,
    [data-theme="midnight-slate"] .btn-cetak-nota:hover,
    [data-theme="midnight-slate"] .btn-cek-status:hover {
        background: #1e2c45 !important;
        border-color: #3b82f6 !important;
        color: #f8fafc !important;
    }

    /* --- THEME SPECIFIC BUTTON OVERRIDES: DARK --- */
    [data-theme="dark"] .btn-primary,
    [data-theme="dark"] .btn-action-primary,
    [data-theme="dark"] .btn-action-simpan,
    [data-theme="dark"] .btn-save-primary,
    [data-theme="dark"] .btn-save-blue,
    [data-theme="dark"] .btn-add-petugas,
    [data-theme="dark"] .btn-add-member,
    [data-theme="dark"] .btn-add-buku,
    [data-theme="dark"] .btn-create-buku,
    [data-theme="dark"] .btn-create-member,
    [data-theme="dark"] .btn-create-submit,
    [data-theme="dark"] .btn-submit-update,
    [data-theme="dark"] .btn-submit-save,
    [data-theme="dark"] .btn-submit,
    [data-theme="dark"] .btn-submit-loan,
    [data-theme="dark"] .btn-submit-masuk,
    [data-theme="dark"] .btn-submit-manual,
    [data-theme="dark"] .btn-submit-batch-return,
    [data-theme="dark"] .btn-filter-submit,
    [data-theme="dark"] .btn-apply-filter,
    [data-theme="dark"] .btn-cari-buku,
    [data-theme="dark"] .btn-search,
    [data-theme="dark"] .btn-search-trx,
    [data-theme="dark"] .btn-table-search,
    [data-theme="dark"] .btn-katalog,
    [data-theme="dark"] .btn-pinjam,
    [data-theme="dark"] .btn-pinjam-buku,
    [data-theme="dark"] .btn-ubah-foto,
    [data-theme="dark"] .btn-print,
    [data-theme="dark"] .btn-print-action,
    [data-theme="dark"] .btn-cta,
    [data-theme="dark"] .btn-cta-primary,
    [data-theme="dark"] .btn-green,
    [data-theme="dark"] .btn-bayar-denda,
    [data-theme="dark"] .btn-confirm-loan,
    [data-theme="dark"] .btn-confirm-return,
    [data-theme="dark"] .btn-checkout-booking,
    [data-theme="dark"] .btn-lanjutkan,
    [data-theme="dark"] .btn-modal-primary,
    [data-theme="dark"] .btn-modal-confirm,
    [data-theme="dark"] .btn-modal-konfirmasi,
    [data-theme="dark"] .btn-dialog-primary,
    [data-theme="dark"] .btn-popup-primary,
    [data-theme="dark"] .btn-pref-apply,
    [data-theme="dark"] .btn-unduh-laporan,
    [data-theme="dark"] .btn-simpan-barcode,
    [data-theme="dark"] .action-pay-qr {
        background: #10b981 !important;
        border-color: #10b981 !important;
        color: #ffffff !important;
        box-shadow: 0 3px 10px rgba(16, 185, 129, 0.25) !important;
    }

    [data-theme="dark"] .btn-primary:hover,
    [data-theme="dark"] .btn-action-primary:hover,
    [data-theme="dark"] .btn-action-simpan:hover,
    [data-theme="dark"] .btn-save-primary:hover,
    [data-theme="dark"] .btn-save-blue:hover,
    [data-theme="dark"] .btn-add-petugas:hover,
    [data-theme="dark"] .btn-add-member:hover,
    [data-theme="dark"] .btn-add-buku:hover,
    [data-theme="dark"] .btn-create-buku:hover,
    [data-theme="dark"] .btn-create-member:hover,
    [data-theme="dark"] .btn-create-submit:hover,
    [data-theme="dark"] .btn-submit-update:hover,
    [data-theme="dark"] .btn-submit-save:hover,
    [data-theme="dark"] .btn-submit:hover,
    [data-theme="dark"] .btn-submit-loan:hover,
    [data-theme="dark"] .btn-submit-masuk:hover,
    [data-theme="dark"] .btn-submit-manual:hover,
    [data-theme="dark"] .btn-submit-batch-return:hover,
    [data-theme="dark"] .btn-filter-submit:hover,
    [data-theme="dark"] .btn-apply-filter:hover,
    [data-theme="dark"] .btn-cari-buku:hover,
    [data-theme="dark"] .btn-search:hover,
    [data-theme="dark"] .btn-search-trx:hover,
    [data-theme="dark"] .btn-table-search:hover,
    [data-theme="dark"] .btn-katalog:hover,
    [data-theme="dark"] .btn-pinjam:hover,
    [data-theme="dark"] .btn-pinjam-buku:hover,
    [data-theme="dark"] .btn-ubah-foto:hover,
    [data-theme="dark"] .btn-print:hover,
    [data-theme="dark"] .btn-print-action:hover,
    [data-theme="dark"] .btn-cta:hover,
    [data-theme="dark"] .btn-cta-primary:hover,
    [data-theme="dark"] .btn-green:hover,
    [data-theme="dark"] .btn-bayar-denda:hover,
    [data-theme="dark"] .btn-confirm-loan:hover,
    [data-theme="dark"] .btn-confirm-return:hover,
    [data-theme="dark"] .btn-checkout-booking:hover,
    [data-theme="dark"] .btn-lanjutkan:hover,
    [data-theme="dark"] .btn-modal-primary:hover,
    [data-theme="dark"] .btn-modal-confirm:hover,
    [data-theme="dark"] .btn-modal-konfirmasi:hover,
    [data-theme="dark"] .btn-dialog-primary:hover,
    [data-theme="dark"] .btn-popup-primary:hover,
    [data-theme="dark"] .btn-pref-apply:hover,
    [data-theme="dark"] .btn-unduh-laporan:hover,
    [data-theme="dark"] .btn-simpan-barcode:hover,
    [data-theme="dark"] .action-pay-qr:hover {
        background: #059669 !important;
        border-color: #059669 !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .btn-secondary,
    [data-theme="dark"] .btn-action-secondary,
    [data-theme="dark"] .btn-action-outline,
    [data-theme="dark"] .btn-filter-reset,
    [data-theme="dark"] .btn-reset,
    [data-theme="dark"] .btn-reset-filter,
    [data-theme="dark"] .btn-cancel,
    [data-theme="dark"] .btn-back,
    [data-theme="dark"] .btn-back-dasbor,
    [data-theme="dark"] .btn-back-denda,
    [data-theme="dark"] .btn-back-detail,
    [data-theme="dark"] .btn-back-katalog,
    [data-theme="dark"] .btn-kembali,
    [data-theme="dark"] .btn-kembali-dasbor,
    [data-theme="dark"] .btn-nav-register,
    [data-theme="dark"] .btn-auth-register,
    [data-theme="dark"] .btn-nav-login,
    [data-theme="dark"] .btn-auth-login,
    [data-theme="dark"] .btn-edit-action,
    [data-theme="dark"] .btn-detail-link,
    [data-theme="dark"] .btn-explore-katalog,
    [data-theme="dark"] .btn-blue,
    [data-theme="dark"] .btn-modal-cancel,
    [data-theme="dark"] .btn-modal-batal,
    [data-theme="dark"] .btn-modal-secondary,
    [data-theme="dark"] .btn-dialog-cancel,
    [data-theme="dark"] .btn-popup-secondary,
    [data-theme="dark"] .btn-pref-reset,
    [data-theme="dark"] .btn-preview-opening,
    [data-theme="dark"] .btn-cart-quick,
    [data-theme="dark"] .btn-add-cart,
    [data-theme="dark"] .btn-kembalikan-shortcut,
    [data-theme="dark"] .btn-lihat-denda,
    [data-theme="dark"] .btn-lihat-nota,
    [data-theme="dark"] .btn-cetak-nota,
    [data-theme="dark"] .btn-cek-status {
        background: #1f2937 !important;
        border-color: #374151 !important;
        color: #e5e7eb !important;
    }

    [data-theme="dark"] .btn-secondary:hover,
    [data-theme="dark"] .btn-action-secondary:hover,
    [data-theme="dark"] .btn-action-outline:hover,
    [data-theme="dark"] .btn-filter-reset:hover,
    [data-theme="dark"] .btn-reset:hover,
    [data-theme="dark"] .btn-reset-filter:hover,
    [data-theme="dark"] .btn-cancel:hover,
    [data-theme="dark"] .btn-back:hover,
    [data-theme="dark"] .btn-back-dasbor:hover,
    [data-theme="dark"] .btn-back-denda:hover,
    [data-theme="dark"] .btn-back-detail:hover,
    [data-theme="dark"] .btn-back-katalog:hover,
    [data-theme="dark"] .btn-kembali:hover,
    [data-theme="dark"] .btn-kembali-dasbor:hover,
    [data-theme="dark"] .btn-nav-register:hover,
    [data-theme="dark"] .btn-auth-register:hover,
    [data-theme="dark"] .btn-nav-login:hover,
    [data-theme="dark"] .btn-auth-login:hover,
    [data-theme="dark"] .btn-edit-action:hover,
    [data-theme="dark"] .btn-detail-link:hover,
    [data-theme="dark"] .btn-explore-katalog:hover,
    [data-theme="dark"] .btn-blue:hover,
    [data-theme="dark"] .btn-modal-cancel:hover,
    [data-theme="dark"] .btn-modal-batal:hover,
    [data-theme="dark"] .btn-modal-secondary:hover,
    [data-theme="dark"] .btn-dialog-cancel:hover,
    [data-theme="dark"] .btn-popup-secondary:hover,
    [data-theme="dark"] .btn-pref-reset:hover,
    [data-theme="dark"] .btn-preview-opening:hover,
    [data-theme="dark"] .btn-cart-quick:hover,
    [data-theme="dark"] .btn-add-cart:hover,
    [data-theme="dark"] .btn-kembalikan-shortcut:hover,
    [data-theme="dark"] .btn-lihat-denda:hover,
    [data-theme="dark"] .btn-lihat-nota:hover,
    [data-theme="dark"] .btn-cetak-nota:hover,
    [data-theme="dark"] .btn-cek-status:hover {
        background: #374151 !important;
        border-color: #4b5563 !important;
        color: #10b981 !important;
    }

    /* --- THEME SPECIFIC BUTTON OVERRIDES: VAN GOGH (STARRY NIGHT) --- */
    [data-theme="van-gogh"] .btn-primary,
    [data-theme="van-gogh"] .btn-action-primary,
    [data-theme="van-gogh"] .btn-action-simpan,
    [data-theme="van-gogh"] .btn-save-primary,
    [data-theme="van-gogh"] .btn-save-blue,
    [data-theme="van-gogh"] .btn-add-petugas,
    [data-theme="van-gogh"] .btn-add-member,
    [data-theme="van-gogh"] .btn-add-buku,
    [data-theme="van-gogh"] .btn-create-buku,
    [data-theme="van-gogh"] .btn-create-member,
    [data-theme="van-gogh"] .btn-create-submit,
    [data-theme="van-gogh"] .btn-submit-update,
    [data-theme="van-gogh"] .btn-submit-save,
    [data-theme="van-gogh"] .btn-submit,
    [data-theme="van-gogh"] .btn-submit-loan,
    [data-theme="van-gogh"] .btn-submit-masuk,
    [data-theme="van-gogh"] .btn-submit-manual,
    [data-theme="van-gogh"] .btn-submit-batch-return,
    [data-theme="van-gogh"] .btn-filter-submit,
    [data-theme="van-gogh"] .btn-apply-filter,
    [data-theme="van-gogh"] .btn-cari-buku,
    [data-theme="van-gogh"] .btn-search,
    [data-theme="van-gogh"] .btn-search-trx,
    [data-theme="van-gogh"] .btn-table-search,
    [data-theme="van-gogh"] .btn-katalog,
    [data-theme="van-gogh"] .btn-pinjam,
    [data-theme="van-gogh"] .btn-pinjam-buku,
    [data-theme="van-gogh"] .btn-ubah-foto,
    [data-theme="van-gogh"] .btn-print,
    [data-theme="van-gogh"] .btn-print-action,
    [data-theme="van-gogh"] .btn-cta,
    [data-theme="van-gogh"] .btn-cta-primary,
    [data-theme="van-gogh"] .btn-green,
    [data-theme="van-gogh"] .btn-bayar-denda,
    [data-theme="van-gogh"] .btn-confirm-loan,
    [data-theme="van-gogh"] .btn-confirm-return,
    [data-theme="van-gogh"] .btn-checkout-booking,
    [data-theme="van-gogh"] .btn-lanjutkan,
    [data-theme="van-gogh"] .btn-modal-primary,
    [data-theme="van-gogh"] .btn-modal-confirm,
    [data-theme="van-gogh"] .btn-modal-konfirmasi,
    [data-theme="van-gogh"] .btn-dialog-primary,
    [data-theme="van-gogh"] .btn-popup-primary,
    [data-theme="van-gogh"] .btn-pref-apply,
    [data-theme="van-gogh"] .btn-unduh-laporan,
    [data-theme="van-gogh"] .btn-simpan-barcode,
    [data-theme="van-gogh"] .action-pay-qr {
        background: #245b8f !important;
        border-color: #316ba3 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(36, 91, 143, 0.4) !important;
    }

    [data-theme="van-gogh"] .btn-primary:hover,
    [data-theme="van-gogh"] .btn-action-primary:hover,
    [data-theme="van-gogh"] .btn-action-simpan:hover,
    [data-theme="van-gogh"] .btn-save-primary:hover,
    [data-theme="van-gogh"] .btn-save-blue:hover,
    [data-theme="van-gogh"] .btn-add-petugas:hover,
    [data-theme="van-gogh"] .btn-add-member:hover,
    [data-theme="van-gogh"] .btn-add-buku:hover,
    [data-theme="van-gogh"] .btn-create-buku:hover,
    [data-theme="van-gogh"] .btn-create-member:hover,
    [data-theme="van-gogh"] .btn-create-submit:hover,
    [data-theme="van-gogh"] .btn-submit-update:hover,
    [data-theme="van-gogh"] .btn-submit-save:hover,
    [data-theme="van-gogh"] .btn-submit:hover,
    [data-theme="van-gogh"] .btn-submit-loan:hover,
    [data-theme="van-gogh"] .btn-submit-masuk:hover,
    [data-theme="van-gogh"] .btn-submit-manual:hover,
    [data-theme="van-gogh"] .btn-submit-batch-return:hover,
    [data-theme="van-gogh"] .btn-filter-submit:hover,
    [data-theme="van-gogh"] .btn-apply-filter:hover,
    [data-theme="van-gogh"] .btn-cari-buku:hover,
    [data-theme="van-gogh"] .btn-search:hover,
    [data-theme="van-gogh"] .btn-search-trx:hover,
    [data-theme="van-gogh"] .btn-table-search:hover,
    [data-theme="van-gogh"] .btn-katalog:hover,
    [data-theme="van-gogh"] .btn-pinjam:hover,
    [data-theme="van-gogh"] .btn-pinjam-buku:hover,
    [data-theme="van-gogh"] .btn-ubah-foto:hover,
    [data-theme="van-gogh"] .btn-print:hover,
    [data-theme="van-gogh"] .btn-print-action:hover,
    [data-theme="van-gogh"] .btn-cta:hover,
    [data-theme="van-gogh"] .btn-cta-primary:hover,
    [data-theme="van-gogh"] .btn-green:hover,
    [data-theme="van-gogh"] .btn-bayar-denda:hover,
    [data-theme="van-gogh"] .btn-confirm-loan:hover,
    [data-theme="van-gogh"] .btn-confirm-return:hover,
    [data-theme="van-gogh"] .btn-checkout-booking:hover,
    [data-theme="van-gogh"] .btn-lanjutkan:hover,
    [data-theme="van-gogh"] .btn-modal-primary:hover,
    [data-theme="van-gogh"] .btn-modal-confirm:hover,
    [data-theme="van-gogh"] .btn-modal-konfirmasi:hover,
    [data-theme="van-gogh"] .btn-dialog-primary:hover,
    [data-theme="van-gogh"] .btn-popup-primary:hover,
    [data-theme="van-gogh"] .btn-pref-apply:hover,
    [data-theme="van-gogh"] .btn-unduh-laporan:hover,
    [data-theme="van-gogh"] .btn-simpan-barcode:hover,
    [data-theme="van-gogh"] .action-pay-qr:hover {
        background: #1b456e !important;
        border-color: #f59e0b !important;
        color: #f6f0df !important;
        box-shadow: 0 6px 18px rgba(245, 158, 11, 0.35) !important;
    }

    [data-theme="van-gogh"] .btn-secondary,
    [data-theme="van-gogh"] .btn-action-secondary,
    [data-theme="van-gogh"] .btn-action-outline,
    [data-theme="van-gogh"] .btn-filter-reset,
    [data-theme="van-gogh"] .btn-reset,
    [data-theme="van-gogh"] .btn-reset-filter,
    [data-theme="van-gogh"] .btn-cancel,
    [data-theme="van-gogh"] .btn-back,
    [data-theme="van-gogh"] .btn-back-dasbor,
    [data-theme="van-gogh"] .btn-back-denda,
    [data-theme="van-gogh"] .btn-back-detail,
    [data-theme="van-gogh"] .btn-back-katalog,
    [data-theme="van-gogh"] .btn-kembali,
    [data-theme="van-gogh"] .btn-kembali-dasbor,
    [data-theme="van-gogh"] .btn-nav-register,
    [data-theme="van-gogh"] .btn-auth-register,
    [data-theme="van-gogh"] .btn-nav-login,
    [data-theme="van-gogh"] .btn-auth-login,
    [data-theme="van-gogh"] .btn-edit-action,
    [data-theme="van-gogh"] .btn-detail-link,
    [data-theme="van-gogh"] .btn-explore-katalog,
    [data-theme="van-gogh"] .btn-blue,
    [data-theme="van-gogh"] .btn-modal-cancel,
    [data-theme="van-gogh"] .btn-modal-batal,
    [data-theme="van-gogh"] .btn-modal-secondary,
    [data-theme="van-gogh"] .btn-dialog-cancel,
    [data-theme="van-gogh"] .btn-popup-secondary,
    [data-theme="van-gogh"] .btn-pref-reset,
    [data-theme="van-gogh"] .btn-preview-opening,
    [data-theme="van-gogh"] .btn-cart-quick,
    [data-theme="van-gogh"] .btn-add-cart,
    [data-theme="van-gogh"] .btn-kembalikan-shortcut,
    [data-theme="van-gogh"] .btn-lihat-denda,
    [data-theme="van-gogh"] .btn-lihat-nota,
    [data-theme="van-gogh"] .btn-cetak-nota,
    [data-theme="van-gogh"] .btn-cek-status {
        background: #132238 !important;
        border-color: #22446a !important;
        color: #d8e2ec !important;
    }

    [data-theme="van-gogh"] .btn-secondary:hover,
    [data-theme="van-gogh"] .btn-action-secondary:hover,
    [data-theme="van-gogh"] .btn-action-outline:hover,
    [data-theme="van-gogh"] .btn-filter-reset:hover,
    [data-theme="van-gogh"] .btn-reset:hover,
    [data-theme="van-gogh"] .btn-reset-filter:hover,
    [data-theme="van-gogh"] .btn-cancel:hover,
    [data-theme="van-gogh"] .btn-back:hover,
    [data-theme="van-gogh"] .btn-back-dasbor:hover,
    [data-theme="van-gogh"] .btn-back-denda:hover,
    [data-theme="van-gogh"] .btn-back-detail:hover,
    [data-theme="van-gogh"] .btn-back-katalog:hover,
    [data-theme="van-gogh"] .btn-kembali:hover,
    [data-theme="van-gogh"] .btn-kembali-dasbor:hover,
    [data-theme="van-gogh"] .btn-nav-register:hover,
    [data-theme="van-gogh"] .btn-auth-register:hover,
    [data-theme="van-gogh"] .btn-nav-login:hover,
    [data-theme="van-gogh"] .btn-auth-login:hover,
    [data-theme="van-gogh"] .btn-edit-action:hover,
    [data-theme="van-gogh"] .btn-detail-link:hover,
    [data-theme="van-gogh"] .btn-explore-katalog:hover,
    [data-theme="van-gogh"] .btn-blue:hover,
    [data-theme="van-gogh"] .btn-modal-cancel:hover,
    [data-theme="van-gogh"] .btn-modal-batal:hover,
    [data-theme="van-gogh"] .btn-modal-secondary:hover,
    [data-theme="van-gogh"] .btn-dialog-cancel:hover,
    [data-theme="van-gogh"] .btn-popup-secondary:hover,
    [data-theme="van-gogh"] .btn-pref-reset:hover,
    [data-theme="van-gogh"] .btn-preview-opening:hover,
    [data-theme="van-gogh"] .btn-cart-quick:hover,
    [data-theme="van-gogh"] .btn-add-cart:hover,
    [data-theme="van-gogh"] .btn-kembalikan-shortcut:hover,
    [data-theme="van-gogh"] .btn-lihat-denda:hover,
    [data-theme="van-gogh"] .btn-lihat-nota:hover,
    [data-theme="van-gogh"] .btn-cetak-nota:hover,
    [data-theme="van-gogh"] .btn-cek-status:hover {
        background: #193354 !important;
        border-color: #f59e0b !important;
        color: #fcd34d !important;
    }

    /* ==========================================================================
       ACTIVE NAVIGATION & TAB INDICATORS ACROSS THEMES
       ========================================================================== */
    /* Sidebar Navigation Active Items */
    [data-theme="van-gogh"] .sidebar-nav-item.active > a,
    [data-theme="van-gogh"] .sidebar-menu > li.active > a,
    [data-theme="van-gogh"] .sidebar-link.active,
    [data-theme="van-gogh"] .nav-item.active > .nav-link,
    [data-theme="van-gogh"] .sidebar-nav-link.active {
        background: linear-gradient(90deg, rgba(36, 91, 143, 0.45) 0%, rgba(25, 51, 84, 0.7) 100%) !important;
        color: #fcd34d !important;
        border-left: 3.5px solid #f59e0b !important;
        box-shadow: inset 0 0 12px rgba(245, 158, 11, 0.12), 0 2px 8px rgba(0, 0, 0, 0.35) !important;
        font-weight: 700 !important;
    }

    [data-theme="van-gogh"] .sidebar-link.active::after {
        background: #f59e0b !important;
    }

    [data-theme="van-gogh"] .sidebar-link.navigating-active {
        background: #193354 !important;
        color: #fcd34d !important;
    }

    [data-theme="van-gogh"] .sidebar-link.navigating-active .sidebar-link-icon {
        color: #fcd34d !important;
    }

    [data-theme="van-gogh"] .sidebar-nav-item.active > a svg,
    [data-theme="van-gogh"] .sidebar-menu > li.active > a svg,
    [data-theme="van-gogh"] .sidebar-link.active svg,
    [data-theme="van-gogh"] .nav-item.active > .nav-link svg,
    [data-theme="van-gogh"] .sidebar-nav-link.active svg {
        color: #fcd34d !important;
        stroke: currentColor !important;
    }

    [data-theme="soft-blush"] .sidebar-nav-item.active > a,
    [data-theme="soft-blush"] .sidebar-menu > li.active > a,
    [data-theme="soft-blush"] .sidebar-link.active,
    [data-theme="soft-blush"] .nav-item.active > .nav-link,
    [data-theme="soft-blush"] .sidebar-nav-link.active {
        background: #fdf2f5 !important;
        color: #b85d77 !important;
        border-left: 3px solid #b85d77 !important;
        font-weight: 700 !important;
    }

    [data-theme="soft-blush"] .sidebar-link.active::after {
        background: #b85d77 !important;
    }

    [data-theme="soft-blush"] .sidebar-link.navigating-active {
        background: #fdf2f5 !important;
        color: #b85d77 !important;
    }

    [data-theme="soft-blush"] .sidebar-link.navigating-active .sidebar-link-icon {
        color: #b85d77 !important;
    }

    [data-theme="soft-blush"] .sidebar-nav-item.active > a svg,
    [data-theme="soft-blush"] .sidebar-menu > li.active > a svg,
    [data-theme="soft-blush"] .sidebar-link.active svg,
    [data-theme="soft-blush"] .nav-item.active > .nav-link svg,
    [data-theme="soft-blush"] .sidebar-nav-link.active svg {
        color: #b85d77 !important;
        stroke: currentColor !important;
    }

    [data-theme="midnight-slate"] .sidebar-nav-item.active > a,
    [data-theme="midnight-slate"] .sidebar-menu > li.active > a,
    [data-theme="midnight-slate"] .sidebar-link.active,
    [data-theme="midnight-slate"] .nav-item.active > .nav-link,
    [data-theme="midnight-slate"] .sidebar-nav-link.active {
        background: rgba(56, 189, 248, 0.12) !important;
        color: #38bdf8 !important;
        border-left: 3px solid #38bdf8 !important;
        font-weight: 700 !important;
    }

    [data-theme="midnight-slate"] .sidebar-link.active::after {
        background: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .sidebar-link.navigating-active {
        background: rgba(56, 189, 248, 0.14) !important;
        color: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .sidebar-link.navigating-active .sidebar-link-icon {
        color: #38bdf8 !important;
    }

    [data-theme="midnight-slate"] .sidebar-nav-item.active > a svg,
    [data-theme="midnight-slate"] .sidebar-menu > li.active > a svg,
    [data-theme="midnight-slate"] .sidebar-link.active svg,
    [data-theme="midnight-slate"] .nav-item.active > .nav-link svg,
    [data-theme="midnight-slate"] .sidebar-nav-link.active svg {
        color: #38bdf8 !important;
        stroke: currentColor !important;
    }

    [data-theme="dark"] .sidebar-nav-item.active > a,
    [data-theme="dark"] .sidebar-menu > li.active > a,
    [data-theme="dark"] .sidebar-link.active,
    [data-theme="dark"] .nav-item.active > .nav-link,
    [data-theme="dark"] .sidebar-nav-link.active {
        background: rgba(16, 185, 129, 0.14) !important;
        color: #10b981 !important;
        border-left: 3px solid #10b981 !important;
        font-weight: 700 !important;
    }

    [data-theme="dark"] .sidebar-link.active::after {
        background: #10b981 !important;
    }

    [data-theme="dark"] .sidebar-link.navigating-active {
        background: rgba(16, 185, 129, 0.16) !important;
        color: #10b981 !important;
    }

    [data-theme="dark"] .sidebar-link.navigating-active .sidebar-link-icon {
        color: #10b981 !important;
    }

    [data-theme="dark"] .sidebar-nav-item.active > a svg,
    [data-theme="dark"] .sidebar-menu > li.active > a svg,
    [data-theme="dark"] .sidebar-link.active svg,
    [data-theme="dark"] .nav-item.active > .nav-link svg,
    [data-theme="dark"] .sidebar-nav-link.active svg {
        color: #10b981 !important;
        stroke: currentColor !important;
    }

    /* Topbar Active Links */
    [data-theme="van-gogh"] .topbar-nav-link.active {
        background: rgba(245, 158, 11, 0.18) !important;
        color: #fcd34d !important;
        border-bottom: 2px solid #f59e0b !important;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.25) !important;
    }

    [data-theme="soft-blush"] .topbar-nav-link.active {
        background: #fdf2f5 !important;
        color: #b85d77 !important;
        box-shadow: 0 2px 8px rgba(184, 93, 119, 0.16) !important;
    }

    [data-theme="midnight-slate"] .topbar-nav-link.active {
        background: rgba(56, 189, 248, 0.14) !important;
        color: #38bdf8 !important;
        box-shadow: 0 2px 8px rgba(56, 189, 248, 0.2) !important;
    }

    [data-theme="dark"] .topbar-nav-link.active {
        background: rgba(16, 185, 129, 0.16) !important;
        color: #10b981 !important;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2) !important;
    }

    /* Segmented Filter Tabs Bar Containers */
    [data-theme="van-gogh"] .filter-tabs-bar {
        background: #0f2035 !important;
        border: 1px solid #1e3a5f !important;
    }
    [data-theme="van-gogh"] .filter-tab-btn {
        color: #9fb3c8 !important;
    }
    [data-theme="van-gogh"] .filter-tab-btn:hover {
        color: #fcd34d !important;
    }
    [data-theme="van-gogh"] .filter-badge-count {
        background: #172c47 !important;
        color: #cbd5e1 !important;
    }

    [data-theme="midnight-slate"] .filter-tabs-bar {
        background: #0f172a !important;
        border: 1px solid #24354e !important;
    }
    [data-theme="midnight-slate"] .filter-tab-btn {
        color: #94a3b8 !important;
    }
    [data-theme="midnight-slate"] .filter-tab-btn:hover {
        color: #38bdf8 !important;
    }
    [data-theme="midnight-slate"] .filter-badge-count {
        background: #1e293b !important;
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .filter-tabs-bar {
        background: #0b1220 !important;
        border: 1px solid #23314d !important;
    }
    [data-theme="dark"] .filter-tab-btn {
        color: #94a3b8 !important;
    }
    [data-theme="dark"] .filter-tab-btn:hover {
        color: #f8fafc !important;
    }
    [data-theme="dark"] .filter-badge-count {
        background: #1e293b !important;
        color: #94a3b8 !important;
    }

    [data-theme="soft-blush"] .filter-tabs-bar {
        background: #fdf2f5 !important;
        border: 1px solid #eedde2 !important;
    }
    [data-theme="soft-blush"] .filter-tab-btn {
        color: #826e75 !important;
    }
    [data-theme="soft-blush"] .filter-badge-count {
        background: #fae1e8 !important;
        color: #b85d77 !important;
    }

    /* Active Tabs, Pills, and Segmented Filters */
    [data-theme="van-gogh"] .nav-tabs .nav-link.active,
    [data-theme="van-gogh"] .tab-nav-btn.active,
    [data-theme="van-gogh"] .nav-pill-btn.active,
    [data-theme="van-gogh"] .nav-pill-item.active,
    [data-theme="van-gogh"] .circ-tab-btn.active,
    [data-theme="van-gogh"] .filter-tab-btn.active,
    [data-theme="van-gogh"] .search-tab-pill.active,
    [data-theme="van-gogh"] [role="tab"][aria-selected="true"] {
        color: #fcd34d !important;
        background: linear-gradient(135deg, #183354 0%, #112338 100%) !important;
        border-color: #f59e0b !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4), 0 0 8px rgba(245, 158, 11, 0.2) !important;
    }

    [data-theme="van-gogh"] .filter-tab-btn.active .filter-badge-count,
    [data-theme="van-gogh"] .nav-pill-btn.active .filter-badge-count {
        background: #f59e0b !important;
        color: #0b1726 !important;
        font-weight: 800 !important;
    }

    [data-theme="soft-blush"] .nav-tabs .nav-link.active,
    [data-theme="soft-blush"] .tab-nav-btn.active,
    [data-theme="soft-blush"] .nav-pill-btn.active,
    [data-theme="soft-blush"] .nav-pill-item.active,
    [data-theme="soft-blush"] .circ-tab-btn.active,
    [data-theme="soft-blush"] .filter-tab-btn.active,
    [data-theme="soft-blush"] .search-tab-pill.active,
    [data-theme="soft-blush"] [role="tab"][aria-selected="true"] {
        color: #b85d77 !important;
        background: #ffffff !important;
        border-color: #b85d77 !important;
        box-shadow: 0 2px 8px rgba(184, 93, 119, 0.15) !important;
    }

    [data-theme="soft-blush"] .filter-tab-btn.active .filter-badge-count,
    [data-theme="soft-blush"] .nav-pill-btn.active .filter-badge-count {
        background: #b85d77 !important;
        color: #ffffff !important;
    }

    [data-theme="midnight-slate"] .nav-tabs .nav-link.active,
    [data-theme="midnight-slate"] .tab-nav-btn.active,
    [data-theme="midnight-slate"] .nav-pill-btn.active,
    [data-theme="midnight-slate"] .nav-pill-item.active,
    [data-theme="midnight-slate"] .circ-tab-btn.active,
    [data-theme="midnight-slate"] .filter-tab-btn.active,
    [data-theme="midnight-slate"] .search-tab-pill.active,
    [data-theme="midnight-slate"] [role="tab"][aria-selected="true"] {
        color: #38bdf8 !important;
        background: #162238 !important;
        border-color: #38bdf8 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="midnight-slate"] .filter-tab-btn.active .filter-badge-count,
    [data-theme="midnight-slate"] .nav-pill-btn.active .filter-badge-count {
        background: #2563eb !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .nav-tabs .nav-link.active,
    [data-theme="dark"] .tab-nav-btn.active,
    [data-theme="dark"] .nav-pill-btn.active,
    [data-theme="dark"] .nav-pill-item.active,
    [data-theme="dark"] .circ-tab-btn.active,
    [data-theme="dark"] .filter-tab-btn.active,
    [data-theme="dark"] .search-tab-pill.active,
    [data-theme="dark"] [role="tab"][aria-selected="true"] {
        color: #10b981 !important;
        background: #1f2937 !important;
        border-color: #10b981 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="dark"] .filter-tab-btn.active .filter-badge-count,
    [data-theme="dark"] .nav-pill-btn.active .filter-badge-count {
        background: #10b981 !important;
        color: #ffffff !important;
    }

    /* Active Pagination */
    [data-theme="van-gogh"] .pagination .page-item.active .page-link,
    [data-theme="van-gogh"] .custom-page-btn.active,
    [data-theme="van-gogh"] .page-btn.active {
        background: #245b8f !important;
        border-color: #f59e0b !important;
        color: #ffffff !important;
        box-shadow: 0 3px 10px rgba(245, 158, 11, 0.35) !important;
    }

    [data-theme="soft-blush"] .pagination .page-item.active .page-link,
    [data-theme="soft-blush"] .custom-page-btn.active,
    [data-theme="soft-blush"] .page-btn.active {
        background: #b85d77 !important;
        border-color: #b85d77 !important;
        color: #ffffff !important;
        box-shadow: 0 3px 8px rgba(184, 93, 119, 0.3) !important;
    }

    [data-theme="midnight-slate"] .pagination .page-item.active .page-link,
    [data-theme="midnight-slate"] .custom-page-btn.active,
    [data-theme="midnight-slate"] .page-btn.active {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
        box-shadow: 0 3px 8px rgba(37, 99, 235, 0.3) !important;
    }

    [data-theme="dark"] .pagination .page-item.active .page-link,
    [data-theme="dark"] .custom-page-btn.active,
    [data-theme="dark"] .page-btn.active {
        background: #10b981 !important;
        border-color: #10b981 !important;
        color: #ffffff !important;
        box-shadow: 0 3px 8px rgba(16, 185, 129, 0.3) !important;
    }

    /* Inactive Pagination Items */
    [data-theme="van-gogh"] .page-btn:not(.active),
    [data-theme="van-gogh"] .custom-page-btn:not(.active),
    [data-theme="van-gogh"] .pagination .page-item:not(.active) .page-link {
        background: #132238 !important;
        border-color: #22446a !important;
        color: #f6f0df !important;
    }

    [data-theme="van-gogh"] .page-btn:not(.active):hover,
    [data-theme="van-gogh"] .custom-page-btn:not(.active):hover,
    [data-theme="van-gogh"] .pagination .page-item:not(.active) .page-link:hover {
        background: #1f3c60 !important;
        border-color: #f59e0b !important;
        color: #fcd34d !important;
    }

    [data-theme="midnight-slate"] .page-btn:not(.active),
    [data-theme="midnight-slate"] .custom-page-btn:not(.active),
    [data-theme="midnight-slate"] .pagination .page-item:not(.active) .page-link {
        background: #141f33 !important;
        border-color: #24354e !important;
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .page-btn:not(.active):hover,
    [data-theme="midnight-slate"] .custom-page-btn:not(.active):hover,
    [data-theme="midnight-slate"] .pagination .page-item:not(.active) .page-link:hover {
        background: #1e2c42 !important;
        border-color: #38bdf8 !important;
        color: #38bdf8 !important;
    }

    [data-theme="dark"] .page-btn:not(.active),
    [data-theme="dark"] .custom-page-btn:not(.active),
    [data-theme="dark"] .pagination .page-item:not(.active) .page-link {
        background: #131d31 !important;
        border-color: #23314d !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .page-btn:not(.active):hover,
    [data-theme="dark"] .custom-page-btn:not(.active):hover,
    [data-theme="dark"] .pagination .page-item:not(.active) .page-link:hover {
        background: #1e293b !important;
        border-color: #2dd4bf !important;
        color: #2dd4bf !important;
    }

    [data-theme="soft-blush"] .page-btn:not(.active),
    [data-theme="soft-blush"] .custom-page-btn:not(.active),
    [data-theme="soft-blush"] .pagination .page-item:not(.active) .page-link {
        background: #ffffff !important;
        border-color: #eedde2 !important;
        color: #4a2835 !important;
    }

    [data-theme="soft-blush"] .page-btn:not(.active):hover,
    [data-theme="soft-blush"] .custom-page-btn:not(.active):hover,
    [data-theme="soft-blush"] .pagination .page-item:not(.active) .page-link:hover {
        background: #fdf2f5 !important;
        border-color: #b85d77 !important;
        color: #b85d77 !important;
    }

    /* Universal Public Footers */
    [data-theme="van-gogh"] .footer,
    [data-theme="van-gogh"] .about-footer {
        background: #091522 !important;
        border-top: 1px solid #1e3a5f !important;
    }

    [data-theme="van-gogh"] .footer-brand h3,
    [data-theme="van-gogh"] .footer-heading,
    [data-theme="van-gogh"] .footer-brand-title,
    [data-theme="van-gogh"] .footer-column-title {
        color: #f6f0df !important;
    }

    [data-theme="van-gogh"] .footer-brand p,
    [data-theme="van-gogh"] .footer-brand-text,
    [data-theme="van-gogh"] .footer-brand-tagline,
    [data-theme="van-gogh"] .footer-contact-item,
    [data-theme="van-gogh"] .footer-address,
    [data-theme="van-gogh"] .footer-links a,
    [data-theme="van-gogh"] .footer-copyright,
    [data-theme="van-gogh"] .about-footer-bottom {
        color: #9fb3c8 !important;
    }

    [data-theme="van-gogh"] .footer-links a:hover {
        color: #fcd34d !important;
    }

    [data-theme="midnight-slate"] .footer,
    [data-theme="midnight-slate"] .about-footer {
        background: #0b1329 !important;
        border-top: 1px solid #1e3156 !important;
    }

    [data-theme="midnight-slate"] .footer-brand h3,
    [data-theme="midnight-slate"] .footer-heading,
    [data-theme="midnight-slate"] .footer-brand-title,
    [data-theme="midnight-slate"] .footer-column-title {
        color: #f8fafc !important;
    }

    [data-theme="midnight-slate"] .footer-brand p,
    [data-theme="midnight-slate"] .footer-brand-text,
    [data-theme="midnight-slate"] .footer-brand-tagline,
    [data-theme="midnight-slate"] .footer-contact-item,
    [data-theme="midnight-slate"] .footer-address,
    [data-theme="midnight-slate"] .footer-links a,
    [data-theme="midnight-slate"] .footer-copyright,
    [data-theme="midnight-slate"] .about-footer-bottom {
        color: #94a3b8 !important;
    }

    [data-theme="midnight-slate"] .footer-links a:hover {
        color: #38bdf8 !important;
    }

    [data-theme="dark"] .footer,
    [data-theme="dark"] .about-footer {
        background: #0b1220 !important;
        border-top: 1px solid #23314d !important;
    }

    [data-theme="dark"] .footer-brand h3,
    [data-theme="dark"] .footer-heading,
    [data-theme="dark"] .footer-brand-title,
    [data-theme="dark"] .footer-column-title {
        color: #f8fafc !important;
    }

    [data-theme="dark"] .footer-brand p,
    [data-theme="dark"] .footer-brand-text,
    [data-theme="dark"] .footer-brand-tagline,
    [data-theme="dark"] .footer-contact-item,
    [data-theme="dark"] .footer-address,
    [data-theme="dark"] .footer-links a,
    [data-theme="dark"] .footer-copyright,
    [data-theme="dark"] .about-footer-bottom {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .footer-links a:hover {
        color: #2dd4bf !important;
    }

    [data-theme="soft-blush"] .footer,
    [data-theme="soft-blush"] .about-footer {
        background: #ffffff !important;
        border-top: 1px solid #eedde2 !important;
    }

    [data-theme="soft-blush"] .footer-brand h3,
    [data-theme="soft-blush"] .footer-heading,
    [data-theme="soft-blush"] .footer-brand-title,
    [data-theme="soft-blush"] .footer-column-title {
        color: #4a2835 !important;
    }

    [data-theme="soft-blush"] .footer-brand p,
    [data-theme="soft-blush"] .footer-brand-text,
    [data-theme="soft-blush"] .footer-brand-tagline,
    [data-theme="soft-blush"] .footer-contact-item,
    [data-theme="soft-blush"] .footer-address,
    [data-theme="soft-blush"] .footer-links a,
    [data-theme="soft-blush"] .footer-copyright,
    [data-theme="soft-blush"] .about-footer-bottom {
        color: #826e75 !important;
    }

    [data-theme="soft-blush"] .footer-links a:hover {
        color: #b85d77 !important;
    }

    /* Active Scan & Toggle Buttons */
    [data-theme="van-gogh"] .btn-scan-action.active,
    [data-theme="van-gogh"] .btn-toggle-all.active,
    [data-theme="van-gogh"] .btn-toggle-eksemplar.active,
    [data-theme="van-gogh"] .btn-toggle-scan.active,
    [data-theme="van-gogh"] .preset-btn.active,
    [data-theme="van-gogh"] .step-item.active {
        background: #245b8f !important;
        border-color: #e9b949 !important;
        color: #f6f0df !important;
    }

    [data-theme="soft-blush"] .btn-scan-action.active,
    [data-theme="soft-blush"] .btn-toggle-all.active,
    [data-theme="soft-blush"] .btn-toggle-eksemplar.active,
    [data-theme="soft-blush"] .btn-toggle-scan.active,
    [data-theme="soft-blush"] .preset-btn.active,
    [data-theme="soft-blush"] .step-item.active {
        background: #b85d77 !important;
        border-color: #b85d77 !important;
        color: #ffffff !important;
    }

    [data-theme="midnight-slate"] .btn-scan-action.active,
    [data-theme="midnight-slate"] .btn-toggle-all.active,
    [data-theme="midnight-slate"] .btn-toggle-eksemplar.active,
    [data-theme="midnight-slate"] .btn-toggle-scan.active,
    [data-theme="midnight-slate"] .preset-btn.active,
    [data-theme="midnight-slate"] .step-item.active {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .btn-scan-action.active,
    [data-theme="dark"] .btn-toggle-all.active,
    [data-theme="dark"] .btn-toggle-eksemplar.active,
    [data-theme="dark"] .btn-toggle-scan.active,
    [data-theme="dark"] .preset-btn.active,
    [data-theme="dark"] .step-item.active {
        background: #10b981 !important;
        border-color: #10b981 !important;
        color: #ffffff !important;
    }

    /* ==========================================================================
       THEME-ADAPTIVE ICON ENGINE
       ========================================================================== */
    /* Icons in interactive controls follow currentColor */
    .btn svg,
    .btn-icon svg,
    .tab-icon svg,
    .dropdown-item svg,
    .sidebar-link svg {
        transition: color 0.18s ease, stroke 0.18s ease;
    }

    /* Theme Option Card Icon Boxes */
    [data-theme="van-gogh"] .pref-option-icon-box svg {
        color: #e9b949 !important;
    }

    [data-theme="soft-blush"] .pref-option-icon-box svg {
        color: #b85d77 !important;
    }

    [data-theme="midnight-slate"] .pref-option-icon-box svg {
        color: #38bdf8 !important;
    }

    [data-theme="dark"] .pref-option-icon-box svg {
        color: #10b981 !important;
    }

    /* DYNAMIC FONT APPLICATION ACROSS APPLICATION */
    html[data-font="Inter"], html[data-font="Inter"] body, html[data-font="Inter"] h1, html[data-font="Inter"] h2, html[data-font="Inter"] h3, html[data-font="Inter"] h4, html[data-font="Inter"] h5, html[data-font="Inter"] h6, html[data-font="Inter"] p, html[data-font="Inter"] span, html[data-font="Inter"] a, html[data-font="Inter"] li, html[data-font="Inter"] button, html[data-font="Inter"] input, html[data-font="Inter"] select, html[data-font="Inter"] textarea {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
    }
    html[data-font="Poppins"], html[data-font="Poppins"] body, html[data-font="Poppins"] h1, html[data-font="Poppins"] h2, html[data-font="Poppins"] h3, html[data-font="Poppins"] h4, html[data-font="Poppins"] h5, html[data-font="Poppins"] h6, html[data-font="Poppins"] p, html[data-font="Poppins"] span, html[data-font="Poppins"] a, html[data-font="Poppins"] li, html[data-font="Poppins"] button, html[data-font="Poppins"] input, html[data-font="Poppins"] select, html[data-font="Poppins"] textarea {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
    }
    html[data-font="Roboto"], html[data-font="Roboto"] body, html[data-font="Roboto"] h1, html[data-font="Roboto"] h2, html[data-font="Roboto"] h3, html[data-font="Roboto"] h4, html[data-font="Roboto"] h5, html[data-font="Roboto"] h6, html[data-font="Roboto"] p, html[data-font="Roboto"] span, html[data-font="Roboto"] a, html[data-font="Roboto"] li, html[data-font="Roboto"] button, html[data-font="Roboto"] input, html[data-font="Roboto"] select, html[data-font="Roboto"] textarea {
        font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif !important;
    }
    html[data-font="Open Sans"], html[data-font="Open Sans"] body, html[data-font="Open Sans"] h1, html[data-font="Open Sans"] h2, html[data-font="Open Sans"] h3, html[data-font="Open Sans"] h4, html[data-font="Open Sans"] h5, html[data-font="Open Sans"] h6, html[data-font="Open Sans"] p, html[data-font="Open Sans"] span, html[data-font="Open Sans"] a, html[data-font="Open Sans"] li, html[data-font="Open Sans"] button, html[data-font="Open Sans"] input, html[data-font="Open Sans"] select, html[data-font="Open Sans"] textarea {
        font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif !important;
    }
    html[data-font="Lato"], html[data-font="Lato"] body, html[data-font="Lato"] h1, html[data-font="Lato"] h2, html[data-font="Lato"] h3, html[data-font="Lato"] h4, html[data-font="Lato"] h5, html[data-font="Lato"] h6, html[data-font="Lato"] p, html[data-font="Lato"] span, html[data-font="Lato"] a, html[data-font="Lato"] li, html[data-font="Lato"] button, html[data-font="Lato"] input, html[data-font="Lato"] select, html[data-font="Lato"] textarea {
        font-family: 'Lato', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif !important;
    }
    html[data-font="Plus Jakarta Sans"], html[data-font="Plus Jakarta Sans"] body, html[data-font="Plus Jakarta Sans"] h1, html[data-font="Plus Jakarta Sans"] h2, html[data-font="Plus Jakarta Sans"] h3, html[data-font="Plus Jakarta Sans"] h4, html[data-font="Plus Jakarta Sans"] h5, html[data-font="Plus Jakarta Sans"] h6, html[data-font="Plus Jakarta Sans"] p, html[data-font="Plus Jakarta Sans"] span, html[data-font="Plus Jakarta Sans"] a, html[data-font="Plus Jakarta Sans"] li, html[data-font="Plus Jakarta Sans"] button, html[data-font="Plus Jakarta Sans"] input, html[data-font="Plus Jakarta Sans"] select, html[data-font="Plus Jakarta Sans"] textarea {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    }

    /* DYNAMIC FONT SIZE APPLICATION */
    html[data-font-size="small"] body {
        font-size: 13.5px;
    }
    html[data-font-size="normal"] body {
        font-size: 14.5px;
    }
    html[data-font-size="large"] body {
        font-size: 16px;
    }

    /* ANIMATIONS & REDUCED MOTION */
    @keyframes bnZoomIn {
        from { opacity: 0; transform: scale(0.92) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    @keyframes bnWipeBar {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    @media (prefers-reduced-motion: reduce) {
        .intro-curtain,
        .intro-curtain-inner,
        .bn-opening-demo-overlay,
        .pref-option-card,
        .pref-font-card,
        .btn-pref-apply {
            animation: none !important;
            transition: none !important;
        }
    }

    /* ========================================================
       UNIVERSAL THEME-AWARE UI CONTROLS & ICONS OVERRIDES
       ======================================================== */
    input[type="checkbox"] {
        accent-color: var(--theme-accent, #0f766e);
    }

    .btn-return-action,
    .btn-ticket-action,
    .action-pay-qr,
    .btn-explore-katalog,
    .btn-kembalikan-shortcut,
    .btn-bulk-submit,
    .btn-bayar-denda,
    .btn-filter-submit,
    .btn-create-buku,
    .btn-create-member,
    .btn-add-petugas,
    .btn-create-submit,
    .btn-panel-add-copy,
    .btn-export-excel,
    .btn-apply-filter,
    .btn-filter,
    .btn-action-primary,
    .btn-action,
    .btn-toggle-scan:not(.active),
    .btn-submit-loan:not(:disabled),
    .btn-submit-batch-return:not(:disabled),
    .btn-confirm-return:not(:disabled),
    .btn-modal-confirm {
        background: var(--theme-btn-primary-bg, #0f766e) !important;
        color: var(--theme-btn-primary-text, #ffffff) !important;
    }

    .btn-return-action:hover,
    .btn-ticket-action:hover,
    .action-pay-qr:hover,
    .btn-explore-katalog:hover,
    .btn-kembalikan-shortcut:hover,
    .btn-bulk-submit:hover,
    .btn-bayar-denda:hover,
    .btn-filter-submit:hover,
    .btn-create-buku:hover,
    .btn-create-member:hover,
    .btn-add-petugas:hover,
    .btn-create-submit:hover,
    .btn-panel-add-copy:hover,
    .btn-export-excel:hover,
    .btn-apply-filter:hover,
    .btn-filter:hover,
    .btn-action-primary:hover,
    .btn-action:hover,
    .btn-toggle-scan:hover:not(.active),
    .btn-submit-loan:hover:not(:disabled),
    .btn-submit-batch-return:hover:not(:disabled),
    .btn-confirm-return:hover:not(:disabled),
    .btn-modal-confirm:hover {
        background: var(--theme-btn-primary-hover, #0d5f58) !important;
        color: var(--theme-btn-primary-text, #ffffff) !important;
    }

    .btn-edit-action:hover,
    .btn-detail-link:hover {
        background: var(--theme-accent-light, rgba(15, 118, 110, 0.12)) !important;
        color: var(--theme-accent, #0f766e) !important;
        border-color: var(--theme-border, #99f6e4) !important;
    }

    .stat-icon-teal,
    .stat-icon--teal,
    .stat-icon.primary,
    .stat-icon-tile,
    .book-icon-box {
        background: var(--theme-accent-light, rgba(15, 118, 110, 0.12)) !important;
        color: var(--theme-accent, #0f766e) !important;
    }

    .stat-box--teal::before {
        background: var(--theme-accent, #0f766e) !important;
    }

    .badge-buku-count,
    .badge-role-petugas,
    .badge-role-badge,
    .count-badge,
    .trx-badge {
        background: var(--theme-accent-light, rgba(15, 118, 110, 0.12)) !important;
        color: var(--theme-accent, #0f766e) !important;
        border-color: var(--theme-border, #ccfbf1) !important;
    }

    .search-input:focus,
    .filter-select:focus,
    .search-input-field:focus,
    .form-control:focus,
    .form-input:focus,
    .input-manual:focus,
    .select-kondisi:focus {
        border-color: var(--theme-accent, #0f766e) !important;
        box-shadow: 0 0 0 3px var(--theme-accent-light, rgba(15, 118, 110, 0.12)) !important;
    }
</style>
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
            document.documentElement.setAttribute('lang', savedLang);
        } catch (e) {}
    })();
</script>

