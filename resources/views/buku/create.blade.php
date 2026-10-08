<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Buku</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; }
        body { font-family: sans-serif; background: #f8fafc; padding: 20px 14px; margin: 0; }
        .card { background: white; max-width: 600px; width: 100%; margin: auto; padding: 22px 18px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; font-size: 13px; }
        input, select { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; }
        .btn-submit { background: #2563eb; color: white; padding: 11px 16px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 10px; font-size: 14px; }
        .error { color: #dc2626; font-size: 12px; margin-top: 2px; display: block; }
    </style>
</head>
<body>

<div class="card">
    <h2 style="margin-top: 0;">Tambah Buku Baru</h2>
    <form action="{{ route('buku.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Kode Barcode</label>
            <input type="text" name="kodeBarcode" value="{{ old('kodeBarcode') }}" placeholder="Contoh: BK-NV-001" required>
            @error('kodeBarcode') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="idKategori" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoris as $k)
                    <option value="{{ $k->idKategori }}" {{ old('idKategori') == $k->idKategori ? 'selected' : '' }}>{{ $k->namaKategori }}</option>
                @endforeach
            </select>
            @error('idKategori') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" name="judul" value="{{ old('judul') }}" required>
            @error('judul') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Penulis</label>
            <input type="text" name="penulis" value="{{ old('penulis') }}" required>
            @error('penulis') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Penerbit</label>
            <input type="text" name="penerbit" value="{{ old('penerbit') }}" required>
            @error('penerbit') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Tahun Terbit</label>
            <input type="number" name="tahunTerbit" value="{{ old('tahunTerbit', 2026) }}" required>
            @error('tahunTerbit') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" step="0.01" name="harga" value="{{ old('harga', 0) }}" required>
            @error('harga') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" value="{{ old('stok', 1) }}" min="0" required>
            @error('stok') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Kondisi</label>
            <select name="kondisi">
                <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                <option value="Rusak" {{ old('kondisi') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="Hilang" {{ old('kondisi') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
            </select>
        </div>

        <button type="submit" class="btn-submit">Simpan Buku</button>
        <a href="{{ route('buku.index') }}" style="display:block; text-align:center; margin-top:10px; color:#64748b; text-decoration:none; font-size:13px;">Batal</a>
    </form>
</div>

</body>
</html>