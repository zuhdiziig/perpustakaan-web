<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label QR Eksemplar - {{ $eksemplar->buku->judul }} #{{ $eksemplar->nomor_eksemplar }}</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 24px 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .label-container {
            width: 100%;
            max-width: 380px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 2px solid #0f172a;
            padding: 20px;
            text-align: center;
        }
        .header-title {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .qr-box {
            display: inline-block;
            padding: 10px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .qr-box svg {
            display: block;
        }
        .book-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin: 0 0 6px 0;
            line-height: 1.3;
        }
        .book-meta {
            font-size: 12px;
            color: #475569;
            margin-bottom: 12px;
        }
        .eksemplar-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: bold;
            background: #dbeafe;
            color: #1e40af;
            margin-bottom: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            background: #f1f5f9;
            color: #334155;
            margin-left: 6px;
        }
        .barcode-ref {
            font-family: monospace;
            font-size: 12px;
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 8px;
            color: #1e293b;
        }
        .qr-token-text {
            font-family: monospace;
            font-size: 11px;
            color: #64748b;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            padding: 6px;
            border-radius: 4px;
            word-break: break-all;
            margin-top: 6px;
        }
        .actions {
            margin-top: 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-print {
            background: #0f172a;
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
            .label-container {
                box-shadow: none;
                margin: auto;
            }
            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="label-container">
    <div class="header-title">
        Perpustakaan Digital • Stiker Buku Fisik
    </div>

    <div>
        <span class="eksemplar-badge">Eksemplar #{{ $eksemplar->nomor_eksemplar }}</span>
        <span class="status-badge">Status: {{ $eksemplar->status }}</span>
    </div>

    <div class="qr-box">
        {!! $qrCodeSvg !!}
    </div>

    <div class="book-title">
        {{ $eksemplar->buku->judul }}
    </div>

    <div class="book-meta">
        {{ $eksemplar->buku->penulis }} • {{ $eksemplar->buku->penerbit }}
    </div>

    @if ($eksemplar->kode_barcode || $eksemplar->buku->barcode)
        <div>
            <span class="barcode-ref">Barcode: {{ $eksemplar->kode_barcode ?? $eksemplar->buku->barcode->kodeBarcode }}</span>
        </div>
    @endif

    <div class="qr-token-text">
        <strong>QR Fisik:</strong> {{ $eksemplar->qr_token }}
    </div>

    <div class="actions">
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Stiker</button>
        <a href="javascript:history.back()" class="btn btn-back">&larr; Kembali</a>
    </div>
</div>

</body>
</html>
