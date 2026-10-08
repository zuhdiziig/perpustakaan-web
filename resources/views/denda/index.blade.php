<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Denda</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; margin: 0; padding: 15px; }
        body { font-family: sans-serif; background: #f8fafc; }
        .wrapper { width: 100%; max-width: 1000px; margin: auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
        .page-header h2 { margin: 0; font-size: 1.25rem; }
        .card { background: white; padding: 20px 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; width: 100%; }
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; margin-top: 10px; }
        table { width: 100%; min-width: 600px; border-collapse: collapse; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 13px; }
        th { background: #f1f5f9; }
        .badge { padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; white-space: nowrap; }
        .badge-lunas { background: #dcfce7; color: #166534; }
        .badge-belum { background: #fee2e2; color: #991b1b; }
        .btn { padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold; display: inline-block; white-space: nowrap; }
        .btn-calc { background: #ea580c; color: white; }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="page-header">
        <h2>12. Kelola Data Tagihan Denda (Petugas)</h2>
        <a href="{{ route('dashboard') }}" style="color: #64748b; font-size: 13px; text-decoration: none;">&larr; Dashboard</a>
    </div>

    @if (session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pengembalian yang butuh verifikasi denda --}}
    @if ($pengembalianTertunda->count() > 0)
        <div class="card" style="border-left: 4px solid #ea580c;">
            <h3 style="margin-top: 0; color: #ea580c;">Pengembalian Membutuhkan Penetapan Denda</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID Return</th>
                            <th>Member</th>
                            <th>Tanggal Kembali</th>
                            <th>Kondisi Buku</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengembalianTertunda as $p)
                            <tr>
                                <td>#RET-{{ str_pad($p->idPengembalian, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $p->peminjaman->member->name ?? '-' }}</td>
                                <td>{{ $p->tanggalKembali }}</td>
                                <td>{{ $p->kondisiBuku }}</td>
                                <td>
                                    <a href="{{ route('denda.hitung', $p->idPengembalian) }}" class="btn btn-calc">Hitung & Konfirmasi Denda</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Daftar Denda Tercatat --}}
    <div class="card">
        <h3 style="margin-top: 0;">Daftar Tagihan Denda</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID Denda</th>
                        <th>Member</th>
                        <th>Rincian Denda</th>
                        <th>Nominal Tagihan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dendas as $d)
                        <tr>
                            <td>#DND-{{ str_pad($d->idDenda, 5, '0', STR_PAD_LEFT) }}</td>
                            <td><strong>{{ $d->pengembalian->peminjaman->member->name ?? '-' }}</strong></td>
                            <td>{{ $d->jenisDenda }}</td>
                            <td><strong style="color: #dc2626;">Rp {{ number_format($d->jumlah, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="badge {{ $d->status === 'Lunas' ? 'badge-lunas' : 'badge-belum' }}">
                                    {{ $d->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('denda.show', $d->idDenda) }}" style="color: #2563eb; font-weight: bold; text-decoration: none;">Tampilkan Tagihan</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b;">Belum ada tagihan denda tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 15px;">
            {{ $dendas->links() }}
        </div>
    </div>
</div>

</body>
</html>