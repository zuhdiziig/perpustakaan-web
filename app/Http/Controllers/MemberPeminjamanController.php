<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use App\Services\BarcodeService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MemberPeminjamanController extends Controller
{
    /**
     * Menampilkan form review dan konfirmasi peminjaman buku oleh anggota.
     */
    public function ajukan(Request $request, ?int $id = null): View|RedirectResponse
    {
        if (! $id) {
            return redirect()->route('katalog.index')
                ->with('info', 'Silakan pilih buku yang ingin dipinjam terlebih dahulu.');
        }

        /** @var User $user */
        $user = $request->user();

        if ($user->status !== 'aktif') {
            return redirect()->route('dashboard')
                ->withErrors(['peminjaman' => 'Status keanggotaan Anda tidak aktif. Silakan hubungi petugas perpustakaan.']);
        }

        $buku = Buku::with(['kategori', 'barcode', 'eksemplar'])->findOrFail($id);

        if ($buku->stok <= 0) {
            return redirect()->route('katalog.show', $buku->idBuku)
                ->withErrors(['stok' => 'Maaf, semua eksemplar buku ini sedang dipinjam oleh anggota lain.']);
        }

        // Cek kuota peminjaman aktif anggota (maksimal 7 buku dengan status dipinjam)
        $bukuSedangDipinjam = $user->jumlahBukuSedangDipinjam();

        if ($bukuSedangDipinjam >= Peminjaman::BATAS_MAKSIMAL_BUKU) {
            return redirect()->route('katalog.show', $buku->idBuku)
                ->withErrors(['kuota' => "Anda saat ini sedang meminjam {$bukuSedangDipinjam} buku (batas maksimal: ".Peminjaman::BATAS_MAKSIMAL_BUKU.' buku). Kembalikan buku terlebih dahulu untuk meminjam buku baru.']);
        }

        $tanggalPinjam = Carbon::now();
        $batasKembali = Carbon::now()->addDays(Peminjaman::MASA_PINJAM_HARI);
        $durasiHari = Peminjaman::MASA_PINJAM_HARI;

        // Calon kode transaksi (preview): PJ-YYYYMMDD-XXXX
        $nomorUnik = $buku->barcode?->kodeBarcode
            ? (int) preg_replace('/\D/', '', $buku->barcode->kodeBarcode)
            : $buku->idBuku;
        $calonKodeTransaksi = sprintf('PJ-%s-%04d', $tanggalPinjam->format('Ymd'), $nomorUnik % 10000);

        return view('peminjaman.ajukan', [
            'buku' => $buku,
            'user' => $user,
            'tanggalPinjam' => $tanggalPinjam,
            'batasKembali' => $batasKembali,
            'durasiHari' => $durasiHari,
            'calonKodeTransaksi' => $calonKodeTransaksi,
            'bukuSedangDipinjam' => $bukuSedangDipinjam,
            'batasMaksimal' => Peminjaman::BATAS_MAKSIMAL_BUKU,
        ]);
    }

    /**
     * Memproses konfirmasi pengajuan peminjaman oleh anggota.
     */
    public function proses(Request $request, int $id): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->status !== 'aktif') {
            return redirect()->route('dashboard')
                ->withErrors(['peminjaman' => 'Akun keanggotaan Anda tidak aktif.']);
        }

        $request->validate([
            'setuju_ketentuan' => ['accepted'],
        ], [
            'setuju_ketentuan.accepted' => 'Anda harus menyetujui ketentuan peminjaman terlebih dahulu.',
        ]);

        $buku = Buku::findOrFail($id);

        // Cek kuota peminjaman aktif (maksimal 7 buku dengan status dipinjam)
        $bukuSedangDipinjam = $user->jumlahBukuSedangDipinjam();

        if ($bukuSedangDipinjam >= Peminjaman::BATAS_MAKSIMAL_BUKU) {
            return redirect()->route('katalog.show', $buku->idBuku)
                ->withErrors(['kuota' => "Anda saat ini sedang meminjam {$bukuSedangDipinjam} buku (batas maksimal: ".Peminjaman::BATAS_MAKSIMAL_BUKU.' buku). Kembalikan buku terlebih dahulu untuk meminjam buku baru.']);
        }

        try {
            $peminjaman = DB::transaction(function () use ($user, $buku) {
                // Kunci eksemplar fisik yang tersedia
                $eksemplar = BukuEksemplar::where('idBuku', $buku->idBuku)
                    ->where('status', 'Tersedia')
                    ->lockForUpdate()
                    ->first();

                if (! $eksemplar) {
                    throw new \DomainException('Maaf, stok eksemplar fisik buku ini baru saja habis dipinjam pengguna lain.');
                }

                $tanggalPinjam = Carbon::now();
                $batasKembali = Carbon::now()->addDays(Peminjaman::MASA_PINJAM_HARI);

                $peminjaman = Peminjaman::create([
                    'idUserMember' => $user->id,
                    'idUserPetugas' => null, // Petugas mencatat saat penyerahan buku fisik di meja layanan
                    'tanggalPinjam' => $tanggalPinjam->toDateString(),
                    'batasKembali' => $batasKembali->toDateString(),
                    'status' => 'Dipinjam',
                    'totalBuku' => 1,
                ]);

                DetailPeminjaman::create([
                    'idPeminjaman' => $peminjaman->idPeminjaman,
                    'idBuku' => $buku->idBuku,
                    'idEksemplar' => $eksemplar->idEksemplar,
                    'jumlah' => 1,
                    'statusBuku' => 'Dipinjam',
                ]);

                $eksemplar->update(['status' => 'Dipinjam']);
                $buku->syncStok();

                return $peminjaman;
            });
        } catch (\DomainException $e) {
            return redirect()->route('katalog.show', $buku->idBuku)
                ->withErrors(['stok' => $e->getMessage()]);
        }

        return redirect()->route('peminjaman.sukses', $peminjaman->idPeminjaman)
            ->with('success', 'Peminjaman buku berhasil diajukan! Silakan ambil buku fisik di meja sirkulasi.');
    }

    /**
     * Menampilkan halaman Step 3: Berhasil (Bukti / Struk Peminjaman).
     */
    public function sukses(Request $request, int $id, BarcodeService $barcodeService): View|RedirectResponse
    {
        $user = $request->user();

        $peminjaman = Peminjaman::with([
            'member',
            'details.buku.kategori',
            'details.buku.barcode',
            'details.eksemplar',
        ])->findOrFail($id);

        // Hanya pemilik atau admin/petugas yang dapat mengakses
        if ($peminjaman->idUserMember !== $user->id && ! in_array($user->role, ['admin', 'petugas'])) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $detail = $peminjaman->details->first();
        $buku = $detail?->buku;
        $barcodeSvg = $barcodeService->generateCode128Svg($peminjaman->kode_transaksi);

        return view('peminjaman.sukses', [
            'peminjaman' => $peminjaman,
            'detail' => $detail,
            'buku' => $buku,
            'barcodeSvg' => $barcodeSvg,
        ]);
    }
}
