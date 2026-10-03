<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Proses Pengembalian Buku</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .card { background: white; max-width: 650px; margin: auto; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px; }
        input, select { width: 100%; padding: 9px; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 4px; }
        .btn { padding: 9px 16px; border-radius: 4px; cursor: pointer; border: none; font-weight: bold; }
        .btn-search { background: #2563eb; color: white; }
        .btn-submit { background: #16a34a; color: white; width: 100%; padding: 12px; margin-top: 15px; font-size: 14px; }
        .box-info { background: #f1f5f9; padding: 15px; border-radius: 6px; margin: 15px 0; font-size: 13px; }
        .box-info p { margin: 4px 0; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; }
    </style>
</head>
<body>

<div class="card">
    <h2 style="margin-top: 0;">Pengembalian Buku</h2>

    {{-- Form Pencarian / Scan Barcode --}}
    <form action="{{ route('pengembalian.create') }}" method="GET">
        <label>Scan / Masukkan Barcode Buku yang Dikembalikan</label>
        <div style="display: flex; gap: 8px; margin-bottom: 15px;">
            <input type="text" name="barcode" value="{{ $barcodeInput }}" placeholder="Contoh: BK-IT-001" autofocus required>
            <button type="submit" class="btn btn-search">Cari</button>
        </div>
    </form>

    @if ($barcodeInput && (!$buku || !$transaksi))
        <div class="alert-error">
            Barcode <strong>{{ $barcodeInput }}</strong> tidak ditemukan atau saat ini tidak tercatat dalam transaksi peminjaman aktif!
        </div>
    @endif

    {{-- Detail Transaksi Peminjaman Jika Ditemukan --}}
    @if ($transaksi && $buku)
        <div class="box-info">
            <h4 style="margin: 0 0 8px 0; color: #1e293b;">Data Peminjaman Ditemukan:</h4>
            <p><strong>Judul Buku:</strong> {{ $buku->judul }}</p>
            <p><strong>Peminjam:</strong> {{ $transaksi->member->name }}</p>
            <p><strong>Tanggal Pinjam:</strong> {{ $transaksi->tanggalPinjam }}</p>
            <p><strong>Jatuh Tempo:</strong> <span style="color: #dc2626; font-weight: bold;">{{ $transaksi->batasKembali }}</span></p>
        </div>

        {{-- Form Konfirmasi Kondisi Buku & Pengembalian --}}
        <form action="{{ route('pengembalian.store') }}" method="POST">
            @csrf
            <input type="hidden" name="idPeminjaman" value="{{ $transaksi->idPeminjaman }}">
            <input type="hidden" name="idBuku" value="{{ $buku->idBuku }}">

            <div class="form-group">
                <label>Cek Kondisi Fisik Buku</label>
                <select name="kondisiBuku" required>
                    <option value="Baik">Baik (Bebas Denda Kerusakan)</option>
                    <option value="Rusak">Rusak (Denda 50% Harga Buku)</option>
                    <option value="Hilang">Hilang (Denda 100% Harga Buku)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-submit">Konfirmasi Pengembalian</button>
        </form>
    @endif

    <div style="margin-top: 15px; text-align: center;">
        <a href="{{ route('pengembalian.index') }}" style="color: #64748b; font-size: 13px; text-decoration: none;">&larr; Riwayat Pengembalian</a>
    </div>
</div>

</body>
</html>