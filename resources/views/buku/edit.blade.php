<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Buku</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; margin: 0; padding: 16px; }
        body { font-family: sans-serif; background: #f8fafc; }
        .card { background: white; width: 100%; max-width: 600px; margin: auto; padding: 20px 18px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; font-size: 13px; }
        input, select { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 14px; }
        .btn-submit { background: #d97706; color: white; padding: 12px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 10px; font-size: 14px; }
        .error { color: #dc2626; font-size: 12px; margin-top: 2px; display: block; }
    </style>
</head>
<body>

<div class="card">
    <h2 style="margin-top: 0;">Ubah Data Buku</h2>
    <form action="{{ route('buku.update', $buku->idBuku) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Kode Barcode</label>
            <input type="text" name="kodeBarcode" value="{{ old('kodeBarcode', $buku->barcode->kodeBarcode ?? '') }}" required>
            @error('kodeBarcode') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="idKategori" required>
                @foreach ($kategoris as $k)
                    <option value="{{ $k->idKategori }}" {{ old('idKategori', $buku->idKategori) == $k->idKategori ? 'selected' : '' }}>
                        {{ $k->namaKategori }}
                    </option>
                @endforeach
            </select>
            @error('idKategori') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}" required>
            @error('judul') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Penulis</label>
            <input type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}" required>
            @error('penulis') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Penerbit</label>
            <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}" required>
            @error('penerbit') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Tahun Terbit</label>
            <input type="number" name="tahunTerbit" value="{{ old('tahunTerbit', $buku->tahunTerbit) }}" required>
            @error('tahunTerbit') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" step="0.01" name="harga" value="{{ old('harga', $buku->harga) }}" required>
            @error('harga') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}" min="0" required>
            @error('stok') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Kondisi</label>
            <select name="kondisi">
                <option value="Baik" {{ old('kondisi', $buku->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                <option value="Rusak" {{ old('kondisi', $buku->kondisi) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="Hilang" {{ old('kondisi', $buku->kondisi) == 'Hilang' ? 'selected' : '' }}>Hilang</option>
            </select>
        </div>

        <button type="submit" class="btn-submit">Simpan Perubahan</button>
        <a href="{{ route('buku.index') }}" style="display:block; text-align:center; margin-top:10px; color:#64748b; text-decoration:none; font-size:13px;">Batal</a>
    </form>
</div>

</body>
</html>