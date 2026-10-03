<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bayar Denda via QRIS</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .card { background: white; max-width: 480px; margin: auto; padding: 25px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); text-align: center; }
        .nominal-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px; margin: 15px 0; }
        .nominal-box span { font-size: 13px; color: #991b1b; }
        .nominal-box h2 { margin: 5px 0 0 0; color: #dc2626; }
        .qr-wrapper { margin: 20px 0; padding: 15px; background: #ffffff; display: inline-block; border: 2px dashed #cbd5e1; border-radius: 8px; }
        .btn-action { width: 100%; padding: 10px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 8px; }
        .btn-success { background: #16a34a; color: white; }
        .btn-cancel { background: #dc2626; color: white; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; }
    </style>
</head>
<body>

<div class="card">
    <h3 style="margin-top: 0;">Pembayaran Denda via QRIS</h3>
    <p style="font-size: 13px; color: #64748b; margin-bottom: 0;">
        Pindai kode QR di bawah ini menggunakan aplikasi e-wallet (GoPay, OVO, Dana, ShopeePay) atau Mobile Banking Anda.
    </p>

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="nominal-box">
        <span>Total Tagihan Denda</span>
        <h2>Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</h2>
    </div>

    {{-- Tampilan QR Code --}}
    <div class="qr-wrapper">
        <img src="{{ $qrImageUrl }}" alt="QRIS Code" style="width: 220px; height: 220px; display: block;">
        <span style="font-size: 11px; color: #94a3b8; display: block; margin-top: 8px;">NMID: ID10293847562</span>
    </div>

    <div style="font-size: 12px; color: #64748b; margin-bottom: 15px;">
        ID Transaksi: <strong>#PAY-{{ str_pad($pembayaran->idPembayaran, 5, '0', STR_PAD_LEFT) }}</strong>
    </div>

    {{-- Simulasi Pembayaran oleh Payment Gateway --}}
    <form action="{{ route('bayar.proses_qr', $pembayaran->idPembayaran) }}" method="POST">
        @csrf
        <input type="hidden" name="simulasi_status" value="berhasil">
        <button type="submit" class="btn-action btn-success">Simulasi Bayar Berhasil (Settlement)</button>
    </form>

    <form action="{{ route('bayar.proses_qr', $pembayaran->idPembayaran) }}" method="POST">
        @csrf
        <input type="hidden" name="simulasi_status" value="gagal">
        <button type="submit" class="btn-action btn-cancel">Simulasi Bayar Gagal / Batal</button>
    </form>

    <a href="{{ route('denda.show', $denda->idDenda) }}" style="display: block; margin-top: 15px; color: #64748b; font-size: 12px; text-decoration: none;">
        &larr; Batalkan dan Kembali
    </a>
</div>

</body>
</html>