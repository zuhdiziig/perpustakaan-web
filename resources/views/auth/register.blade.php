<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKNEST - Daftar Member</title>
    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

                var savedLang = (prefs && prefs.lang) ? prefs.lang : (localStorage.getItem('booknest_lang') || 'id');
                if (savedLang) document.documentElement.setAttribute('lang', savedLang);
            } catch (e) {}
        })();
    </script>
    
    <style>
        :root {
            --theme-accent: #0f766e;
            --theme-accent-hover: #0d5f58;
            --theme-accent-shadow: rgba(15, 118, 110, 0.25);
            --logo-text: #0f172a;
        }

        [data-theme="dark"] {
            --theme-accent: #10b981;
            --theme-accent-hover: #059669;
            --theme-accent-shadow: rgba(16, 185, 129, 0.4);
            --logo-text: #f8fafc;
        }

        [data-theme="soft-blush"] {
            --theme-accent: #b85d77;
            --theme-accent-hover: #9c4c64;
            --theme-accent-shadow: rgba(184, 93, 119, 0.35);
            --logo-text: #2e2528;
        }

        [data-theme="van-gogh"] {
            --theme-accent: #245b8f;
            --theme-accent-hover: #1b456e;
            --theme-accent-shadow: rgba(233, 185, 73, 0.35);
            --logo-text: #f6f0df;
        }

        [data-theme="midnight-slate"] {
            --theme-accent: #2563eb;
            --theme-accent-hover: #1d4ed8;
            --theme-accent-shadow: rgba(37, 99, 235, 0.45);
            --logo-text: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Bersih: Murni Hanya Logo */
        .navbar-container {
            max-width: 1200px;
            margin: 24px auto 0;
            width: calc(100% - 48px);
        }

        .navbar {
            background: #ffffff;
            border-radius: 18px;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: var(--theme-accent, #0f766e) !important;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff !important;
            box-shadow: 0 2px 6px var(--theme-accent-shadow, rgba(15, 118, 110, 0.25)) !important;
            transition: background 0.25s ease, box-shadow 0.25s ease, color 0.25s ease !important;
        }

        .logo-icon svg {
            color: #ffffff !important;
            stroke: currentColor !important;
        }

        .logo-text {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: var(--logo-text, #0f172a) !important;
            transition: color 0.25s ease !important;
        }

        /* Layout Utama 2 Kolom */
        .main-wrapper {
            max-width: 1200px;
            margin: 40px auto;
            width: calc(100% - 48px);
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            gap: 60px;
            align-items: start;
            flex: 1;
        }

        @keyframes authFadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes navFadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .navbar {
            animation: navFadeInDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        /* Hero Kiri */
        .hero-left {
            display: flex;
            flex-direction: column;
            animation: authFadeInUp 0.85s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .hero-img-box {
            width: 100%;
            height: 380px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
            margin-bottom: 24px;
            background-color: #e2e8f0;
            border: 1px solid #e2e8f0;
        }

        .hero-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .hero-title {
            font-size: 25px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
            letter-spacing: -0.3px;
        }

        .hero-desc {
            font-size: 14.5px;
            color: #64748b;
            line-height: 1.6;
        }

        /* Form Kanan */
        .card-auth {
            background: #ffffff;
            border-radius: 22px;
            padding: 40px 48px;
            box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.03);
            border: 1px solid #e2e8f0;
            animation: authFadeInUp 0.85s cubic-bezier(0.16, 1, 0.3, 1) 0.08s both;
        }

        @media (prefers-reduced-motion: reduce) {
            .navbar,
            .hero-left,
            .card-auth {
                animation-duration: 0.45s !important;
                transform: none !important;
            }
        }

        .form-title {
            font-size: 30px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .form-subtitle {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            color: #0f172a;
            outline: none;
            transition: all 0.2s;
            background: #ffffff;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: var(--theme-accent, #0f766e);
            box-shadow: 0 0 0 4px var(--theme-accent-shadow, rgba(15, 118, 110, 0.15));
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: var(--theme-accent, #0f766e);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px var(--theme-accent-shadow, rgba(15, 118, 110, 0.25));
        }

        .btn-submit:hover {
            background-color: var(--theme-accent-hover, #0d5f58);
            box-shadow: 0 6px 16px var(--theme-accent-shadow, rgba(15, 118, 110, 0.35));
            transform: translateY(-1px);
        }

        .switch-auth-text {
            text-align: center;
            font-size: 13.5px;
            color: #64748b;
        }

        .switch-auth-text a {
            color: var(--theme-accent, #0f766e);
            font-weight: 700;
            text-decoration: none;
        }

        .switch-auth-text a:hover {
            text-decoration: underline;
        }

        .alert-danger {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13.5px;
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fee2e2;
        }

        .footer {
            text-align: center;
            padding: 24px 20px 32px;
            font-size: 13px;
            color: #64748b;
            margin-top: auto;
        }

        html, body { overflow-x: hidden; width: 100%; max-width: 100%; }

        @media (max-width: 900px) {
            .navbar {
                justify-content: center;
            }
            .main-wrapper {
                grid-template-columns: 1fr;
                gap: 32px;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .card-auth {
                padding: 30px 24px;
            }
        }

        @media (max-width: 640px) {
            .navbar-container { margin: 16px auto 0; width: calc(100% - 24px); }
            .navbar { padding: 12px 18px; border-radius: 14px; }
            .main-wrapper { margin: 20px auto; width: calc(100% - 24px); gap: 24px; }
            .hero-img-box { height: 220px; border-radius: 16px; margin-bottom: 16px; }
            .hero-title { font-size: 20px; }
            .hero-desc { font-size: 13.5px; }
            .card-auth { padding: 24px 16px; border-radius: 18px; }
            .form-title { font-size: 24px; }
            .form-subtitle { font-size: 13px; margin-bottom: 20px; }
        }

        /* --- DARK THEME OVERRIDES --- */
        [data-theme="dark"] body {
            background-color: #0b1120 !important;
            color: #cbd5e1 !important;
        }

        [data-theme="dark"] .navbar {
            background: #131d31 !important;
            border-color: #23314d !important;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.3) !important;
        }

        [data-theme="dark"] .logo-text,
        [data-theme="dark"] .hero-title,
        [data-theme="dark"] .form-title {
            color: #f8fafc !important;
        }

        [data-theme="dark"] .hero-desc,
        [data-theme="dark"] .form-subtitle,
        [data-theme="dark"] .switch-auth-text,
        [data-theme="dark"] .footer {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .card-auth {
            background: #131d31 !important;
            border-color: #23314d !important;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.4) !important;
        }

        [data-theme="dark"] .hero-img-box {
            background-color: #1e293b !important;
            border-color: #23314d !important;
        }

        [data-theme="dark"] .form-label {
            color: #cbd5e1 !important;
        }

        [data-theme="dark"] .form-input,
        [data-theme="dark"] .form-select {
            background: #0b1220 !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }

        [data-theme="dark"] .form-input:focus,
        [data-theme="dark"] .form-select:focus {
            border-color: #14b8a6 !important;
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.16) !important;
        }

        [data-theme="dark"] .switch-auth-text a {
            color: #2dd4bf !important;
        }

        [data-theme="dark"] .alert-danger {
            background: rgba(239, 68, 68, 0.14) !important;
            border-color: rgba(239, 68, 68, 0.3) !important;
            color: #fca5a5 !important;
        }

        /* Van Gogh Theme Overrides */
        [data-theme="van-gogh"] body {
            background-color: #0b1726 !important;
            background-image: 
                radial-gradient(circle at 82% 14%, rgba(245, 158, 11, 0.22) 0%, rgba(233, 185, 73, 0.08) 22%, transparent 45%),
                radial-gradient(ellipse at 15% 25%, rgba(36, 91, 143, 0.35) 0%, rgba(16, 42, 67, 0.22) 35%, transparent 58%),
                radial-gradient(circle at 50% 85%, rgba(27, 69, 110, 0.28) 0%, transparent 50%),
                linear-gradient(140deg, #071320 0%, #0d1e33 40%, #153255 75%, #091522 100%) !important;
            background-attachment: fixed !important;
            color: #e2e8f0 !important;
        }

        [data-theme="van-gogh"] .navbar {
            background: linear-gradient(90deg, #0e1e33 0%, #10253f 50%, #0d1a2c 100%) !important;
            border: 1px solid #1e3a5f !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
        }

        [data-theme="van-gogh"] .logo-icon {
            background: radial-gradient(circle at 35% 35%, #2d6ca8 0%, #15375d 100%) !important;
            color: #fcd34d !important;
            border: 1px solid rgba(245, 158, 11, 0.55) !important;
            box-shadow: 0 0 16px rgba(245, 158, 11, 0.4) !important;
        }
        [data-theme="van-gogh"] .logo-icon svg {
            color: #fcd34d !important;
            stroke: currentColor !important;
        }
        [data-theme="van-gogh"] .logo-text,
        [data-theme="van-gogh"] .hero-title,
        [data-theme="van-gogh"] .form-title {
            color: #f6f0df !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        [data-theme="van-gogh"] .hero-desc,
        [data-theme="van-gogh"] .form-subtitle,
        [data-theme="van-gogh"] .switch-auth-text {
            color: #cbd5e1 !important;
        }

        [data-theme="van-gogh"] .card-auth {
            background: linear-gradient(180deg, #14243b 0%, #101c2e 100%) !important;
            border: 1px solid #1e3a5f !important;
            border-top: 2.5px solid #f59e0b !important;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.6), 0 0 20px rgba(36, 91, 143, 0.25) !important;
        }

        [data-theme="van-gogh"] .hero-img-box {
            background-color: #10233b !important;
            border: 2px solid rgba(245, 158, 11, 0.45) !important;
            box-shadow: 0 8px 30px rgba(11, 23, 38, 0.8), 0 0 24px rgba(245, 158, 11, 0.3) !important;
        }

        [data-theme="van-gogh"] .form-label {
            color: #f6f0df !important;
            font-weight: 600 !important;
        }

        [data-theme="van-gogh"] .form-input,
        [data-theme="van-gogh"] .form-select {
            background: #0d1b2a !important;
            border: 1px solid #22446a !important;
            color: #f6f0df !important;
        }

        [data-theme="van-gogh"] .form-input:focus,
        [data-theme="van-gogh"] .form-select:focus {
            border-color: #f59e0b !important;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25) !important;
        }

        [data-theme="van-gogh"] .switch-auth-text a {
            color: #fcd34d !important;
            font-weight: 700 !important;
        }

        [data-theme="van-gogh"] .btn-submit {
            background: #245b8f !important;
            border: 1px solid #3a7ebd !important;
            color: #ffffff !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 14px rgba(36, 91, 143, 0.4) !important;
        }

        [data-theme="van-gogh"] .btn-submit:hover {
            background: #1b456e !important;
            border-color: #f59e0b !important;
            color: #f6f0df !important;
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.35) !important;
        }

        [data-theme="van-gogh"] .alert-danger {
            background: rgba(239, 68, 68, 0.18) !important;
            border: 1px solid rgba(239, 68, 68, 0.45) !important;
            color: #fca5a5 !important;
        }
    </style>
</head>
<body>

    <!-- Header Bersih: Murni Hanya Logo Identitas -->
    <div class="navbar-container">
        <header class="navbar">
            <div class="logo-area">
                <div class="logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <span class="logo-text">BOOKNEST</span>
            </div>
        </header>
    </div>

    <!-- Main Konten Register -->
    <main class="main-wrapper">
        <section class="hero-left">
            <div class="hero-img-box">
                <img src="{{ asset('images/library-table.jpg') }}" 
                     alt="Meja Perpustakaan"
                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=1200&auto=format&fit=crop';">
            </div>
            <h2 class="hero-title">Bergabunglah Bersama Kami.</h2>
            <p class="hero-desc">
                Dapatkan kartu anggota digital dengan QR Code instan untuk meminjam ribuan buku fisik di perpustakaan.
            </p>
        </section>

        <!-- FORM REGISTRASI KEANGGOTAAN -->
        <section class="card-auth">
            <h1 class="form-title">Daftar Akun Anggota</h1>
            <p class="form-subtitle">Lengkapi formulir di bawah ini untuk membuat akun baru.</p>

            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                @php
                    $redirectTarget = old('redirect', request('redirect', session('url.intended')));
                @endphp
                @if($redirectTarget)
                    <input type="hidden" name="redirect" value="{{ $redirectTarget }}">
                @endif

                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input" placeholder="Masukkan nama lengkap" required autofocus>
                </div>

                <div class="form-grid">
                    <div>
                        <label class="form-label" for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="nama@email.com" required>
                    </div>
                    <div>
                        <label class="form-label" for="noTelepon">Nomor Telepon</label>
                        <input type="text" id="noTelepon" name="noTelepon" value="{{ old('noTelepon') }}" class="form-input" placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="alamat">Alamat Domisili</label>
                    <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}" class="form-input" placeholder="Jl. Contoh No. 123" required>
                </div>

                <div class="form-grid">
                    <div>
                        <label class="form-label" for="password">Password</label>
                        <div style="position: relative;">
                            <input type="password" id="password" name="password" class="form-input" style="padding-right: 42px;" placeholder="Minimal 6 karakter" required>
                            <button type="button" onclick="togglePasswordEye('password', this)" title="Lihat/Sembunyikan password" aria-label="Lihat password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 4px; display: flex; align-items: center; justify-content: center;">
                                <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                        <div style="position: relative;">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" style="padding-right: 42px;" placeholder="Ulangi password" required>
                            <button type="button" onclick="togglePasswordEye('password_confirmation', this)" title="Lihat/Sembunyikan konfirmasi password" aria-label="Lihat konfirmasi password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 4px; display: flex; align-items: center; justify-content: center;">
                                <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Daftar Sekarang</button>

                <div class="switch-auth-text">
                    Sudah punya akun? <a href="{{ route('login', $redirectTarget ? ['redirect' => $redirectTarget] : []) }}">Masuk di sini</a>
                </div>
            </form>
        </section>
    </main>

    <footer class="footer">
        © 2026 BOOKNEST · Perpustakaan umum untuk semua
    </footer>

    <script>
        function togglePasswordEye(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');
            if (input.type === 'password') {
                input.type = 'text';
                if (eyeOpen) eyeOpen.style.display = 'none';
                if (eyeClosed) eyeClosed.style.display = 'block';
            } else {
                input.type = 'password';
                if (eyeOpen) eyeOpen.style.display = 'block';
                if (eyeClosed) eyeClosed.style.display = 'none';
            }
        }
    </script>
</body>
</html>