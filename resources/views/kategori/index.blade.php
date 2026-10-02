<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori Buku</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .wrapper { max-width: 900px; margin: auto; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .form-group { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; font-size: 13px; }
        input, textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 4px; }
        .btn { padding: 8px 14px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; border: none; cursor: pointer; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-warning { background: #d97706; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 14px; }
        th { background: #f1f5f9; }
        .alert-success { background: #dcfce7; color: #166534; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .error { color: #dc2626; font-size: 12px; }
    </style>
</head>
<body>

<div class="wrapper">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2>Kelola Kategori Buku (Admin)</h2>
        <a href="{{ route('dashboard') }}" style="color: #64748b; font-size: 13px; text-decoration: none;">&larr; Kembali ke Dashboard</a>
    </div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    {{-- Form Tambah Kategori Baru --}}
    <div class="card">
        <h3 style="margin-top: 0;">Tambah Kategori</h3>
        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="namaKategori" value="{{ old('namaKategori') }}" placeholder="Contoh: Sains & Teknologi" required>
                @error('namaKategori') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="2" placeholder="Keterangan singkat kategori...">{{ old('deskripsi') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">+ Simpan Kategori</button>
        </form>
    </div>

    {{-- Tabel Kategori Terbaru --}}
    <div class="card">
        <h3 style="margin-top: 0;">Daftar Kategori</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Buku</th>
                    <th style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategoris as $k)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $k->namaKategori }}</strong></td>
                        <td>{{ $k->deskripsi ?? '-' }}</td>
                        <td>{{ $k->buku_count }} buku</td>
                        <td>
                            <a href="{{ route('kategori.edit', $k->idKategori) }}" class="btn btn-warning" style="padding: 4px 8px;">Ubah</a>
                            <form action="{{ route('kategori.destroy', $k->idKategori) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 4px 8px;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #64748b;">Belum ada kategori buku.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 15px;">
            {{ $kategoris->links() }}
        </div>
    </div>
</div>

</body>
</html>