<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Pengembalian #{{ $pengembalian->idPengembalian }}</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .receipt { background: white; max-width: 550px; margin: auto; padding: 25px; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); border-top: 4px solid #16a34a; }
        .header { text-align: center; border-bottom: 2px dashed #cbd5e1; padding-bottom: 15px; margin-bottom: 15px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
        .denda-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px; margin: 15px 0; }
        .btn-link { display: inline-block; margin-top: 15px; color: #2563eb; text-decoration: none; font-size: 13px; font-weight: bold; }
    </style>
</head>
<body>

<div class="receipt">
    <div class="header">
        <h3 style="margin: 0 0 5px 0;">BUKTI PENGEMBALIAN BUKU</h3>
        <p style="margin: 0; color: #64748b; font-size: 12px;">Perpustakaan Digital</p>
    </div>

    <div class="row">
        <span>No. Pengembalian:</span>
        <strong>#RET-{{ str_pad($pengembalian->idPengembalian, 5, '0', STR_PAD_LEFT) }}</strong>
    </div>
    <div class="row">
        <span>Nama Member:</span>
        <strong>{{ $pengembalian->peminjaman->member->name }}</strong>
    </div>
    <div class="row">
        <span>Petugas Penerima:</span>
        <span>{{ $pengembalian->petugas->name ?? '-' }}</span>
    </div>
    <div class="row">
        <span>Tanggal Pengembalian:</span>
        <span>{{ \Carbon\Carbon::parse($pengembalian->tanggalKembali)->translatedFormat('d F Y') }}</span>
    </div>
    <div class="row">
        <span>Kondisi Buku:</span>
        <strong>{{ $pengembalian->kondisiBuku }}</strong>
    </div>

    {{-- Info Denda --}}
    @if ($pengembalian->denda)
        <div class="denda-box">
            <div class="row" style="color: #991b1b; font-weight: bold;">
                <span>Jenis Denda:</span>
                <span>{{ $pengembalian->denda->jenisDenda }}</span>
            </div>
            <div class="row" style="color: #991b1b; font-size: 15px; font-weight: bold;">
                <span>Total Denda:</span>
                <span>Rp {{ number_format($pengembalian->denda->jumlah, 0, ',', '.') }}</span>
            </div>
            <div class="row" style="margin-top: 5px; font-size: 12px;">
                <span>Status:</span>
                <span>{{ $pengembalian->denda->status }}</span>
            </div>
        </div>
    @else
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px; border-radius: 6px; margin: 15px 0; font-size: 13px; text-align: center;">
            <strong>Pengembalian Tepat Waktu & Kondisi Baik. Bebas Biaya Denda.</strong>
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
        <a href="{{ route('pengembalian.create') }}" class="btn-link">&larr; Kembalikan Buku Lain</a>
        <button onclick="window.print()" style="padding: 7px 14px; background: #0f172a; color: white; border: none; border-radius: 4px; cursor: pointer;">Cetak</button>
    </div>
</div>

</body>
</html>