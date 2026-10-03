<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman & Pengembalian Saya</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .wrapper { max-width: 950px; margin: auto; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 13px; vertical-align: top; }
        th { background: #f1f5f9; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; }
        .badge-dipinjam { background: #fef3c7; color: #92400e; }
        .badge-selesai { background: #dcfce7; color: #166534; }
        .book-list { margin: 0; padding-left: 18px; }
        .book-list li { margin-bottom: 4px; }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="header">
        <div>
            <h2 style="margin: 0 0 5px 0;">Riwayat Peminjaman & Pengembalian</h2>
            <span style="font-size: 13px; color: #64748b;">Member: <strong>{{ auth()->user()->name }}</strong></span>
        </div>
        <a href="{{ route('dashboard') }}" style="color: #64748b; font-size: 13px; text-decoration: none;">&larr; Dashboard</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th style="width: 110px;">No. Transaksi</th>
                    <th>Buku yang Dipinjam</th>
                    <th style="width: 120px;">Tgl Pinjam</th>
                    <th style="width: 120px;">Jatuh Tempo</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 140px;">Pengembalian</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayats as $item)
                    <tr>
                        <td><strong>#TRX-{{ str_pad($item->idPeminjaman, 5, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>
                            <ul class="book-list">
                                @foreach ($item->details as $detail)
                                    <li>
                                        {{ $detail->buku->judul ?? 'Buku' }}
                                        <code style="font-size: 11px;">({{ $detail->buku->barcode->kodeBarcode ?? '-' }})</code>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggalPinjam)->translatedFormat('d M Y') }}</td>
                        <td>
                            <span style="color: {{ \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($item->batasKembali)) && $item->status === 'Dipinjam' ? '#dc2626' : '#1e293b' }};">
                                {{ \Carbon\Carbon::parse($item->batasKembali)->translatedFormat('d M Y') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $item->status === 'Dipinjam' ? 'badge-dipinjam' : 'badge-selesai' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>
                            @if ($item->pengembalians->isNotEmpty())
                                @foreach ($item->pengembalians as $ret)
                                    <div>Tgl: {{ \Carbon\Carbon::parse($ret->tanggalKembali)->translatedFormat('d M Y') }}</div>
                                    <div style="font-size: 11px; color: #64748b;">Kondisi: {{ $ret->kondisiBuku }}</div>
                                @endforeach
                            @else
                                <span style="color: #64748b; font-style: italic;">Belum dikembalikan</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #64748b; padding: 20px;">
                            Belum ada riwayat peminjaman buku.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 15px;">
            {{ $riwayats->links() }}
        </div>
    </div>
</div>

</body>
</html>