<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KeranjangBookingController extends Controller
{
    /**
     * Pastikan hanya anggota perpustakaan yang dapat mengakses fitur ini.
     */
    private function ensureMemberOnly(): ?RedirectResponse
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'member') {
            abort(403, 'Fitur keranjang booking buku hanya tersedia khusus untuk Anggota Perpustakaan.');
        }

        return null;
    }

    /**
     * Halaman daftar buku dalam keranjang booking anggota.
     */
    public function index(): View|RedirectResponse
    {
        if ($redirect = $this->ensureMemberOnly()) {
            return $redirect;
        }

        $user = auth()->user();
        $cart = session()->get('keranjang_booking', []);

        $bookIds = array_keys($cart);
        $bukus = Buku::with(['kategori', 'barcode', 'eksemplarTersedia'])
            ->whereIn('idBuku', $bookIds)
            ->get()
            ->keyBy('idBuku');

        // Bersihkan item jika buku sudah dihapus dari database
        $items = [];
        foreach ($cart as $idBuku => $cartData) {
            if ($bukus->has($idBuku)) {
                $buku = $bukus->get($idBuku);
                $isTersedia = (int) $buku->stok > 0 && $buku->eksemplarTersedia->isNotEmpty();
                $items[] = [
                    'buku' => $buku,
                    'isTersedia' => $isTersedia,
                    'waktuDitambahkan' => $cartData['waktu'] ?? now()->toDateTimeString(),
                ];
            } else {
                unset($cart[$idBuku]);
                session()->put('keranjang_booking', $cart);
            }
        }

        $bukuSedangDipinjam = $user->jumlahBukuSedangDipinjam();
        $bukuAktif = $user->jumlahBukuAktif();
        $batasMaksimalBuku = Peminjaman::BATAS_MAKSIMAL_BUKU;
        $totalItemKeranjang = count($items);

        $sisaKuotaSaatIni = max(0, $batasMaksimalBuku - $bukuSedangDipinjam);
        $sisaKuotaSetelahBooking = max(0, $batasMaksimalBuku - ($bukuSedangDipinjam + $totalItemKeranjang));
        $kuotaMelebihiBatas = ($bukuSedangDipinjam + $totalItemKeranjang) > $batasMaksimalBuku;

        $durasiHari = Peminjaman::MASA_PINJAM_HARI;
        $batasAmbilJam = Peminjaman::BATAS_AMBIL_BOOKING_JAM;

        return view('peminjaman.keranjang', compact(
            'items',
            'user',
            'bukuSedangDipinjam',
            'bukuAktif',
            'batasMaksimalBuku',
            'totalItemKeranjang',
            'sisaKuotaSaatIni',
            'sisaKuotaSetelahBooking',
            'kuotaMelebihiBatas',
            'durasiHari',
            'batasAmbilJam'
        ));
    }

    /**
     * Tambahkan sebuah buku ke keranjang booking anggota.
     */
    public function tambah(Request $request, $idBuku): JsonResponse|RedirectResponse
    {
        if ($redirect = $this->ensureMemberOnly()) {
            return $redirect;
        }

        $user = auth()->user();

        if ($user->status !== 'aktif') {
            $msg = 'Status keanggotaan Anda tidak aktif. Silakan hubungi petugas perpustakaan.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }

            return back()->with('error', $msg);
        }

        $buku = Buku::with('eksemplarTersedia')->findOrFail($idBuku);

        // 1. Cek ketersediaan fisik buku
        if ((int) $buku->stok <= 0 || $buku->eksemplarTersedia->isEmpty()) {
            $msg = "Buku '{$buku->judul}' saat ini stoknya habis atau sedang dipinjam.";
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return back()->with('error', $msg);
        }

        $cart = session()->get('keranjang_booking', []);

        // 2. Cek apakah buku sudah ada di keranjang
        if (isset($cart[$idBuku])) {
            $msg = "Buku '{$buku->judul}' sudah ada di dalam keranjang booking Anda.";
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg, 'already_in_cart' => true], 200);
            }

            return back()->with('info', $msg);
        }

        // 3. Cek apakah member sudah meminjam/membooking judul buku ini secara aktif
        $sedangPinjamBukuIni = DetailPeminjaman::whereHas('peminjaman', function ($q) use ($user) {
            $q->where('idUserMember', $user->id)->whereIn('status', ['Booking', 'Siap Diambil', 'Dipinjam']);
        })->where('idBuku', $idBuku)->whereIn('statusBuku', ['Booking', 'Siap Diambil', 'Dipinjam'])->exists();

        if ($sedangPinjamBukuIni) {
            $msg = "Anda sudah memiliki pinjaman atau booking aktif untuk judul '{$buku->judul}'.";
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return back()->with('error', $msg);
        }

        // 4. Cek aturan kuota maksimal 7 buku
        $bukuSedangDipinjam = $user->jumlahBukuSedangDipinjam();
        $totalSetelahDitambah = $bukuSedangDipinjam + count($cart) + 1;

        if ($totalSetelahDitambah > Peminjaman::BATAS_MAKSIMAL_BUKU) {
            $msg = 'Gagal menambahkan: Batas maksimal peminjaman adalah '.Peminjaman::BATAS_MAKSIMAL_BUKU." buku. Anda sedang meminjam {$bukuSedangDipinjam} buku dan memiliki ".count($cart).' buku di keranjang.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return back()->with('error', $msg);
        }

        // Simpan ke session keranjang
        $cart[$idBuku] = [
            'idBuku' => $buku->idBuku,
            'judul' => $buku->judul,
            'waktu' => now()->toDateTimeString(),
        ];
        session()->put('keranjang_booking', $cart);

        $totalItem = count($cart);
        $msg = "Buku '{$buku->judul}' berhasil ditambahkan ke keranjang booking ({$totalItem} buku).";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'totalItem' => $totalItem,
                'idBuku' => $buku->idBuku,
                'judul' => $buku->judul,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Hapus sebuah buku dari keranjang booking.
     */
    public function hapus(Request $request, $idBuku): JsonResponse|RedirectResponse
    {
        if ($redirect = $this->ensureMemberOnly()) {
            return $redirect;
        }

        $cart = session()->get('keranjang_booking', []);

        if (isset($cart[$idBuku])) {
            $judul = $cart[$idBuku]['judul'] ?? 'Buku';
            unset($cart[$idBuku]);
            session()->put('keranjang_booking', $cart);
            $msg = "Buku '{$judul}' berhasil dihapus dari keranjang booking.";
        } else {
            $msg = 'Buku tidak ditemukan di keranjang booking.';
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'totalItem' => count($cart),
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Kosongkan seluruh item dalam keranjang booking.
     */
    public function kosongkan(Request $request): JsonResponse|RedirectResponse
    {
        if ($redirect = $this->ensureMemberOnly()) {
            return $redirect;
        }

        session()->forget('keranjang_booking');
        $msg = 'Seluruh buku di keranjang booking berhasil dikosongkan.';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'totalItem' => 0,
            ]);
        }

        return redirect()->route('keranjang.index')->with('info', $msg);
    }

    /**
     * Checkout dan ajukan booking seluruh buku di keranjang secara bersamaan (Simultan).
     */
    public function checkout(Request $request): RedirectResponse
    {
        if ($redirect = $this->ensureMemberOnly()) {
            return $redirect;
        }

        $user = auth()->user();

        if ($user->status !== 'aktif') {
            return back()->with('error', 'Status keanggotaan Anda tidak aktif.');
        }

        $cart = session()->get('keranjang_booking', []);
        if (empty($cart)) {
            return redirect()->route('katalog.index')->with('error', 'Keranjang booking Anda masih kosong. Silakan pilih buku dari katalog terlebih dahulu.');
        }

        $request->validate([
            'opsi_pengambilan' => ['nullable', 'in:siapkan_petugas,ambil_mandiri'],
        ]);

        $opsiPengambilan = $request->input('opsi_pengambilan', 'siapkan_petugas');
        $totalBukuBooking = count($cart);

        // Cek Kuota 7 buku
        $bukuSedangDipinjam = $user->jumlahBukuSedangDipinjam();
        if (($bukuSedangDipinjam + $totalBukuBooking) > Peminjaman::BATAS_MAKSIMAL_BUKU) {
            return back()->with('error', 'Gagal booking: Total peminjaman akan menjadi '.($bukuSedangDipinjam + $totalBukuBooking).' buku (batas maksimal '.Peminjaman::BATAS_MAKSIMAL_BUKU.' buku). Harap kurangi buku di keranjang atau kembalikan buku yang sedang dipinjam terlebih dahulu.');
        }

        try {
            $peminjaman = DB::transaction(function () use ($user, $cart, $opsiPengambilan, $totalBukuBooking) {
                $tanggalPinjam = Carbon::now();
                $batasKembali = Carbon::now()->addDays(Peminjaman::MASA_PINJAM_HARI);
                $estimasiBatasAmbil = Carbon::now()->addHours(Peminjaman::BATAS_AMBIL_BOOKING_JAM);

                $statusAwal = $opsiPengambilan === 'ambil_mandiri' ? 'Siap Diambil' : 'Booking';

                $peminjamanBaru = Peminjaman::create([
                    'idUserMember' => $user->id,
                    'tanggalPinjam' => $tanggalPinjam->toDateString(),
                    'batasKembali' => $batasKembali->toDateString(),
                    'batasAmbil' => $estimasiBatasAmbil,
                    'status' => $statusAwal,
                    'opsi_pengambilan' => $opsiPengambilan,
                    'totalBuku' => $totalBukuBooking,
                ]);

                $kodeBooking = sprintf('BK-%s-%04d', $tanggalPinjam->format('Ymd'), $peminjamanBaru->idPeminjaman);
                $qrToken = 'book_'.bin2hex(random_bytes(10));

                $peminjamanBaru->update([
                    'kode_booking' => $kodeBooking,
                    'qr_token' => $qrToken,
                ]);

                foreach (array_keys($cart) as $idBuku) {
                    $buku = Buku::lockForUpdate()->findOrFail($idBuku);
                    $eksemplar = BukuEksemplar::where('idBuku', $idBuku)
                        ->where('status', 'Tersedia')
                        ->lockForUpdate()
                        ->first();

                    if (! $eksemplar) {
                        throw new \DomainException("Buku '{$buku->judul}' tidak memiliki eksemplar fisik yang tersedia saat ini.");
                    }

                    DetailPeminjaman::create([
                        'idPeminjaman' => $peminjamanBaru->idPeminjaman,
                        'idBuku' => $buku->idBuku,
                        'idEksemplar' => $eksemplar->idEksemplar,
                        'jumlah' => 1,
                        'statusBuku' => $statusAwal,
                    ]);

                    $eksemplar->update(['status' => 'Dibooking']);
                    $buku->syncStok();
                }

                return $peminjamanBaru;
            });

            // Kosongkan keranjang setelah berhasil checkout
            session()->forget('keranjang_booking');

            return redirect()->route('peminjaman.booking.tiket', $peminjaman->idPeminjaman)
                ->with('success', "Booking untuk {$peminjaman->totalBuku} buku sekaligus berhasil dibuat! Simpan kode booking atau tiket QR di bawah untuk ditunjukkan kepada petugas.");
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses booking sekaligus: '.$e->getMessage());
        }
    }
}
