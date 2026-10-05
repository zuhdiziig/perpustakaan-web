@extends('layouts.member')

@section('title', 'Katalog Buku Perpustakaan - BOOKNEST')

@section('styles')
<style>
    /* =========================================================
       KATALOG PAGE
    ========================================================== */

    .katalog-page {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 32px;
    }

    /* =========================================================
       HEADER
    ========================================================== */

    .katalog-header {
        padding: 40px 0 24px;
    }

    .katalog-title {
        margin: 0;

        color: #0f172a;

        font-size: 28px;
        font-weight: 800;
        line-height: 1.25;

        letter-spacing: -0.5px;
    }

    .katalog-subtitle {
        max-width: 720px;

        margin: 7px 0 0;

        color: #64748b;

        font-size: 13.5px;
        line-height: 1.7;
    }

    /* =========================================================
       FILTER & SEARCH
    ========================================================== */

    .filter-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 18px;

        margin-bottom: 32px;
        padding: 18px 20px;

        background: #ffffff;

        border: 1px solid #e2e8f0;
        border-radius: 16px;

        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
    }

    .search-katalog-form {
        display: flex;
        align-items: center;

        gap: 8px;

        flex: 1;
        max-width: 500px;
    }

    .search-input-wrapper {
        position: relative;

        flex: 1;
    }

    .search-input-icon {
        position: absolute;

        top: 50%;
        left: 13px;

        width: 17px;
        height: 17px;

        color: #94a3b8;

        transform: translateY(-50%);

        pointer-events: none;
    }

    .katalog-search-input {
        width: 100%;

        padding: 10px 13px 10px 39px;

        color: #0f172a;

        background: #ffffff;

        border: 1px solid #cbd5e1;
        border-radius: 9px;

        outline: none;

        font-size: 13px;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .katalog-search-input::placeholder {
        color: #94a3b8;
    }

    .katalog-search-input:focus {
        border-color: #2563eb;

        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    .btn-cari-katalog {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        padding: 10px 18px;

        color: #ffffff;

        background: #2563eb;

        border: none;
        border-radius: 9px;

        font-size: 13px;
        font-weight: 700;

        cursor: pointer;

        transition:
            background 0.2s ease,
            transform 0.2s ease;
    }

    .btn-cari-katalog:hover {
        background: #1d4ed8;

        transform: translateY(-1px);
    }

    /* =========================================================
       CATEGORY FILTER
    ========================================================== */

    .category-filter-list {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        gap: 7px;

        flex-wrap: wrap;
    }

    .chip-filter {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 7px 13px;

        color: #475569;

        background: #ffffff;

        border: 1px solid #cbd5e1;
        border-radius: 999px;

        font-size: 11.5px;
        font-weight: 600;

        white-space: nowrap;

        text-decoration: none;

        transition:
            color 0.2s ease,
            background 0.2s ease,
            border-color 0.2s ease;
    }

    .chip-filter:hover {
        color: #1d4ed8;

        background: #eff6ff;

        border-color: #bfdbfe;
    }

    .chip-filter.active {
        color: #ffffff;

        background: #2563eb;

        border-color: #2563eb;
    }

    /* =========================================================
       RESULT INFO
    ========================================================== */

    .katalog-result-info {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 16px;
    }

    .katalog-result-info span {
        color: #64748b;

        font-size: 12.5px;
    }

    /* =========================================================
       BOOK GRID
    ========================================================== */

    .katalog-grid {
        display: grid;

        grid-template-columns: repeat(4, minmax(0, 1fr));

        gap: 20px;

        margin-bottom: 40px;
    }

    .book-card-katalog {
        display: flex;
        flex-direction: column;

        min-width: 0;

        padding: 14px;

        color: inherit;

        background: #ffffff;

        border: 1px solid #e2e8f0;
        border-radius: 16px;

        text-decoration: none;

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            border-color 0.2s ease;
    }

    .book-card-katalog:hover {
        transform: translateY(-4px);

        border-color: #bfdbfe;

        box-shadow:
            0 14px 30px rgba(15, 23, 42, 0.08);
    }

    /* =========================================================
       BOOK COVER
    ========================================================== */

    .cover-box-katalog {
        width: 100%;
        height: 250px;

        margin-bottom: 13px;

        overflow: hidden;

        background: #f1f5f9;

        border-radius: 12px;
    }

    .cover-box-katalog img {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: cover;

        transition: transform 0.3s ease;
    }

    .book-card-katalog:hover .cover-box-katalog img {
        transform: scale(1.025);
    }

    .cover-placeholder-katalog {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 100%;
        height: 100%;

        color: #94a3b8;

        background:
            linear-gradient(
                135deg,
                #f8fafc 0%,
                #e2e8f0 100%
            );
    }

    .cover-placeholder-katalog svg {
        width: 42px;
        height: 42px;
    }

    /* =========================================================
       BOOK META
    ========================================================== */

    .book-meta-row {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 8px;

        min-height: 22px;
    }

    .book-location {
        overflow: hidden;

        color: #64748b;

        font-size: 10.5px;

        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .badge-katalog-tersedia,
    .badge-katalog-habis {
        display: inline-flex;
        align-items: center;

        flex-shrink: 0;

        padding: 4px 8px;

        border-radius: 999px;

        font-size: 10px;
        font-weight: 700;
    }

    .badge-katalog-tersedia {
        color: #15803d;

        background: #dcfce7;
    }

    .badge-katalog-habis {
        color: #b91c1c;

        background: #fee2e2;
    }

    /* =========================================================
       BOOK TITLE
    ========================================================== */

    .title-katalog-buku {
        display: -webkit-box;

        margin: 8px 0 4px;

        overflow: hidden;

        color: #0f172a;

        font-size: 15px;
        font-weight: 800;
        line-height: 1.4;

        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .author-katalog-buku {
        display: -webkit-box;

        margin: 0 0 15px;

        overflow: hidden;

        color: #64748b;

        font-size: 11.5px;
        line-height: 1.5;

        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    /* =========================================================
       DETAIL BUTTON
    ========================================================== */

    .btn-detail-buku {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 100%;

        margin-top: auto;

        padding: 9px;

        color: #2563eb;

        background: #eff6ff;

        border-radius: 9px;

        font-size: 11.5px;
        font-weight: 700;

        transition:
            color 0.2s ease,
            background 0.2s ease;
    }

    .book-card-katalog:hover .btn-detail-buku {
        color: #ffffff;

        background: #2563eb;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .katalog-empty {
        grid-column: 1 / -1;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        min-height: 280px;

        padding: 40px;

        text-align: center;

        background: #ffffff;

        border: 1px dashed #cbd5e1;
        border-radius: 16px;
    }

    .katalog-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 52px;
        height: 52px;

        margin-bottom: 14px;

        color: #64748b;

        background: #f1f5f9;

        border-radius: 50%;
    }

    .katalog-empty h3 {
        margin: 0 0 5px;

        color: #334155;

        font-size: 15px;
        font-weight: 700;
    }

    .katalog-empty p {
        max-width: 420px;

        margin: 0;

        color: #94a3b8;

        font-size: 12px;
        line-height: 1.6;
    }

    .katalog-empty-reset {
        display: inline-flex;

        margin-top: 16px;
        padding: 8px 14px;

        color: #2563eb;

        background: #eff6ff;

        border-radius: 8px;

        font-size: 12px;
        font-weight: 700;

        text-decoration: none;
    }

    /* =========================================================
       PAGINATION
    ========================================================== */

    .pagination-wrapper {
        display: flex;
        justify-content: center;

        margin-bottom: 60px;
    }

    /* =========================================================
       RESPONSIVE - TABLET
    ========================================================== */

    @media (max-width: 1100px) {

        .katalog-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .filter-wrapper {
            align-items: flex-start;

            flex-direction: column;
        }

        .search-katalog-form {
            width: 100%;
            max-width: none;
        }

        .category-filter-list {
            justify-content: flex-start;
        }
    }

    /* =========================================================
       RESPONSIVE - SMALL TABLET
    ========================================================== */

    @media (max-width: 800px) {

        .katalog-page {
            padding: 0 20px;
        }

        .katalog-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));

            gap: 16px;
        }

        .cover-box-katalog {
            height: 230px;
        }
    }

    /* =========================================================
       RESPONSIVE - MOBILE
    ========================================================== */

    @media (max-width: 560px) {

        .katalog-page {
            padding: 0 16px;
        }

        .katalog-header {
            padding: 28px 0 20px;
        }

        .katalog-title {
            font-size: 23px;
        }

        .katalog-subtitle {
            font-size: 12.5px;
        }

        .filter-wrapper {
            gap: 14px;

            padding: 14px;

            border-radius: 14px;
        }

        .search-katalog-form {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-cari-katalog {
            width: 100%;
        }

        .category-filter-list {
            width: 100%;

            overflow-x: auto;

            flex-wrap: nowrap;

            padding-bottom: 3px;

            scrollbar-width: none;
        }

        .category-filter-list::-webkit-scrollbar {
            display: none;
        }

        .chip-filter {
            flex-shrink: 0;
        }

        .katalog-result-info {
            gap: 10px;
        }

        .katalog-result-info span {
            font-size: 11px;
        }

        .katalog-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));

            gap: 12px;
        }

        .book-card-katalog {
            padding: 10px;

            border-radius: 13px;
        }

        .cover-box-katalog {
            height: 190px;

            margin-bottom: 10px;

            border-radius: 9px;
        }

        .book-meta-row {
            align-items: flex-start;

            flex-direction: column;

            gap: 5px;
        }

        .book-location {
            max-width: 100%;
        }

        .title-katalog-buku {
            font-size: 13px;
        }

        .author-katalog-buku {
            margin-bottom: 11px;

            font-size: 10.5px;
        }

        .btn-detail-buku {
            padding: 8px 5px;

            font-size: 10.5px;
        }

        .katalog-empty {
            padding: 30px 20px;
        }
    }

    /* =========================================================
       RESPONSIVE - VERY SMALL MOBILE
    ========================================================== */

    @media (max-width: 360px) {

        .katalog-grid {
            grid-template-columns: 1fr;
        }

        .cover-box-katalog {
            height: 280px;
        }
    }
