@extends('layouts.anggota')

@section('title', 'Denda Saya - BOOKNEST')

@section('styles')
<style>
    .denda-page {
        width: 100%;
    }

    .denda-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 16px;
        margin-bottom: 22px;
    }

    .denda-heading {
        margin: 0;
        font-size: 26px;
        line-height: 1.2;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.5px;
    }

    .denda-subtitle {
        margin: 6px 0 0;
        color: var(--text-muted);
        font-size: 13.5px;
    }

    .summary-box {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .summary-box.clean {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .summary-nominal {
        font-size: 26px;
        font-weight: 800;
        color: #dc2626;
        margin-top: 4px;
        letter-spacing: -0.5px;
    }

    .denda-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .denda-card-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 16px;
    }

    .denda-table-wrap {
        overflow-x: auto;
    }

    .denda-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        text-align: left;
    }

    .denda-table th {
        background: #ffffff;
        padding: 12px 14px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        border-bottom: 1px solid #f1f5f9;
    }

    .denda-table td {
        padding: 14px;
        color: #334155;
        border-bottom: 1px solid #f8fafc;
        vertical-align: middle;
    }

    .denda-table tr:hover td {
        background-color: #fbfcfd;
    }

    .badge-denda {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 11px;
    }

    .badge-denda.lunas {
        background: #dcfce7;
        color: #166534;
    }

    .badge-denda.belum {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-pay-qr {
        background: #0f766e;
        color: #ffffff;
        padding: 7px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        transition: background 0.15s;
    }

    .btn-pay-qr:hover {
        background: #115e59;
    }
</style>
@endsection

@section('content')
<div class="denda-page">

    <div class="denda-header">
        <div>
            <h1 class="denda-heading">Data Denda & Status Tagihan</h1>
            <p class="denda-subtitle">
                Member: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->kode_anggota }})
            </p>
        </div>

        <a href="{{ route('dashboard') }}" style="color: #0f766e; font-size: 13px; font-weight: 700;">
            &larr; Kembali ke Dasbor
        </a>
    </div>

    {{-- Ringkasan Tunggakan --}}
    @if ($totalTunggakan > 0)
        <div class="summary-box">
            <div>
                <span style="color: #991b1b; font-size: 13px; font-weight: 700;">Total Tunggakan Belum Dibayar:</span>
                <div class="summary-nominal">
                    Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                </div>
            </div>
            <span style="font-size: 12.5px; color: #7f1d1d;">Silakan lunasi tagihan menggunakan QRIS di tombol aksi.</span>
        </div>
    @else
        <div class="summary-box clean">
            <div>
                <strong style="color: #166534; font-size: 15px;">Tidak ada tanggungan denda!</strong>
                <div style="font-size: 13px; color: #15803d; margin-top: 2px;">Akun Anda bersih dari denda keterlambatan maupun ganti rugi fisik buku.</div>
            </div>
        </div>
    @endif

    <div class="denda-card">
        <h3 class="denda-card-title">Rincian Riwayat Denda</h3>

        <div class="denda-table-wrap">
            <table class="denda-table">
                <thead>
                    <tr>
                        <th style="width: 120px;">ID Denda</th>
                        <th>Judul Buku Terkait</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Nominal</th>
                        <th style="width: 130px;">Status</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dendas as $item)
                        <tr>
                            <td><strong>#DND-{{ str_pad($item->idDenda, 5, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>
                                @php
                                    $bukuJudul = $item->pengembalian->peminjaman->details->first()->buku->judul ?? 'Buku Perpustakaan';
                                @endphp
                                <strong>{{ $bukuJudul }}</strong>
                            </td>
                            <td>{{ $item->jenisDenda }}</td>
                            <td><strong style="color: #dc2626;">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="badge-denda {{ $item->status === 'Lunas' ? 'lunas' : 'belum' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td>
                                @if ($item->status === 'Belum Dibayar')
                                    <a href="{{ route('bayar.qr', $item->idDenda) }}" class="btn-pay-qr">Bayar QR &rarr;</a>
                                @else
                                    <span style="color: #16a34a; font-size: 12px; font-weight: 700;">Lunas Terverifikasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #94a3b8; padding: 36px 20px;">
                                Belum ada catatan riwayat denda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $dendas->links() }}
        </div>
    </div>
</div>
@endsection