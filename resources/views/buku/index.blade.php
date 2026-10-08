<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Buku</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; }
        body { font-family: sans-serif; background: #f8fafc; padding: 20px 14px; margin: 0; }
        .card { background: white; padding: 20px 16px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 100%; box-sizing: border-box; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
        .btn { padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; border: none; cursor: pointer; display: inline-flex; align-items: center; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-warning { background: #d97706; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; margin-top: 10px; }
        table { width: 100%; min-width: 700px; border-collapse: collapse; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 13.5px; }
        th { background: #f1f5f9; }
        .alert-success { background: #dcfce7; color: #166534; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="card">
    <div class="header">
        <h2 style="margin: 0; font-size: 20px;">Daftar Data Buku (Admin)</h2>
        <div>
            <a href="{{ route('buku.create') }}" class="btn btn-primary">+ Tambah Buku Baru</a>
            <a href="{{ route('dashboard') }}" style="margin-left: 10px; font-size: 13px; color: #64748b;">Kembali ke Dashboard</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
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
                        <a href="{{ route('buku.cetak-qr', $item->idBuku) }}" target="_blank" class="btn" style="background: #0f172a; color: white; padding: 4px 8px;">Cetak QR</a>
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
    </div>

    <div style="margin-top: 15px;">
        {{ $bukus->links() }}
    </div>
</div>

</body>
</html>