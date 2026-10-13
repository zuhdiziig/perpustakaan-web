<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PembayaranController extends Controller
{
    /**
     * Fitur 18: Web QRIS Menunggu (Sesuai Figma Frame 18)
     */
    public function bayarQr($idDenda)
    {
        $denda = Denda::with([
            'pengembalian.peminjaman.details.buku.barcode',
            'pengembalian.peminjaman.member',
            'details.peminjaman.member',
        ])->findOrFail($idDenda);

        $user = auth()->user();
        if ($user && $user->role === 'member') {
            $ownerId = $denda->pengembalian?->peminjaman?->idUserMember
                ?? $denda->details->first()?->peminjaman?->idUserMember;

            if ($ownerId && (int) $ownerId !== (int) $user->id) {
                abort(403, 'Anda tidak memiliki hak akses melihat tagihan denda ini.');
            }
        }

        // Ambil atau buat record pembayaran pending untuk denda ini
        $pembayaran = Pembayaran::firstOrCreate(
            [
                'idDenda' => $denda->idDenda,
                'status' => 'Pending',
            ],
            [
                'tanggalBayar' => now(),
                'nominal' => $denda->jumlah,
                'metode' => 'QRIS',
            ]
        );

        if ($denda->status === 'Lunas') {
            return redirect()->route('bayar.sukses', $pembayaran->idPembayaran);
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
    public function prosesBayarQr(Request $request, $idPembayaran)
    {
        $pembayaran = Pembayaran::with([
            'denda.pengembalian.peminjaman',
            'denda.details.peminjaman',
        ])->findOrFail($idPembayaran);

        $user = auth()->user();
        if ($user && $user->role === 'member') {
            $ownerId = $pembayaran->denda?->pengembalian?->peminjaman?->idUserMember
                ?? $pembayaran->denda?->details->first()?->peminjaman?->idUserMember;

            if ($ownerId && (int) $ownerId !== (int) $user->id) {
                abort(403, 'Akses ditolak.');
            }
        }

        // Simulasi hasil pembayaran dari gateway (default berhasil)
        $statusInput = $request->input('simulasi_status', 'berhasil');

        if ($statusInput === 'berhasil') {
            DB::transaction(function () use ($pembayaran) {
                // Perbarui status pembayaran menjadi Sukses
                $pembayaran->update([
                    'tanggalBayar' => now(),
                    'status' => 'Sukses',
                ]);

                // Perbarui status denda menjadi Lunas
                if ($pembayaran->denda) {
                    $pembayaran->denda->update([
                        'status' => 'Lunas',
                    ]);
                }
            });

            return redirect()->route('bayar.sukses', $pembayaran->idPembayaran)
                ->with('success', 'Pembayaran via QRIS berhasil diverifikasi!');
        }

        return back()->with('error', 'Pembayaran belum terdeteksi. Silakan coba beberapa saat lagi.');
    }

    /**
     * Fitur 19: Web QRIS Berhasil (Sesuai Figma Frame 19)
     */
    public function sukses($idPembayaran)
    {
        $pembayaran = Pembayaran::with([
            'denda.pengembalian.peminjaman.details.buku.barcode',
            'denda.pengembalian.peminjaman.member',
            'denda.details.peminjaman.member',
        ])->findOrFail($idPembayaran);

        $user = auth()->user();
        if ($user && $user->role === 'member') {
            $ownerId = $pembayaran->denda?->pengembalian?->peminjaman?->idUserMember
                ?? $pembayaran->denda?->details->first()?->peminjaman?->idUserMember;

            if ($ownerId && (int) $ownerId !== (int) $user->id) {
                abort(403, 'Anda tidak memiliki hak akses melihat bukti pembayaran ini.');
            }
        }

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
    public function nota($idPembayaran)
    {
        $pembayaran = Pembayaran::with([
            'denda.pengembalian.peminjaman.details.buku.barcode',
            'denda.pengembalian.peminjaman.member',
            'denda.details.peminjaman.member',
        ])->find($idPembayaran);

        if (! $pembayaran) {
            // Cek apakah $idPembayaran adalah idDenda
            $denda = Denda::with([
                'pengembalian.peminjaman.details.buku.barcode',
                'pengembalian.peminjaman.member',
                'details.peminjaman.member',
            ])->find($idPembayaran);

            if ($denda) {
                $pembayaran = Pembayaran::firstOrCreate(
                    ['idDenda' => $denda->idDenda],
                    [
                        'tanggalBayar' => now(),
                        'nominal' => $denda->jumlah,
                        'metode' => 'QRIS',
                        'status' => 'Sukses',
                    ]
                );
                $pembayaran->load([
                    'denda.pengembalian.peminjaman.details.buku.barcode',
                    'denda.pengembalian.peminjaman.member',
                    'denda.details.peminjaman.member',
                ]);
            }
        }

        if (! $pembayaran) {
            abort(404, 'Data nota pembayaran tidak ditemukan.');
        }

        $user = auth()->user();
        if ($user && $user->role === 'member') {
            $ownerId = $pembayaran->denda?->pengembalian?->peminjaman?->idUserMember
                ?? $pembayaran->denda?->details->first()?->peminjaman?->idUserMember;

            if ($ownerId && (int) $ownerId !== (int) $user->id) {
                abort(403, 'Anda tidak memiliki hak akses melihat nota pembayaran ini.');
            }
        }

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
    public function verifikasi($idPembayaran)
    {
        $pembayaran = Pembayaran::with('denda')->findOrFail($idPembayaran);

        // 1. Sistem kirim permintaan verifikasi ke Payment Gateway
        $statusGateway = $this->cekStatusKePaymentGateway($pembayaran->idPembayaran);

        // 2. Evaluasi keputusan: Status valid/lunas?
        if ($statusGateway === 'settlement' || $statusGateway === 'paid') {
            DB::transaction(function () use ($pembayaran) {
                // Simpan status pembayaran
                $pembayaran->update([
                    'status' => 'Sukses',
                ]);

                // Update status denda terkait menjadi Lunas
                if ($pembayaran->denda) {
                    $pembayaran->denda->update([
                        'status' => 'Lunas',
                    ]);
                }
            });

            // Tampilkan pembayaran terverifikasi
            return back()->with('success', 'Pembayaran terverifikasi! Status denda telah lunas.');
        }

        // Tampilkan pembayaran belum berhasil
        return back()->with('error', 'Pembayaran belum berhasil atau belum diselesaikan di Payment Gateway.');
    }

    /**
     * Simulasi komunikasi ke API Payment Gateway
     * Di lingkungan produksi nyata, fungsi ini memanggil Http::withToken()->get(...)
     */
    private function cekStatusKePaymentGateway($idPembayaran)
    {
        // Mock simulasi: anggap pembayaran terbayar (settlement/paid)
        // Ubah jadi 'pending' jika ingin menguji skenario gagal
        return 'settlement';
    }
}
