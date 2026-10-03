<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Peminjaman #{{ $peminjaman->idPeminjaman }}</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .receipt { background: white; max-width: 550px; margin: auto; padding: 25px; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); border-top: 4px solid #2563eb; }
        .header { text-align: center; border-bottom: 2px dashed #cbd5e1; padding-bottom: 15px; margin-bottom: 15px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 8px 4px; text-align: left; font-size: 13px; }
        th { color: #64748b; font-size: 12px; }
        .badge { background: #dbeafe; color: #1e40af; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; }
        .btn-print { background: #0f172a; color: white; padding: 8px 14px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<div class="receipt">
    <div class="header">
        <h3 style="margin: 0 0 5px 0;">BUKTI PEMINJAMAN BUKU</h3>
        <p style="margin: 0; color: #64748b; font-size: 12px;">Perpustakaan Digital</p>
    </div>

    <div class="info-row">
        <span>No. Transaksi:</span>
        <strong>#TRX-{{ str_pad($peminjaman->idPeminjaman, 5, '0', STR_PAD_LEFT) }}</strong>
    </div>
    <div class="info-row">
        <span>Nama Member:</span>
        <strong>{{ $peminjaman->member->name }}</strong>
    </div>
    <div class="info-row">
        <span>Petugas:</span>
        <span>{{ $peminjaman->petugas->name ?? '-' }}</span>
    </div>
    <div class="info-row">
        <span>Tanggal Pinjam:</span>
        <span>{{ \Carbon\Carbon::parse($peminjaman->tanggalPinjam)->translatedFormat('d F Y') }}</span>
    </div>
    <div class="info-row">
        <span>Batas Pengembalian (Jatuh Tempo):</span>
        <strong style="color: #dc2626;">{{ \Carbon\Carbon::parse($peminjaman->batasKembali)->translatedFormat('d F Y') }}</strong>
    </div>
    <div class="info-row">
        <span>Status Transaksi:</span>
        <span class="badge">{{ $peminjaman->status }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Barcode</th>
                <th>Judul Buku</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peminjaman->details as $idx => $d)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td><code>{{ $d->buku->barcode->kodeBarcode ?? '-' }}</code></td>
                    <td>{{ $d->buku->judul }}</td>
                    <td>1 Eks</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 25px; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('peminjaman.index') }}" style="color: #64748b; font-size: 13px; text-decoration: none;">&larr; Riwayat Peminjaman</a>
        <button class="btn-print" onclick="window.print()">Cetak Bukti</button>
    </div>
</div>

</body>
</html>