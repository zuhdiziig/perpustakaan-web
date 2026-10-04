<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Member</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .btn { padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; border: none; cursor: pointer; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-warning { background: #d97706; color: white; }
        .btn-status-aktif { background: #fee2e2; color: #991b1b; }
        .btn-status-nonaktif { background: #dcfce7; color: #166534; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 14px; }
        th { background: #f1f5f9; }
        .badge { padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .badge-aktif { background: #dcfce7; color: #166534; }
        .badge-nonaktif { background: #fee2e2; color: #991b1b; }
        .alert-success { background: #dcfce7; color: #166534; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="card">
    <div class="header">
        <h2>Daftar Member Perpustakaan (Admin)</h2>
        <div>
            <a href="{{ route('member.create') }}" class="btn btn-primary">+ Tambah Member</a>
            <a href="{{ route('dashboard') }}" style="margin-left: 10px; font-size: 13px; color: #64748b; text-decoration: none;">&larr; Dashboard</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Nama Member</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Status</th>
                <th style="width: 220px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($members as $m)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $m->name }}</strong></td>
                    <td>{{ $m->email }}</td>
                    <td>{{ $m->noTelepon ?? '-' }}</td>
                    <td>{{ $m->alamat ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $m->status === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                            {{ ucfirst($m->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('member.cetak-qr', $m->id) }}" target="_blank" class="btn" style="background: #0284c7; color: white;">Kartu QR</a>
                        <a href="{{ route('member.edit', $m->id) }}" class="btn btn-warning">Ubah</a>

                        {{-- Tombol Toggle Aktif / Nonaktif --}}
                        <form action="{{ route('member.toggle-status', $m->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Ubah status member ini?');">
                            @csrf
                            @method('PATCH')
                            @if ($m->status === 'aktif')
                                <button type="submit" class="btn btn-status-aktif">Nonaktifkan</button>
                            @else
                                <button type="submit" class="btn btn-status-nonaktif">Aktifkan</button>
                            @endif
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #64748b;">Belum ada member terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        {{ $members->links() }}
    </div>
</div>

</body>
</html>