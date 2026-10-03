<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scan Barcode Buku</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .card { background: white; max-width: 620px; margin: auto; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .input-group { display: flex; gap: 8px; margin-bottom: 20px; }
        input[type="text"] { flex: 1; padding: 10px; font-size: 15px; border: 2px solid #2563eb; border-radius: 6px; outline: none; }
        button { padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; }
        .alert-error { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 14px; }
        .result-box { border: 1px solid #e2e8f0; border-radius: 6px; padding: 18px; background: #ffffff; }
        .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .label { color: #64748b; font-weight: bold; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-tersedia { background: #dcfce7; color: #166534; }
        .badge-habis { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

<div class="card">
    <div class="header">
        <h2 style="margin: 0;">Scan Barcode Buku</h2>
        <a href="{{ route('dashboard') }}" style="color: #64748b; font-size: 13px; text-decoration: none;">&larr; Dashboard</a>
    </div>

    {{-- Form Input Scanner --}}
    <form action="{{ route('barcode.scan') }}" method="GET">
        <label style="display: block; margin-bottom: 6px; font-size: 13px; color: #475569; font-weight: bold;">
            Arahkan Scanner atau Masukkan Kode Barcode:
        </label>
        <div class="input-group">
            <input type="text" name="kodeBarcode" value="{{ $kodeBarcode }}" placeholder="Scan atau ketik kode barcode di sini..." autofocus required>
            <button type="submit">Cari Buku</button>
        </div>
    </form>

    {{-- Notifikasi Jika Barcode Tidak Ditemukan --}}
    @if ($error)
        <div class="alert-error">
            <strong>Gagal:</strong> {{ $error }}
        </div>
    @endif

    {{-- Tampilan Rincian Data Buku Jika Ditemukan --}}
    @if ($buku)
        <div class="result-box">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                <h3 style="margin: 0; color: #0f172a;">{{ $buku->judul }}</h3>
                <span class="badge {{ $buku->stok > 0 ? 'badge-tersedia' : 'badge-habis' }}">
                    {{ $buku->stok > 0 ? 'Tersedia (' . $buku->stok . ' eks)' : 'Stok Habis' }}
                </span>
            </div>

            <div class="row">
                <span class="label">Kode Barcode</span>
                <code>{{ $buku->barcode->kodeBarcode }}</code>
            </div>
            <div class="row">
                <span class="label">Kategori</span>
                <span>{{ $buku->kategori->namaKategori ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Penulis</span>
                <span>{{ $buku->penulis }}</span>
            </div>
            <div class="row">
                <span class="label">Penerbit / Tahun</span>
                <span>{{ $buku->penerbit }} ({{ $buku->tahunTerbit }})</span>
            </div>
            <div class="row">
                <span class="label">Kondisi Fisik</span>
                <span>{{ $buku->kondisi }}</span>
            </div>
            <div class="row">
                <span class="label">Harga Buku (Nilai Ganti Rugi)</span>
                <strong>Rp {{ number_format($buku->harga, 0, ',', '.') }}</strong>
            </div>

            <div style="margin-top: 15px; display: flex; gap: 8px;">
                <a href="{{ route('peminjaman.create') }}" style="flex: 1; text-align: center; background: #2563eb; color: white; padding: 8px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold;">Proses Pinjam</a>
                <a href="{{ route('pengembalian.create', ['barcode' => $buku->barcode->kodeBarcode]) }}" style="flex: 1; text-align: center; background: #16a34a; color: white; padding: 8px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold;">Proses Kembali</a>
            </div>
        </div>
    @endif
</div>

</body>
</html>