<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi & Keuangan Perpustakaan</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; color: #1e293b; }
        .wrapper { max-width: 1050px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 18px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #2563eb; }
        .stat-card span { font-size: 12px; color: #64748b; font-weight: bold; text-transform: uppercase; }
        .stat-card h3 { margin: 6px 0 0 0; font-size: 20px; }
        .filter-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .filter-form { display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap; }
        .form-group { display: flex; flex-direction: column; gap: 5px; font-size: 13px; font-weight: bold; }
        .form-group input, .form-group select { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 13px; }
        .btn { padding: 8px 16px; border-radius: 4px; font-size: 13px; font-weight: bold; cursor: pointer; border: none; text-decoration: none; }
        .btn-filter { background: #2563eb; color: white; }
        .btn-print { background: #0f172a; color: white; }
        .report-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        th { background: #f1f5f9; font-weight: bold; }
        .badge { padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }

        @media print {
            .filter-card, .header a, .btn-print, .btn-filter { display: none !important; }
            body { background: white; padding: 0; }
            .report-card, .stat-card { box-shadow: none; border: 1px solid #cbd5e1; }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="header">
        <div>
            <h2 style="margin: 0 0 5px 0;">17. Laporan Perpustakaan (Admin)</h2>
            <span style="font-size: 13px; color: #64748b;">
                Periode: <strong>{{ \Carbon\Carbon::parse($tglMulai)->translatedFormat('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d M Y') }}</strong>
            </span>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-print" onclick="window.print()">Cetak Laporan</button>
            <a href="{{ route('dashboard') }}" class="btn" style="background: #e2e8f0; color: #1e293b;">Dashboard</a>
        </div>
    </div>

    {{-- Kartu Metrik Ringkasan --}}
    <div class="stats-grid">
        <div class="stat-card" style="border-left-color: #2563eb;">
            <span>Total Peminjaman</span>
            <h3>{{ $ringkasan['total_pinjam'] }} Transaksi</h3>
        </div>
        <div class="stat-card" style="border-left-color: #10b981;">
            <span>Total Pengembalian</span>
            <h3>{{ $ringkasan['total_kembali'] }} Buku</h3>
        </div>
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <span>Total Denda Diterbitkan</span>
            <h3 style="color: #d97706;">Rp {{ number_format($ringkasan['total_denda'], 0, ',', '.') }}</h3>
        </div>
        <div class="stat-card" style="border-left-color: #16a34a;">
            <span>Realisasi Denda Lunas</span>
            <h3 style="color: #16a34a;">Rp {{ number_format($ringkasan['denda_lunas'], 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- Filter Periode & Kategori Laporan --}}
    <div class="filter-card">
        <form action="{{ route('laporan.index') }}" method="GET" class="filter-form">
            <div class="form-group">
                <label>Jenis Laporan</label>
                <select name="jenis">
                    <option value="peminjaman" {{ $jenisLaporan === 'peminjaman' ? 'selected' : '' }}>Laporan Peminjaman</option>
                    <option value="pengembalian" {{ $jenisLaporan === 'pengembalian' ? 'selected' : '' }}>Laporan Pengembalian</option>
                    <option value="denda" {{ $jenisLaporan === 'denda' ? 'selected' : '' }}>Laporan Denda & Keuangan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Dari Tanggal</label>
                <input type="date" name="tgl_mulai" value="{{ $tglMulai }}" required>
            </div>
            <div class="form-group">
                <label>Sampai Tanggal</label>
                <input type="date" name="tgl_selesai" value="{{ $tglSelesai }}" required>
            </div>
            <button type="submit" class="btn btn-filter">Tampilkan Laporan</button>
        </form>
    </div>

    {{-- Tampilan Tabel Laporan Sesuai Jenis --}}
    <div class="report-card">
        {{-- 1. Tabel Laporan Peminjaman --}}
        @if ($jenisLaporan === 'peminjaman')
            <h3 style="margin-top: 0;">Laporan Transaksi Peminjaman Buku</h3>
            <table>
                <thead>
                    <tr>
                        <th>No. Peminjaman</th>
                        <th>Member</th>
                        <th>Petugas</th>
                        <th>Daftar Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataPeminjaman as $p)
                        <tr>
                            <td><strong>#TRX-{{ str_pad($p->idPeminjaman, 5, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $p->member->name ?? '-' }}</td>
                            <td>{{ $p->petugas->name ?? '-' }}</td>
                            <td>
                                @foreach ($p->details as $d)
                                    <div>• {{ $d->buku->judul ?? '-' }}</div>
                                @endforeach
                            </td>
                            <td>{{ $p->tanggalPinjam }}</td>
                            <td>{{ $p->batasKembali }}</td>
                            <td>
                                <span class="badge {{ $p->status === 'Dipinjam' ? 'badge-warning' : 'badge-success' }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align: center; color: #64748b;">Tidak ada data peminjaman pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>

        {{-- 2. Tabel Laporan Pengembalian --}}
        @elseif ($jenisLaporan === 'pengembalian')
            <h3 style="margin-top: 0;">Laporan Sirkulasi Pengembalian Buku</h3>
            <table>
                <thead>
                    <tr>
                        <th>No. Return</th>
                        <th>Member Peminjam</th>
                        <th>Petugas Penerima</th>
                        <th>Tanggal Kembali</th>
                        <th>Kondisi Buku</th>
                        <th>Status Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataPengembalian as $ret)
                        <tr>
                            <td><strong>#RET-{{ str_pad($ret->idPengembalian, 5, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $ret->peminjaman->member->name ?? '-' }}</td>
                            <td>{{ $ret->petugas->name ?? '-' }}</td>
                            <td>{{ $ret->tanggalKembali }}</td>
                            <td>
                                <span class="badge {{ $ret->kondisiBuku === 'Baik' ? 'badge-success' : ($ret->kondisiBuku === 'Rusak' ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $ret->kondisiBuku }}
                                </span>
                            </td>
                            <td>
                                @if ($ret->denda)
                                    <strong style="color: #dc2626;">Rp {{ number_format($ret->denda->jumlah, 0, ',', '.') }}</strong> ({{ $ret->denda->status }})
                                @else
                                    <span style="color: #16a34a;">Bebas Denda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align: center; color: #64748b;">Tidak ada data pengembalian pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>

        {{-- 3. Tabel Laporan Denda & Keuangan --}}
        @elseif ($jenisLaporan === 'denda')
            <h3 style="margin-top: 0;">Laporan Tagihan Denda & Arus Pembayaran</h3>
            <table>
                <thead>
                    <tr>
                        <th>No. Denda</th>
                        <th>Member</th>
                        <th>Rincian Pelanggaran</th>
                        <th>Nominal Tagihan</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th>Tgl Terbit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataDenda as $d)
                        <tr>
                            <td><strong>#DND-{{ str_pad($d->idDenda, 5, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $d->pengembalian->peminjaman->member->name ?? '-' }}</td>
                            <td>{{ $d->jenisDenda }}</td>
                            <td><strong>Rp {{ number_format($d->jumlah, 0, ',', '.') }}</strong></td>
                            <td>{{ $d->pembayaran->metode ?? 'Tunai / Belum' }}</td>
                            <td>
                                <span class="badge {{ $d->status === 'Lunas' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $d->status }}
                                </span>
                            </td>
                            <td>{{ $d->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align: center; color: #64748b;">Tidak ada data denda pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>
</div>

</body>
</html>