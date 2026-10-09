<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan Denda #{{ $denda->idDenda }}</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; margin: 0; padding: 15px; }
        body { font-family: sans-serif; background: #f8fafc; }
        .invoice { background: white; width: 100%; max-width: 520px; margin: auto; padding: 22px 18px; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); border-top: 4px solid #dc2626; }
        .row { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; font-size: 13px; }
        .total { font-size: 18px; font-weight: bold; color: #dc2626; padding-top: 12px; border-top: 2px dashed #cbd5e1; }
        .btn-qr { display: block; text-align: center; background: #2563eb; color: white; padding: 12px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 15px; font-size: 14px; }
    </style>
</head>
<body>

<div class="invoice">
    <div style="text-align: center; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
        <h3 style="margin: 0;">TAGIHAN DENDA PERPUSTAKAAN</h3>
        <span style="font-size: 12px; color: #64748b;">No: #DND-{{ str_pad($denda->idDenda, 5, '0', STR_PAD_LEFT) }}</span>
    </div>

    <div class="row">
        <span>Nama Member:</span>
        <strong>{{ $denda->pengembalian->peminjaman->member->name ?? '-' }}</strong>
    </div>
    <div class="row">
        <span>Jenis Pelanggaran:</span>
        <span>{{ $denda->jenisDenda }}</span>
    </div>
    <div class="row">
        <span>Status Tagihan:</span>
        <strong>{{ $denda->status }}</strong>
    </div>
    <div class="row total">
        <span>Total Wajib Bayar:</span>
        <span>Rp {{ number_format($denda->jumlah, 0, ',', '.') }}</span>
    </div>

    @if ($denda->status === 'Belum Dibayar')
        {{-- Tautan langsung ke pembayaran QR (Activity 13) --}}
        <a href="{{ route('bayar.qr', $denda->idDenda) }}" class="btn-qr">Lanjut ke Pembayaran QR &rarr;</a>
    @else
        <div style="background: #dcfce7; color: #166534; padding: 10px; text-align: center; border-radius: 4px; margin-top: 15px; font-weight: bold; font-size: 13px;">
            Status tagihan tercatat lunas; riwayat pembayaran belum diverifikasi oleh gateway.
        </div>
    @endif

    <a href="{{ route('denda.index') }}" style="display: block; text-align: center; margin-top: 15px; color: #64748b; font-size: 12px; text-decoration: none;">&larr; Kembali ke Kelola Denda</a>
</div>

</body>
</html>
