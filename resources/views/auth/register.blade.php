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
    
    <style>
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
            background: linear-gradient(135deg, #28635a, #1d4d46);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 2px 6px rgba(35, 92, 84, 0.25);
        }

        .logo-text {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #0f172a;
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

        /* Hero Kiri */
        .hero-left {
            display: flex;
            flex-direction: column;
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

        .form-input:focus {
            border-color: #235c54;
            box-shadow: 0 0 0 4px rgba(35, 92, 84, 0.12);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #2e625a;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(46, 98, 90, 0.25);
        }

        .btn-submit:hover {
            background-color: #234c46;
            box-shadow: 0 6px 16px rgba(46, 98, 90, 0.35);
            transform: translateY(-1px);
        }

        .switch-auth-text {
            text-align: center;
            font-size: 13.5px;
            color: #64748b;
        }

        .switch-auth-text a {
            color: #235c54;
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