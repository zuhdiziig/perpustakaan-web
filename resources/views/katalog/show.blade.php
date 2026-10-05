@extends('layouts.member')

@section('title', 'Detail Buku - ' . $buku->judul)

@section('content')
<style>
    .detail-page {
        padding: 36px 0 60px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 28px;
        font-size: 14px;
        color: #64748b;
    }

    .breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color .2s ease;
    }

    .breadcrumb a:hover {
        color: #111827;
    }

    .breadcrumb-current {
        color: #111827;
        font-weight: 600;
    }

    .detail-card {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 48px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 36px;
        box-shadow: 0 8px 30px rgba(15, 23, 42, .05);
    }

    .book-cover-wrapper {
        width: 100%;
    }

    .book-cover {
        width: 100%;
        aspect-ratio: 3 / 4;
        object-fit: cover;
        display: block;
        border-radius: 18px;
        background: #f1f5f9;
    }

    .book-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .book-category {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 7px 13px;
        margin-bottom: 16px;
        border-radius: 999px;
        background: #eef2ff;
        color: #4338ca;
        font-size: 13px;
        font-weight: 700;
    }

    .book-title {
        margin: 0 0 10px;
        color: #0f172a;
        font-size: 36px;
        line-height: 1.2;
        letter-spacing: -.8px;
    }

    .book-author {
        margin: 0 0 28px;
        color: #64748b;
        font-size: 16px;
    }

    .book-author strong {
        color: #334155;
    }

    .availability {
        display: flex;
        align-items: center;
        gap: 10px;
        width: fit-content;
        padding: 10px 14px;
        margin-bottom: 28px;
        border-radius: 10px;
        background: {{ ($buku->stok ?? 0) > 0 ? '#f0fdf4' : '#fef2f2' }};
        color: {{ ($buku->stok ?? 0) > 0 ? '#15803d' : '#b91c1c' }};
        font-size: 14px;
        font-weight: 700;
    }

    .availability-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
    }

    .book-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 28px;
    }

    .meta-item {
        padding: 18px 0;
    }

    .meta-item:nth-child(odd) {
        padding-right: 20px;
    }

    .meta-item:nth-child(even) {
        padding-left: 20px;
        border-left: 1px solid #e2e8f0;
    }

    .meta-label {
        display: block;
        margin-bottom: 6px;
        color: #94a3b8;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .meta-value {
        color: #1e293b;
        font-size: 15px;
        font-weight: 600;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: fit-content;
        padding: 12px 18px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        color: #334155;
        background: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        transition: all .2s ease;
    }

    .back-button:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        color: #0f172a;
    }

    @media (max-width: 900px) {
        .detail-card {
            grid-template-columns: 240px 1fr;
            gap: 32px;
            padding: 28px;
        }

        .book-title {
            font-size: 30px;
        }
    }

    @media (max-width: 700px) {
        .detail-page {
            padding: 24px 0 40px;
        }

        .breadcrumb {
            margin-bottom: 20px;
        }

        .detail-card {
            grid-template-columns: 1fr;
            gap: 28px;
            padding: 20px;
            border-radius: 18px;
        }

        .book-cover-wrapper {
            max-width: 240px;
            margin: 0 auto;
        }

        .book-info {
            display: block;
        }

        .book-title {
            font-size: 26px;
        }

        .book-meta {
            grid-template-columns: 1fr;
        }

        .meta-item:nth-child(odd) {
            padding-right: 0;
        }

        .meta-item:nth-child(even) {
            padding-left: 0;
            border-left: 0;
            border-top: 1px solid #e2e8f0;
        }

        .back-button {
            width: 100%;
        }
    }
</style>

<div class="detail-page">

    <div class="breadcrumb">
        <a href="{{ route('katalog.index') }}">Katalog</a>
        <span>›</span>
        <span class="breadcrumb-current">Detail Buku</span>
    </div>

    <div class="detail-card">

        <div class="book-cover-wrapper">
            <img
                class="book-cover"
                src="{{ $buku->cover ?? 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=700&q=80' }}"
                alt="Cover {{ $buku->judul }}"
            >
        </div>

        <div class="book-info">

            <span class="book-category">
                {{ $buku->kategori->namaKategori ?? $buku->kategori->nama_kategori ?? 'Tanpa Kategori' }}
            </span>

            <h1 class="book-title">
                {{ $buku->judul }}
            </h1>

            <p class="book-author">
                Oleh <strong>{{ $buku->penulis ?? $buku->pengarang ?? 'Penulis tidak diketahui' }}</strong>
            </p>

            <div class="availability">
                <span class="availability-dot"></span>

                @if(($buku->stok ?? 0) > 0)
                    {{ $buku->stok }} buku tersedia
                @else
                    Buku sedang tidak tersedia
                @endif
            </div>

            <div class="book-meta">

                <div class="meta-item">
                    <span class="meta-label">Penerbit</span>
                    <span class="meta-value">
                        {{ $buku->penerbit ?? '-' }}
                    </span>
                </div>

                <div class="meta-item">
                    <span class="meta-label">Tahun Terbit</span>
                    <span class="meta-value">
                        {{ $buku->tahunTerbit ?? $buku->tahun_terbit ?? '-' }}
                    </span>
                </div>

                <div class="meta-item">
                    <span class="meta-label">Kondisi</span>
                    <span class="meta-value">
                        {{ $buku->kondisi ?? 'Baik' }}
                    </span>
                </div>

                <div class="meta-item">
                    <span class="meta-label">Ketersediaan</span>
                    <span class="meta-value">
                        {{ $buku->stok ?? 0 }} eksemplar
                    </span>
                </div>

            </div>

            <a href="{{ route('katalog.index') }}" class="back-button">
                ← Kembali ke Katalog
            </a>

        </div>

    </div>
</div>
@endsection