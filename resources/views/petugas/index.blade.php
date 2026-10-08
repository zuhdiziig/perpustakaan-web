<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Petugas</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; margin: 0; padding: 15px; }
        body { font-family: sans-serif; background: #f8fafc; }
        .card { background: white; padding: 20px 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 100%; max-width: 1000px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 1.25rem; }
        .btn { padding: 8px 14px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; border: none; cursor: pointer; display: inline-block; white-space: nowrap; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-warning { background: #d97706; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; margin-top: 10px; }
        table { width: 100%; min-width: 650px; border-collapse: collapse; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 13px; }
        th { background: #f1f5f9; }
        .alert-success { background: #dcfce7; color: #166534; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; }
    </style>
</head>
<body>

<div class="card">
    <div class="header">
        <h2>Daftar Petugas Perpustakaan (Admin)</h2>
        <div>
            <a href="{{ route('petugas.create') }}" class="btn btn-primary">+ Tambah Petugas</a>
            <a href="{{ route('dashboard') }}" style="margin-left: 10px; font-size: 13px; color: #64748b; text-decoration: none;">&larr; Dashboard</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Petugas</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                    <th>Alamat</th>
                    <th style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($petugas as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $p->name }}</strong></td>
                        <td>{{ $p->email }}</td>
                        <td>{{ $p->noTelepon ?? '-' }}</td>
                        <td>{{ $p->alamat ?? '-' }}</td>
                        <td>
                            <a href="{{ route('petugas.edit', $p->id) }}" class="btn btn-warning" style="padding: 4px 8px;">Ubah</a>
                            <form action="{{ route('petugas.destroy', $p->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus petugas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 4px 8px;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #64748b;">Belum ada akun petugas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 15px;">
        {{ $petugas->links() }}
    </div>
</div>

</body>
</html>