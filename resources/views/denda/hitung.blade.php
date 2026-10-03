<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Perhitungan Denda</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .card { background: white; max-width: 600px; margin: auto; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
        .btn-submit { background: #ea580c; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 15px; }
    </style>
</head>
<body>

<div class="card">
    <h3 style="margin-top: 0;">Konfirmasi Perhitungan Denda</h3>

    <div class="row">
        <span>Member:</span>
        <strong>{{ $pengembalian->peminjaman->member->name }}</strong>
    </div>
    <div class="row">
        <span>Judul Buku:</span>
        <span>{{ $buku->judul ?? '-' }}</span>
    </div>
    <div class="row">
        <span>Harga Buku Acuan:</span>
        <span>Rp {{ number_format($buku->harga ?? 0, 0, ',', '.') }}</span>
    </div>
    <div class="row">
        <span>Terlambat:</span>
        <span>{{ $hariTerlambat }} Hari ({{ $mingguTerlambat }} Pekan &rarr; {{ $persenKeterlambatan }}%)</span>
    </div>
    <div class="row">
        <span>Denda Keterlambatan:</span>
        <strong>Rp {{ number_format($dendaKeterlambatan, 0, ',', '.') }}</strong>
    </div>
    <div class="row">
        <span>Kondisi Fisik:</span>
        <span>{{ $pengembalian->kondisiBuku }}</span>
    </div>
    <div class="row">
        <span>Denda Fisik (Rusak/Hilang):</span>
        <strong>Rp {{ number_format($dendaFisik, 0, ',', '.') }}</strong>
    </div>
    <div class="row" style="font-size: 16px; border-bottom: none; margin-top: 10px;">
        <strong>Total Tagihan Denda:</strong>
        <strong style="color: #dc2626;">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</strong>
    </div>

    <form action="{{ route('denda.store') }}" method="POST">
        @csrf
        <input type="hidden" name="idPengembalian" value="{{ $pengembalian->idPengembalian }}">
        <input type="hidden" name="jenisDenda" value="Keterlambatan ({{ $persenKeterlambatan }}%) + Fisik {{ $pengembalian->kondisiBuku }}">
        <input type="hidden" name="jumlah" value="{{ $totalTagihan }}">

        <button type="submit" class="btn-submit">Simpan & Terbitkan Tagihan</button>
        <a href="{{ route('denda.index') }}" style="display:block; text-align:center; margin-top:10px; color:#64748b; font-size:13px; text-decoration:none;">Batal</a>
    </form>
</div>

</body>
</html>