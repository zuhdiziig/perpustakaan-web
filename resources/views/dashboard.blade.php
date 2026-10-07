@extends('layouts.member')

@section('title', 'Beranda Anggota - BOOKNEST')

@section('styles')
<style>
    /* =========================================================
       DASHBOARD MEMBER
       ========================================================= */

    .member-dashboard {
        width: 100%;
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 28px 20px;
        box-sizing: border-box;
    }

    /* =========================================================
       HERO
       ========================================================= */

    .hero {
        padding: 42px 0 50px;
        display: grid;
        grid-template-columns: minmax(0, 1.02fr) minmax(0, 0.98fr);
        gap: 52px;
        align-items: center;
    }

    .hero-left {
        min-width: 0;
    }

    .hero-tag {
        display: inline-block;
        color: #2e625a;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 11px;
        letter-spacing: 0.1px;
    }

    .hero-title {
        margin: 0 0 15px;
        color: #0f172a;
        font-size: clamp(32px, 3.2vw, 42px);
        font-weight: 800;
        line-height: 1.13;
        letter-spacing: -1.2px;
    }

    .hero-desc {
        max-width: 530px;
        margin: 0 0 24px;
        color: #64748b;
        font-size: 14px;
        line-height: 1.7;
    }

    /* =========================================================
       SEARCH
       ========================================================= */

    .search-box-wrap {
        margin-bottom: 20px;
        max-width: 570px;
    }

    .search-label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 12.5px;
        font-weight: 700;
    }

    .search-bar {
        display: flex;
        align-items: stretch;
        gap: 9px;
    }

    .search-input-box {
        flex: 1;
        min-width: 0;
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 10px 13px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 11px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .search-input-box:focus-within {
        border-color: #2e625a;
        box-shadow: 0 0 0 3px rgba(46, 98, 90, 0.08);
    }

    .search-input-box svg {
        flex: 0 0 auto;
        color: #94a3b8;
    }

    .search-input-box input {
        width: 100%;
        min-width: 0;
        border: none;
        outline: none;
        background: transparent;
        color: #0f172a;
        font-size: 13px;
    }

    .search-input-box input::placeholder {
        color: #94a3b8;
    }

    .btn-search {
        flex: 0 0 auto;
        padding: 0 22px;
        border: none;
        border-radius: 11px;
        background: #2e625a;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s, transform 0.2s;
    }

    .btn-search:hover {
        background: #234c46;
        transform: translateY(-1px);
    }

    /* =========================================================
       HERO ACTIONS
       ========================================================= */

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .btn-katalog,
    .btn-qr {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 9px 17px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-katalog {
        background: #2e625a;
        color: #ffffff;
    }

    .btn-qr {
        background: #4361ee;
        color: #ffffff;
    }

    .btn-katalog:hover,
    .btn-qr:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.1);
    }

    .hero-note {
        margin: 0;
        color: #94a3b8;
        font-size: 11.5px;
        line-height: 1.5;
    }

    /* =========================================================
       HERO IMAGE
       ========================================================= */

    .hero-img-box {
        width: 100%;
        height: 350px;
        overflow: hidden;
        border-radius: 20px;
        background: #f1f5f9;
        box-shadow: 0 18px 38px -12px rgba(15, 23, 42, 0.14);
    }

    .hero-img-box img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
    }

    /* =========================================================
       STATS
       ========================================================= */

    .stats-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        padding: 0 0 52px;
    }

    .stat-card-member {
        min-width: 0;
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 15px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.025);
    }

    .stat-icon-wrap {
        width: 45px;
        height: 45px;
        flex: 0 0 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #e6f7f4;
        color: #2e625a;
    }

    .stat-number {
        color: #0f172a;
        font-size: 23px;
        font-weight: 800;
        line-height: 1.1;
    }

    .stat-label-text {
        margin-top: 4px;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.4;
    }

    /* =========================================================
       SECTION TITLE
       ========================================================= */

    .section-title {
        margin: 0 0 19px;
        color: #0f172a;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -0.4px;
    }

    /* =========================================================
       CATEGORY
       ========================================================= */

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 11px;
        margin-bottom: 54px;
    }

    .cat-btn {
        min-width: 0;
        min-height: 104px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 15px 8px;
        border: 1px solid #e2e8f0;
        border-radius: 13px;
        background: #ffffff;
        color: #334155;
        text-align: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .cat-btn:hover {
        border-color: #2e625a;
        background: #f8fffd;
        transform: translateY(-2px);
        box-shadow: 0 7px 16px rgba(15, 23, 42, 0.05);
    }

    .cat-btn svg {
        color: #2e625a;
    }

    .cat-btn span {
        max-width: 100%;
        overflow: hidden;
        color: #334155;
        font-size: 11.5px;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* =========================================================
       BOOK SECTION
       ========================================================= */

    .books-section {
        margin-bottom: 62px;
    }

    .books-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 21px;
    }

    .books-header-text {
        min-width: 0;
    }

    .books-header .section-title {
        margin-bottom: 4px !important;
    }

    .books-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 12.5px;
        line-height: 1.5;
    }

    .books-see-all {
        flex: 0 0 auto;
        color: #4361ee;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .books-see-all:hover {
        text-decoration: underline;
    }

    .books-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 17px;
    }

    .book-card {
        min-width: 0;
        display: flex;
        flex-direction: column;
        padding: 13px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        color: inherit;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .book-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.07);
        transform: translateY(-3px);
    }

    .book-cover {
        width: 100%;
        height: 218px;
        overflow: hidden;
        margin-bottom: 12px;
        border-radius: 10px;
        background: #f1f5f9;
    }

    .book-cover img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
    }

    .book-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 7px;
    }

    .badge-status,
    .badge-empty {
        display: inline-flex;
        align-items: center;
        min-height: 20px;
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 10.5px;
        font-weight: 700;
    }

    .badge-status {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-empty {
        background: #fee2e2;
        color: #b91c1c;
    }

    .book-rating {
        color: #f59e0b;
        font-size: 11px;
        font-weight: 700;
    }

    .card-title-book {
        margin: 0 0 4px;
        overflow: hidden;
        color: #0f172a;
        font-size: 14px;
        font-weight: 800;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .card-author-book {
        min-height: 34px;
        margin: 0 0 12px;
        overflow: hidden;
        color: #64748b;
        font-size: 11.5px;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .card-btn-action {
        display: block;
        margin-top: auto;
        padding: 8px;
        border-radius: 8px;
        background: #2e625a;
        color: #ffffff;
        text-align: center;
        font-size: 11.5px;
        font-weight: 700;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1100px) {
        .member-dashboard {
            padding-left: 24px;
            padding-right: 24px;
        }

        .hero {
            gap: 32px;
        }

        .categories-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .books-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .hero {
            grid-template-columns: 1fr;
            gap: 28px;
            padding-top: 32px;
        }

        .hero-desc {
            max-width: 650px;
        }

        .hero-img-box {
            height: 280px;
        }

        .stats-summary {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 11px;
        }

        .stat-card-member {
            padding: 16px;
            gap: 11px;
        }

        .stat-icon-wrap {
            width: 40px;
            height: 40px;
            flex-basis: 40px;
        }

        .stat-number {
            font-size: 20px;
        }

        .stat-label-text {
            font-size: 10.5px;
        }
    }

    @media (max-width: 700px) {
        .member-dashboard {
            padding: 0 17px 10px;
        }

        .hero {
            padding: 26px 0 34px;
        }

        .hero-title {
            font-size: 31px;
            letter-spacing: -0.8px;
        }

        .hero-desc {
            font-size: 13px;
        }

        .search-bar {
            flex-direction: column;
        }

        .search-input-box {
            width: 100%;
            box-sizing: border-box;
        }

        .btn-search {
            width: 100%;
            min-height: 42px;
        }

        .hero-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-katalog,
        .btn-qr {
            width: 100%;
            box-sizing: border-box;
        }

        .hero-img-box {
            height: 230px;
            border-radius: 16px;
        }

        .stats-summary {
            grid-template-columns: 1fr;
            padding-bottom: 40px;
        }

        .stat-card-member {
            padding: 17px;
        }

        .categories-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 9px;
            margin-bottom: 42px;
        }

        .cat-btn {
            min-height: 92px;
        }

        .books-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 8px;
        }

        .books-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 11px;
        }

        .book-card {
            padding: 10px;
            border-radius: 13px;
        }

        .book-cover {
            height: 190px;
        }

        .card-title-book {
            font-size: 13px;
        }

        .card-author-book {
            font-size: 10.5px;
        }

        .card-btn-action {
            font-size: 10.5px;
        }
    }

    @media (max-width: 430px) {
        .member-dashboard {
            padding-left: 14px;
            padding-right: 14px;
        }

        .hero-title {
            font-size: 28px;
        }

        .hero-img-box {
            height: 205px;
        }

        .books-grid {
            grid-template-columns: 1fr 1fr;
        }

        .book-cover {
            height: 170px;
        }
    }
