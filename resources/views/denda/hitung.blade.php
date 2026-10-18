<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Perhitungan Denda</title>
    <script>
        (function() {
            var theme = localStorage.getItem('booknest_theme');
            if (theme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
        })();
    </script>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; margin: 0; padding: 15px; }
        body { font-family: sans-serif; background: #f8fafc; color: #1e293b; }
        .card { background: white; width: 100%; max-width: 600px; margin: auto; padding: 20px 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .row { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
        .btn-submit { background: #ea580c; color: white; border: none; padding: 12px; width: 100%; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 15px; font-size: 14px; }

        /* Dark mode */
        [data-theme="dark"] body { background: #0b1120; color: #cbd5e1; }
        [data-theme="dark"] .card { background: #131d31; box-shadow: 0 4px 16px rgba(0,0,0,0.4); border: 1px solid #23314d; }
        [data-theme="dark"] h3 { color: #f8fafc; }
        [data-theme="dark"] .row { border-bottom-color: #23314d; }
        [data-theme="dark"] .row strong { color: #f8fafc; }
        [data-theme="dark"] .row span { color: #cbd5e1; }
        [data-theme="dark"] strong[style*="color: #dc2626"] { color: #f87171 !important; }
        [data-theme="dark"] a[href*="denda"] { color: #94a3b8 !important; }
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