</style>
@endsection


@section('content')

<div class="katalog-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="katalog-header">

        <h1 class="katalog-title">
            Katalog Koleksi Buku
        </h1>

        <p class="katalog-subtitle">
            Cari judul buku yang kamu butuhkan dan temukan
            informasi koleksi perpustakaan.
        </p>

    </div>


    {{-- =====================================================
         FILTER & SEARCH
    ====================================================== --}}

    <div class="filter-wrapper">

        <form
            action="{{ route('katalog.index') }}"
            method="GET"
            class="search-katalog-form"
        >

            @if(request('kategori'))

                <input
                    type="hidden"
                    name="kategori"
                    value="{{ request('kategori') }}"
                >

            @endif


            <div class="search-input-wrapper">

                <svg
                    class="search-input-icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <circle
                        cx="11"
                        cy="11"
                        r="6.5"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />

                    <path
                        d="M16 16L21 21"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />
                </svg>


                <input
                    type="text"
                    name="q"
                    class="katalog-search-input"
                    placeholder="Cari judul, penulis, atau penerbit..."
                    value="{{ $keyword }}"
                >

            </div>


            <button
                type="submit"
                class="btn-cari-katalog"
            >
                Cari
            </button>

        </form>


        {{-- =================================================
             CATEGORY FILTER
        ================================================== --}}

        <div class="category-filter-list">

            <a
                href="{{ route('katalog.index', request()->except(['kategori', 'page'])) }}"
                class="chip-filter {{ !$kategoriId ? 'active' : '' }}"
            >
                Semua Kategori
            </a>


            @foreach($kategoris as $kat)

                <a
                    href="{{ route('katalog.index', array_merge(request()->except('page'), [
                        'kategori' => $kat->idKategori
                    ])) }}"
                    class="chip-filter {{ $kategoriId == $kat->idKategori ? 'active' : '' }}"
                >
                    {{ $kat->namaKategori }}
                </a>

            @endforeach

        </div>

    </div>


    {{-- =====================================================
         RESULT INFO
    ================================================== --}}

    @if($bukus->total() > 0)

        <div class="katalog-result-info">

            <span>
                Menampilkan
                {{ $bukus->firstItem() }}
                –
                {{ $bukus->lastItem() }}
                dari
                {{ $bukus->total() }}
                koleksi buku
            </span>


            @if($keyword || $kategoriId)

                <span>
                    Filter aktif
                </span>

            @endif

        </div>

    @endif


    {{-- =====================================================
         BOOK GRID
    ================================================== --}}

    <div class="katalog-grid">

        @forelse($bukus as $buku)

            @php
                $stok = $buku->stok ?? 0;

                $penulis = $buku->penulis
                    ?? 'Penulis tidak diketahui';

                $kategoriNama =
                    optional($buku->kategori)->namaKategori
                    ?? 'Umum';

                /*
                 * Field cover/lokasiRak dibuat aman.
                 * Kalau kolom tersebut belum ada di database,
                 * nilainya otomatis null.
                 */
                $cover = $buku->cover ?? null;

                $lokasiRak = $buku->lokasiRak
                    ?? $buku->lokasi_rak
                    ?? null;
            @endphp


            <a
                href="{{ route('katalog.show', $buku->idBuku) }}"
                class="book-card-katalog"
            >

                {{-- =================================================
                     COVER
                ================================================== --}}

                <div class="cover-box-katalog">

                    @if($cover)

                        <img
                            src="{{ asset('storage/' . $cover) }}"
                            alt="Cover {{ $buku->judul }}"
                            loading="lazy"
                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500';"
                        >

                    @else

                        <div class="cover-placeholder-katalog">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                            >
                                <path
                                    d="M5 4.5C5 3.67 5.67 3 6.5 3H18C18.55 3 19 3.45 19 4V20C19 20.55 18.55 21 18 21H6.5C5.67 21 5 20.33 5 19.5V4.5Z"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                />

                                <path
                                    d="M8 7H16"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />

                                <path
                                    d="M8 10.5H16"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />

                                <path
                                    d="M8 14H13"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>

                        </div>

                    @endif

                </div>


                {{-- =================================================
                     META
                ================================================== --}}

                <div class="book-meta-row">

                    @if($stok > 0)

                        <span class="badge-katalog-tersedia">
                            Tersedia · {{ $stok }}
                        </span>

                    @else

                        <span class="badge-katalog-habis">
                            Sedang Dipinjam
                        </span>

                    @endif


                    @if($lokasiRak)

                        <span class="book-location">
                            📍 {{ $lokasiRak }}
                        </span>

                    @endif

                </div>


                {{-- =================================================
                     TITLE
                ================================================== --}}

                <h3 class="title-katalog-buku">
                    {{ $buku->judul }}
                </h3>


                {{-- =================================================
                     AUTHOR + CATEGORY
                ================================================== --}}

                <p class="author-katalog-buku">
                    {{ $penulis }}
                    ·
                    {{ $kategoriNama }}
                </p>


                {{-- =================================================
                     DETAIL
                ================================================== --}}

                <div class="btn-detail-buku">
                    Lihat Informasi Buku
                </div>

            </a>

        @empty

            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}

            <div class="katalog-empty">

                <div class="katalog-empty-icon">

                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        aria-hidden="true"
                    >
                        <circle
                            cx="11"
                            cy="11"
                            r="6.5"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />

                        <path
                            d="M16 16L21 21"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                    </svg>

                </div>


                <h3>
                    Buku tidak ditemukan
                </h3>


                <p>
                    Tidak ada koleksi buku yang sesuai dengan
                    pencarian atau kategori yang kamu pilih.
                </p>


                @if($keyword || $kategoriId)

                    <a
                        href="{{ route('katalog.index') }}"
                        class="katalog-empty-reset"
                    >
                        Reset Filter
                    </a>

                @endif

            </div>

        @endforelse

    </div>


    {{-- =====================================================
         PAGINATION
    ================================================== --}}

    @if($bukus->hasPages())

        <div class="pagination-wrapper">

            {{ $bukus->withQueryString()->links() }}

        </div>

    @endif

</div>

@endsection