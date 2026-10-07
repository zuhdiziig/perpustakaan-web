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

    {{-- Tampilan Rincian Data Booking Jika Ditemukan --}}
    @if ($booking)
        <div class="result-box" style="border: 2px solid #0f766e; background: #f0fdf4; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 14px;">
                <div>
                    <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; background: #ccfbf1; color: #0f766e; padding: 3px 8px; border-radius: 6px;">
                        🎫 Tiket Booking Online
                    </span>
                    <h3 style="margin: 6px 0 0 0; color: #0f172a; font-size: 18px;">{{ $booking->kode_booking }}</h3>
                </div>
                <span class="badge" style="background: {{ $booking->status === 'Siap Diambil' ? '#dcfce7' : '#fef3c7' }}; color: {{ $booking->status === 'Siap Diambil' ? '#166534' : '#b45309' }}; font-size: 13px; padding: 4px 10px;">
                    {{ $booking->status }}
                </span>
            </div>

            <div class="row">
                <span class="label">Anggota Pemesan</span>
                <span><strong>{{ $booking->member?->name }}</strong> ({{ $booking->member?->kode_anggota }})</span>
            </div>
            <div class="row">
                <span class="label">Judul Buku</span>
                <strong>{{ $buku?->judul ?? 'Buku Perpustakaan' }}</strong>
            </div>
            <div class="row">
                <span class="label">Lokasi Rak Fisik</span>
                <span style="color: #0f766e; font-weight: 700;">📍 {{ $buku?->rak ?? 'Rak Utama' }}</span>
            </div>
            <div class="row">
                <span class="label">Metode Pengambilan</span>
                <span>{{ $booking->opsi_pengambilan === 'siapkan_petugas' ? '📦 Disiapkan Petugas di Meja' : '🚶 Ambil Mandiri dari Rak' }}</span>
            </div>
            <div class="row">
                <span class="label">Batas Waktu Pengambilan</span>
                <span>{{ $booking->batasAmbil ? \Carbon\Carbon::parse($booking->batasAmbil)->translatedFormat('d M Y, H:i') : '-' }}</span>
            </div>

            <div style="margin-top: 16px; display: flex; flex-direction: column; gap: 8px;">
                <form method="POST" action="{{ route('petugas.booking.serah-terima', $booking->idPeminjaman) }}" style="margin: 0;" onsubmit="return confirm('Konfirmasi serah terima buku kepada {{ $booking->member?->name }}? Transaksi akan beralih menjadi Dipinjam (30 hari).');">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 11px; background: #0f766e; color: white; border: none; border-radius: 6px; font-weight: 700; font-size: 14px; cursor: pointer;">
                        🤝 Serah Terima Buku (Konfirmasi Selesai)
                    </button>
                </form>

                <div style="display: flex; gap: 8px;">
                    @if($booking->status === 'Booking')
                        <form method="POST" action="{{ route('petugas.booking.siapkan', $booking->idPeminjaman) }}" style="flex: 1; margin: 0;">
                            @csrf
                            <button type="submit" style="width: 100%; padding: 9px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a; border-radius: 6px; font-weight: 700; font-size: 13px; cursor: pointer;">
                                📦 Tandai Siap Diambil
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('peminjaman.booking.tiket', $booking->idPeminjaman) }}" target="_blank" style="flex: 1; text-align: center; background: #ffffff; color: #475569; border: 1px solid #cbd5e1; padding: 9px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 700;">
                        🔍 Buka Tiket QR
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Tampilan Rincian Data Buku Jika Ditemukan --}}
    @if ($buku && ! $booking)
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