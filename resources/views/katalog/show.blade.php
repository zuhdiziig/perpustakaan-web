<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $buku->judul }} - Katalog BOOKNEST</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-primary: #0f766e;
            --brand-primary-dark: #115e59;
            --brand-primary-light: #ccfbf1;
            --brand-accent: #14b8a6;
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-body);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font-family: inherit;
            cursor: pointer;
            border: none;
            background: none;
        }

        /* --- NAVBAR --- */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
        }

        .topbar-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text-heading);
        }

        .topbar-brand-icon {
            width: 36px;
            height: 36px;
            background: #0f766e;
            color: #ffffff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .topbar-nav {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-nav-link {
            font-size: 13.5px;
            font-weight: 600;
            color: #64748b;
            padding: 6px 14px;
            border-radius: 8px;
            transition: all 0.15s;
        }

        .topbar-nav-link:hover {
            color: #0f766e;
        }

        .topbar-nav-link.active {
            background: #ccfbf1;
            color: #0f766e;
            font-weight: 700;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-auth-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            background: #2563eb;
            color: #ffffff;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
        }

        .btn-auth-register {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            background: #0f766e;
            color: #ffffff;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
        }

        .btn-user-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .user-initials {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: #0f766e;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
        }

        /* --- CONTAINER --- */
        .detail-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 32px 24px 70px;
        }

        .breadcrumb {
            font-size: 12.5px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .breadcrumb a {
            color: #0f766e;
            font-weight: 700;
        }

        .breadcrumb-separator {
            color: #cbd5e1;
        }

        /* --- DETAIL CARD --- */
        .book-detail-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 36px;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 40px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
            margin-bottom: 48px;
        }

        .cover-box {
            width: 100%;
            height: 380px;
            background: #f1f5f9;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cover-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-col {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .badge-cat-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .badge-cat {
            background: #ccfbf1;
            color: #0f766e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .badge-status.tersedia {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-status.habis {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .badge-status.tersedia .badge-dot {
            background: #16a34a;
        }

        .badge-status.habis .badge-dot {
            background: #dc2626;
        }

        .book-title-main {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-heading);
            line-height: 1.25;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .book-author-main {
            font-size: 15px;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        .book-author-main strong {
            color: var(--text-heading);
        }

        /* Specification Grid */
        .specs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            padding: 20px 0;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 24px;
        }

        .spec-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .spec-label {
            font-size: 11.5px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .spec-val {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        /* Borrow Guide Box */
        .guide-box {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .guide-content h4 {
            font-size: 14px;
            font-weight: 800;
            color: #065f46;
            margin-bottom: 4px;
        }

        .guide-content p {
            font-size: 12.5px;
            color: #047857;
            line-height: 1.5;
        }

        .btn-action-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #0f766e;
            color: #ffffff;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            white-space: nowrap;
            transition: background 0.15s;
        }

        .btn-action-primary:hover {
            background: #115e59;
        }

        /* --- RELATED BOOKS SECTION --- */
        .section-related {
            margin-top: 40px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 18px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .related-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }

        .related-card:hover {
            transform: translateY(-3px);
        }

        .related-cover {
            height: 160px;
            background: #f1f5f9;
            overflow: hidden;
        }

        .related-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .related-body {
            padding: 14px;
        }

        .related-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
        }

        .related-author {
            font-size: 12px;
            color: #64748b;
        }

        /* --- FOOTER --- */
        .footer {
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            padding: 48px 24px 32px;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1240px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.3fr 1fr 1.2fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand h3 {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 8px;
        }

        .footer-brand p {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .footer-heading {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 14px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .footer-links a {
            font-size: 13px;
            color: var(--text-muted);
            transition: color 0.15s;
        }

        .footer-links a:hover {
            color: #0f766e;
        }

        .footer-contact-item {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 6px;
            line-height: 1.5;
        }

        .footer-copyright {
            max-width: 1240px;
            margin: 0 auto;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
            font-size: 12.5px;
            color: #94a3b8;
            text-align: left;
        }

        @media (max-width: 860px) {
            .book-detail-card {
                grid-template-columns: 1fr;
            }
            .cover-box {
                height: 300px;
            }
            .related-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .footer-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-container">
            <a href="{{ route('home') }}" class="topbar-brand">
                <div class="topbar-brand-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <span>BOOKNEST</span>
            </a>

            <nav class="topbar-nav">
                <a href="{{ route('home') }}" class="topbar-nav-link">Beranda</a>
                <a href="{{ route('katalog.index') }}" class="topbar-nav-link active">Katalog</a>
                <a href="{{ route('dashboard') }}" class="topbar-nav-link">Dasbor</a>
                <a href="{{ route('home') }}#tentang" class="topbar-nav-link">Tentang</a>
            </nav>

            <div class="topbar-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-user-badge">
                        <div class="user-initials">{{ auth()->user()->inisial ?? 'US' }}</div>
                        <span>Dasbor</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-auth-login">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-auth-register">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- MAIN DETAIL CONTENT -->
    <main class="detail-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('home') }}">BOOKNEST</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('katalog.index') }}">Katalog Buku</a>
            <span class="breadcrumb-separator">/</span>
            <span>{{ $buku->judul }}</span>
        </div>

        <!-- Book Detail Card -->
        <div class="book-detail-card">
            <!-- Cover -->
            <div class="cover-box">
                @if (!empty($buku->cover))
                    <img src="{{ $buku->cover }}" alt="{{ $buku->judul }}" class="cover-img" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=800&auto=format&fit=crop';">
                @else
                    <div style="background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%); width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; padding: 24px; text-align: center;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <h3 style="font-size: 18px; font-weight: 800; margin-top: 14px;">{{ $buku->judul }}</h3>
                        <p style="font-size: 13px; color: #ccfbf1; margin-top: 6px;">{{ $buku->penulis }}</p>
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="info-col">
                <div>
                    <!-- Badges -->
                    <div class="badge-cat-row">
                        <span class="badge-cat">{{ $buku->kategori?->namaKategori ?? 'Umum' }}</span>
                        @if ($buku->stok > 0)
                            <span class="badge-status tersedia">
                                <span class="badge-dot"></span>
                                Tersedia ({{ $buku->stok }} eksemplar)
                            </span>
                        @else
                            <span class="badge-status habis">
                                <span class="badge-dot"></span>
                                Stok Habis / Sedang Dipinjam
                            </span>
                        @endif
                    </div>

                    <h1 class="book-title-main">{{ $buku->judul }}</h1>
                    <p class="book-author-main">Karya <strong>{{ $buku->penulis }}</strong> · Diterbitkan oleh {{ $buku->penerbit }} ({{ $buku->tahunTerbit }})</p>

                    <!-- Specifications Grid -->
                    <div class="specs-grid">
                        <div class="spec-item">
                            <span class="spec-label">Lokasi Rak Fisik</span>
                            <span class="spec-val" style="color: #0f766e;">{{ $buku->rak ?? 'Rak F-12' }} (Perpustakaan Pusat)</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Jumlah Halaman</span>
                            <span class="spec-val">{{ $buku->jumlahHalaman ?? 320 }} Halaman</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Kode Barcode / Eksemplar</span>
                            <span class="spec-val">{{ $buku->barcode?->kodeBarcode ?? sprintf('BK-%05d', $buku->idBuku) }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Kondisi Buku</span>
                            <span class="spec-val">{{ $buku->kondisi ?? 'Baik' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Borrowing Guide Box -->
                <div class="guide-box">
                    <div class="guide-content">
                        <h4>Panduan Peminjaman Fisik</h4>
                        @guest
                            <p>Buku ini dapat dipinjam langsung di perpustakaan. Silakan masuk atau daftar anggota untuk mendapatkan kartu anggota digital.</p>
                        @else
                            @if (auth()->user()->role === 'member')
                                <p>Buku tersedia di <strong>{{ $buku->rak ?? 'Rak F-12' }}</strong>. Tunjukkan kartu QR anggota Anda kepada petugas di meja sirkulasi.</p>
                            @else
                                <p>Anda login sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong>. Buka form sirkulasi untuk mencatat peminjaman.</p>
                            @endif
                        @endguest
                    </div>

                    @guest
                        <a href="{{ route('login') }}" class="btn-action-primary">
                            Masuk untuk Pinjam
                        </a>
                    @else
                        @if (auth()->user()->role === 'member')
                            <a href="{{ route('member.kartu-saya') }}" class="btn-action-primary">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                </svg>
                                Kartu / QR Saya
                            </a>
                        @else
                            <a href="{{ route('peminjaman.create') }}" class="btn-action-primary">
                                Form Sirkulasi
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
        </div>

        <!-- Related Books -->
        @if (isset($bukuTerkait) && $bukuTerkait->isNotEmpty())
            <div class="section-related">
                <h3 class="section-title">Buku Terkait dalam Kategori {{ $buku->kategori?->namaKategori }}</h3>
                <div class="related-grid">
                    @foreach ($bukuTerkait as $terkait)
                        <a href="{{ route('katalog.show', $terkait->idBuku) }}" class="related-card">
                            <div class="related-cover">
                                @if (!empty($terkait->cover))
                                    <img src="{{ $terkait->cover }}" alt="{{ $terkait->judul }}" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=800&auto=format&fit=crop';">
                                @else
                                    <div style="background: #e2e8f0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="related-body">
                                <h4 class="related-title">{{ $terkait->judul }}</h4>
                                <p class="related-author">{{ $terkait->penulis }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-brand">
                <h3>BOOKNEST</h3>
                <p>Temukan. Baca. Berkembang.<br>Ruang pengetahuan untuk semua.</p>
            </div>

            <div>
                <h4 class="footer-heading">Jelajahi BOOKNEST</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('katalog.index') }}">Katalog buku</a></li>
                    <li><a href="{{ route('register') }}">Keanggotaan</a></li>
                    <li><a href="{{ route('home') }}#tentang">Tentang kami</a></li>
                    <li><a href="{{ route('katalog.index') }}">Panduan peminjaman</a></li>
                    <li><a href="{{ route('home') }}">Kebijakan privasi · Syarat layanan</a></li>
                </ul>
            </div>

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

</body>
</html>