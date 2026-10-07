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

    /* --- SIDEBAR PANEL --- */
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
        text-decoration: none;
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
        color: var(--text-heading, #0f172a);
    }

    .sidebar-link:hover .sidebar-link-icon {
        color: var(--brand-primary, #0f766e);
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
        transition: all 0.15s;
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

    .sidebar-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        z-index: 45;
        backdrop-filter: blur(2px);
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
    }

    @media (max-width: 992px) {
        .mobile-toggle-btn {
            display: flex;
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
