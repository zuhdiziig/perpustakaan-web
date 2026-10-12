<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PembayaranController extends Controller
{
    /**
     * Fitur 18: Web QRIS Menunggu (Sesuai Figma Frame 18)
     */
    public function bayarQr(int $idDenda): View|RedirectResponse
    {
        $denda = $this->findOwnedDenda($idDenda);

        if ($denda->status === 'Lunas') {
            $pembayaran = $denda->pembayaran()
                ->where('status', 'Sukses')
                ->orderByDesc('idPembayaran')
                ->first();

            if ($pembayaran && $this->paymentMatchesFine($pembayaran, $denda)) {
                return redirect()->route('bayar.sukses', $pembayaran->idPembayaran);
            }

            return redirect()->route('denda.saya')
                ->with('error', 'Denda tercatat lunas, tetapi pembayaran tidak dapat diverifikasi. Silakan hubungi petugas.');
        }

        if ($denda->status !== 'Belum Dibayar' || ! $this->isPositiveAmount((string) $denda->jumlah)) {
            return redirect()->route('denda.saya')
                ->with('error', 'Status atau nominal denda tidak valid untuk pembayaran. Silakan hubungi petugas.');
        }

        $pembayaran = DB::transaction(function () use ($denda): ?Pembayaran {
            $lockedDenda = Denda::query()
                ->whereKey($denda->idDenda)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedDenda->status !== 'Belum Dibayar') {
                return null;
            }

            $pembayaran = $lockedDenda->pembayaran()
                ->where('status', 'Pending')
                ->orderBy('idPembayaran')
                ->lockForUpdate()
                ->first();

            if (! $pembayaran) {
                $pembayaran = $lockedDenda->pembayaran()->create([
                    'tanggalBayar' => now(),
                    'nominal' => $lockedDenda->jumlah,
                    'metode' => 'QRIS',
                    'status' => 'Pending',
                ]);
            }

            return $pembayaran;
        });

        if (! $pembayaran) {
            return redirect()->route('denda.saya')
                ->with('error', 'Status denda berubah. Muat ulang halaman sebelum melanjutkan.');
        }

        if (! $this->paymentMatchesFine($pembayaran, $denda)) {
            return redirect()->route('denda.saya')
                ->with('error', 'Nominal pembayaran tidak sesuai dengan denda. Silakan hubungi petugas.');
        }

        $pengembalian = $denda->pengembalian;
        $peminjaman = $pengembalian?->peminjaman ?? $denda->details->first()?->peminjaman;
        $member = $peminjaman?->member ?? auth()->user();
        $buku = $peminjaman?->details?->first()?->buku;

        // Metadata ID & Kode
        $idPembayaranText = 'QR-'.Carbon::parse($pembayaran->created_at)->format('Ymd').'-'.str_pad($pembayaran->idPembayaran, 4, '0', STR_PAD_LEFT);
        $nomorAnggota = $member?->kode_anggota ?? ('AG-'.date('Y').'-'.str_pad($member?->id ?? 1, 5, '0', STR_PAD_LEFT));
        $kodeBuku = $buku?->barcode?->kodeBarcode ?? ('BK-'.str_pad($buku?->idBuku ?? 1, 5, '0', STR_PAD_LEFT));

        // Kalkulasi keterlambatan
        $hariTerlambat = 0;
        if ($pengembalian && $peminjaman && $pengembalian->tanggalKembali && $peminjaman->batasKembali) {
            $tglKembali = Carbon::parse($pengembalian->tanggalKembali);
            $batas = Carbon::parse($peminjaman->batasKembali);
            if ($tglKembali->greaterThan($batas)) {
                $hariTerlambat = (int) $batas->diffInDays($tglKembali);
            }
        }
        if ($hariTerlambat === 0 && $denda->jumlah > 0) {
            $hariTerlambat = max(1, (int) round($denda->jumlah / 1000));
        }

        $berlakuHingga = now()->addMinutes(15)->locale('id')->translatedFormat('d M Y, H.i').' WIB';

        // QRIS offline-first generation
        $qrData = 'PERPUS-QRIS-'.$pembayaran->idPembayaran.'-NOMINAL-'.$pembayaran->nominal;
        $qrSvg = (string) QrCode::size(260)->generate($qrData);
        $qrImageUrl = 'data:image/svg+xml;base64,'.base64_encode($qrSvg);

        return view('pembayaran.bayar_qr', compact(
            'denda',
            'pembayaran',
            'pengembalian',
            'peminjaman',
            'member',
            'buku',
            'idPembayaranText',
            'nomorAnggota',
            'kodeBuku',
            'hariTerlambat',
            'berlakuHingga',
            'qrImageUrl'
        ));
    }

    /**
     * Konfirmasi / Cek Status Pembayaran QRIS
     */
    public function prosesBayarQr(int $idPembayaran): RedirectResponse
    {
        $pembayaran = $this->findOwnedPembayaran($idPembayaran);
        $denda = $pembayaran->denda;

        if ($pembayaran->status === 'Sukses' && $denda?->status === 'Lunas' && $this->paymentMatchesFine($pembayaran, $denda)) {
            return redirect()->route('bayar.sukses', $pembayaran->idPembayaran);
        }

        if ($pembayaran->status !== 'Pending' || $denda?->status !== 'Belum Dibayar' || ! $this->paymentMatchesFine($pembayaran, $denda)) {
            return back()->with('error', 'Status atau nominal transaksi tidak valid. Pembayaran tidak diubah.');
        }

        return back()->with('error', 'Pembayaran belum dapat diverifikasi karena integrasi payment gateway belum tersedia. Status pembayaran tidak diubah.');
    }

    /**
     * Fitur 19: Web QRIS Berhasil (Sesuai Figma Frame 19)
     */
    public function sukses(int $idPembayaran): View
    {
        $pembayaran = $this->findOwnedPembayaran($idPembayaran);

        abort_unless($this->isPaymentConfirmed($pembayaran), 404);

        $denda = $pembayaran->denda;
        $pengembalian = $denda?->pengembalian;
        $peminjaman = $pengembalian?->peminjaman ?? $denda?->details->first()?->peminjaman;
        $member = $peminjaman?->member ?? auth()->user();
        $buku = $peminjaman?->details?->first()?->buku;

        $idPembayaranText = 'QR-'.Carbon::parse($pembayaran->created_at)->format('Ymd').'-'.str_pad($pembayaran->idPembayaran, 4, '0', STR_PAD_LEFT);
        $nomorAnggota = $member?->kode_anggota ?? ('AG-'.date('Y').'-'.str_pad($member?->id ?? 1, 5, '0', STR_PAD_LEFT));
        $kodeBuku = $buku?->barcode?->kodeBarcode ?? ('BK-'.str_pad($buku?->idBuku ?? 1, 5, '0', STR_PAD_LEFT));

        $hariTerlambat = 0;
        if ($pengembalian && $peminjaman && $pengembalian->tanggalKembali && $peminjaman->batasKembali) {
            $tglKembali = Carbon::parse($pengembalian->tanggalKembali);
            $batas = Carbon::parse($peminjaman->batasKembali);
            if ($tglKembali->greaterThan($batas)) {
                $hariTerlambat = (int) $batas->diffInDays($tglKembali);
            }
        }
        if ($hariTerlambat === 0 && $denda?->jumlah > 0) {
            $hariTerlambat = max(1, (int) round($denda->jumlah / 1000));
        }

        $waktuPembayaranText = Carbon::parse($pembayaran->tanggalBayar ?? now())->locale('id')->translatedFormat('d M Y, H.i').' WIB';
        $namaPanggilan = explode(' ', trim((string) $member?->name))[0] ?? 'Anggota';

        return view('pembayaran.sukses', compact(
            'pembayaran',
            'denda',
            'pengembalian',
            'peminjaman',
            'member',
            'buku',
            'idPembayaranText',
            'nomorAnggota',
            'kodeBuku',
            'hariTerlambat',
            'waktuPembayaranText',
            'namaPanggilan'
        ));
    }

    /**
     * Fitur 20: Web Nota Pembayaran Resmi (Sesuai Figma Frame 20)
     */
    public function nota(int $idPembayaran): View
    {
        $pembayaran = $this->findOwnedPembayaran($idPembayaran);

        abort_unless($this->isPaymentConfirmed($pembayaran), 404);

        $denda = $pembayaran->denda;
        $pengembalian = $denda?->pengembalian;
        $peminjaman = $pengembalian?->peminjaman ?? $denda?->details->first()?->peminjaman;
        $member = $peminjaman?->member ?? auth()->user();
        $buku = $peminjaman?->details?->first()?->buku;

        $nomorNota = 'NT-'.Carbon::parse($pembayaran->tanggalBayar ?? $pembayaran->created_at)->format('Ymd').'-'.str_pad($pembayaran->idPembayaran, 4, '0', STR_PAD_LEFT);
        $idPembayaranText = 'QR-'.Carbon::parse($pembayaran->created_at)->format('Ymd').'-'.str_pad($pembayaran->idPembayaran, 4, '0', STR_PAD_LEFT);
        $tanggalPembayaranText = Carbon::parse($pembayaran->tanggalBayar ?? now())->locale('id')->translatedFormat('d M Y, H.i').' WIB';

        $kodeTransaksiPeminjaman = $peminjaman?->kode_transaksi ?? ('PJ-'.date('Ymd').'-'.str_pad($peminjaman?->idPeminjaman ?? 1, 4, '0', STR_PAD_LEFT));
        $nomorAnggota = $member?->kode_anggota ?? ('AG-'.date('Y').'-'.str_pad($member?->id ?? 1, 5, '0', STR_PAD_LEFT));
        $kodeBuku = $buku?->barcode?->kodeBarcode ?? ('BK-'.str_pad($buku?->idBuku ?? 1, 5, '0', STR_PAD_LEFT));

        $tglPinjamText = $peminjaman?->tanggalPinjam ? Carbon::parse($peminjaman->tanggalPinjam)->locale('id')->translatedFormat('d M Y') : '-';
        $jatuhTempoText = $peminjaman?->batasKembali ? Carbon::parse($peminjaman->batasKembali)->locale('id')->translatedFormat('d M Y') : '-';
        $tglKembaliText = $pengembalian?->tanggalKembali ? Carbon::parse($pengembalian->tanggalKembali)->locale('id')->translatedFormat('d M Y') : '-';

        $hariTerlambat = 0;
        if ($pengembalian && $peminjaman && $pengembalian->tanggalKembali && $peminjaman->batasKembali) {
            $tglKembali = Carbon::parse($pengembalian->tanggalKembali);
            $batas = Carbon::parse($peminjaman->batasKembali);
            if ($tglKembali->greaterThan($batas)) {
                $hariTerlambat = (int) $batas->diffInDays($tglKembali);
            }
        }
        if ($hariTerlambat === 0 && $denda?->jumlah > 0) {
            $hariTerlambat = max(1, (int) round($denda->jumlah / 1000));
        }

        return view('pembayaran.nota', compact(
            'pembayaran',
            'denda',
            'pengembalian',
            'peminjaman',
            'member',
            'buku',
            'nomorNota',
            'idPembayaranText',
            'tanggalPembayaranText',
            'kodeTransaksiPeminjaman',
            'nomorAnggota',
            'kodeBuku',
            'tglPinjamText',
            'jatuhTempoText',
            'tglKembaliText',
            'hariTerlambat'
        ));
    }

    // Aktor: Buka daftar pembayaran
    public function index()
    {
        $pembayarans = Pembayaran::with(['denda.pengembalian.peminjaman.member'])
            ->orderBy('idPembayaran', 'desc')
            ->get();

        return view('pembayaran.index', compact('pembayarans'));
    }

    // Aktor minta status transaksi -> Sistem kirim ke Gateway -> Evaluasi status
    public function verifikasi(int $idPembayaran): RedirectResponse
    {
        $pembayaran = Pembayaran::with('denda')->findOrFail($idPembayaran);

        if ($this->isPaymentConfirmed($pembayaran)) {
            return back()->with('success', 'Pembayaran ini sudah tercatat lunas sebelumnya.');
        }

        if ($pembayaran->status !== 'Pending' || $pembayaran->denda?->status !== 'Belum Dibayar' || ! $this->paymentMatchesFine($pembayaran, $pembayaran->denda)) {
            return back()->with('error', 'Status atau nominal transaksi tidak valid. Pembayaran tidak diubah.');
        }

        return back()->with('error', 'Pembayaran belum dapat diverifikasi karena integrasi payment gateway belum tersedia. Status pembayaran tidak diubah.');
    }

    private function findOwnedDenda(int $idDenda): Denda
    {
        $query = Denda::with([
            'pengembalian.peminjaman.details.buku.barcode',
            'pengembalian.peminjaman.member',
            'details.peminjaman.member',
        ]);

        $user = auth()->user();
        if ($user && $user->role === 'member') {
            $query->where(function (Builder $q) use ($user): void {
                $q->whereHas('pengembalian.peminjaman', function (Builder $sq) use ($user): void {
                    $sq->where('idUserMember', $user->id);
                })->orWhereHas('details.peminjaman', function (Builder $sq) use ($user): void {
                    $sq->where('idUserMember', $user->id);
                });
            });
        }

        return $query->findOrFail($idDenda);
    }

    private function findOwnedPembayaran(int $idPembayaran): Pembayaran
    {
        $query = Pembayaran::with([
            'denda.pengembalian.peminjaman.details.buku.barcode',
            'denda.pengembalian.peminjaman.member',
            'denda.details.buku.barcode',
            'denda.details.peminjaman.member',
        ]);

        $user = auth()->user();
        if ($user && $user->role === 'member') {
            $query->where(function (Builder $q) use ($user): void {
                $q->whereHas('denda.pengembalian.peminjaman', function (Builder $sq) use ($user): void {
                    $sq->where('idUserMember', $user->id);
                })->orWhereHas('denda.details.peminjaman', function (Builder $sq) use ($user): void {
                    $sq->where('idUserMember', $user->id);
                });
            });
        }

        return $query->findOrFail($idPembayaran);
    }

    private function isPaymentConfirmed(Pembayaran $pembayaran): bool
    {
        return $pembayaran->status === 'Sukses'
            && $pembayaran->denda?->status === 'Lunas'
            && $this->paymentMatchesFine($pembayaran, $pembayaran->denda);
    }

    private function paymentMatchesFine(Pembayaran $pembayaran, Denda $denda): bool
    {
        $paymentAmount = $this->normalizeAmount((string) $pembayaran->nominal);
        $fineAmount = $this->normalizeAmount((string) $denda->jumlah);

        return $paymentAmount !== null && $paymentAmount === $fineAmount;
    }

    private function isPositiveAmount(string $amount): bool
    {
        $normalizedAmount = $this->normalizeAmount($amount);

        return $normalizedAmount !== null
            && $normalizedAmount !== '0'
            && ! str_starts_with($normalizedAmount, '-');
    }

    private function normalizeAmount(string $amount): ?string
    {
        if (! preg_match('/\A(-?)(\d+)(?:\.(\d+))?\z/', $amount, $matches)) {
            return null;
        }

        $whole = ltrim($matches[2], '0');
        $whole = $whole === '' ? '0' : $whole;
        $fraction = rtrim($matches[3] ?? '', '0');
        $isZero = $whole === '0' && $fraction === '';
        $sign = $matches[1] === '-' && ! $isZero ? '-' : '';

        return $sign.$whole.($fraction === '' ? '' : '.'.$fraction);
    }
}
