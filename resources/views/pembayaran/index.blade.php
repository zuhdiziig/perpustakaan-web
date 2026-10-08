<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pembayaran Denda</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; margin: 0; padding: 15px; }
        body { font-family: sans-serif; background: #f8fafc; }
        .table-container { background: white; padding: 20px 16px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 100%; max-width: 1050px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 1.25rem; }
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; margin-top: 15px; }
        table { width: 100%; min-width: 650px; border-collapse: collapse; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 13px; }
        th { background: #edf2f7; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; white-space: nowrap; }
        .badge-pending { background: #feebc8; color: #7b341e; }
        .badge-sukses { background: #c6f6d5; color: #22543d; }
        .btn { padding: 8px 14px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: bold; white-space: nowrap; }
        .btn-check { background: #3182ce; color: white; }
        .alert-success { background: #c6f6d5; color: #22543d; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; }
        .alert-error { background: #fed7d7; color: #742a2a; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; }
    </style>
</head>
<body>

<div class="table-container">
    <div class="header">
        <h2>Daftar Pembayaran Denda (Petugas)</h2>
        <a href="{{ route('dashboard') }}" style="color: #64748b; font-size: 13px; text-decoration: none;">&larr; Dashboard</a>
    </div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
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
</div>

</body>
</html>