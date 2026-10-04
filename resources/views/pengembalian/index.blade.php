<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengembalian</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 13px; }
        th { background: #f1f5f9; }
        .btn { padding: 7px 14px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; }
        .btn-primary { background: #2563eb; color: white; }
    </style>
</head>
<body>

<div class="card">
    <div class="header">
        <h2>Riwayat Pengembalian Buku</h2>
        <div>
            <a href="{{ route('pengembalian.create') }}" class="btn btn-primary">+ Proses Pengembalian</a>
            <a href="{{ route('dashboard') }}" style="margin-left: 10px; font-size: 13px; color: #64748b; text-decoration: none;">&larr; Dashboard</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Return</th>
                <th>Member</th>
                <th>Tgl Kembali</th>
                <th>Kondisi Buku</th>
                <th>Denda</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengembalians as $item)
                <tr>
                    <td>#RET-{{ str_pad($item->idPengembalian, 5, '0', STR_PAD_LEFT) }}</td>
                    <td><strong>{{ $item->peminjaman->member->name ?? '-' }}</strong></td>
                    <td>{{ $item->tanggalKembali }}</td>
                    <td>{{ $item->kondisiBuku }}</td>
                    <td>
                        @if ($item->denda)
                            <span style="color: #dc2626; font-weight: bold;">Rp {{ number_format($item->denda->jumlah, 0, ',', '.') }}</span>
                        @else
                            <span style="color: #16a34a;">Rp 0</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pengembalian.show', $item->idPengembalian) }}" style="color: #2563eb; font-weight: bold; text-decoration: none;">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #64748b;">Belum ada riwayat pengembalian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        {{ $pengembalians->links() }}
    </div>
</div>

</body>
</html>