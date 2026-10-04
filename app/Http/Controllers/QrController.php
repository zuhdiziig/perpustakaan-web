<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QrController extends Controller
{
    /**
     * Helper universal untuk generate string SVG QR Code
     */
    private function generateSvgQr(string $content, int $size = 200): string
    {
        if (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
            try {
                return (string) \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)->generate($content);
            } catch (\Throwable $e) {
                // Fallback ke native BaconQrCode jika facade gagal
            }
        }

        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        return $writer->writeString($content);
    }

    /**
     * Pastikan pengguna adalah Petugas atau Admin untuk operasional sirkulasi
     */
    private function checkStaffPermission(): ?JsonResponse
    {
        $role = auth()->user()->role ?? '';
        if ($role !== 'admin' && $role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya Petugas atau Admin yang berwenang melakukan scan operasional.',
            ], 403);
        }

        return null;
    }

    /**
     * Tampilkan dan Cetak Kartu Member beserta QR Code
     */
    public function cetakMember($id)
    {
        $user = auth()->user();
        if ($user && $user->role === 'member' && (int) $user->id !== (int) $id) {
            abort(403, 'Anda hanya dapat melihat atau mencetak kartu member milik Anda sendiri.');
        }

        $member = User::where('role', 'member')->findOrFail($id);

        if (empty($member->qr_token)) {
            $member->qr_token = 'usr_' . bin2hex(random_bytes(16));
            $member->save();
        }

        $qrCodeSvg = $this->generateSvgQr($member->qr_token, 200);

        return view('qr.cetak_member', compact('member', 'qrCodeSvg'));
    }

    /**
     * Tampilkan dan Cetak Label QR Code untuk Semua Eksemplar Buku (Read-Only)
     */
    public function cetakBuku($id)
    {
        $role = auth()->user()->role ?? '';
        if ($role !== 'admin' && $role !== 'petugas') {
            abort(403, 'Akses terbatas untuk Petugas dan Admin.');
        }

        $buku = Buku::with(['kategori', 'barcode', 'eksemplar'])->findOrFail($id);

        $eksemplarQr = [];
        foreach ($buku->eksemplar as $eks) {
            $eksemplarQr[$eks->idEksemplar] = $this->generateSvgQr($eks->qr_token, 150);
        }

        return view('qr.cetak_buku', compact('buku', 'eksemplarQr'));
    }

    /**
     * Tampilkan dan Cetak Label QR Code untuk Satu Eksemplar Fisik Tertentu (Read-Only)
     */
    public function cetakEksemplar($id)
    {
        $role = auth()->user()->role ?? '';
        if ($role !== 'admin' && $role !== 'petugas') {
            abort(403, 'Akses terbatas untuk Petugas dan Admin.');
        }

        $eksemplar = BukuEksemplar::with(['buku.kategori', 'buku.barcode'])->findOrFail($id);
        $qrCodeSvg = $this->generateSvgQr($eksemplar->qr_token, 170);

        return view('qr.cetak_eksemplar', compact('eksemplar', 'qrCodeSvg'));
    }

    /**
     * API Lookup Member via QR Token untuk Peminjaman & Pengembalian
     */
    public function apiScanMember(Request $request): JsonResponse
    {
        if ($denied = $this->checkStaffPermission()) {
            return $denied;
        }

        $token = trim($request->input('token', ''));

        if (empty($token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token QR Member tidak boleh kosong.',
            ], 422);
        }

        $member = User::where('qr_token', $token)->first();

        if (! $member) {
            return response()->json([
                'success' => false,
                'message' => 'Member dengan QR Token tersebut tidak ditemukan.',
            ], 404);
        }

        if ($member->role !== 'member') {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan berstatus sebagai Member.',
            ], 400);
        }

        if ($member->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Member sedang NONAKTIF. Tidak dapat melakukan transaksi.',
            ], 403);
        }

        $bukuSedangDipinjam = DetailPeminjaman::whereHas('peminjaman', function ($q) use ($member) {
            $q->where('idUserMember', $member->id)->where('status', 'Dipinjam');
        })->where('statusBuku', 'Dipinjam')->count();

        $sisaKuota = max(0, 7 - $bukuSedangDipinjam);

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'noTelepon' => $member->noTelepon ?? '-',
                'status' => $member->status,
                'sedangDipinjam' => $bukuSedangDipinjam,
                'sisaKuota' => $sisaKuota,
            ],
        ]);
    }

    /**
     * API Lookup Buku via QR Token atau Barcode untuk Peminjaman (Berbasis BukuEksemplar)
     */
    public function apiScanBuku(Request $request): JsonResponse
    {
        if ($denied = $this->checkStaffPermission()) {
            return $denied;
        }

        $token = trim($request->input('token', ''));

        if (empty($token)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode QR / Barcode buku tidak boleh kosong.',
            ], 422);
        }

        $eksemplarQuery = BukuEksemplar::with(['buku.kategori', 'buku.barcode']);
        if (is_numeric($token)) {
            $eksemplar = (clone $eksemplarQuery)->where('idEksemplar', $token)->first()
                ?? (clone $eksemplarQuery)->where('qr_token', $token)->orWhere('kode_barcode', $token)->first();
        } else {
            $eksemplar = (clone $eksemplarQuery)->where('qr_token', $token)->orWhere('kode_barcode', $token)->first();
        }

        if (! $eksemplar) {
            if (Buku::where('qr_token', $token)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode QR ini merupakan QR level judul buku, bukan QR eksemplar fisik. Silakan scan QR stiker pada fisik buku.',
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => "Buku fisik dengan kode '{$token}' tidak ditemukan dalam database.",
            ], 404);
        }

        $buku = $eksemplar->buku;

        if ($eksemplar->status !== 'Tersedia') {
            return response()->json([
                'success' => false,
                'message' => "Buku fisik '{$buku->judul}' (Eksemplar #{$eksemplar->nomor_eksemplar}) statusnya sedang {$eksemplar->status}.",
                'buku' => [
                    'idBuku' => $buku->idBuku,
                    'idEksemplar' => $eksemplar->idEksemplar,
                    'nomor_eksemplar' => $eksemplar->nomor_eksemplar,
                    'judul' => $buku->judul,
                    'status' => $eksemplar->status,
                ],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'buku' => [
                'idBuku' => $buku->idBuku,
                'idEksemplar' => $eksemplar->idEksemplar,
                'nomor_eksemplar' => $eksemplar->nomor_eksemplar,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'penerbit' => $buku->penerbit,
                'tahunTerbit' => $buku->tahunTerbit,
                'kategori' => $buku->kategori->namaKategori ?? '-',
                'harga' => (float) $buku->harga,
                'harga_formatted' => 'Rp ' . number_format($buku->harga, 0, ',', '.'),
                'stok' => $buku->stok,
                'kondisi' => $eksemplar->kondisi,
                'status' => $eksemplar->status,
                'kodeBarcode' => $eksemplar->kode_barcode ?? $buku->barcode->kodeBarcode ?? '-',
            ],
        ]);
    }

    /**
     * API Lookup Pengembalian: Menampilkan seluruh pinjaman aktif member
     */
    public function apiScanPengembalianMember(Request $request): JsonResponse
    {
        if ($denied = $this->checkStaffPermission()) {
            return $denied;
        }

        $token = trim($request->input('token', ''));

        if (empty($token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token QR Member tidak boleh kosong.',
            ], 422);
        }

        $member = User::where('qr_token', $token)->first();

        if (! $member) {
            return response()->json([
                'success' => false,
                'message' => 'Member tidak ditemukan.',
            ], 404);
        }

        $peminjamans = Peminjaman::with(['details' => function ($q) {
            $q->where('statusBuku', 'Dipinjam')->with(['buku.barcode', 'eksemplar']);
        }])
            ->where('idUserMember', $member->id)
            ->where('status', 'Dipinjam')
            ->latest('idPeminjaman')
            ->get();

        $daftarBuku = [];
        $today = Carbon::now();

        foreach ($peminjamans as $p) {
            $batasKembali = Carbon::parse($p->batasKembali);
            $terlambat = $today->greaterThan($batasKembali);
            $hariTerlambat = $terlambat ? max(1, $batasKembali->diffInDays($today)) : 0;
            $mingguTerlambat = (int) ceil($hariTerlambat / 7);
            $persenDenda = min($mingguTerlambat, 10) * 0.10;

            foreach ($p->details as $d) {
                if ($d->buku) {
                    $hargaBuku = (float) $d->buku->harga;
                    $estDendaTelat = $terlambat ? ($hargaBuku * $persenDenda) : 0;

                    $daftarBuku[] = [
                        'idPeminjaman' => $p->idPeminjaman,
                        'idDetail' => $d->id,
                        'idBuku' => $d->buku->idBuku,
                        'idEksemplar' => $d->idEksemplar,
                        'nomor_eksemplar' => $d->eksemplar->nomor_eksemplar ?? null,
                        'statusEksemplar' => $d->eksemplar->status ?? 'Dipinjam',
                        'judul' => $d->buku->judul,
                        'penulis' => $d->buku->penulis,
                        'harga' => $hargaBuku,
                        'kodeBarcode' => $d->eksemplar->kode_barcode ?? $d->buku->barcode->kodeBarcode ?? '-',
                        'tanggalPinjam' => $p->tanggalPinjam,
                        'batasKembali' => $p->batasKembali,
                        'terlambat' => $terlambat,
                        'hariTerlambat' => $hariTerlambat,
                        'mingguTerlambat' => $mingguTerlambat,
                        'estDendaTelat' => $estDendaTelat,
                        'estDendaTelat_formatted' => 'Rp ' . number_format($estDendaTelat, 0, ',', '.'),
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
            ],
            'totalBukuDipinjam' => count($daftarBuku),
            'bukuDipinjam' => $daftarBuku,
        ]);
    }
}