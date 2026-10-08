<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeriksaan Kondisi Buku</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; margin: 0; padding: 15px; }
        body { font-family: sans-serif; background: #f8fafc; }
        .card { background: white; padding: 20px 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 100%; max-width: 950px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 1.25rem; }
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; margin-top: 15px; }
        table { width: 100%; min-width: 650px; border-collapse: collapse; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 13px; }
        th { background: #f1f5f9; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; white-space: nowrap; }
        .badge-baik { background: #dcfce7; color: #166534; }
        .badge-rusak { background: #fef3c7; color: #92400e; }
        .badge-hilang { background: #fee2e2; color: #991b1b; }
        .alert-biaya { background: #fef2f2; border-left: 4px solid #ef4444; padding: 12px; margin-bottom: 15px; border-radius: 4px; font-size: 13px; }
        .alert-aman { background: #f0fdf4; border-left: 4px solid #22c55e; padding: 12px; margin-bottom: 15px; border-radius: 4px; font-size: 13px; }
        .btn-check { background: #0284c7; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold; white-space: nowrap; display: inline-block; }
    </style>
</head>
<body>

<div class="card">
    <div class="header">
        <h2>11. Cek & Input Kondisi Fisik Buku</h2>
        <a href="{{ route('dashboard') }}" style="color: #64748b; font-size: 13px; text-decoration: none;">&larr; Dashboard</a>
    </div>

    {{-- Tampilan Hasil Evaluasi Sistem (Biaya Kerusakan) --}}
    @if (session('success'))
        @if (session('biayaKerusakan') > 0)
            <div class="alert-biaya">
                <strong>Status Kondisi: {{ session('kondisi') }}</strong> untuk buku <em>{{ session('judulBuku') }}</em>.<br>
                <span>Biaya Penggantian/Kerusakan Dihitung: <strong>Rp {{ number_format(session('biayaKerusakan'), 0, ',', '.') }}</strong> (Seharga nilai buku).</span>
            </div>
        @else
            <div class="alert-aman">
                <strong>Status Kondisi: Baik</strong> untuk buku <em>{{ session('judulBuku') }}</em>.<br>
                <span>Buku dalam keadaan terawat. Tidak ada estimasi biaya ganti rugi (Rp 0).</span>
            </div>
        @endif
    @endif

    {{-- Filter Kondisi --}}
    <form action="{{ route('kondisi.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
        <label style="font-size: 13px; font-weight: bold;">Filter Kondisi:</label>
        <select name="kondisi" onchange="this.form.submit()" style="padding: 6px; border-radius: 4px; border: 1px solid #cbd5e1;">
            <option value="">Semua Kondisi</option>
            <option value="Baik" {{ $statusFilter == 'Baik' ? 'selected' : '' }}>Baik</option>
            <option value="Rusak" {{ $statusFilter == 'Rusak' ? 'selected' : '' }}>Rusak</option>
            <option value="Hilang" {{ $statusFilter == 'Hilang' ? 'selected' : '' }}>Hilang</option>
        </select>
        @if ($statusFilter)
            <a href="{{ route('kondisi.index') }}" style="font-size: 12px; color: #64748b;">Reset Filter</a>
        @endif
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Barcode</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Harga Satuan</th>
                    <th>Kondisi Saat Ini</th>
                    <th>Aksi Petugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bukus as $b)
                    <tr>
                        <td><code>{{ $b->barcode->kodeBarcode ?? '-' }}</code></td>
                        <td><strong>{{ $b->judul }}</strong></td>
                        <td>{{ $b->kategori->namaKategori ?? '-' }}</td>
                        <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $b->kondisi == 'Baik' ? 'badge-baik' : ($b->kondisi == 'Rusak' ? 'badge-rusak' : 'badge-hilang') }}">
                                {{ $b->kondisi }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('kondisi.edit', $b->idBuku) }}" class="btn-check">Periksa / Ubah Kondisi</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #64748b;">Tidak ada data buku.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 15px;">
        {{ $bukus->links() }}
    </div>
</div>

</body>
</html>