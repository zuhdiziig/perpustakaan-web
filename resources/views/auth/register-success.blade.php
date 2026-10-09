<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - BOOKNEST</title>
    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
        }

        body {
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .success-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 36px 28px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.04), 0 0 1px 1px rgba(15, 23, 42, 0.02);
            border: 1px solid #e2e8f0;
        }

        @media (max-width: 480px) {
            .success-card {
                padding: 28px 18px;
                border-radius: 18px;
            }
            .card-title {
                font-size: 22px;
            }
        }

        .icon-circle {
            width: 56px;
            height: 56px;
            background-color: #dcfce7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .icon-circle svg {
            width: 28px;
            height: 28px;
            stroke: #16a34a;
        }

        .card-title {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .card-desc {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #dcfce7;
            color: #16a34a;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            background-color: #16a34a;
            border-radius: 50%;
        }

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 28px;
            padding-bottom: 4px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13.5px;
        }

        .info-label {
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            color: #0f172a;
            font-weight: 700;
            text-align: right;
        }

        .btn-login {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: #3b6d65;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 12px;
            font-size: 14.5px;
            font-weight: 700;
            transition: all 0.2s;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(59, 109, 101, 0.25);
        }

        .btn-login:hover {
            background-color: #2e554f;
            transform: translateY(-1px);
        }

        .footnote {
            text-align: center;
            font-size: 12.5px;
            color: #94a3b8;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <div class="success-card">
        <!-- Ikon Sukses Centang -->
        <div class="icon-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <h1 class="card-title">Pendaftaran Berhasil!</h1>
        <p class="card-desc">
            Selamat bergabung, {{ explode(' ', $user->name)[0] }}. Akunmu sudah aktif. Masuk untuk menemukan buku favorit dan mulai meminjam.
        </p>

        <div class="badge-status">
            <span class="badge-dot"></span>
            <span>Anggota aktif</span>
        </div>

        <div class="info-list">
            <div class="info-row">
                <span class="info-label">Nama anggota</span>
                <span class="info-value">{{ $user->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nomor anggota</span>
                <span class="info-value">{{ $user->kode_anggota }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $user->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal bergabung</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}</span>
            </div>
        </div>

        <!-- Tombol Menuju Login -->
        <a href="{{ route('login', session('url.intended') ? ['redirect' => session('url.intended')] : []) }}" class="btn-login">Masuk ke Akun</a>

        <p class="footnote">
            Simpan nomor anggota untuk pengambilan buku di perpustakaan.
        </p>
    </div>

</body>
</html>