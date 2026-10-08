<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Kategori</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; margin: 0; padding: 15px; }
        body { font-family: sans-serif; background: #f8fafc; }
        .card { background: white; width: 100%; max-width: 500px; margin: auto; padding: 20px 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; font-size: 13px; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 14px; }
        .btn-submit { background: #d97706; color: white; padding: 10px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 14px; }
        .error { color: #dc2626; font-size: 12px; }
    </style>
</head>
<body>

<div class="card">
    <h3 style="margin-top: 0;">Ubah Kategori</h3>
    <form action="{{ route('kategori.update', $kategori->idKategori) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="namaKategori" value="{{ old('namaKategori', $kategori->namaKategori) }}" required>
            @error('namaKategori') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
        </div>

        <button type="submit" class="btn-submit">Simpan Perubahan</button>
        <a href="{{ route('kategori.index') }}" style="margin-left: 10px; color: #64748b; text-decoration: none; font-size: 13px;">Batal</a>
    </form>
</div>

</body>
</html>