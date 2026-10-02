<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pembayaran Denda</title>
    <style>
        body { font-family: sans-serif; padding: 25px; background: #f8fafc; }
        .table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        th { background: #edf2f7; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-pending { background: #feebc8; color: #7b341e; }
        .badge-sukses { background: #c6f6d5; color: #22543d; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-check { background: #3182ce; color: white; }
        .alert-success { background: #c6f6d5; color: #22543d; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .alert-error { background: #fed7d7; color: #742a2a; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="table-container">
    <h2>Daftar Pembayaran Denda (Petugas)</h2>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID Pembayaran</th>
                <th>Member</th>
                <th>Jenis Denda</th>
                <th>Nominal</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pembayarans as $item)
                <tr>
                    <td>#{{ $item->idPembayaran }}</td>
                    <td>{{ $item->denda->pengembalian->peminjaman->member->name ?? 'Member' }}</td>
                    <td>{{ $item->denda->jenisDenda ?? '-' }}</td>
                    <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td>{{ $item->metode }}</td>
                    <td>
                        <span class="badge {{ $item->status === 'Sukses' ? 'badge-sukses' : 'badge-pending' }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td>
                        @if ($item->status !== 'Sukses')
                            <form action="{{ route('pembayaran.verifikasi', $item->idPembayaran) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-check">Verifikasi Status</button>
                            </form>
                        @else
                            <span>Terverifikasi</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Belum ada data transaksi pembayaran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>