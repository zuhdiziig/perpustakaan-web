<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Label QR Semua Eksemplar - {{ $buku->judul }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 30px 20px;
            box-sizing: border-box;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .grid-labels {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        .label-container {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            border: 2px solid #0f172a;
            padding: 16px;
            text-align: center;
            page-break-inside: avoid;
        }
        .header-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .eksemplar-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: bold;
            background: #dbeafe;
            color: #1e40af;
            margin-bottom: 8px;
        }
        .qr-box {
            display: inline-block;
            padding: 8px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        .qr-box svg {
            display: block;
        }
        .book-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin: 0 0 4px 0;
            line-height: 1.3;
        }
        .book-meta {
            font-size: 11px;
            color: #475569;
            margin-bottom: 8px;
        }
        .qr-token-text {
            font-family: monospace;
            font-size: 9px;
            color: #64748b;
            word-break: break-all;
            background: #f8fafc;
            padding: 4px;
            border-radius: 4px;
            border: 1px dashed #cbd5e1;
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
        .btn-print { background: #0f172a; color: white; }
        .btn-back { background: #e2e8f0; color: #334155; }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .top-bar {
                display: none;
            }
            .grid-labels {
                gap: 15px;
            }
            .label-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="top-bar">
        <div>
            <h3 style="margin: 0; color: #0f172a;">Label QR Eksemplar Fisik: {{ $buku->judul }}</h3>
            <span style="font-size: 13px; color: #64748b;">Total {{ count($buku->eksemplar) }} Eksemplar Terdaftar</span>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Semua Stiker</button>
            <a href="{{ route('buku.index') }}" class="btn btn-back">&larr; Kembali</a>
        </div>
    </div>

    <div class="grid-labels">
        @forelse ($buku->eksemplar as $eks)
            <div class="label-container">
                <div class="header-title">
                    Perpustakaan Digital • Stiker Buku Fisik
                </div>

                <div class="eksemplar-badge">
                    Eksemplar #{{ $eks->nomor_eksemplar }} ({{ $eks->status }})
                </div>

                <div class="qr-box">
                    {!! $eksemplarQr[$eks->idEksemplar] ?? '' !!}
                </div>

                <div class="book-title">
                    {{ $buku->judul }}
                </div>

                <div class="book-meta">
                    {{ $buku->penulis }} • {{ $buku->penerbit }}
                </div>

                @if ($eks->kode_barcode || $buku->barcode)
                    <div style="font-family: monospace; font-size: 11px; margin-bottom: 6px; color: #334155;">
                        Barcode: {{ $eks->kode_barcode ?? $buku->barcode->kodeBarcode }}
                    </div>
                @endif

                <div class="qr-token-text">
                    {{ $eks->qr_token }}
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; background: white; border-radius: 8px; border: 1px dashed #cbd5e1; color: #64748b;">
                <p style="font-size: 16px; font-weight: bold; margin-bottom: 6px; color: #334155;">Belum Ada Eksemplar Fisik</p>
                <p style="font-size: 13px; margin: 0;">Buku ini saat ini memiliki stok 0 atau belum didaftarkan eksemplar fisiknya.</p>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>
