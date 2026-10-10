<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - BOOKNEST Perpustakaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f7f9fa;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 24px;
        }

        .main-wrapper {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        /* Top Header Navbar */
        .top-navbar {
            background: #ffffff;
            border: 1px solid #e5e9ee;
            border-radius: 16px;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
            margin-bottom: 40px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #1e293b;
        }

        .brand-icon-box {
            width: 36px;
            height: 36px;
            background-color: #345e59;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            flex-shrink: 0;
        }

        .brand-icon-box svg {
            width: 20px;
            height: 20px;
        }

        .brand-name {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #1e293b;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .nav-pill-group {
            border: 1px solid #e2e8f0;
            border-radius: 9999px;
            padding: 4px 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
        }

        .nav-pill-item {
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            color: #64748b;
            padding: 6px 14px;
            border-radius: 9999px;
            transition: all 0.2s ease;
        }

        .nav-pill-item:hover {
            color: #1e293b;
        }

        .nav-pill-item.active {
            background-color: #d1fae5;
            color: #047857;
            font-weight: 600;
        }

        .btn-nav-masuk {
            background-color: #4361ee;
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 600;
            padding: 9px 24px;
            border-radius: 9px;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.1s ease;
            display: inline-block;
        }

        .btn-nav-masuk:hover {
            background-color: #3651d4;
        }

        .btn-nav-daftar {
            background-color: #345e59;
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 600;
            padding: 9px 24px;
            border-radius: 9px;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.1s ease;
            display: inline-block;
        }

        .btn-nav-daftar:hover {
            background-color: #2a4c48;
        }

        /* Content Layout */
        .content-grid {
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            gap: 48px;
            align-items: flex-start;
        }

        /* Left Hero Section */
        .hero-section {
            display: flex;
            flex-direction: column;
        }

        .hero-image-wrapper {
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            background-color: #e2e8f0;
        }

        .hero-image {
            width: 100%;
            height: auto;
            aspect-ratio: 4 / 2.9;
            object-fit: cover;
            display: block;
        }

        .hero-heading {
            font-size: 26px;
            font-weight: 800;
            color: #1e293b;
            margin-top: 26px;
            margin-bottom: 10px;
            letter-spacing: -0.02em;
        }

        .hero-description {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            max-width: 480px;
        }

        /* Right Form Card */
        .auth-card {
            background: #ffffff;
            border: 1px solid #eef2f6;
            border-radius: 24px;
            padding: 42px 38px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
        }

        .card-title {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .card-subtitle {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .alert-box {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13.5px;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            color: #1e293b;
            background-color: #ffffff;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            border-color: #345e59;
            box-shadow: 0 0 0 3px rgba(52, 94, 89, 0.12);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .field-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }

        .btn-submit-masuk {
            width: 100%;
            background-color: #345e59;
            color: #ffffff;
            border: none;
            padding: 13px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            margin-top: 6px;
        }

        .btn-submit-masuk:hover {
            background-color: #2a4c48;
        }

        .btn-submit-masuk:active {
            transform: scale(0.99);
        }

        .card-switch-link {
            text-align: center;
            margin-top: 22px;
            font-size: 13.5px;
            color: #64748b;
        }

        .card-switch-link a {
            color: #334155;
            font-weight: 600;
            text-decoration: none;
            margin-left: 4px;
        }

        .card-switch-link a:hover {
            text-decoration: underline;
        }

        .card-bottom-notice {
            margin-top: 24px;
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }

        /* Footer */
        .page-footer {
            text-align: center;
            padding-top: 48px;
            padding-bottom: 12px;
            font-size: 13px;
            color: #64748b;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
        }

        /* Responsive Breakpoint */
        @media (max-width: 900px) {
            .content-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .top-navbar {
                flex-direction: column;
                gap: 16px;
                align-items: stretch;
            }

            .nav-right {
                flex-direction: column;
                width: 100%;
            }

            .nav-pill-group {
                width: 100%;
                justify-content: center;
            }

            .btn-nav-masuk, .btn-nav-daftar {
                text-align: center;
                width: 100%;
            }

            .auth-card {
                padding: 30px 24px;
            }
        }

        @media (max-width: 640px) {
            body {
                padding: 12px;
            }

            .top-navbar {
                padding: 12px 14px;
                border-radius: 14px;
                margin-bottom: 20px;
            }

            .auth-card {
                padding: 22px 16px;
                border-radius: 18px;
            }

            .card-title {
                font-size: 24px;
            }

            .banner-hero-img-box {
                height: 200px;
            }
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <!-- Top Navbar Card -->
    <header class="top-navbar">
        <a href="{{ url('/') }}" class="brand-logo">
            <div class="brand-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <span class="brand-name">BOOKNEST</span>
        </a>

        <div class="nav-right">
            <nav class="nav-pill-group">
                <a href="{{ url('/') }}" class="nav-pill-item">Beranda</a>
                <a href="{{ route('katalog.index') }}" class="nav-pill-item">Katalog</a>
                <a href="{{ route('dashboard') }}" class="nav-pill-item">Dasbor</a>
                <a href="{{ route('tentang') }}" class="nav-pill-item">Tentang</a>
            </nav>

            <a href="{{ route('login') }}" class="btn-nav-masuk">Masuk</a>
            <a href="{{ route('register') }}" class="btn-nav-daftar">Daftar</a>
        </div>
    </header>

    <!-- Main Grid: Left Hero & Right Form -->
    <main class="content-grid">
        <!-- Left Section: Image and Slogan -->
        <section class="hero-section">
            <div class="hero-image-wrapper">
                <img src="{{ asset('images/library-table.jpg') }}" alt="Suasana Meja Membaca Perpustakaan" class="hero-image">
            </div>
            <h2 class="hero-heading">Temukan. Baca. Berkembang.</h2>
            <p class="hero-description">Buku berikutnya bisa menjadi awal dari sesuatu yang besar. Kami siap menemanimu menemukannya.</p>
        </section>

        <!-- Right Section: Forgot Password Card -->
        <section class="auth-card">
            <h1 class="card-title">Lupa Password</h1>
            <p class="card-subtitle">Masukkan email dan nomor telepon terdaftar untuk mereset kata sandi akun Anda.</p>

            @if ($errors->any())
                <div class="alert-box alert-error">
                    <svg style="width: 18px; height: 18px; flex-shrink: 0;" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Periksa data verifikasi yang Anda masukkan di bawah.</span>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email Terdaftar</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="Masukkan alamat email akun" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                    >
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="noTelepon">Nomor Telepon Akun (Verifikasi)</label>
                    <input 
                        type="text" 
                        id="noTelepon" 
                        name="noTelepon" 
                        class="form-control" 
                        placeholder="Contoh: 08123456789" 
                        value="{{ old('noTelepon') }}" 
                        required
                    >
                    @error('noTelepon')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password Baru</label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            style="padding-right: 42px;"
                            placeholder="Minimal 6 karakter" 
                            required
                        >
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
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-control" 
                            style="padding-right: 42px;"
                            placeholder="Ulangi password baru" 
                            required
                        >
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

                <button type="submit" class="btn-submit-masuk">Perbarui Kata Sandi</button>
            </form>

            <div class="card-switch-link">
                Ingat kata sandi Anda? <a href="{{ route('login') }}">Masuk</a>
            </div>

            <p class="card-bottom-notice">
                Setelah kata sandi diperbarui, Anda dapat langsung masuk dengan kata sandi baru.
            </p>
        </section>
    </main>
</div>

<!-- Page Footer -->
<footer class="page-footer">
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
