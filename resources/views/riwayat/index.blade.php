@extends('layouts.member')

@section('title', 'Riwayat Peminjaman - BOOKNEST')

@section('styles')
<style>
    .history-page {
        width: 100%;
        max-width: 1240px;
        margin: 0 auto;
        padding: 28px 28px 40px;
        box-sizing: border-box;
    }

    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 24px;
    }

    .history-heading {
        margin: 0;
        font-size: 28px;
        line-height: 1.2;
        font-weight: 800;
        color: #172033;
        letter-spacing: -0.5px;
    }

    .history-subtitle {
        margin: 8px 0 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }

    .history-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #ffffff;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: 0.2s ease;
        white-space: nowrap;
    }

    .history-back:hover {
        border-color: #cbd5e1;
        color: #172033;
        transform: translateY(-1px);
    }

    .history-card {
        background: #ffffff;
        border: 1px solid #e8edf3;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(15, 23, 42, 0.05);
    }

    .history-card-top {
        padding: 20px 22px;
        border-bottom: 1px solid #eef2f7;
    }

    .history-card-title {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: #172033;
    }

    .history-card-desc {
        margin: 5px 0 0;
        color: #94a3b8;
        font-size: 12px;
    }

    .history-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        min-width: 820px;
        border-collapse: collapse;
    }

    .history-table th {
        padding: 13px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e8edf3;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: left;
        white-space: nowrap;
    }

    .history-table td {
        padding: 17px 18px;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 13px;
        vertical-align: top;
    }

    .history-table tbody tr:last-child td {
        border-bottom: none;
    }

    .history-table tbody tr:hover {
        background: #fafcff;
    }

    .transaction-number {
        font-size: 12px;
        font-weight: 800;
        color: #172033;
        white-space: nowrap;
    }

    .book-list {
        display: flex;
        flex-direction: column;
        gap: 9px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .book-item {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        color: #334155;
        line-height: 1.45;
    }

    .book-icon {
        width: 28px;
        height: 28px;
        flex: 0 0 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f5f9;
        font-size: 13px;
    }

    .book-name {
        padding-top: 4px;
        font-weight: 700;
    }

    .date-main {
        color: #334155;
        font-weight: 600;
        white-space: nowrap;
    }

    .date-overdue {
        color: #dc2626;
        font-weight: 800;
    }

    .date-note {
        margin-top: 3px;
        color: #dc2626;
        font-size: 11px;
        font-weight: 700;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-borrowed {
        background: #fef3c7;
        color: #92400e;
    }

    .status-finished {
        background: #dcfce7;
        color: #166534;
    }

    .status-other {
        background: #e2e8f0;
        color: #475569;
    }

    .return-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .return-date {
        font-weight: 700;
        color: #334155;
    }

    .return-condition {
        color: #64748b;
        font-size: 11px;
    }

    .not-returned {
        color: #94a3b8;
        font-size: 12px;
        font-style: italic;
    }

    .history-empty {
        padding: 70px 25px;
        text-align: center;
    }

    .empty-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: #f1f5f9;
        font-size: 24px;
    }

    .empty-title {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 800;
    }

    .empty-text {
        max-width: 420px;
        margin: 7px auto 0;
        color: #94a3b8;
        font-size: 13px;
        line-height: 1.6;
    }

    .history-pagination {
        padding: 18px 22px;
        border-top: 1px solid #eef2f7;
    }

    .history-pagination nav {
        display: flex;
        justify-content: center;
    }

    @media (max-width: 700px) {
        .history-page {
            padding: 20px 17px 30px;
        }

        .history-header {
            align-items: flex-start;
            flex-direction: column;
            margin-bottom: 18px;
        }

        .history-heading {
            font-size: 23px;
        }

        .history-back {
            width: 100%;
            justify-content: center;
        }

        .history-card {
            border-radius: 14px;
        }

        .history-card-top {
            padding: 17px;
        }

        .history-table {
            min-width: 760px;
        }

        .history-table th,
        .history-table td {
            padding: 13px 14px;
        }
    }

    @media (max-width: 430px) {
        .history-page {
            padding-left: 14px;
            padding-right: 14px;
        }

        .history-heading {
            font-size: 21px;
        }
    }
</style>
@endsection

@section('content')
<div class="history-page">

    {{-- HEADER --}}
    <div class="history-header">
        <div>
            <h1 class="history-heading">Riwayat Peminjaman</h1>
            <p class="history-subtitle">
                Lihat seluruh riwayat peminjaman dan pengembalian buku Anda.
            </p>
        </div>

        <a href="{{ route('dashboard') }}" class="history-back">
            ← Kembali ke Beranda
        </a>
    </div>

    {{-- HISTORY CARD --}}
    <div class="history-card">

        <div class="history-card-top">
            <h2 class="history-card-title">Aktivitas Peminjaman</h2>
            <p class="history-card-desc">
                Daftar transaksi buku yang pernah Anda pinjam.
            </p>
        </div>

        @if ($riwayats->count())

            <div class="history-table-wrap">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Pengembalian</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($riwayats as $item)

                            @php
                                $tanggalPinjam = $item->tanggalPinjam
                                    ? \Carbon\Carbon::parse($item->tanggalPinjam)
                                    : null;

                                $batasKembali = $item->batasKembali
                                    ? \Carbon\Carbon::parse($item->batasKembali)
                                    : null;

                                $isOverdue =
                                    $batasKembali &&
                                    \Carbon\Carbon::now()->greaterThan($batasKembali) &&
                                    $item->status === 'Dipinjam';
                            @endphp

                            <tr>

                                {{-- TRANSACTION --}}
                                <td>
                                    <div class="transaction-number">
                                        #TRX-{{ str_pad($item->idPeminjaman, 5, '0', STR_PAD_LEFT) }}
                                    </div>
                                </td>

                                {{-- BOOKS --}}
                                <td>
                                    <ul class="book-list">
                                        @foreach ($item->details as $detail)
                                            <li class="book-item">
                                                <span class="book-icon">📖</span>

                                                <span class="book-name">
                                                    {{ $detail->buku->judul ?? 'Buku tidak tersedia' }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                {{-- BORROW DATE --}}
                                <td>
                                    @if ($tanggalPinjam)
                                        <span class="date-main">
                                            {{ $tanggalPinjam->translatedFormat('d M Y') }}
                                        </span>
                                    @else
                                        <span class="not-returned">-</span>
                                    @endif
                                </td>

                                {{-- DUE DATE --}}
                                <td>
                                    @if ($batasKembali)
                                        <span class="{{ $isOverdue ? 'date-overdue' : 'date-main' }}">
                                            {{ $batasKembali->translatedFormat('d M Y') }}
                                        </span>

                                        @if ($isOverdue)
                                            <div class="date-note">
                                                Terlambat dikembalikan
                                            </div>
                                        @endif
                                    @else
                                        <span class="not-returned">-</span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @if ($item->status === 'Dipinjam')
                                        <span class="status-badge status-borrowed">
                                            Sedang Dipinjam
                                        </span>
                                    @elseif ($item->status === 'Selesai')
                                        <span class="status-badge status-finished">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="status-badge status-other">
                                            {{ $item->status ?? '-' }}
                                        </span>
                                    @endif
                                </td>

                                {{-- RETURN --}}
                                <td>
                                    @if ($item->pengembalians->isNotEmpty())
                                        <div class="return-info">

                                            @foreach ($item->pengembalians as $ret)
                                                <span class="return-date">
                                                    {{ \Carbon\Carbon::parse($ret->tanggalKembali)->translatedFormat('d M Y') }}
                                                </span>

                                                @if ($ret->kondisiBuku)
                                                    <span class="return-condition">
                                                        Kondisi: {{ $ret->kondisiBuku }}
                                                    </span>
                                                @endif
                                            @endforeach

                                        </div>
                                    @else
                                        <span class="not-returned">
                                            Belum dikembalikan
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="history-pagination">
                {{ $riwayats->links() }}
            </div>

        @else

            {{-- EMPTY STATE --}}
            <div class="history-empty">
                <div class="empty-icon">📚</div>

                <h3 class="empty-title">
                    Belum Ada Riwayat Peminjaman
                </h3>

                <p class="empty-text">
                    Anda belum memiliki transaksi peminjaman buku.
                    Yuk, cari buku yang ingin dibaca di katalog.
                </p>
            </div>

        @endif

    </div>

</div>
@endsection