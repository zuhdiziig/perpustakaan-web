<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Hasil Pemeriksaan Buku</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; margin: 0; padding: 15px; }
        body { font-family: sans-serif; background: #f8fafc; }
        .card { background: white; width: 100%; max-width: 520px; margin: auto; padding: 20px 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px; }
        select, textarea { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 14px; }
        .btn-submit { background: #0284c7; color: white; width: 100%; padding: 12px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 14px; }
        .info-box { background: #f1f5f9; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; line-height: 1.6; }
    </style>
</head>
<body>

<div class="card">
    <h3 style="margin-top: 0;">Pemeriksaan Kondisi Fisik Buku</h3>

    <div class="info-box">
        <div><strong>Judul:</strong> {{ $buku->judul }}</div>
        <div><strong>Barcode:</strong> <code>{{ $buku->barcode->kodeBarcode ?? '-' }}</code></div>
        <div><strong>Harga Buku:</strong> Rp {{ number_format($buku->harga, 0, ',', '.') }}</div>
    </div>

    <form action="{{ route('kondisi.update', $buku->idBuku) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Input Kondisi Fisik Buku</label>
            <select name="kondisi" required>
                <option value="Baik" {{ $buku->kondisi == 'Baik' ? 'selected' : '' }}>Baik (Bebas Denda / Normal)</option>
                <option value="Rusak" {{ $buku->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak (Denda Biaya Penggantian 100%)</option>
                <option value="Hilang" {{ $buku->kondisi == 'Hilang' ? 'selected' : '' }}>Hilang (Denda Biaya Penggantian 100%)</option>
            </select>
        </div>

        <button type="submit" class="btn-submit">Simpan & Hitung Biaya</button>
        <a href="{{ route('kondisi.index') }}" style="display: block; text-align: center; margin-top: 12px; color: #64748b; font-size: 13px; text-decoration: none;">Batal</a>
    </form>
</div>

</body>
</html>