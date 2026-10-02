<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Buku - {{ $buku->judul }}</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; }
        .card { background: white; max-width: 600px; margin: auto; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .label { color: #64748b; font-weight: bold; }
        .btn-back { display: inline-block; margin-top: 20px; text-decoration: none; color: #2563eb; font-size: 14px; }
    </style>
</head>
<body>

<div class="card">
    <span style="font-size: 12px; background: #e0e7ff; color: #3730a3; padding: 3px 8px; border-radius: 4px;">
        {{ $buku->kategori->namaKategori ?? 'Tanpa Kategori' }}
    </span>
    <h2 style="margin: 10px 0 20px 0;">{{ $buku->judul }}</h2>

    <div class="row">
        <span class="label">Penulis</span>
        <span>{{ $buku->penulis }}</span>
    </div>
    <div class="row">
        <span class="label">Penerbit</span>
        <span>{{ $buku->penerbit }}</span>
    </div>
    <div class="row">
        <span class="label">Tahun Terbit</span>
        <span>{{ $buku->tahunTerbit }}</span>
    </div>
    <div class="row">
        <span class="label">Kondisi Fisik</span>
        <span>{{ $buku->kondisi }}</span>
    </div>
    <div class="row">
        <span class="label">Stok Tersedia</span>
        <span style="font-weight: bold; color: {{ $buku->stok > 0 ? '#16a34a' : '#dc2626' }};">
            {{ $buku->stok }} Buku
        </span>
    </div>
    <div class="row">
        <span class="label">Kode Barcode Buku</span>
        <code>{{ $buku->barcode->kodeBarcode ?? 'Belum ada Barcode' }}</code>
    </div>

    <a href="{{ route('katalog.index') }}" class="btn-back">&larr; Kembali ke Katalog</a>
</div>

</body>
</html>