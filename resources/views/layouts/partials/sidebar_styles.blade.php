<style>
    :root {
        --sidebar-width: 256px;
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
        background: #ccfbf1 !important;
        color: #0f766e !important;
        font-weight: 700;
        box-shadow: 0 1px 3px rgba(15, 118, 110, 0.08);
        transform: none !important;
        cursor: default;
    }

    .sidebar-link.active:hover {
        background: #ccfbf1 !important;
        color: #0f766e !important;
        transform: none !important;
    }

    .sidebar-link.active .sidebar-link-icon {
        color: #0f766e !important;
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
        background: #0f766e;
        border-radius: 4px;
        animation: activeIndicatorPop 0.28s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    /* Immediate Click Navigating Feedback */
    .sidebar-link.navigating-active {
        background: #e6fffa !important;
        color: #0f766e !important;
        transform: translateX(4px) !important;
    }

    .sidebar-link.navigating-active .sidebar-link-icon {
        color: #0f766e !important;
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
</style>
