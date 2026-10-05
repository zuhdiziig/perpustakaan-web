<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKNEST - Masuk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #f8fafc; color: #1e293b; min-height: 100vh; display: flex; flex-direction: column; }

        /* Header Bersih: Hanya Logo */
        .navbar-container { max-width: 1200px; margin: 24px auto 0; width: calc(100% - 48px); }
        .navbar {
            background: #ffffff;
            border-radius: 18px;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
        }
        .logo-area { display: flex; align-items: center; gap: 12px; text-decoration: none; }
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
        .logo-text { font-size: 19px; font-weight: 800; letter-spacing: 0.5px; color: #0f172a; }

        /* Layout Utama 2 Kolom */
        .main-wrapper {
            max-width: 1200px;
            margin: 40px auto;
            width: calc(100% - 48px);
            display: grid;
            grid-template-columns: 1fr 1.05fr;
            gap: 60px;
            align-items: start;
            flex: 1;
        }

        .hero-left { display: flex; flex-direction: column; }
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
        .hero-img-box img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .hero-title { font-size: 25px; font-weight: 800; color: #0f172a; margin-bottom: 12px; }
        .hero-desc { font-size: 14.5px; color: #64748b; line-height: 1.6; }

        .card-auth {
            background: #ffffff;
            border-radius: 22px;
            padding: 44px 48px;
            box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.03);
            border: 1px solid #e2e8f0;
        }
        .form-title { font-size: 32px; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
        .form-subtitle { font-size: 14px; color: #64748b; line-height: 1.5; margin-bottom: 30px; }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13.5px; font-weight: 600; color: #334155; margin-bottom: 8px; }
        .form-input {
            width: 100%;
            padding: 13px 18px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            color: #0f172a;
            outline: none;
            transition: all 0.2s;
        }
        .form-input:focus { border-color: #235c54; box-shadow: 0 0 0 4px rgba(35, 92, 84, 0.12); }

        .form-row-remember {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
            margin-bottom: 26px;
            font-size: 13.5px;
        }
        .checkbox-label { display: flex; align-items: center; gap: 8px; color: #475569; cursor: pointer; }
        .checkbox-label input[type="checkbox"] { width: 17px; height: 17px; accent-color: #235c54; }
        .forgot-link { color: #475569; text-decoration: none; font-weight: 600; }
        .forgot-link:hover { color: #235c54; text-decoration: underline; }

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
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(46, 98, 90, 0.25);
        }
        .btn-submit:hover { background-color: #234c46; }

        .switch-auth-text { text-align: center; font-size: 13.5px; color: #64748b; margin-bottom: 28px; }
        .switch-auth-text a { color: #235c54; font-weight: 700; text-decoration: none; }
        .switch-auth-text a:hover { text-decoration: underline; }

        .card-notice {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.5;
            padding-top: 18px;
            border-top: 1px dashed #e2e8f0;
        }

        .alert { padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; }
        .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fee2e2; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; }
        .footer { text-align: center; padding: 24px 20px 32px; font-size: 13px; color: #64748b; margin-top: auto; }

        @media (max-width: 900px) {
            .navbar { justify-content: center; }
            .main-wrapper { grid-template-columns: 1fr; gap: 32px; }
            .card-auth { padding: 32px 24px; }
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

    <!-- Konten Login -->
    <main class="main-wrapper">
        <section class="hero-left">
            <div class="hero-img-box">
                <img src="{{ asset('images/library-table.jpg') }}" 
                     alt="Meja Perpustakaan"
                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=1200&auto=format&fit=crop';">
            </div>
            <h2 class="hero-title">Temukan. Baca. Berkembang.</h2>
            <p class="hero-desc">Buku berikutnya bisa menjadi awal dari sesuatu yang besar. Kami siap menemanimu menemukannya.</p>
        </section>

        <section class="card-auth">
            <h1 class="form-title">Selamat datang kembali</h1>
            <p class="form-subtitle">Masuk untuk melanjutkan perjalanan membaca dan mengelola peminjamanmu.</p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="Masukkan alamat email" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan password" required>
                </div>

                <div class="form-row-remember">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat Saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
                </div>

                <button type="submit" class="btn-submit">Masuk</button>

                <div class="switch-auth-text">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
                </div>

                <p class="card-notice">
                    Akun anggota, petugas, dan admin menggunakan halaman masuk yang sama.
                </p>
            </form>
        </section>
    </main>

    <footer class="footer">
        © 2026 BOOKNEST · Perpustakaan umum untuk semua
    </footer>

</body>
</html>