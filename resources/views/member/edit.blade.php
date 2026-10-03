<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Data Member</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .card { background: white; max-width: 500px; margin: auto; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; font-size: 13px; }
        input, textarea, select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 4px; }
        .btn-submit { background: #d97706; color: white; padding: 10px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 10px; }
        .error { color: #dc2626; font-size: 12px; }
    </style>
</head>
<body>

<div class="card">
    <h3 style="margin-top: 0;">Ubah Data Member</h3>
    <form action="{{ route('member.update', $member->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $member->name) }}" required>
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $member->email) }}" required>
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Password Baru (Kosongkan jika tidak diubah)</label>
            <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">
            @error('password') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Nomor Telepon</label>
            <input type="text" name="noTelepon" value="{{ old('noTelepon', $member->noTelepon) }}" required>
            @error('noTelepon') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Alamat Domisili</label>
            <textarea name="alamat" rows="2" required>{{ old('alamat', $member->alamat) }}</textarea>
            @error('alamat') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Status Akun</label>
            <select name="status">
                <option value="aktif" {{ old('status', $member->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status', $member->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <button type="submit" class="btn-submit">Simpan Perubahan</button>
        <a href="{{ route('member.index') }}" style="display:block; text-align:center; margin-top:10px; color:#64748b; text-decoration:none; font-size:13px;">Batal</a>
    </form>
</div>

</body>
</html>