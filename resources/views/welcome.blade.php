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

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #ffffff;
            color: #0f172a;
            line-height: 1.6;
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* --- TOP NAVBAR --- */
        .navbar {
            padding: 24px 0 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
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
            gap: 12px;
        }

        .btn-nav-login {
            color: #2e625a;
            border: 1px solid #cbd5e1;
            padding: 8px 18px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-nav-login:hover {
            border-color: #2e625a;
            background: #f0fdf4;
        }

        .btn-nav-register {
            background-color: #4361ee;
            color: white;
            padding: 9px 20px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-nav-register:hover {
            background-color: #3651d4;
        }

        /* --- HERO SECTION --- */
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
            transition: all 0.2s;
        }

        .btn-search:hover {
            background: #234c46;
        }

        .hero-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .btn-green {
            background: #2e625a;
            color: white;
            padding: 11px 22px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-green:hover {
            background: #234c46;
        }

        .btn-blue {
            background: #4361ee;
            color: white;
            padding: 11px 22px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-blue:hover {
            background: #3651d4;
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
            background: #f1f5f9;
        }

        .hero-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
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

        .stat-item h3 {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
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
            transition: all 0.2s;
        }

        .cat-card:hover {
            border-color: #2e625a;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
            transform: translateY(-2px);
        }

        .cat-icon {
            color: #2e625a;
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
            background: #e6f7f4;
            border-radius: 16px;
            padding: 32px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 70px;
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
            padding: 11px 24px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .btn-cta:hover {
            background: #234c46;
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

        @media (max-width: 992px) {
            .hero {
                grid-template-columns: 1fr;
                gap: 36px;
            }
            .categories-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            .features-grid, .footer-grid {
                grid-template-columns: 1fr;
            }
            .cta-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 18px;
            }
        }

        @media (max-width: 600px) {
            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR ATAS -->
    <header class="container">
        <nav class="navbar">
            <a href="/" class="logo-area">
                <div class="logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <span class="logo-text">BOOKNEST</span>
            </a>

            <div class="nav-links">
                <a href="{{ route('login') }}" class="btn-nav-login">Masuk</a>
                <a href="{{ route('register') }}" class="btn-nav-register">Daftar Anggota</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <!-- 1. HERO SECTION -->
        <section class="hero">
            <div class="hero-left">
                <span class="hero-tag">Temukan. Baca. Berkembang.</span>
                <h1 class="hero-title">Temukan Buku Favoritmu</h1>
                <p class="hero-desc">
                    Setiap buku membuka dunia baru. Jelajahi ribuan koleksi, pinjam dengan mudah, dan tumbuh bersama perpustakaan umum BOOKNEST.
                </p>

                <!-- Search Input Form -->
                <div class="search-form-wrap">
                    <label class="search-label">Cari koleksi</label>
                    <div class="search-box">
                        <div class="search-input-wrap">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" placeholder="Cari judul, penulis, atau kategori...">
                        </div>
                        <button type="button" class="btn-search">Cari</button>
                    </div>
                </div>

                <div class="hero-buttons">
                    <a href="javascript:void(0)" class="btn-green">Jelajahi Katalog</a>
                    <a href="{{ route('register') }}" class="btn-blue">Daftar Anggota</a>
                </div>

                <p class="hero-note">Gratis menjadi anggota · Peminjaman hingga 14 hari</p>
            </div>

            <!-- Hero Image Right -->
            <div class="hero-img-box">
                <div class="hero-img-container">
                    <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=1200&auto=format&fit=crop" 
                         alt="Perpustakaan BOOKNEST">
                </div>
                <div class="hero-img-caption">
                    <strong>12.480+</strong> <span>buku menunggu untuk kamu temukan</span>
                </div>
            </div>
        </section>

        <!-- 2. STATS SECTION -->
        <section class="stats-grid">
            <div class="stat-item">
                <h3>12.480</h3>
                <p>Koleksi buku</p>
            </div>
            <div class="stat-item">
                <h3>3.240</h3>
                <p>Anggota aktif</p>
            </div>
            <div class="stat-item">
                <h3>7</h3>
                <p>Kategori pilihan</p>
            </div>
        </section>

        <!-- 3. KATEGORI BUKU -->
        <section>
            <h2 class="section-title">Apa yang ingin kamu baca?</h2>
            <div class="categories-grid">
                <a href="javascript:void(0)" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <span class="cat-name">Fiksi</span>
                </a>

                <a href="javascript:void(0)" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <span class="cat-name">Pendidikan</span>
                </a>

                <a href="javascript:void(0)" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="21" x2="21" y2="21"></line><line x1="6" y1="18" x2="6" y2="7"></line><line x1="10" y1="18" x2="10" y2="7"></line><line x1="14" y1="18" x2="14" y2="7"></line><line x1="18" y1="18" x2="18" y2="7"></line><polygon points="12 2 20 7 4 7"></polygon></svg>
                    </div>
                    <span class="cat-name">Sejarah</span>
                </a>

                <a href="javascript:void(0)" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    </div>
                    <span class="cat-name">Teknologi</span>
                </a>

                <a href="javascript:void(0)" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M12 22V12"></path></svg>
                    </div>
                    <span class="cat-name">Agama</span>
                </a>

                <a href="javascript:void(0)" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                    </div>
                    <span class="cat-name">Anak</span>
                </a>

                <a href="javascript:void(0)" class="cat-card">
                    <div class="cat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"></line><line x1="4" y1="12" x2="20" y2="12"></line><line x1="4" y1="18" x2="20" y2="18"></line></svg>
                    </div>
                    <span class="cat-name">Umum</span>
                </a>
            </div>
        </section>

        <!-- (BAGIAN BUKU POPULER DIKOSONGKAN SEMENTARA SESUAI PERMINTAAN) -->

        <!-- 4. KEUNGGULAN / FITUR -->
        <section class="features-section">
            <div class="features-header">
                <h2>Perpustakaan, kini lebih dekat</h2>
                <p>Semua kebutuhan membaca dalam satu tempat yang mudah digunakan.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
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

                <div class="feature-card">
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

                <div class="feature-card">
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
        <section class="cta-banner">
            <div class="cta-text">
                <h3>Mulai perjalanan membacamu hari ini</h3>
                <p>Daftar gratis dan jadilah bagian dari komunitas yang terus belajar. Satu akun, ribuan kesempatan untuk berkembang.</p>
            </div>
            <a href="{{ route('register') }}" class="btn-cta">Daftar Sekarang</a>
        </section>
    </main>

    <!-- 6. FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h4>BOOKNEST</h4>
                    <p>Temukan. Baca. Berkembang.<br>Ruang pengetahuan untuk semua.</p>
                </div>

                <div class="footer-col">
                    <h5>Jelajahi BOOKNEST</h5>
                    <ul>
                        <li><a href="javascript:void(0)">Katalog buku</a></li>
                        <li><a href="{{ route('register') }}">Keanggotaan</a></li>
                        <li><a href="javascript:void(0)">Tentang kami</a></li>
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

</body>
</html>