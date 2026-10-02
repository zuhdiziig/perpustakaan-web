<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Buku</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .btn { padding: 8px 14px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; border: none; cursor: pointer; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-warning { background: #d97706; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 14px; }
        th { background: #f1f5f9; }
        .alert-success { background: #dcfce7; color: #166534; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="card">
    <div class="header">
        <h2>Daftar Data Buku (Admin)</h2>
        <div>
            <a href="{{ route('buku.create') }}" class="btn btn-primary">+ Tambah Buku Baru</a>
            <a href="{{ route('dashboard') }}" style="margin-left: 10px; font-size: 13px; color: #64748b;">Kembali ke Dashboard</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Barcode</th>
                <th>Judul Buku</th>
                <th>Kategori</th>
                <th>Penulis / Penerbit</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Kondisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bukus as $item)
                <tr>
                    <td><code>{{ $item->barcode->kodeBarcode ?? '-' }}</code></td>
                    <td><strong>{{ $item->judul }}</strong></td>
                    <td>{{ $item->kategori->namaKategori ?? '-' }}</td>
                    <td>{{ $item->penulis }} / {{ $item->penerbit }}</td>
                    <td>{{ $item->tahunTerbit }}</td>
                    <td>{{ $item->stok }}</td>
                    <td>{{ $item->kondisi }}</td>
                    <td>
                        <a href="{{ route('buku.edit', $item->idBuku) }}" class="btn btn-warning" style="padding: 4px 8px;">Ubah</a>
                        <form action="{{ route('buku.destroy', $item->idBuku) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 4px 8px;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Belum ada data buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        {{ $bukus->links() }}
    </div>
</div>

</body>
</html>