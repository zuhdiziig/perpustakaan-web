<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Denda Saya - Perpustakaan</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .wrapper { max-width: 950px; margin: auto; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .summary-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .summary-box.clean { background: #f0fdf4; border-color: #bbf7d0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 13px; }
        th { background: #f1f5f9; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; }
        .badge-lunas { background: #dcfce7; color: #166534; }
        .badge-belum { background: #fee2e2; color: #991b1b; }
        .btn-pay { background: #2563eb; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="header">
        <div>
            <h2 style="margin: 0 0 5px 0;">Data Denda & Status Tagihan</h2>
            <span style="font-size: 13px; color: #64748b;">Member: <strong>{{ auth()->user()->name }}</strong></span>
        </div>
        <a href="{{ route('dashboard') }}" style="color: #64748b; font-size: 13px; text-decoration: none;">&larr; Dashboard</a>
    </div>

    {{-- Ringkasan Tunggakan --}}
    @if ($totalTunggakan > 0)
        <div class="summary-box">
            <div>
                <span style="color: #991b1b; font-size: 13px; font-weight: bold;">Total Tunggakan Belum Dibayar:</span>
                <div style="font-size: 22px; font-weight: bold; color: #dc2626; margin-top: 4px;">
                    Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                </div>
            </div>
            <span style="font-size: 12px; color: #7f1d1d;">Silakan lunasi tagihan menggunakan QRIS di tombol aksi.</span>
        </div>
    @else
        <div class="summary-box clean">
            <div>
                <strong style="color: #166534;">Tidak ada tanggungan denda!</strong>
                <div style="font-size: 13px; color: #15803d; margin-top: 2px;">Akun Anda bersih dari denda keterlambatan maupun ganti rugi fisik buku.</div>
            </div>
        </div>
    @endif

    <div class="card">
        <h3 style="margin-top: 0;">Rincian Denda</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 100px;">ID Denda</th>
                    <th>Judul Buku Terkait</th>
                    <th>Jenis Pelanggaran</th>
                    <th>Nominal</th>
                    <th style="width: 120px;">Status</th>
                    <th style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dendas as $item)
                    <tr>
                        <td><strong>#DND-{{ str_pad($item->idDenda, 5, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>
                            @php
                                $bukuJudul = $item->pengembalian->peminjaman->details->first()->buku->judul ?? 'Buku Perpustakaan';
                            @endphp
                            {{ $bukuJudul }}
                        </td>
                        <td>{{ $item->jenisDenda }}</td>
                        <td><strong style="color: #dc2626;">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</strong></td>
                        <td>
                            <span class="badge {{ $item->status === 'Lunas' ? 'badge-lunas' : 'badge-belum' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>
                            @if ($item->status === 'Belum Dibayar')
                                <a href="{{ route('bayar.qr', $item->idDenda) }}" class="btn-pay">Bayar QR &rarr;</a>
                            @else
                                <span style="color: #16a34a; font-size: 12px; font-weight: bold;">Lunas Terverifikasi</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #64748b; padding: 20px;">
                            Tidak ada riwayat denda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 15px;">
            {{ $dendas->links() }}
        </div>
    </div>
</div>

</body>
</html>