</style>
@endsection

@section('content')
<div class="member-dashboard">

    {{-- =====================================================
         1. HERO
         ===================================================== --}}
    <section class="hero">
        <div class="hero-left">

            <span class="hero-tag">
                Temukan. Baca. Berkembang.
            </span>

            <h1 class="hero-title">
                Temukan Buku Favoritmu
            </h1>

            <p class="hero-desc">
                Selamat datang kembali, {{ auth()->user()->name }}.
                Jelajahi katalog koleksi buku perpustakaan BOOKNEST
                dan temukan bacaan yang sesuai dengan minatmu.
            </p>

            <div class="search-box-wrap">
                <label class="search-label">
                    Cari koleksi perpustakaan
                </label>

                <form
                    action="{{ route('katalog.index') }}"
                    method="GET"
                    class="search-bar"
                >
                    <div class="search-input-box">

                        <svg
                            width="17"
                            height="17"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle cx="11" cy="11" r="8"></circle>
                            <line
                                x1="21"
                                y1="21"
                                x2="16.65"
                                y2="16.65"
                            ></line>
                        </svg>

                        <input
                            type="text"
                            name="q"
                            placeholder="Cari judul buku, pengarang, atau kategori..."
                            value="{{ request('q') }}"
                        >
                    </div>

                    <button type="submit" class="btn-search">
                        Cari
                    </button>
                </form>
            </div>

            <div class="hero-actions">
                <a
                    href="{{ route('katalog.index') }}"
                    class="btn-katalog"
                >
                    Jelajahi Katalog
                </a>

                <a
                    href="{{ route('member.kartu-saya') }}"
                    class="btn-qr"
                >
                    Tampilkan QR Code Saya
                </a>
            </div>

            <p class="hero-note">
                Peminjaman hingga 30 hari · Pengembalian langsung ke meja sirkulasi
            </p>
        </div>

        <div class="hero-img-box">
            <img
                src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=1200&auto=format&fit=crop"
                alt="Perpustakaan BOOKNEST"
            >
        </div>
    </section>


    {{-- =====================================================
         2. STATISTIK MEMBER
         ===================================================== --}}

    @php
        $peminjamanUser = \App\Models\Peminjaman::where(
            'idUserMember',
            auth()->id()
        )->get();

        $sedangDipinjam = $peminjamanUser
            ->where('status', 'dipinjam')
            ->count();

        $totalSelesai = $peminjamanUser
            ->where('status', 'kembali')
            ->count();

        $totalBukuTersedia = \App\Models\Buku::count();
    @endphp

    <section class="stats-summary">

        <div class="stat-card-member">

            <div class="stat-icon-wrap">
                <svg
                    width="22"
                    height="22"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>

            <div>
                <div class="stat-number">
                    {{ $sedangDipinjam }}
                </div>

                <div class="stat-label-text">
                    Buku Sedang Kamu Pinjam
                </div>
            </div>

        </div>


        <div class="stat-card-member">

            <div class="stat-icon-wrap">
                <svg
                    width="22"
                    height="22"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>

            <div>
                <div class="stat-number">
                    {{ $totalSelesai }}
                </div>

                <div class="stat-label-text">
                    Buku Selesai Dibaca
                </div>
            </div>

        </div>


        <div class="stat-card-member">

            <div class="stat-icon-wrap">
                <svg
                    width="22"
                    height="22"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>

            <div>
                <div class="stat-number">
                    {{ $totalBukuTersedia }}
                </div>

                <div class="stat-label-text">
                    Total Koleksi Judul Buku
                </div>
            </div>

        </div>

    </section>


    {{-- =====================================================
         3. KATEGORI
         ===================================================== --}}

    @php
        $kategoriDB = \App\Models\Kategori::orderBy('namaKategori')
            ->take(7)
            ->get();
    @endphp

    <section>

        <h2 class="section-title">
            Apa yang ingin kamu baca?
        </h2>

        <div class="categories-grid">

            @forelse($kategoriDB as $kat)

                <a
                    href="{{ route('katalog.index', ['kategori' => $kat->idKategori]) }}"
                    class="cat-btn"
                >

                    <svg
                        width="22"
                        height="22"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>

                    <span>
                        {{ $kat->namaKategori }}
                    </span>

                </a>

            @empty

                @foreach([
                    'Fiksi',
                    'Pendidikan',
                    'Sejarah',
                    'Teknologi',
                    'Agama',
                    'Anak',
                    'Umum'
                ] as $defaultKat)

                    <a
                        href="{{ route('katalog.index') }}"
                        class="cat-btn"
                    >

                        <svg
                            width="22"
                            height="22"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>

                        <span>
                            {{ $defaultKat }}
                        </span>

                    </a>

                @endforeach

            @endforelse

        </div>

    </section>


    {{-- =====================================================
         4. BUKU REKOMENDASI
         ===================================================== --}}

    @php
        $bukuRekomendasi = \App\Models\Buku::with('kategori')
            ->latest('idBuku')
            ->take(4)
            ->get();
    @endphp

    <section class="books-section">

        <div class="books-header">

            <div class="books-header-text">

                <h2 class="section-title">
                    Buku populer minggu ini
                </h2>

                <p class="books-subtitle">
                    Cerita dan gagasan yang bisa kamu eksplorasi di BOOKNEST.
                </p>

            </div>

            <a
                href="{{ route('katalog.index') }}"
                class="books-see-all"
            >
                Lihat Semua Buku &rarr;
            </a>

        </div>


        <div class="books-grid">

            @forelse($bukuRekomendasi as $buku)

                <a
                    href="{{ route('katalog.show', $buku->idBuku) }}"
                    class="book-card"
                >

                    <div class="book-cover">

                        @if($buku->cover)

                            <img
                                src="{{ asset('storage/' . $buku->cover) }}"
                                alt="{{ $buku->judul }}"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400';"
                            >

                        @else

                            <img
                                src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400"
                                alt="{{ $buku->judul }}"
                            >

                        @endif

                    </div>


                    <div class="book-meta">

                        @if(($buku->stok ?? 0) > 0)

                            <span class="badge-status">
                                Tersedia
                            </span>

                        @else

                            <span class="badge-empty">
                                Tidak Tersedia
                            </span>

                        @endif

                        <span class="book-rating">
                            ★ 4.8
                        </span>

                    </div>


                    <h3 class="card-title-book">
                        {{ $buku->judul }}
                    </h3>


                    <p class="card-author-book">
                        {{ $buku->penulis ?? 'Penulis tidak tersedia' }}
                        ·
                        {{ $buku->kategori->namaKategori ?? 'Umum' }}
                    </p>


                    <span class="card-btn-action">
                        Lihat Detail Buku
                    </span>

                </a>

            @empty

                <div style="
                    grid-column: 1 / -1;
                    text-align: center;
                    padding: 48px 20px;
                    color: #94a3b8;
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    border-radius: 14px;
                ">
                    Belum ada koleksi buku di database.
                </div>

            @endforelse

        </div>

    </section>

</div>
@endsection