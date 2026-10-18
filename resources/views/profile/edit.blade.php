@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'petugas' ? 'layouts.petugas' : 'layouts.anggota'))

@section('title', 'Profil Saya - BOOKNEST')

@section('styles')
<style>
    /* --- ANIMATION KEYFRAMES --- */
    @keyframes profileContentFadeIn {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* --- BREADCRUMB & HEADER --- */
    .profile-header {
        margin-bottom: 24px;
        animation: profileContentFadeIn 0.52s cubic-bezier(0.16, 1, 0.3, 1) both;
        will-change: transform, opacity;
    }

    .profile-breadcrumb {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--theme-accent, #0f766e);
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
    }

    .profile-breadcrumb a {
        color: var(--theme-accent, #0f766e);
        transition: color 0.15s;
    }

    .profile-breadcrumb a:hover {
        color: var(--theme-btn-primary-hover, #115e59);
    }

    .profile-breadcrumb .separator {
        color: #94a3b8;
    }

    .profile-breadcrumb .current {
        color: var(--theme-accent, #0f766e);
        font-weight: 700;
    }

    .profile-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.6px;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .profile-subtitle {
        font-size: 13.5px;
        color: var(--text-muted);
    }

    /* --- ALERTS --- */
    .profile-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 22px;
        font-size: 13.5px;
        line-height: 1.5;
        animation: profileContentFadeIn 0.3s ease both;
    }

    .profile-alert.success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .profile-alert.error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .profile-alert svg {
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* --- PROFILE GRID LAYOUT --- */
    .profile-grid {
        display: grid;
        grid-template-columns: 290px 1fr;
        gap: 24px;
        align-items: start;
    }

    /* --- LEFT COLUMN: SUMMARY CARD --- */
    .summary-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 28px 22px 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        animation: profileContentFadeIn 0.55s cubic-bezier(0.16, 1, 0.3, 1) 0.08s both;
        will-change: transform, opacity;
    }

    .avatar-wrapper {
        position: relative;
        margin-bottom: 14px;
    }

    .avatar-large {
        width: 86px;
        height: 86px;
        border-radius: 50%;
        background: var(--theme-accent-light, #ccfbf1);
        color: var(--theme-accent, #0f766e);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: 0.5px;
        overflow: hidden;
        border: 2px solid var(--theme-border, #99f6e4);
        box-shadow: 0 4px 12px var(--theme-accent-light, rgba(15, 118, 110, 0.12));
    }

    .avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .summary-name {
        font-size: 19px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.3px;
        line-height: 1.3;
        margin-bottom: 3px;
        word-break: break-word;
    }

    .summary-code {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 18px;
    }

    .badge-status-pill.aktif {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-status-pill.nonaktif {
        background: #fee2e2;
        color: #b91c1c;
    }

    .badge-status-pill .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .summary-meta-table {
        width: 100%;
        border-top: 1px solid #f1f5f9;
        padding-top: 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 18px;
    }

    .summary-meta-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
    }

    .summary-meta-label {
        color: var(--text-muted);
        font-weight: 500;
    }

    .summary-meta-value {
        color: var(--text-heading);
        font-weight: 700;
    }

    .btn-ubah-foto {
        width: 100%;
        padding: 10px 16px;
        background: var(--theme-btn-primary-bg, #2563eb);
        color: var(--theme-btn-primary-text, #ffffff);
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.15s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        cursor: pointer;
    }

    .btn-ubah-foto:hover {
        background: var(--theme-btn-primary-hover, #1d4ed8);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.22);
    }

    .btn-ubah-foto:active {
        transform: translateY(0);
    }

    .btn-hapus-foto {
        margin-top: 8px;
        font-size: 12px;
        color: #ef4444;
        font-weight: 600;
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        transition: color 0.15s;
    }

    .btn-hapus-foto:hover {
        color: #b91c1c;
        text-decoration: underline;
    }

    /* --- LEFT COLUMN: PRIVACY CARD --- */
    .privacy-card {
        margin-top: 18px;
        background: var(--theme-accent-light, #f0fdfa);
        border: 1px solid var(--theme-border, #99f6e4);
        border-radius: 16px;
        padding: 18px 20px;
    }

    .privacy-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 6px;
    }

    .privacy-desc {
        font-size: 12.5px;
        color: #475569;
        line-height: 1.6;
    }

    /* --- RIGHT COLUMN: SECTIONS --- */
    .profile-card-section {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 24px 28px 26px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 20px;
        animation: profileContentFadeIn 0.55s cubic-bezier(0.16, 1, 0.3, 1) 0.14s both;
        will-change: transform, opacity;
    }

    .section-header-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 18px;
        letter-spacing: -0.3px;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-size: 13.5px;
        color: var(--text-heading);
        background: #ffffff;
        outline: none;
        transition: border-color 0.22s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.22s cubic-bezier(0.16, 1, 0.3, 1),
                    background-color 0.18s ease;
        font-family: inherit;
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .form-control:focus {
        border-color: var(--theme-accent, #0f766e);
        box-shadow: 0 0 0 3px var(--theme-accent-light, rgba(15, 118, 110, 0.14));
    }

    .form-control.is-readonly {
        background: #f8fafc;
        color: #64748b;
        cursor: not-allowed;
        border-color: #e2e8f0;
    }

    .form-error-msg {
        font-size: 12px;
        color: #dc2626;
        margin-top: 4px;
        font-weight: 600;
        animation: validationMessageFadeIn 0.22s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes validationMessageFadeIn {
        from {
            opacity: 0;
            transform: translateY(-3px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-save-primary {
        width: 100%;
        padding: 12px 20px;
        background: var(--theme-btn-primary-bg, #0f766e);
        color: var(--theme-btn-primary-text, #ffffff);
        font-size: 14px;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.22s cubic-bezier(0.16, 1, 0.3, 1),
                    background-color 0.18s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin-top: 6px;
    }

    .btn-save-primary:hover {
        background: var(--theme-btn-primary-hover, #115e59);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.22);
    }

    .btn-save-primary:active {
        transform: translateY(0);
    }

    .btn-save-blue {
        padding: 10px 22px;
        background: var(--theme-btn-primary-bg, #2563eb);
        color: var(--theme-btn-primary-text, #ffffff);
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.22s cubic-bezier(0.16, 1, 0.3, 1),
                    background-color 0.18s ease;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    }

    .btn-save-blue:hover {
        background: var(--theme-btn-primary-hover, #1d4ed8);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.22);
    }

    .btn-save-blue:active {
        transform: translateY(0);
    }

    /* --- NOTIFICATION PREFERENCES (CHECKBOXES) --- */
    .notif-options-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .notif-checkbox-label {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        user-select: none;
        font-size: 13.5px;
        color: #334155;
        font-weight: 600;
        transition: color 0.15s;
    }

    .notif-checkbox-label:hover {
        color: var(--text-heading);
    }

    .custom-checkbox {
        position: relative;
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkbox-mark {
        position: absolute;
        top: 0;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #ffffff;
        border: 2px solid #cbd5e1;
        border-radius: 5px;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-checkbox input:checked ~ .checkbox-mark {
        background-color: var(--theme-btn-primary-bg, #0f766e);
        border-color: var(--theme-btn-primary-bg, #0f766e);
    }

    .checkbox-mark svg {
        display: none;
        color: #ffffff;
    }

    .custom-checkbox input:checked ~ .checkbox-mark svg {
        display: block;
    }

    .notif-saved-toast {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        color: var(--theme-accent, #0f766e);
        background: var(--theme-accent-light, #ccfbf1);
        padding: 4px 10px;
        border-radius: 6px;
        opacity: 0;
        transition: opacity 0.3s ease;
        margin-left: 10px;
    }

    .notif-saved-toast.show {
        opacity: 1;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- RESPONSIVE BREAKPOINTS --- */
    @media (max-width: 900px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }

        .summary-card {
            max-width: 480px;
            margin: 0 auto 16px;
            width: 100%;
        }

        .privacy-card {
            max-width: 480px;
            margin: 0 auto 20px;
            width: 100%;
        }
    }

    @media (max-width: 580px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .profile-card-section {
            padding: 20px 18px;
        }
    }

    /* --- FOOTER IN PROFILE / PENGATURAN --- */
    .footer {
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border-color, #e2e8f0);
        padding: 44px 28px 28px;
        margin-top: 48px;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }

    .footer-container {
        max-width: 1240px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.3fr 1fr 1.2fr;
        gap: 36px;
        margin-bottom: 32px;
    }

    .footer-brand h3 {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        margin-bottom: 8px;
    }

    .footer-brand p {
        font-size: 13.5px;
        color: var(--text-muted, #64748b);
        line-height: 1.6;
        margin: 0;
    }

    .footer-heading {
        font-size: 14px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        margin-bottom: 14px;
    }

    .footer-links {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 0;
        margin: 0;
    }

    .footer-links a {
        font-size: 13px;
        color: var(--text-muted, #64748b);
        transition: color 0.15s;
        text-decoration: none;
    }

    .footer-links a:hover {
        color: var(--theme-accent, #0f766e);
    }

    .footer-contact-item {
        font-size: 13px;
        color: var(--text-muted, #64748b);
        margin-bottom: 6px;
        line-height: 1.5;
    }

    .footer-copyright {
        max-width: 1240px;
        margin: 0 auto;
        padding-top: 20px;
        border-top: 1px solid var(--border-color, #f1f5f9);
        font-size: 12.5px;
        color: var(--text-muted, #94a3b8);
        text-align: left;
    }

    @media (max-width: 768px) {
        .footer-container {
            grid-template-columns: 1fr;
            gap: 24px;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-container">

    <!-- PAGE HEADER -->
    <div class="profile-header">
        <div class="profile-breadcrumb">
            <a href="{{ route('home') }}">BOOKNEST</a>
            <span class="separator">/</span>
            <span>{{ ucfirst($user->role === 'petugas' ? 'Petugas' : ($user->role === 'admin' ? 'Admin' : 'Anggota')) }}</span>
            <span class="separator">/</span>
            <span class="current" id="prefBreadcrumbCurrent">{{ request('tab') === 'preferences' ? 'Preferensi Aplikasi' : 'Profil Saya' }}</span>
        </div>
        <h1 class="profile-title" id="prefPageTitle" data-i18n="{{ request('tab') === 'preferences' ? 'pref.title' : 'topbar.profile' }}">
            {{ request('tab') === 'preferences' ? 'Preferensi Aplikasi' : 'Profil Saya' }}
        </h1>
        <p class="profile-subtitle" id="prefPageSubtitle" data-i18n="{{ request('tab') === 'preferences' ? 'pref.subtitle' : '' }}">
            {{ request('tab') === 'preferences' ? 'Atur tema tampilan antarmuka dan preferensi bahasa aplikasi BookNest pada peramban ini.' : ($user->role === 'member' ? 'Kelola data pribadi, keanggotaan, dan keamanan akunmu.' : 'Kelola data pribadi, informasi akun, dan keamanan.') }}
        </p>
    </div>

    <!-- TAB SWITCHER NAV -->
    <div class="preferences-tab-nav" role="tablist" aria-label="Navigasi Pengaturan">
        <button type="button" class="tab-nav-btn {{ request('tab') !== 'preferences' ? 'active' : '' }}" id="tabBtnProfile" onclick="switchSettingsTab('profile')" role="tab" aria-selected="{{ request('tab') !== 'preferences' ? 'true' : 'false' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span data-i18n="pref.tab_profile">Profil Akun</span>
        </button>
        <button type="button" class="tab-nav-btn {{ request('tab') === 'preferences' ? 'active' : '' }}" id="tabBtnPreferences" onclick="switchSettingsTab('preferences')" role="tab" aria-selected="{{ request('tab') === 'preferences' ? 'true' : 'false' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg>
            <span data-i18n="pref.tab_preferences">Preferensi Aplikasi</span>
        </button>
    </div>

    @if(session('success'))
        <div class="profile-alert success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="profile-alert error">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div>
                {{ session('error') ?? $errors->first() }}
            </div>
        </div>
    @endif

    <!-- ============================================ -->
    <!-- TAB 1: PROFIL AKUN (DATA, KEAMANAN, NOTIFIKASI) -->
    <!-- ============================================ -->
    <div id="tabContentProfile" style="display: {{ request('tab') === 'preferences' ? 'none' : 'grid' }};" class="profile-grid">


        <!-- ============================================ -->
        <!-- KOLOM KIRI: RINGKASAN AKUN & PRIVASI -->
        <!-- ============================================ -->
        <div>
            <!-- RINGKASAN MEMBER CARD -->
            <div class="summary-card">
                <div class="avatar-wrapper">
                    <div class="avatar-large" id="avatarPreviewBox">
                        @if($user->foto)
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar {{ $user->name }}" id="avatarImg" onerror="this.style.display='none'; document.getElementById('avatarInitialsFallback').style.display='flex';">
                            <span id="avatarInitialsFallback" style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center;">{{ $user->inisial }}</span>
                        @else
                            <span id="avatarInitialsText">{{ $user->inisial }}</span>
                        @endif
                    </div>
                </div>

                <h2 class="summary-name">{{ $user->name }}</h2>
                <p class="summary-code">{{ $user->kode_anggota ?? ('ID #'.$user->id) }}</p>

                <div class="badge-status-pill {{ $user->status === 'aktif' ? 'aktif' : 'nonaktif' }}">
                    <span class="dot"></span>
                    <span>{{ ucfirst($user->role === 'petugas' ? 'Petugas' : ($user->role === 'admin' ? 'Admin' : 'Anggota')) }} {{ $user->status ?? 'aktif' }}</span>
                </div>

                <div class="summary-meta-table">
                    <div class="summary-meta-row">
                        <span class="summary-meta-label">Bergabung</span>
                        <span class="summary-meta-value">
                            {{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '01 Sep 2026' }}
                        </span>
                    </div>
                    @if($user->role === 'member')
                        <div class="summary-meta-row">
                            <span class="summary-meta-label">Batas pinjam</span>
                            <span class="summary-meta-value">{{ $batasPinjam ?? 3 }} buku</span>
                        </div>
                    @else
                        <div class="summary-meta-row">
                            <span class="summary-meta-label">Peran sistem</span>
                            <span class="summary-meta-value">{{ ucfirst($user->role) }}</span>
                        </div>
                    @endif
                </div>

                <!-- FORM UPLOAD FOTO -->
                <form id="formUploadFoto" action="{{ route('profile.foto') }}" method="POST" enctype="multipart/form-data" style="width: 100%;">
                    @csrf
                    <input type="file" name="foto" id="fotoInput" accept="image/png,image/jpeg,image/jpg,image/webp" style="display: none;" onchange="submitFotoOtomatis(this)">
                    <button type="button" class="btn-ubah-foto" onclick="document.getElementById('fotoInput').click()">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                        <span>Ubah Foto</span>
                    </button>
                    @error('foto')
                        <span class="form-error-msg" style="text-align: center; display: block; margin-top: 6px;">{{ $message }}</span>
                    @enderror
                </form>

                @if($user->foto)
                    <form action="{{ route('profile.foto.destroy') }}" method="POST" style="margin-top: 6px;" onsubmit="return confirm('Hapus foto profil dan gunakan inisial nama?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-hapus-foto">Hapus Foto</button>
                    </form>
                @endif
            </div>

            <!-- PRIVASI DATA -->
            <div class="privacy-card">
                <h3 class="privacy-title">{{ $user->role === 'member' ? 'Privasi data anggota' : 'Privasi & Keamanan' }}</h3>
                <p class="privacy-desc">
                    Identitas dan akses akun Anda dilindungi oleh protokol keamanan terintegrasi BOOKNEST.
                </p>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- KOLOM KANAN: FORM DATA PRIBADI, KEAMANAN, PREFERENSI -->
        <!-- ============================================ -->
        <div>

            <!-- CARD 1: DATA PRIBADI -->
            <div class="profile-card-section">
                <h2 class="section-header-title">Data pribadi</h2>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-grid-2">
                        <!-- Nama Lengkap -->
                        <div class="form-group">
                            <label class="form-label" for="inputName">Nama Lengkap</label>
                            <input type="text" id="inputName" name="name" class="form-control" value="{{ old('name', $user->name) }}" required placeholder="Nama Lengkap">
                            @error('name')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- NIK -->
                        <div class="form-group">
                            <label class="form-label" for="inputNik">NIK</label>
                            @if(!empty($user->nik) && $user->role === 'member')
                                <input type="text" id="inputNik" name="nik" class="form-control is-readonly" value="{{ $user->nik }}" readonly title="NIK telah diverifikasi. Hubungi petugas jika ingin mengganti.">
                            @else
                                <input type="text" id="inputNik" name="nik" class="form-control" value="{{ old('nik', $user->nik) }}" placeholder="3273011505980004" maxlength="25">
                            @endif
                            @error('nik')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label" for="inputEmail">Email</label>
                            <input type="email" id="inputEmail" name="email" class="form-control" value="{{ old('email', $user->email) }}" required placeholder="rizky.pratama@email.com">
                            @error('email')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div class="form-group">
                            <label class="form-label" for="inputTelepon">Telepon</label>
                            <input type="text" id="inputTelepon" name="noTelepon" class="form-control" value="{{ old('noTelepon', $user->noTelepon) }}" placeholder="0812 3456 7890">
                            @error('noTelepon')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="form-group">
                            <label class="form-label" for="inputAlamat">Alamat</label>
                            <input type="text" id="inputAlamat" name="alamat" class="form-control" value="{{ old('alamat', $user->alamat) }}" placeholder="Jl. Melati No. 18, Bandung">
                            @error('alamat')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="form-group">
                            <label class="form-label" for="inputTanggalLahir">Tanggal Lahir</label>
                            <input type="date" id="inputTanggalLahir" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', optional($user->tanggal_lahir)->format('Y-m-d')) }}">
                            @error('tanggal_lahir')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn-save-primary">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- CARD 2: KEAMANAN AKUN -->
            <div class="profile-card-section">
                <h2 class="section-header-title">Keamanan akun</h2>

                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-grid-2">
                        <!-- Password Saat Ini -->
                        <div class="form-group">
                            <label class="form-label" for="inputCurrentPassword">Password Saat Ini</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <input type="password" id="inputCurrentPassword" name="current_password" class="form-control" required placeholder="••••••••••" style="padding-right: 42px;">
                                <button type="button" onclick="togglePassVisibility('inputCurrentPassword', this)" aria-label="Lihat password" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; color: #94a3b8; display: flex; align-items: center; justify-content: center; padding: 4px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </div>
                            @error('current_password')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Baru -->
                        <div class="form-group">
                            <label class="form-label" for="inputNewPassword">Password Baru</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <input type="password" id="inputNewPassword" name="password" class="form-control" required placeholder="Minimal 8 karakter" style="padding-right: 42px;">
                                <button type="button" onclick="togglePassVisibility('inputNewPassword', this)" aria-label="Lihat password" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; color: #94a3b8; display: flex; align-items: center; justify-content: center; padding: 4px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </div>
                            @error('password')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div class="form-group full-width">
                            <label class="form-label" for="inputConfirmPassword">Konfirmasi Password Baru</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <input type="password" id="inputConfirmPassword" name="password_confirmation" class="form-control" required placeholder="Ulangi password baru" style="padding-right: 42px;">
                                <button type="button" onclick="togglePassVisibility('inputConfirmPassword', this)" aria-label="Lihat password" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; color: #94a3b8; display: flex; align-items: center; justify-content: center; padding: 4px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 18px;">
                        <button type="submit" class="btn-save-blue">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- CARD 3: PREFERENSI NOTIFIKASI -->
            <div class="profile-card-section">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
                    <h2 class="section-header-title" style="margin-bottom: 0;">Preferensi notifikasi</h2>
                    <span id="notifStatusToast" class="notif-saved-toast">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Tersimpan</span>
                    </span>
                </div>

                <form id="notifPreferencesForm" action="{{ route('profile.notifikasi') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="notif-options-list">
                        <!-- Checkbox 1: Jatuh tempo -->
                        <label class="notif-checkbox-label">
                            <span class="custom-checkbox">
                                <input type="checkbox" name="notif_jatuh_tempo" id="checkNotifJatuhTempo" value="1" {{ old('notif_jatuh_tempo', $user->notif_jatuh_tempo ?? true) ? 'checked' : '' }} onchange="autoSimpanNotifikasi()">
                                <span class="checkbox-mark">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </span>
                            </span>
                            <span>Pengingat jatuh tempo melalui email</span>
                        </label>

                        <!-- Checkbox 2: Koleksi baru -->
                        <label class="notif-checkbox-label">
                            <span class="custom-checkbox">
                                <input type="checkbox" name="notif_koleksi_baru" id="checkNotifKoleksiBaru" value="1" {{ old('notif_koleksi_baru', $user->notif_koleksi_baru ?? true) ? 'checked' : '' }} onchange="autoSimpanNotifikasi()">
                                <span class="checkbox-mark">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </span>
                            </span>
                            <span>Informasi koleksi baru dan kegiatan perpustakaan</span>
                        </label>
                    </div>
                </form>
            </div>

        </div>

    </div>
    <!-- END TAB 1 (PROFIL AKUN) -->

    <!-- ============================================ -->
    <!-- TAB 2: PREFERENSI APLIKASI (LENGKAP 4 SEKSI) -->
    <!-- ============================================ -->
    <div id="tabContentPreferences" style="display: {{ request('tab') === 'preferences' ? 'block' : 'none' }};">

        <!-- KARTU 1: TEMA TAMPILAN ANTARMUKA -->
        <div class="pref-section-card">
            <div class="pref-section-header">
                <div class="pref-section-info">
                    <h2 data-i18n="pref.theme_title">Tema Tampilan Antarmuka</h2>
                    <p data-i18n="pref.theme_desc">Pilih tampilan visual yang paling nyaman untuk mata Anda saat menggunakan sistem perpustakaan.</p>
                </div>
            </div>

            <!-- HORIZONTAL THEME SELECTOR CONTAINER -->
            <div class="theme-selector-container opening-selector-container">
                <button type="button" class="theme-nav-arrow opening-nav-arrow prev" onclick="scrollThemeTrack(-1)" aria-label="Geser pilihan tema ke kiri">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>

                <div class="theme-horizontal-track opening-horizontal-track" id="themeHorizontalTrack">
                    <!-- PILIHAN 1: MODE TERANG -->
                    <div class="pref-option-card theme-h-card opening-h-card" id="themeCardLight" onclick="selectPrefTheme('light')" role="button" tabindex="0" aria-label="Aktifkan Mode Terang">
                        <div class="pref-mockup-wrapper">
                            <div class="theme-mockup-light">
                                <div class="mockup-nav">
                                    <span style="width: 14px; height: 6px; border-radius: 2px; background: #0f766e; display: inline-block;"></span>
                                    <span style="width: 24px; height: 4px; border-radius: 2px; background: #cbd5e1; display: inline-block;"></span>
                                    <span style="width: 20px; height: 4px; border-radius: 2px; background: #cbd5e1; display: inline-block;"></span>
                                </div>
                                <div class="mockup-body">
                                    <div class="mockup-sidebar"></div>
                                    <div class="mockup-content">
                                        <div class="mockup-card"></div>
                                        <div class="mockup-card" style="flex: 1.4;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="theme-palette-swatches">
                            <span class="theme-swatch" style="background: #ffffff;" title="Surface Putih"></span>
                            <span class="theme-swatch" style="background: #f8fafc;" title="Background Page"></span>
                            <span class="theme-swatch" style="background: #0f766e;" title="Aksen Teal"></span>
                            <span class="theme-swatch" style="background: #0f172a;" title="Teks Utama"></span>
                            <span class="theme-swatch" style="background: #e2e8f0;" title="Border"></span>
                        </div>

                        <div class="pref-option-top">
                            <div class="pref-option-icon-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="5"></circle>
                                    <line x1="12" y1="1" x2="12" y2="3"></line>
                                    <line x1="12" y1="21" x2="12" y2="23"></line>
                                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                    <line x1="1" y1="12" x2="3" y2="12"></line>
                                    <line x1="21" y1="12" x2="23" y2="12"></line>
                                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>

                        <h3 class="pref-option-title" data-i18n="pref.light_title">Mode Terang (Light Mode)</h3>
                        <p class="pref-option-desc" data-i18n="pref.light_desc">Tampilan bersih, cerah, dan kontras tinggi untuk penggunaan di ruangan berpenerangan cukup.</p>
                        <span class="pref-option-badge">Gunakan Pilihan Ini</span>
                    </div>

                    <!-- PILIHAN 2: MODE GELAP -->
                    <div class="pref-option-card theme-h-card opening-h-card" id="themeCardDark" onclick="selectPrefTheme('dark')" role="button" tabindex="0" aria-label="Aktifkan Mode Gelap">
                        <div class="pref-mockup-wrapper">
                            <div class="theme-mockup-dark">
                                <div class="mockup-nav">
                                    <span style="width: 14px; height: 6px; border-radius: 2px; background: #14b8a6; display: inline-block;"></span>
                                    <span style="width: 24px; height: 4px; border-radius: 2px; background: #334155; display: inline-block;"></span>
                                    <span style="width: 20px; height: 4px; border-radius: 2px; background: #334155; display: inline-block;"></span>
                                </div>
                                <div class="mockup-body">
                                    <div class="mockup-sidebar"></div>
                                    <div class="mockup-content">
                                        <div class="mockup-card"></div>
                                        <div class="mockup-card" style="flex: 1.4;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="theme-palette-swatches">
                            <span class="theme-swatch" style="background: #1e293b;" title="Surface Slate"></span>
                            <span class="theme-swatch" style="background: #0b1120;" title="Deep Background"></span>
                            <span class="theme-swatch" style="background: #14b8a6;" title="Aksen Mint"></span>
                            <span class="theme-swatch" style="background: #f8fafc;" title="Teks Terang"></span>
                            <span class="theme-swatch" style="background: #334155;" title="Border"></span>
                        </div>

                        <div class="pref-option-top">
                            <div class="pref-option-icon-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>

                        <h3 class="pref-option-title" data-i18n="pref.dark_title">Mode Gelap (Dark Mode)</h3>
                        <p class="pref-option-desc" data-i18n="pref.dark_desc">Tampilan nyaman di mata dengan dominasi warna gelap, meredakan ketegangan mata saat malam hari.</p>
                        <span class="pref-option-badge">Gunakan Pilihan Ini</span>
                    </div>

                    <!-- PILIHAN 3: SISTEM (AUTO) -->
                    <div class="pref-option-card theme-h-card opening-h-card" id="themeCardSystem" onclick="selectPrefTheme('system')" role="button" tabindex="0" aria-label="Ikuti Setelan Sistem">
                        <div class="pref-mockup-wrapper">
                            <div class="theme-mockup-system">
                                <div class="half-light">
                                    <span style="width: 12px; height: 4px; border-radius: 2px; background: #0f766e; display: inline-block;"></span>
                                    <div style="height: 18px; border-radius: 4px; background: #e2e8f0; margin-top: 4px;"></div>
                                    <div style="height: 28px; border-radius: 4px; background: #ffffff; border: 1px solid #cbd5e1; margin-top: 4px;"></div>
                                </div>
                                <div class="half-dark">
                                    <span style="width: 12px; height: 4px; border-radius: 2px; background: #2dd4bf; display: inline-block;"></span>
                                    <div style="height: 18px; border-radius: 4px; background: #1e293b; margin-top: 4px;"></div>
                                    <div style="height: 28px; border-radius: 4px; background: #131d31; border: 1px solid #23314d; margin-top: 4px;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="theme-palette-swatches">
                            <span class="theme-swatch" style="background: #ffffff;" title="Mode Terang"></span>
                            <span class="theme-swatch" style="background: #0f172a;" title="Mode Gelap"></span>
                            <span class="theme-swatch" style="background: #0f766e;" title="Teal Primary"></span>
                            <span class="theme-swatch" style="background: #2dd4bf;" title="Mint Accent"></span>
                            <span class="theme-swatch" style="background: #64748b;" title="Otomatis Sistem"></span>
                        </div>

                        <div class="pref-option-top">
                            <div class="pref-option-icon-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>

                        <h3 class="pref-option-title" data-i18n="pref.system_title">Mengikuti Sistem (System)</h3>
                        <p class="pref-option-desc" data-i18n="pref.system_desc">Menyesuaikan mode terang atau gelap secara otomatis dengan setelan sistem operasi Anda.</p>
                        <span class="pref-option-badge">Gunakan Pilihan Ini</span>
                    </div>

                    <!-- PILIHAN 4: SOFT BLUSH -->
                    <div class="pref-option-card theme-h-card opening-h-card" id="themeCardSoftBlush" onclick="selectPrefTheme('soft-blush')" role="button" tabindex="0" aria-label="Aktifkan Tema Soft Blush">
                        <div class="pref-mockup-wrapper">
                            <div class="theme-mockup-soft-blush">
                                <div class="mockup-nav">
                                    <span style="width: 14px; height: 6px; border-radius: 2px; background: #b85d77; display: inline-block;"></span>
                                    <span style="width: 24px; height: 4px; border-radius: 2px; background: #e5cfd6; display: inline-block;"></span>
                                    <span style="width: 20px; height: 4px; border-radius: 2px; background: #e5cfd6; display: inline-block;"></span>
                                </div>
                                <div class="mockup-body">
                                    <div class="mockup-sidebar"></div>
                                    <div class="mockup-content">
                                        <div class="mockup-card"></div>
                                        <div class="mockup-card" style="flex: 1.4;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="theme-palette-swatches">
                            <span class="theme-swatch" style="background: #ffffff;" title="Surface Ivory"></span>
                            <span class="theme-swatch" style="background: #fdf8f9;" title="Background Blush"></span>
                            <span class="theme-swatch" style="background: #b85d77;" title="Muted Rose"></span>
                            <span class="theme-swatch" style="background: #2e2528;" title="Text Charcoal"></span>
                            <span class="theme-swatch" style="background: #eedde2;" title="Rose Border"></span>
                        </div>

                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: #fceef2; color: #b85d77;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>

                        <h3 class="pref-option-title" data-i18n="pref.soft_blush_title">Soft Blush</h3>
                        <p class="pref-option-desc" data-i18n="pref.soft_blush_desc">Palet dusty rose & warm ivory yang tenang, lembut, elegan, dan nyaman dibaca.</p>
                        <span class="pref-option-badge">Gunakan Pilihan Ini</span>
                    </div>

                    <!-- PILIHAN 5: VAN GOGH (STARRY NIGHT) -->
                    <div class="pref-option-card theme-h-card opening-h-card" id="themeCardVanGogh" onclick="selectPrefTheme('van-gogh')" role="button" tabindex="0" aria-label="Aktifkan Tema Van Gogh">
                        <div class="pref-mockup-wrapper">
                            <div class="theme-mockup-van-gogh">
                                <div class="mockup-nav">
                                    <span style="width: 14px; height: 6px; border-radius: 2px; background: #e9b949; display: inline-block;"></span>
                                    <span style="width: 24px; height: 4px; border-radius: 2px; background: #245b8f; display: inline-block;"></span>
                                    <span style="width: 20px; height: 4px; border-radius: 2px; background: #245b8f; display: inline-block;"></span>
                                </div>
                                <div class="mockup-body">
                                    <div class="mockup-sidebar"></div>
                                    <div class="mockup-content">
                                        <div class="mockup-card"></div>
                                        <div class="mockup-card" style="flex: 1.4;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="theme-palette-swatches">
                            <span class="theme-swatch" style="background: #132238;" title="Navy Surface"></span>
                            <span class="theme-swatch" style="background: #0d1b2a;" title="Deep Night Navy"></span>
                            <span class="theme-swatch" style="background: #245b8f;" title="Cobalt Blue"></span>
                            <span class="theme-swatch" style="background: #e9b949;" title="Golden Star"></span>
                            <span class="theme-swatch" style="background: #f6f0df;" title="Warm Ivory"></span>
                        </div>

                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: rgba(233, 185, 73, 0.18); color: #d97706;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>

                        <h3 class="pref-option-title" data-i18n="pref.van_gogh_title">Van Gogh (Starry Night)</h3>
                        <p class="pref-option-desc" data-i18n="pref.van_gogh_desc">Estetika langit malam melingkar, kobalt biru, navy pekat, dan aksen cahaya emas artistik.</p>
                        <span class="pref-option-badge">Gunakan Pilihan Ini</span>
                    </div>

                    <!-- PILIHAN 6: MIDNIGHT SLATE -->
                    <div class="pref-option-card theme-h-card opening-h-card" id="themeCardMidnightSlate" onclick="selectPrefTheme('midnight-slate')" role="button" tabindex="0" aria-label="Aktifkan Tema Midnight Slate">
                        <div class="pref-mockup-wrapper">
                            <div class="theme-mockup-midnight-slate">
                                <div class="mockup-nav">
                                    <span style="width: 14px; height: 6px; border-radius: 2px; background: #38bdf8; display: inline-block;"></span>
                                    <span style="width: 24px; height: 4px; border-radius: 2px; background: #334155; display: inline-block;"></span>
                                    <span style="width: 20px; height: 4px; border-radius: 2px; background: #334155; display: inline-block;"></span>
                                </div>
                                <div class="mockup-body">
                                    <div class="mockup-sidebar"></div>
                                    <div class="mockup-content">
                                        <div class="mockup-card"></div>
                                        <div class="mockup-card" style="flex: 1.4;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="theme-palette-swatches">
                            <span class="theme-swatch" style="background: #162035;" title="Slate Card"></span>
                            <span class="theme-swatch" style="background: #0b1329;" title="Deep Slate BG"></span>
                            <span class="theme-swatch" style="background: #38bdf8;" title="Steel Blue"></span>
                            <span class="theme-swatch" style="background: #f8fafc;" title="Teks Terang"></span>
                            <span class="theme-swatch" style="background: #28354b;" title="Slate Border"></span>
                        </div>

                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                    <path d="M2 17l10 5 10-5"></path>
                                    <path d="M2 12l10 5 10-5"></path>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>

                        <h3 class="pref-option-title" data-i18n="pref.midnight_slate_title">Midnight Slate</h3>
                        <p class="pref-option-desc" data-i18n="pref.midnight_slate_desc">Nuansa slate blue & steel modern, tegas, profesional, dan teduh untuk fokus kerja.</p>
                        <span class="pref-option-badge">Gunakan Pilihan Ini</span>
                    </div>
                </div>

                <button type="button" class="theme-nav-arrow opening-nav-arrow next" onclick="scrollThemeTrack(1)" aria-label="Geser pilihan tema ke kanan">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        </div>

        <!-- KARTU 2: FONT DAN UKURAN TEKS -->
        <div class="pref-section-card" style="margin-top: 24px;">
            <div class="pref-section-header">
                <div class="pref-section-info">
                    <h2 data-i18n="pref.font_title">Font & Tipografi</h2>
                    <p data-i18n="pref.font_desc">Pilih jenis huruf yang Anda sukai untuk seluruh teks dan antarmuka BookNest.</p>
                </div>
            </div>

            <!-- Pilihan Font -->
            <div class="pref-font-grid">
                <!-- Inter -->
                <div class="pref-font-card" id="fontCard_Inter" onclick="selectPrefFont('Inter')" role="button" tabindex="0">
                    <div class="pref-font-title" style="font-family: 'Inter', sans-serif;">Inter</div>
                    <div class="pref-font-sample" style="font-family: 'Inter', sans-serif;">Modern, bersih, sangat terbaca di layar digital.</div>
                </div>

                <!-- Poppins -->
                <div class="pref-font-card" id="fontCard_Poppins" onclick="selectPrefFont('Poppins')" role="button" tabindex="0">
                    <div class="pref-font-title" style="font-family: 'Poppins', sans-serif;">Poppins</div>
                    <div class="pref-font-sample" style="font-family: 'Poppins', sans-serif;">Geometris, hangat, dan berkarakter ramah.</div>
                </div>

                <!-- Roboto -->
                <div class="pref-font-card" id="fontCard_Roboto" onclick="selectPrefFont('Roboto')" role="button" tabindex="0">
                    <div class="pref-font-title" style="font-family: 'Roboto', sans-serif;">Roboto</div>
                    <div class="pref-font-sample" style="font-family: 'Roboto', sans-serif;">Standar profesional, presisi, dan proporsional.</div>
                </div>

                <!-- Open Sans -->
                <div class="pref-font-card" id="fontCard_OpenSans" onclick="selectPrefFont('Open Sans')" role="button" tabindex="0">
                    <div class="pref-font-title" style="font-family: 'Open Sans', sans-serif;">Open Sans</div>
                    <div class="pref-font-sample" style="font-family: 'Open Sans', sans-serif;">Netral, seimbang, dan nyaman untuk bacaan panjang.</div>
                </div>

                <!-- Lato -->
                <div class="pref-font-card" id="fontCard_Lato" onclick="selectPrefFont('Lato')" role="button" tabindex="0">
                    <div class="pref-font-title" style="font-family: 'Lato', sans-serif;">Lato</div>
                    <div class="pref-font-sample" style="font-family: 'Lato', sans-serif;">Klasik modern, elegan, dengan lekukan halus.</div>
                </div>
            </div>

            <!-- Pilihan Ukuran Teks -->
            <div class="pref-section-header" style="margin-top: 14px; margin-bottom: 12px;">
                <div class="pref-section-info">
                    <h3 style="font-size: 15px; font-weight: 800; color: var(--text-heading);" data-i18n="pref.font_size_title">Ukuran Teks</h3>
                    <p style="font-size: 13px; color: var(--text-muted);" data-i18n="pref.font_size_desc">Sesuaikan skala ukuran huruf untuk kenyamanan membaca optimal tanpa merusak susunan tata letak.</p>
                </div>
            </div>

            <div class="pref-size-pills">
                <button type="button" class="pref-size-btn" id="fontSizeBtn_small" onclick="selectPrefFontSize('small')">
                    <span style="font-size: 12px; font-weight: 800;">A-</span>
                    <span data-i18n="pref.font_size_small">Kecil (13.5px)</span>
                </button>
                <button type="button" class="pref-size-btn active" id="fontSizeBtn_normal" onclick="selectPrefFontSize('normal')">
                    <span style="font-size: 14px; font-weight: 800;">A</span>
                    <span data-i18n="pref.font_size_normal">Normal (14.5px)</span>
                </button>
                <button type="button" class="pref-size-btn" id="fontSizeBtn_large" onclick="selectPrefFontSize('large')">
                    <span style="font-size: 16px; font-weight: 800;">A+</span>
                    <span data-i18n="pref.font_size_large">Besar (16px)</span>
                </button>
            </div>

            <!-- Pratinjau Tipografi Langsung -->
            <div class="pref-live-preview-box">
                <div class="pref-live-preview-header">
                    <div class="pref-live-preview-title" data-i18n="pref.preview_title">Pratinjau Tipografi Langsung</div>
                    <span class="pref-live-preview-badge" id="prefPreviewFontIndicator">Inter • Normal</span>
                </div>
                <div class="pref-live-preview-text" id="prefLivePreviewText" data-i18n="pref.preview_sample">
                    BookNest — Ruang Pengetahuan Terbuka, Katalog Eksemplar & Sirkulasi Perpustakaan Cepat.
                </div>
            </div>
        </div>

        <!-- KARTU 3: KONSEP LAYAR PEMBUKA (OPENING SPLASH) -->
        <div class="pref-section-card" style="margin-top: 24px;">
            <div class="pref-section-header" style="flex-wrap: wrap; gap: 16px;">
                <div class="pref-section-info">
                    <h2 data-i18n="pref.opening_title">Konsep Layar Pembuka (Opening)</h2>
                    <p data-i18n="pref.opening_desc">Pilih konsep animasi visual splash curtain pembuka saat memuat aplikasi BookNest. Pilihan ini hanya memengaruhi splash pembuka, bukan tema dashboard.</p>
                </div>

                <!-- DROPDOWN GAYA OPENING -->
                <div class="opening-category-dropdown-wrapper">
                    <label for="openingCategorySelect" class="opening-dropdown-label" data-i18n="pref.opening_cat_label">Kategori Gaya Opening:</label>
                    <div class="opening-select-custom">
                        <select id="openingCategorySelect" onchange="changeOpeningCategory(this.value)" class="opening-select-control">
                            <option value="cewek" data-i18n="pref.opening_cat_cewek">Feminine</option>
                            <option value="cowo" data-i18n="pref.opening_cat_cowo">Masculine</option>
                            <option value="artistic" data-i18n="pref.opening_cat_artistic">Artistik (Van Gogh)</option>
                        </select>
                        <svg class="opening-select-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- HORIZONTAL SELECTOR CONTAINER -->
            <div class="opening-selector-container">
                <button type="button" class="opening-nav-arrow prev" onclick="scrollOpeningTrack(-1)" aria-label="Geser ke kiri">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>

                <!-- TRACK 1: FEMININE (5 KONSEP) -->
                <div class="opening-horizontal-track" id="openingTrack_cewek">
                    <!-- Konsep 1: Coquette Blush -->
                    <div class="pref-option-card opening-h-card" id="openingCard_coquette_blush" onclick="selectPrefOpening('coquette-blush')" role="button" tabindex="0">
                        <div class="pref-mockup-wrapper">
                            <div class="opening-mockup-box opening-mockup-coquette">
                                <div class="opening-mockup-center" style="background: rgba(255,255,255,0.85); color: #ad1457; box-shadow: 0 0 16px rgba(244,143,177,0.7); border: 1px solid rgba(244,143,177,0.5);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: #fce4ec; color: #ad1457;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>
                        <h3 class="pref-option-title" data-i18n="pref.opening_coquette_blush_title">Coquette Blush</h3>
                        <p class="pref-option-desc" data-i18n="pref.opening_coquette_blush_desc">Baby pink, cream, rose gold dengan kilau mutiara lembut dan nuansa satin elegan.</p>
                        <button type="button" class="btn-preview-opening" onclick="event.stopPropagation(); window.previewBooknestOpening('coquette-blush');">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span data-i18n="pref.btn_preview_opening">Coba Opening Ini</span>
                        </button>
                    </div>

                    <!-- Konsep 2: Fairy Dream -->
                    <div class="pref-option-card opening-h-card" id="openingCard_fairy_dream" onclick="selectPrefOpening('fairy-dream')" role="button" tabindex="0">
                        <div class="pref-mockup-wrapper">
                            <div class="opening-mockup-box opening-mockup-fairy">
                                <div class="opening-mockup-center" style="background: rgba(255,255,255,0.85); color: #6b21a8; box-shadow: 0 0 16px rgba(192,132,252,0.7); border: 1px solid rgba(192,132,252,0.5);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: #f3e8ff; color: #7e22ce;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>
                        <h3 class="pref-option-title" data-i18n="pref.opening_fairy_dream_title">Fairy Dream</h3>
                        <p class="pref-option-desc" data-i18n="pref.opening_fairy_dream_desc">Lavender, baby pink, dan pearl white dengan efek dreamy lembut nan magis.</p>
                        <button type="button" class="btn-preview-opening" onclick="event.stopPropagation(); window.previewBooknestOpening('fairy-dream');">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span data-i18n="pref.btn_preview_opening">Coba Opening Ini</span>
                        </button>
                    </div>

                    <!-- Konsep 3: Sakura Bloom -->
                    <div class="pref-option-card opening-h-card" id="openingCard_sakura_bloom" onclick="selectPrefOpening('sakura-bloom')" role="button" tabindex="0">
                        <div class="pref-mockup-wrapper">
                            <div class="opening-mockup-box opening-mockup-sakura">
                                <div class="opening-mockup-center" style="background: rgba(255,255,255,0.9); color: #be185d; box-shadow: 0 0 16px rgba(251,113,133,0.7); border: 1px solid rgba(251,113,133,0.5);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: #ffe4e6; color: #be185d;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M12 2a4 4 0 0 1 4 4c0 3-4 6-4 6s-4-3-4-6a4 4 0 0 1 4-4z"></path>
                                    <path d="M12 22a4 4 0 0 1-4-4c0-3 4-6 4-6s4 3 4 6a4 4 0 0 1-4 4z"></path>
                                    <path d="M2 12a4 4 0 0 1 4-4c3 0 6 4 6 4s-3 4-6 4a4 4 0 0 1-4-4z"></path>
                                    <path d="M22 12a4 4 0 0 1-4 4c-3 0-6-4-6-4s3-4 6-4a4 4 0 0 1 4 4z"></path>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>
                        <h3 class="pref-option-title" data-i18n="pref.opening_sakura_bloom_title">Sakura Bloom</h3>
                        <p class="pref-option-desc" data-i18n="pref.opening_sakura_bloom_desc">Sakura pink, putih bersih, dan soft mint dengan kesan hembusan kelopak bunga tenang.</p>
                        <button type="button" class="btn-preview-opening" onclick="event.stopPropagation(); window.previewBooknestOpening('sakura-bloom');">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span data-i18n="pref.btn_preview_opening">Coba Opening Ini</span>
                        </button>
                    </div>

                    <!-- Konsep 4: Strawberry Milk -->
                    <div class="pref-option-card opening-h-card" id="openingCard_strawberry_milk" onclick="selectPrefOpening('strawberry-milk')" role="button" tabindex="0">
                        <div class="pref-mockup-wrapper">
                            <div class="opening-mockup-box opening-mockup-strawberry">
                                <div class="opening-mockup-center" style="background: rgba(255,255,255,0.3); color: #ffffff; box-shadow: 0 0 16px rgba(254,215,170,0.7); border: 1px solid rgba(255,255,255,0.4);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: #ffe4e6; color: #e11d48;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>
                        <h3 class="pref-option-title" data-i18n="pref.opening_strawberry_milk_title">Strawberry Milk</h3>
                        <p class="pref-option-desc" data-i18n="pref.opening_strawberry_milk_desc">Strawberry pink, peach, dan milky white dengan sentuhan glossy playful ceria.</p>
                        <button type="button" class="btn-preview-opening" onclick="event.stopPropagation(); window.previewBooknestOpening('strawberry-milk');">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span data-i18n="pref.btn_preview_opening">Coba Opening Ini</span>
                        </button>
                    </div>

                    <!-- Konsep 5: Pink Garden -->
                    <div class="pref-option-card opening-h-card" id="openingCard_pink_garden" onclick="selectPrefOpening('pink-garden')" role="button" tabindex="0">
                        <div class="pref-mockup-wrapper">
                            <div class="opening-mockup-box opening-mockup-garden">
                                <div class="opening-mockup-center" style="background: rgba(255,255,255,0.85); color: #9d174d; box-shadow: 0 0 16px rgba(110,231,183,0.7); border: 1px solid rgba(236,72,153,0.4);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: #fce7f3; color: #9d174d;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                    <path d="M2 17l10 5 10-5"></path>
                                    <path d="M2 12l10 5 10-5"></path>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>
                        <h3 class="pref-option-title" data-i18n="pref.opening_pink_garden_title">Pink Garden</h3>
                        <p class="pref-option-desc" data-i18n="pref.opening_pink_garden_desc">Dusty pink, sage green, dan cream dengan paduan botani yang anggun dan estetik.</p>
                        <button type="button" class="btn-preview-opening" onclick="event.stopPropagation(); window.previewBooknestOpening('pink-garden');">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span data-i18n="pref.btn_preview_opening">Coba Opening Ini</span>
                        </button>
                    </div>
                </div>

                <!-- TRACK 2: MASCULINE (5 KONSEP) -->
                <div class="opening-horizontal-track" id="openingTrack_cowo" style="display: none;">
                    <!-- Konsep 1: Midnight Blue -->
                    <div class="pref-option-card opening-h-card" id="openingCard_midnight_blue" onclick="selectPrefOpening('midnight-blue')" role="button" tabindex="0">
                        <div class="pref-mockup-wrapper">
                            <div class="opening-mockup-box opening-mockup-midnight">
                                <div class="opening-mockup-center" style="background: rgba(30,41,59,0.7); color: #60a5fa; box-shadow: 0 0 16px rgba(59,130,246,0.6); border: 1px solid rgba(59,130,246,0.4);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: #1e3a8a; color: #93c5fd;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>
                        <h3 class="pref-option-title" data-i18n="pref.opening_midnight_blue_title">Midnight Blue</h3>
                        <p class="pref-option-desc" data-i18n="pref.opening_midnight_blue_desc">Navy pekat dan biru safir gelap dengan aksen dingin yang bersih dan maskulin.</p>
                        <button type="button" class="btn-preview-opening" onclick="event.stopPropagation(); window.previewBooknestOpening('midnight-blue');">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span data-i18n="pref.btn_preview_opening">Coba Opening Ini</span>
                        </button>
                    </div>

                    <!-- Konsep 2: Cyber Neon -->
                    <div class="pref-option-card opening-h-card" id="openingCard_cyber_neon" onclick="selectPrefOpening('cyber-neon')" role="button" tabindex="0">
                        <div class="pref-mockup-wrapper">
                            <div class="opening-mockup-box opening-mockup-cyber">
                                <div class="opening-mockup-center" style="background: rgba(8,47,73,0.8); color: #22d3ee; box-shadow: 0 0 20px rgba(6,182,212,0.8); border: 1px solid rgba(34,211,238,0.6);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: #083344; color: #22d3ee;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>
                        <h3 class="pref-option-title" data-i18n="pref.opening_cyber_neon_title">Cyber Neon</h3>
                        <p class="pref-option-desc" data-i18n="pref.opening_cyber_neon_desc">Dark slate, electric cyan, dan aksen neon berenergi tinggi yang futuristik.</p>
                        <button type="button" class="btn-preview-opening" onclick="event.stopPropagation(); window.previewBooknestOpening('cyber-neon');">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span data-i18n="pref.btn_preview_opening">Coba Opening Ini</span>
                        </button>
                    </div>

                    <!-- Konsep 3: Dark Steel -->
                    <div class="pref-option-card opening-h-card" id="openingCard_dark_steel" onclick="selectPrefOpening('dark-steel')" role="button" tabindex="0">
                        <div class="pref-mockup-wrapper">
                            <div class="opening-mockup-box opening-mockup-steel">
                                <div class="opening-mockup-center" style="background: rgba(30,41,59,0.8); color: #e2e8f0; box-shadow: 0 0 16px rgba(148,163,184,0.5); border: 1px solid rgba(148,163,184,0.4);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: #334155; color: #f1f5f9;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>
                        <h3 class="pref-option-title" data-i18n="pref.opening_dark_steel_title">Dark Steel</h3>
                        <p class="pref-option-desc" data-i18n="pref.opening_dark_steel_desc">Charcoal, abu-abu baja, dan sentuhan silver industrial yang kokoh.</p>
                        <button type="button" class="btn-preview-opening" onclick="event.stopPropagation(); window.previewBooknestOpening('dark-steel');">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span data-i18n="pref.btn_preview_opening">Coba Opening Ini</span>
                        </button>
                    </div>

                    <!-- Konsep 4: Forest Mode -->
                    <div class="pref-option-card opening-h-card" id="openingCard_forest_mode" onclick="selectPrefOpening('forest-mode')" role="button" tabindex="0">
                        <div class="pref-mockup-wrapper">
                            <div class="opening-mockup-box opening-mockup-forest">
                                <div class="opening-mockup-center" style="background: rgba(20,83,45,0.8); color: #86efac; box-shadow: 0 0 16px rgba(34,197,94,0.6); border: 1px solid rgba(34,197,94,0.4);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: #14532d; color: #86efac;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2l4 7h-3l4 7h-4l3 6H8l3-6H7l4-7H8l4-7z"></path>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>
                        <h3 class="pref-option-title" data-i18n="pref.opening_forest_mode_title">Forest Mode</h3>
                        <p class="pref-option-desc" data-i18n="pref.opening_forest_mode_desc">Deep forest green, pinus, dan sage natural yang meneduhkan dan fokus.</p>
                        <button type="button" class="btn-preview-opening" onclick="event.stopPropagation(); window.previewBooknestOpening('forest-mode');">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span data-i18n="pref.btn_preview_opening">Coba Opening Ini</span>
                        </button>
                    </div>

                    <!-- Konsep 5: Sunset Ember -->
                    <div class="pref-option-card opening-h-card" id="openingCard_sunset_ember" onclick="selectPrefOpening('sunset-ember')" role="button" tabindex="0">
                        <div class="pref-mockup-wrapper">
                            <div class="opening-mockup-box opening-mockup-sunset">
                                <div class="opening-mockup-center" style="background: rgba(124,45,18,0.8); color: #fdba74; box-shadow: 0 0 16px rgba(249,115,22,0.7); border: 1px solid rgba(249,115,22,0.5);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: #7c2d12; color: #fdba74;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="5"></circle>
                                    <line x1="12" y1="1" x2="12" y2="3"></line>
                                    <line x1="12" y1="21" x2="12" y2="23"></line>
                                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                    <line x1="1" y1="12" x2="3" y2="12"></line>
                                    <line x1="21" y1="12" x2="23" y2="12"></line>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>
                        <h3 class="pref-option-title" data-i18n="pref.opening_sunset_ember_title">Sunset Ember</h3>
                        <p class="pref-option-desc" data-i18n="pref.opening_sunset_ember_desc">Burnt orange, amber keemasan, dan charcoal hangat seperti bara senja.</p>
                        <button type="button" class="btn-preview-opening" onclick="event.stopPropagation(); window.previewBooknestOpening('sunset-ember');">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span data-i18n="pref.btn_preview_opening">Coba Opening Ini</span>
                        </button>
                    </div>
                </div>

                <!-- TRACK 3: ARTISTIK (VAN GOGH — STARRY NIGHT) -->
                <div class="opening-horizontal-track" id="openingTrack_artistic" style="display: none;">
                    <!-- Konsep 1: Van Gogh — Starry Night -->
                    <div class="pref-option-card opening-h-card" id="openingCard_van_gogh_starry_night" onclick="selectPrefOpening('van-gogh-starry-night')" role="button" tabindex="0">
                        <div class="pref-mockup-wrapper">
                            <div class="opening-mockup-box opening-mockup-van-gogh">
                                <div class="opening-mockup-center" style="background: rgba(16,42,67,0.85); color: #fcd34d; box-shadow: 0 0 24px rgba(245,158,11,0.6); border: 1px solid rgba(245,158,11,0.5);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="pref-option-top">
                            <div class="pref-option-icon-box" style="background: rgba(233, 185, 73, 0.18); color: #d97706;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                                </svg>
                            </div>
                            <span class="pref-radio-indicator"></span>
                        </div>
                        <h3 class="pref-option-title" data-i18n="pref.opening_van_gogh_starry_night_title">Van Gogh — Starry Night</h3>
                        <p class="pref-option-desc" data-i18n="pref.opening_van_gogh_starry_night_desc">Langit malam kobalt melingkar, bulan sabit keemasan, dan bintang celestial yang tenang dan menginspirasi.</p>
                        <button type="button" class="btn-preview-opening" onclick="event.stopPropagation(); window.previewBooknestOpening('van-gogh-starry-night');">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span data-i18n="pref.btn_preview_opening">Coba Opening Ini</span>
                        </button>
                    </div>
                </div>

                <button type="button" class="opening-nav-arrow next" onclick="scrollOpeningTrack(1)" aria-label="Geser ke kanan">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        </div>

        <!-- KARTU 4: BAHASA APLIKASI -->
        <div class="pref-section-card" style="margin-top: 24px;">
            <div class="pref-section-header">
                <div class="pref-section-info">
                    <h2 data-i18n="pref.lang_title">Bahasa Aplikasi</h2>
                    <p data-i18n="pref.lang_desc">Pilih bahasa antarmuka peramban untuk navigasi menu, tombol aksi, dan kontrol sistem.</p>
                </div>
            </div>

            <div class="pref-card-grid">
                <!-- PILIHAN 1: BAHASA INDONESIA -->
                <div class="pref-option-card active" id="langCardId" onclick="selectPrefLang('id')" role="button" tabindex="0" aria-label="Pilih Bahasa Indonesia">
                    <div class="pref-option-top">
                        <div class="pref-option-icon-box" style="font-weight: 800; font-size: 13.5px; letter-spacing: 0.5px;">
                            ID
                        </div>
                        <span class="pref-radio-indicator"></span>
                    </div>

                    <h3 class="pref-option-title" data-i18n="pref.lang_id_title">Bahasa Indonesia</h3>
                    <p class="pref-option-desc" data-i18n="pref.lang_id_desc">Pengaturan bawaan standar untuk seluruh teks antarmuka navigasi dan kontrol BookNest.</p>
                    <span class="pref-option-badge">Sedang Digunakan</span>
                </div>

                <!-- PILIHAN 2: ENGLISH -->
                <div class="pref-option-card" id="langCardEn" onclick="selectPrefLang('en')" role="button" tabindex="0" aria-label="Pilih English">
                    <div class="pref-option-top">
                        <div class="pref-option-icon-box" style="font-weight: 800; font-size: 13.5px; letter-spacing: 0.5px;">
                            EN
                        </div>
                        <span class="pref-radio-indicator"></span>
                    </div>

                    <h3 class="pref-option-title" data-i18n="pref.lang_en_title">English (Beta Interface)</h3>
                    <p class="pref-option-desc" data-i18n="pref.lang_en_desc">Translates navigation menus, action buttons, and interface labels into English.</p>
                    <span class="pref-option-badge">Gunakan Pilihan Ini</span>
                </div>
            </div>

            <!-- KOTAK CATATAN KETERBATASAN BAHASA -->
            <div class="pref-notice-box">
                <div class="pref-notice-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                </div>
                <div>
                    <h4 class="pref-notice-title" data-i18n="pref.notice_title">Informasi Penerapan Bahasa Frontend</h4>
                    <p class="pref-notice-desc" data-i18n="pref.notice_desc">
                        Pilihan bahasa ini diterapkan pada elemen antarmuka frontend (menu sidebar, navbar, tombol aksi, dan kontrol sistem). Data dinamis server (seperti judul buku, sinopsis, nama kategori, dan catatan sirkulasi) tetap ditampilkan dalam bahasa asli tanpa memodifikasi backend perpustakaan.
                    </p>
                </div>
            </div>
        </div>

        <!-- BARIS AKSI PENYIMPANAN: HANYA RESET KE DEFAULT (AUTO-SAVE AKTIF) -->
        <div class="pref-action-bar">
            <button type="button" class="btn-pref-reset" onclick="resetAllPreferences()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                    <path d="M3 3v5h5"></path>
                </svg>
                <span data-i18n="pref.btn_reset">Reset ke Default</span>
            </button>
        </div>

    </div>
    <!-- END TAB 2 (PREFERENSI APLIKASI) -->

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Brand & Tagline -->
            <div class="footer-brand">
                <h3>BOOKNEST</h3>
                <p>Temukan. Baca. Berkembang.<br>Ruang pengetahuan untuk semua.</p>
            </div>

            <!-- Jelajahi -->
            <div>
                <h4 class="footer-heading">Jelajahi BOOKNEST</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('katalog.index') }}">Katalog buku</a></li>
                    <li><a href="{{ route('register') }}">Keanggotaan</a></li>
                    <li><a href="{{ route('tentang') }}">Tentang kami</a></li>
                    <li><a href="{{ route('panduan-peminjaman') }}">Panduan peminjaman</a></li>
                    <li><a href="{{ route('kebijakan-privasi') }}">Kebijakan privasi</a></li>
                    <li><a href="{{ route('syarat-layanan') }}">Syarat layanan</a></li>
                </ul>
            </div>

            <!-- Kunjungi -->
            <div>
                <h4 class="footer-heading">Kunjungi perpustakaan</h4>
                <div class="footer-contact-item">Jl. Merdeka No. 12, Bandung</div>
                <div class="footer-contact-item">Senin–Sabtu, 08.00–17.00 WIB</div>
                <div class="footer-contact-item">halo@booknest.id · (022) 420 1234</div>
            </div>
        </div>

        <div class="footer-copyright">
            &copy; 2026 BOOKNEST. Bersama menumbuhkan budaya membaca.
        </div>
    </footer>

</div>
@endsection

@section('scripts')
<script>
    // In-memory working draft preferences
    let workingPrefs = null;

    function getWorkingPrefs() {
        if (!workingPrefs) {
            workingPrefs = window.getBooknestPreferences ? window.getBooknestPreferences() : {
                theme: 'system',
                font: 'Inter',
                fontSize: 'normal',
                openingCategory: 'cewek',
                opening: 'coquette-blush',
                lang: 'id'
            };
        }
        return workingPrefs;
    }

    function selectPrefTheme(themeKey) {
        getWorkingPrefs().theme = themeKey;
        if (window.setBooknestTheme) {
            window.setBooknestTheme(themeKey, true, true);
        }
    }

    function selectPrefFont(fontName) {
        getWorkingPrefs().font = fontName;
        if (window.setBooknestFont) {
            window.setBooknestFont(fontName, true, true);
        }
        updateTypographyPreviewIndicator();
    }

    function selectPrefFontSize(sizeKey) {
        getWorkingPrefs().fontSize = sizeKey;
        if (window.setBooknestFontSize) {
            window.setBooknestFontSize(sizeKey, true, true);
        }
        updateTypographyPreviewIndicator();
    }

    function changeOpeningCategory(catKey) {
        getWorkingPrefs().openingCategory = catKey;
        if (window.setBooknestOpeningCategory) {
            window.setBooknestOpeningCategory(catKey, true, true);
        }
    }

    function selectPrefOpening(conceptKey) {
        getWorkingPrefs().opening = conceptKey;
        if (window.setBooknestOpening) {
            window.setBooknestOpening(conceptKey, true, true);
        }
    }

    function selectPrefLang(langKey) {
        getWorkingPrefs().lang = langKey;
        if (window.setBooknestLang) {
            window.setBooknestLang(langKey, true, true);
        }
        updateTypographyPreviewIndicator();
    }

    function scrollThemeTrack(direction) {
        const track = document.getElementById('themeHorizontalTrack');
        if (track) {
            const scrollAmount = 280;
            track.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
        }
    }

    function scrollOpeningTrack(direction) {
        const cat = document.getElementById('openingCategorySelect') ? document.getElementById('openingCategorySelect').value : 'cewek';
        const track = document.getElementById('openingTrack_' + cat);
        if (track) {
            const scrollAmount = 260;
            track.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
        }
    }

    function updateTypographyPreviewIndicator() {
        const ind = document.getElementById('prefPreviewFontIndicator');
        const p = getWorkingPrefs();
        if (ind) {
            const sizeLabel = (p.fontSize === 'small') ? 'Kecil' : (p.fontSize === 'large' ? 'Besar' : 'Normal');
            ind.textContent = `${p.font} • ${sizeLabel}`;
        }
    }

    function resetAllPreferences() {
        workingPrefs = Object.assign({}, window.BOOKNEST_PREF_DEFAULTS);
        if (window.saveBooknestPreferences) {
            window.saveBooknestPreferences(workingPrefs);
        }
        if (window.setBooknestTheme) window.setBooknestTheme(workingPrefs.theme, true, false);
        if (window.setBooknestFont) window.setBooknestFont(workingPrefs.font, true, false);
        if (window.setBooknestFontSize) window.setBooknestFontSize(workingPrefs.fontSize, true, false);
        if (window.setBooknestOpeningCategory) window.setBooknestOpeningCategory(workingPrefs.openingCategory, true, false);
        if (window.setBooknestOpening) window.setBooknestOpening(workingPrefs.opening, true, false);
        if (window.setBooknestLang) window.setBooknestLang(workingPrefs.lang, true, false);

        updateTypographyPreviewIndicator();

        const currentLang = document.documentElement.getAttribute('lang') || 'id';
        const dict = (window.BOOKNEST_I18N && window.BOOKNEST_I18N[currentLang]) ? window.BOOKNEST_I18N[currentLang] : window.BOOKNEST_I18N['id'];
        if (window.showPrefToast) {
            window.showPrefToast(dict['pref.reset_toast'] || 'Preferensi personalisasi dikembalikan ke setelan awal.');
        }
    }

    // Tab switching between User Profile and Application Preferences
    function switchSettingsTab(tabName) {
        const profileTab = document.getElementById('tabContentProfile');
        const prefTab = document.getElementById('tabContentPreferences');
        const btnProfile = document.getElementById('tabBtnProfile');
        const btnPref = document.getElementById('tabBtnPreferences');
        const titleEl = document.getElementById('prefPageTitle');
        const subtitleEl = document.getElementById('prefPageSubtitle');
        const breadcrumbEl = document.getElementById('prefBreadcrumbCurrent');
        const currentLang = document.documentElement.getAttribute('lang') || 'id';
        const dict = (window.BOOKNEST_I18N && window.BOOKNEST_I18N[currentLang]) ? window.BOOKNEST_I18N[currentLang] : window.BOOKNEST_I18N['id'];

        if (tabName === 'preferences') {
            if (profileTab) profileTab.style.display = 'none';
            if (prefTab) prefTab.style.display = 'block';
            if (btnProfile) {
                btnProfile.classList.remove('active');
                btnProfile.setAttribute('aria-selected', 'false');
            }
            if (btnPref) {
                btnPref.classList.add('active');
                btnPref.setAttribute('aria-selected', 'true');
            }
            if (titleEl) titleEl.textContent = dict['pref.title'];
            if (subtitleEl) subtitleEl.textContent = dict['pref.subtitle'];
            if (breadcrumbEl) breadcrumbEl.textContent = dict['pref.title'];
            document.title = dict['pref.title'] + ' - BOOKNEST';

            // Refresh cards state from stored preferences
            const prefs = window.getBooknestPreferences ? window.getBooknestPreferences() : getWorkingPrefs();
            workingPrefs = Object.assign({}, prefs);
            if (window.setBooknestTheme) window.setBooknestTheme(prefs.theme, false, false);
            if (window.setBooknestFont) window.setBooknestFont(prefs.font, false, false);
            if (window.setBooknestFontSize) window.setBooknestFontSize(prefs.fontSize, false, false);
            if (window.setBooknestOpeningCategory) window.setBooknestOpeningCategory(prefs.openingCategory || 'cewek', false, false);
            if (window.setBooknestOpening) window.setBooknestOpening(prefs.opening, false, false);
            if (window.setBooknestLang) window.setBooknestLang(prefs.lang, false, false);
            updateTypographyPreviewIndicator();

            try {
                const url = new URL(window.location);
                url.searchParams.set('tab', 'preferences');
                window.history.replaceState({}, '', url);
            } catch (e) {}
        } else {
            if (profileTab) profileTab.style.display = 'grid';
            if (prefTab) prefTab.style.display = 'none';
            if (btnProfile) {
                btnProfile.classList.add('active');
                btnProfile.setAttribute('aria-selected', 'true');
            }
            if (btnPref) {
                btnPref.classList.remove('active');
                btnPref.setAttribute('aria-selected', 'false');
            }
            if (titleEl) titleEl.textContent = dict['pref.tab_profile'];
            if (subtitleEl) subtitleEl.textContent = '{{ $user->role === "member" ? "Kelola data pribadi, keanggotaan, dan keamanan akunmu." : "Kelola data pribadi, informasi akun, dan keamanan." }}';
            if (breadcrumbEl) breadcrumbEl.textContent = dict['pref.tab_profile'];
            document.title = 'Profil Saya - BOOKNEST';

            try {
                const url = new URL(window.location);
                url.searchParams.delete('tab');
                window.history.replaceState({}, '', url);
            } catch (e) {}
        }
    }

    // Initialize preference tab cards state on load
    document.addEventListener('DOMContentLoaded', function () {
        try {
            const prefs = window.getBooknestPreferences ? window.getBooknestPreferences() : getWorkingPrefs();
            workingPrefs = Object.assign({}, prefs);
            if (window.setBooknestTheme) window.setBooknestTheme(prefs.theme, false, false);
            if (window.setBooknestFont) window.setBooknestFont(prefs.font, false, false);
            if (window.setBooknestFontSize) window.setBooknestFontSize(prefs.fontSize, false, false);
            if (window.setBooknestOpeningCategory) window.setBooknestOpeningCategory(prefs.openingCategory || 'cewek', false, false);
            if (window.setBooknestOpening) window.setBooknestOpening(prefs.opening, false, false);
            if (window.setBooknestLang) window.setBooknestLang(prefs.lang, false, false);
            updateTypographyPreviewIndicator();
        } catch (e) {}
    });

    // Submit otomatis saat user memilih file foto
    function submitFotoOtomatis(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validasi ukuran sisi klien (maksimal 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran foto melebihi batas maksimal 2 MB.');
                input.value = '';
                return;
            }

            // Preview instan di avatar box sebelum refresh
            const reader = new FileReader();
            reader.onload = function(e) {
                const box = document.getElementById('avatarPreviewBox');
                if (box) {
                    box.innerHTML = `<img src="${e.target.result}" alt="Preview" style="width:100%; height:100%; object-fit:cover;">`;
                }
            };
            reader.readAsDataURL(file);

            const btn = document.querySelector('.btn-ubah-foto');
            if (btn) {
                btn.style.opacity = '0.7';
                btn.style.pointerEvents = 'none';
                const textSpan = btn.querySelector('span');
                if (textSpan) textSpan.textContent = 'Mengunggah...';
            }

            // Submit form
            document.getElementById('formUploadFoto').submit();
        }
    }

    // Auto-save preferensi notifikasi via AJAX dengan feedback toast
    function autoSimpanNotifikasi() {
        const form = document.getElementById('notifPreferencesForm');
        const token = form.querySelector('input[name="_token"]').value;
        const jatuhTempo = document.getElementById('checkNotifJatuhTempo').checked ? 1 : 0;
        const koleksiBaru = document.getElementById('checkNotifKoleksiBaru').checked ? 1 : 0;
        const toast = document.getElementById('notifStatusToast');

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: JSON.stringify({
                _method: 'PUT',
                notif_jatuh_tempo: jatuhTempo,
                notif_koleksi_baru: koleksiBaru
            })
        })
        .then(response => response.json())
        .then(data => {
            if (toast) {
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 2200);
            }
        })
        .catch(err => {
            console.error('Gagal menyimpan preferensi:', err);
        });
    }

    function togglePassVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        btn.innerHTML = isPassword
            ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`
            : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
    }
</script>
@endsection

