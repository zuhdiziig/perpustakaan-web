<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Member - {{ $member->name }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }
        .card-container {
            width: 420px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .card-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: white;
            padding: 24px;
            text-align: center;
        }
        .card-header h2 {
            margin: 0 0 4px 0;
            font-size: 20px;
            letter-spacing: 0.5px;
        }
        .card-header p {
            margin: 0;
            font-size: 12px;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .card-body {
            padding: 28px 24px;
            text-align: center;
        }
        .qr-wrapper {
            display: inline-block;
            padding: 12px;
            background: white;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .qr-wrapper svg {
            display: block;
        }
        .member-info {
            text-align: left;
            background: #f8fafc;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .info-label {
            color: #64748b;
        }
        .info-value {
            font-weight: 600;
            color: #0f172a;
        }
        .token-badge {
            display: block;
            margin-top: 10px;
            font-family: monospace;
            font-size: 11px;
            color: #64748b;
            background: #e2e8f0;
            padding: 4px 8px;
            border-radius: 4px;
            word-break: break-all;
            text-align: center;
        }
        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }
        .btn {
            padding: 9px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-print {
            background: #2563eb;
            color: white;
        }
        .btn-back {
            background: #e2e8f0;
            color: #334155;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .card-container {
                box-shadow: none;
                border: 1px solid #ccc;
            }
            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="card-container">
    <div class="card-header">
        <h2>PERPUSTAKAAN DIGITAL</h2>
        <p>Kartu Anggota Resmi</p>
    </div>

    <div class="card-body">
        <div class="qr-wrapper">
            {!! $qrCodeSvg !!}
        </div>

        <div class="member-info">
            <div class="info-row">
                <span class="info-label">Nama Anggota</span>
                <span class="info-value">{{ $member->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $member->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">No. Telepon</span>
                <span class="info-value">{{ $member->noTelepon ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value" style="color: {{ $member->status === 'aktif' ? '#16a34a' : '#dc2626' }};">
                    {{ strtoupper($member->status) }}
                </span>
            </div>
            <div class="token-badge">
                ID QR: {{ $member->qr_token }}
            </div>
        </div>

        <div class="actions">
            <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Kartu</button>
            <a href="javascript:history.back()" class="btn btn-back">&larr; Kembali</a>
        </div>
    </div>
</div>

</body>
</html>
