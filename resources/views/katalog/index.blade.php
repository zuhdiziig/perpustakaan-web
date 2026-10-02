<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Buku Perpustakaan</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; }
        .top-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .filter-box { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; display: flex; gap: 10px; }
        .filter-box input, .filter-box select { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px; flex: 1; }
        .filter-box button { padding: 8px 16px; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; }
        .book-card { background: white; border-radius: 8px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; flex-direction: column; justify-content: space-between; }
        .badge { display: inline-block; font-size: 11px; background: #e0e7ff; color: #3730a3; padding: 2px 8px; border-radius: 12px; margin-bottom: 8px; width: fit-content; }
        .stok-badge { font-size: 12px; font-weight: bold; }
        .stok-ada { color: #16a34a; }
        .stok-habis { color: #dc2626; }
        .btn-detail { margin-top: 12px; display: block; text-align: center; background: #0f172a; color: white; text-decoration: none; padding: 8px; border-radius: 4px; font-size: 13px; }
    </style>
</head>
<body>

<div class="container">
    <div class="top-nav">
        <h2>Katalog Buku Perpustakaan</h2>
        <div>
            <span>Halo, <strong>{{ auth()->user()->name }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline; margin-left: 10px;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer;">Keluar</button>
            </form>
        </div>
    </div>

    {{-- Filter & Pencarian --}}
    <form action="{{ route('katalog.index') }}" method="GET" class="filter-box">
        <input type="text" name="q" value="{{ $keyword }}" placeholder="Cari judul, penulis, penerbit...">
        <select name="kategori">
            <option value="">-- Semua Kategori --</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->idKategori }}" {{ $kategoriId == $kategori->idKategori ? 'selected' : '' }}>
                    {{ $kategori->namaKategori }}
                </option>
            @endforeach
        </select>
        <button type="submit">Cari</button>
        @if ($keyword || $kategoriId)
            <a href="{{ route('katalog.index') }}" style="align-self: center; font-size: 13px; color: #64748b; text-decoration: none;">Reset</a>
        @endif
    </form>

    {{-- Daftar Buku --}}
    <div class="book-grid">
        @forelse ($bukus as $item)
            <div class="book-card">
                <div>
                    <span class="badge">{{ $item->kategori->namaKategori ?? 'Umum' }}</span>
                    <h3 style="font-size: 16px; margin: 4px 0;">{{ $item->judul }}</h3>
                    <p style="font-size: 13px; color: #64748b; margin: 2px 0;">Oleh: {{ $item->penulis }}</p>
                    <p style="font-size: 13px; color: #64748b; margin: 2px 0;">Tahun: {{ $item->tahunTerbit }}</p>
                </div>
                <div>
                    <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 10px 0;">
                    <div class="stok-badge {{ $item->stok > 0 ? 'stok-ada' : 'stok-habis' }}">
                        {{ $item->stok > 0 ? "Tersedia: {$item->stok} eksemplar" : 'Stok Habis' }}
                    </div>
                    <a href="{{ route('katalog.show', $item->idBuku) }}" class="btn-detail">Lihat Detail Buku</a>
                </div>
            </div>
        @empty
            <p style="grid-column: 1 / -1; text-align: center; color: #64748b;">Buku tidak ditemukan.</p>
        @endforelse
    </div>

    <div style="margin-top: 20px;">
        {{ $bukus->links() }}
    </div>
</div>

</body>
</html>