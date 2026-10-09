<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kartu Member - {{ $member->name }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 32px 18px;
            display: flex;
            justify-content: center;
            align-items: center;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Helvetica,
                Arial,
                sans-serif;

            background:
                radial-gradient(
                    circle at top,
                    #eff6ff 0%,
                    #f8fafc 45%,
                    #eef2f7 100%
                );

            color: #172033;
        }

        .member-page {
            width: 100%;
            max-width: 460px;
        }

        /* =========================
           MEMBER CARD
        ========================= */

        .member-card {
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            box-shadow:
                0 20px 45px rgba(15, 23, 42, 0.10),
                0 5px 15px rgba(15, 23, 42, 0.05);
        }

        /* =========================
           HEADER
        ========================= */

        .member-header {
            position: relative;
            padding: 28px 25px 26px;

            background:
                linear-gradient(
                    135deg,
                    #172554 0%,
                    #1d4ed8 55%,
                    #2563eb 100%
                );

            color: #ffffff;
            text-align: center;
        }

        .member-header::after {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            right: -65px;
            top: -75px;

            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .brand {
            position: relative;
            z-index: 1;

            margin: 0;
            font-size: 21px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .brand-subtitle {
            position: relative;
            z-index: 1;

            margin: 6px 0 0;

            color: rgba(255, 255, 255, 0.78);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.4px;
        }

        /* =========================
           BODY
        ========================= */

        .member-body {
            padding: 28px 25px 25px;
            text-align: center;
        }

        /* =========================
           AVATAR
        ========================= */

        .member-avatar {
            width: 72px;
            height: 72px;

            margin: -62px auto 18px;

            position: relative;
            z-index: 3;

            display: flex;
            align-items: center;
            justify-content: center;

            overflow: hidden;

            border: 4px solid #ffffff;
            border-radius: 50%;

            background: #dbeafe;
            color: #1d4ed8;

            font-size: 25px;
            font-weight: 800;

            box-shadow: 0 5px 15px rgba(15, 23, 42, 0.12);
        }

        .member-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* =========================
           MEMBER NAME
        ========================= */

        .member-name {
            margin: 0;

            color: #172033;
            font-size: 21px;
            font-weight: 800;
            line-height: 1.3;
        }

        .member-role {
            margin: 5px 0 0;

            color: #64748b;
            font-size: 12px;
        }

        /* =========================
           STATUS
        ========================= */

        .member-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            margin-top: 13px;
            padding: 6px 11px;

            border-radius: 999px;

            font-size: 11px;
            font-weight: 800;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /* =========================
           QR
        ========================= */

        .qr-section {
            margin-top: 24px;
        }

        .qr-title {
            margin: 0 0 5px;

            color: #172033;
            font-size: 13px;
            font-weight: 800;
        }

        .qr-description {
            margin: 0 0 15px;

            color: #94a3b8;
            font-size: 11px;
            line-height: 1.5;
        }

        .qr-wrapper {
            width: fit-content;

            margin: 0 auto;
            padding: 14px;

            background: #ffffff;

            border: 1px solid #e2e8f0;
            border-radius: 16px;

            box-shadow:
                0 5px 18px rgba(15, 23, 42, 0.06);
        }

        .qr-wrapper svg {
            display: block;

            width: 210px;
            height: 210px;
        }

        /* =========================
           MEMBER INFO
        ========================= */

        .member-info {
            margin-top: 22px;

            padding: 15px 16px;

            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 14px;

            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 15px;

            padding: 9px 0;

            border-bottom: 1px solid #e2e8f0;
        }

        .info-row:first-child {
            padding-top: 0;
        }

        .info-row:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            color: #94a3b8;
            font-size: 11px;
            white-space: nowrap;
        }

        .info-value {
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            text-align: right;

            overflow-wrap: anywhere;
        }

        /* =========================
           INSTRUCTION
        ========================= */

        .scan-note {
            margin: 18px 0 0;
            padding: 11px 13px;

            border-radius: 10px;

            background: #eff6ff;
            color: #1e40af;

            font-size: 11px;
            line-height: 1.55;
        }

        /* =========================
           ACTIONS
        ========================= */

        .actions {
            display: flex;
            gap: 10px;

            margin-top: 22px;
        }

        .btn {
            flex: 1;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-height: 42px;
            padding: 10px 15px;

            border-radius: 10px;

            font-size: 12px;
            font-weight: 800;

            text-decoration: none;
            cursor: pointer;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-print {
            border: none;

            background: #2563eb;
            color: #ffffff;

            box-shadow: 0 5px 12px rgba(37, 99, 235, 0.18);
        }

        .btn-print:hover {
            background: #1d4ed8;
        }

        .btn-back {
            border: 1px solid #e2e8f0;

            background: #ffffff;
            color: #475569;
        }

        .btn-back:hover {
            background: #f8fafc;
        }

        /* =========================
           FOOTER
        ========================= */

        .member-footer {
            padding: 15px 20px 20px;

            color: #94a3b8;

            font-size: 10px;
            line-height: 1.5;

            text-align: center;
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 520px) {
            body {
                padding: 20px 12px;
                align-items: flex-start;
            }

            .member-card {
                border-radius: 20px;
            }

            .member-header {
                padding: 24px 20px 23px;
            }

            .member-body {
                padding: 24px 18px 20px;
            }

            .member-avatar {
                width: 66px;
                height: 66px;

                margin-top: -58px;
            }

            .member-name {
                font-size: 19px;
            }

            .qr-wrapper {
                padding: 11px;
            }

            .qr-wrapper svg {
                width: 185px;
                height: 185px;
            }

            .info-row {
                align-items: flex-start;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        /* =========================
           PRINT
        ========================= */

        @media print {
            @page {
                size: auto;
                margin: 10mm;
            }

            body {
                min-height: auto;
                padding: 0;
                background: #ffffff;
            }

            .member-page {
                max-width: 460px;
            }

            .member-card {
                box-shadow: none;
                border: 1px solid #cbd5e1;
            }

            .actions {
                display: none;
            }

            .member-footer {
                padding-bottom: 5px;
            }
        }
    </style>
</head>

<body>

<div class="member-page">

    <div class="member-card">

        {{-- HEADER --}}
        <div class="member-header">
            <h1 class="brand">BOOKNEST</h1>

            <p class="brand-subtitle">
                Kartu Anggota Perpustakaan
            </p>
        </div>

        <div class="member-body">

            {{-- AVATAR --}}
            <div class="member-avatar">
                @if (!empty($member->foto))
                    <img
                        src="{{ asset('storage/' . $member->foto) }}"
                        alt="Foto {{ $member->name }}"
                    >
                @else
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                @endif
            </div>

            {{-- MEMBER NAME --}}
            <h2 class="member-name">
                {{ $member->name }}
            </h2>

            <p class="member-role">
                Anggota BOOKNEST
            </p>

            {{-- STATUS --}}
            @if ($member->status === 'aktif')
                <span class="member-status status-active">
                    <span class="status-dot"></span>
                    Keanggotaan Aktif
                </span>
            @else
                <span class="member-status status-inactive">
                    <span class="status-dot"></span>
                    {{ ucfirst($member->status ?? 'Tidak Aktif') }}
                </span>
            @endif

            {{-- QR --}}
            <div class="qr-section">

                <p class="qr-title">
                    QR Code Anggota
                </p>

                <p class="qr-description">
                    Tunjukkan QR Code ini kepada petugas saat proses peminjaman atau pengembalian.
                </p>

                <div class="qr-wrapper">
                    {!! $qrCodeSvg !!}
                </div>

            </div>

            {{-- MEMBER INFO --}}
            <div class="member-info">

                <div class="info-row">
                    <span class="info-label">
                        Nama
                    </span>

                    <span class="info-value">
                        {{ $member->name }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        Email
                    </span>

                    <span class="info-value">
                        {{ $member->email }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        No. Telepon
                    </span>

                    <span class="info-value">
                        {{ $member->noTelepon ?? '-' }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        Status
                    </span>

                    <span class="info-value">
                        {{ ucfirst($member->status ?? '-') }}
                    </span>
                </div>

            </div>

            {{-- INSTRUCTION --}}
            <div class="scan-note">
                <strong>Tips:</strong>
                Pastikan QR Code tetap terlihat jelas ketika dipindai oleh petugas.
            </div>

            {{-- ACTIONS --}}
            <div class="actions">

                <button
                    type="button"
                    onclick="window.print()"
                    class="btn btn-print"
                >
                    🖨️ Cetak Kartu
                </button>

                <a
                    href="{{ route('dashboard') }}"
                    class="btn btn-back"
                >
                    ← Kembali
                </a>

            </div>

        </div>

        {{-- FOOTER --}}
        <div class="member-footer">
            Kartu anggota ini digunakan sebagai identitas digital
            anggota perpustakaan BOOKNEST.
        </div>

    </div>

</div>

{{-- Realtime Sirkulasi Notification Modal & Polling --}}
@include('layouts.partials.member_feedback_toasts')
@include('layouts.partials.member_realtime_notification')

</body>
</html>
