<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Member - Perpustakaan</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; background: #f4f6f8; padding: 20px 0; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 360px; }
        .form-group { margin-bottom: 12px; }
        label { display: block; margin-bottom: 4px; font-weight: bold; font-size: 14px; }
        input[type="text"], input[type="email"], input[type="password"], textarea { 
            width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; 
        }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        .error { color: #dc3545; font-size: 12px; margin-top: 2px; display: block; }
        .footer-text { text-align: center; margin-top: 15px; font-size: 13px; }
    </style>
</head>
<body>

<div class="card">
    <h2 style="text-align: center; margin-top: 0;">Registrasi Member</h2>

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Nomor Telepon</label>
            <input type="text" name="noTelepon" value="{{ old('noTelepon') }}" placeholder="0812..." required>
            @error('noTelepon') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Alamat Domisili</label>
            <textarea name="alamat" rows="2" required>{{ old('alamat') }}</textarea>
            @error('alamat') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
            @error('password') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit">Daftar Sekarang</button>
    </form>

    <div class="footer-text">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk disini</a>
    </div>
</div>

</body>
</html>