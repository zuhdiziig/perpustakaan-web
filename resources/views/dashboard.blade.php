<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistem Perpustakaan</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; margin: 0; padding: 25px; }
        .container { max-width: 950px; margin: auto; }
        .header { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-top: 4px solid #2563eb; }
        .card h3 { margin-top: 0; margin-bottom: 8px; font-size: 16px; color: #1e293b; }
        .card p { font-size: 13px; color: #64748b; margin-bottom: 15px; }
        .btn-link { display: inline-block; background: #2563eb; color: white; text-decoration: none; padding: 7px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .btn-logout { background: none; border: none; color: #dc2626; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div>
            <h2 style="margin: 0 0 5px 0;">Sistem Manajemen Perpustakaan</h2>
            <span style="font-size: 13px; color: #64748b;">
                Login sebagai: <strong>{{ auth()->user()->name }}</strong> (Role: <span style="text-transform: uppercase;">{{ auth()->user()->role }}</span>)
            </span>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Keluar (Logout)</button>
        </form>
    </div>

    <div class="grid">
        <div class="card" style="border-top-color: #ef4444;">
            <h3>16. Melihat Denda</h3>
            <p>Pemeriksaan status denda member dan link langsung pelunasan QR.</p>
            <a href="{{ route('denda.saya') }}" class="btn-link" style="background: #ef4444;">Denda Saya &rarr;</a>
        </div>

        <div class="card" style="border-top-color: #6366f1;">
            <h3>15. Melihat Riwayat</h3>
            <p>Histori peminjaman dan riwayat pengembalian buku member.</p>
            <a href="{{ route('riwayat.index') }}" class="btn-link" style="background: #6366f1;">Lihat Riwayat &rarr;</a>
        </div>

        <div class="card" style="border-top-color: #ea580c;">
            <h3>12. Kelola Denda</h3>
            <p>Penetapan dan rekap tagihan denda keterlambatan serta kerusakan.</p>
            <a href="{{ route('denda.index') }}" class="btn-link" style="background: #ea580c;">Kelola Denda &rarr;</a>
        </div>      

        <div class="card" style="border-top-color: #0284c7;">
            <h3>11. Cek Kondisi Buku</h3>
            <p>Inspeksi fisik buku dan estimasi biaya ganti rugi rusak/hilang.</p>
            <a href="{{ route('kondisi.index') }}" class="btn-link" style="background: #0284c7;">Cek Kondisi &rarr;</a>
        </div>

        <div class="card" style="border-top-color: #0284c7;">
            <h3>03. Katalog Buku</h3>
            <p>Pencarian, filter kategori, dan detail stok buku.</p>
            <a href="{{ route('katalog.index') }}" class="btn-link">Buka Katalog &rarr;</a>
        </div>

        <div class="card" style="border-top-color: #16a34a;">
            <h3>04. Kelola Buku</h3>
            <p>Tambah, edit data buku, stok, dan barcode fisik.</p>
            <a href="{{ route('buku.index') }}" class="btn-link" style="background: #16a34a;">Kelola Buku &rarr;</a>
        </div>

        <div class="card" style="border-top-color: #8b5cf6;">
            <h3>05. Kelola Kategori</h3>
            <p>Manajemen kategori rak buku dan deskripsi.</p>
            <a href="{{ route('kategori.index') }}" class="btn-link" style="background: #8b5cf6;">Kelola Kategori &rarr;</a>
        </div>

        <div class="card" style="border-top-color: #ea580c;">
            <h3>06. Kelola Petugas</h3>
            <p>Manajemen akun operasional petugas perpustakaan.</p>
            <a href="{{ route('petugas.index') }}" class="btn-link" style="background: #ea580c;">Kelola Petugas &rarr;</a>
        </div>

        <div class="card" style="border-top-color: #0d9488;">
            <h3>07. Kelola Member</h3>
            <p>Daftar anggota, edit profil, dan nonaktifkan akun.</p>
            <a href="{{ route('member.index') }}" class="btn-link" style="background: #0d9488;">Kelola Member &rarr;</a>
        </div>

        <div class="card" style="border-top-color: #2563eb;">
            <h3>08. Peminjaman Buku</h3>
            <p>Transaksi peminjaman (maks 7 buku & tempo 1 bulan).</p>
            <a href="{{ route('peminjaman.index') }}" class="btn-link">Menu Pinjam &rarr;</a>
        </div>

        <div class="card" style="border-top-color: #059669;">
            <h3>09. Pengembalian Buku</h3>
            <p>Pengembalian dan kalkulasi denda bertingkat/rusak.</p>
            <a href="{{ route('pengembalian.index') }}" class="btn-link" style="background: #059669;">Menu Kembali &rarr;</a>
        </div>

        <div class="card" style="border-top-color: #d97706;">
            <h3>10. Scan Barcode Buku</h3>
            <p>Pengecekan instan data buku lewat kode barcode.</p>
            <a href="{{ route('barcode.scan') }}" class="btn-link" style="background: #d97706;">Buka Scanner &rarr;</a>
        </div>

        <div class="card" style="border-top-color: #475569;">
            <h3>14. Verifikasi Pembayaran</h3>
            <p>Cek verifikasi pembayaran QR/tunai denda member.</p>
            <a href="{{ route('pembayaran.index') }}" class="btn-link" style="background: #475569;">Verifikasi &rarr;</a>
        </div>

        <div class="card" style="border-top-color: #1e293b;">
            <h3>17. Melihat Laporan</h3>
            <p>Rekapitulasi sirkulasi buku, kondisi inventaris, dan arus kas denda.</p>
            <a href="{{ route('laporan.index') }}" class="btn-link" style="background: #1e293b;">Buka Laporan &rarr;</a>
        </div>
    </div>
</div>

</body>
</html>