<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peminjaman</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; margin: 0; padding: 15px; }
        body { font-family: sans-serif; background: #f8fafc; }
        .card { background: white; padding: 20px 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 100%; max-width: 1050px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 1.25rem; }
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; margin-top: 10px; }
        table { width: 100%; min-width: 650px; border-collapse: collapse; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 13px; }
        th { background: #f1f5f9; }
        .btn { padding: 8px 14px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; display: inline-block; white-space: nowrap; }
        .btn-primary { background: #2563eb; color: white; }
    </style>
</head>
<body>

<div class="card">
    <div class="header">
        <h2>Daftar Transaksi Peminjaman</h2>
        <div>
            @if (auth()->check() && auth()->user()->role === 'petugas')
                <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">+ Pinjam Buku Baru</a>
            @endif
            <a href="{{ route('dashboard') }}" style="margin-left: 10px; font-size: 13px; color: #64748b; text-decoration: none;">&larr; Dashboard</a>
        </div>
    </div>

    <div class="table-responsive">
        <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Member</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Total Buku</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjamans as $item)
                <tr>
                    <td>#TRX-{{ str_pad($item->idPeminjaman, 5, '0', STR_PAD_LEFT) }}</td>
                    <td><strong>{{ $item->member->name }}</strong></td>
                    <td>{{ $item->tanggalPinjam }}</td>
                    <td>{{ $item->batasKembali }}</td>
                    <td>{{ $item->totalBuku }} Buku</td>
                    <td>{{ $item->status }}</td>
                    <td>
                        <a href="{{ route('peminjaman.show', $item->idPeminjaman) }}" style="color: #2563eb; font-weight: bold; text-decoration: none;">Lihat Bukti</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #64748b;">Belum ada data peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div style="margin-top: 15px;">
        {{ $peminjamans->links() }}
    </div>
</div>

</body>
</html>