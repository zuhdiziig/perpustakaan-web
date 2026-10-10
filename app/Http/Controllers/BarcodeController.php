<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarcodeController extends Controller
{
    /**
     * Halaman Scanner Meja Sirkulasi Langsung (Walk-in Direct Circulation)
     */
    public function scan(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('dashboard')->with('error', 'Layanan meja sirkulasi scan barcode hanya diperuntukkan bagi Petugas Perpustakaan.');
        }

        $kodeBarcode = trim((string) $request->query('kodeBarcode', $request->query('code', '')));
        $buku = null;
        $booking = null;
        $error = null;

        $memberId = $request->query('member_id');
        $selectedMember = null;
        $activeLoans = collect();

        if ($memberId) {
            $selectedMember = User::where('role', 'member')->find($memberId);
            if ($selectedMember) {
                $activeLoans = DetailPeminjaman::with(['buku.kategori', 'buku.barcode', 'eksemplar', 'peminjaman'])
                    ->whereHas('peminjaman', fn ($q) => $q->where('idUserMember', $selectedMember->id))
                    ->whereIn('statusBuku', ['Dipinjam', 'Diajukan Kembali'])
                    ->latest('id')
                    ->get();
            }
        }

        if ($kodeBarcode !== '') {
            $bookingModel = Peminjaman::with(['member', 'details.buku.kategori', 'details.eksemplar'])
                ->where('kode_booking', $kodeBarcode)
                ->orWhere('qr_token', $kodeBarcode)
                ->first();

            if ($bookingModel) {
                $booking = $bookingModel;
                $buku = $bookingModel->details->first()?->buku;
            } else {
                $barcodeModel = Barcode::with(['buku.kategori'])->where('kodeBarcode', $kodeBarcode)->first();

                if ($barcodeModel && $barcodeModel->buku) {
                    $buku = $barcodeModel->buku;
                } else {
                    $eksemplar = BukuEksemplar::with(['buku.kategori', 'buku.barcode'])
                        ->where('kode_barcode', $kodeBarcode)
                        ->orWhere('qr_token', $kodeBarcode)
                        ->first();

                    if ($eksemplar && $eksemplar->buku) {
                        $buku = $eksemplar->buku;
                    } else {
                        $memberCheck = User::where('role', 'member')
                            ->where(function ($q) use ($kodeBarcode) {
                                $q->where('qr_token', $kodeBarcode)
                                    ->orWhere('kode_anggota', $kodeBarcode)
                                    ->orWhere('nik', $kodeBarcode);
                            })
                            ->first();

                        if ($memberCheck) {
                            $selectedMember = $memberCheck;
                            $activeLoans = DetailPeminjaman::with(['buku.kategori', 'buku.barcode', 'eksemplar', 'peminjaman'])
                                ->whereHas('peminjaman', fn ($q) => $q->where('idUserMember', $selectedMember->id))
                                ->where('statusBuku', 'Dipinjam')
                                ->latest('id')
                                ->get();
                        } else {
                            $error = "Barcode '{$kodeBarcode}' tidak ditemukan dalam sistem.";
                        }
                    }
                }
            }
        }

        // Ambil daftar buku yang stoknya tersedia untuk sirkulasi langsung
        $availableBooks = Buku::with(['kategori', 'barcode', 'eksemplar' => fn ($e) => $e->where('status', 'Tersedia')])
            ->where('stok', '>', 0)
            ->latest('idBuku')
            ->limit(12)
            ->get();

        foreach ($availableBooks as $b) {
            if ($b->eksemplar->isEmpty() && $b->stok > 0) {
                $nextNo = ($b->eksemplar()->max('nomor_eksemplar') ?? 0) + 1;
                $b->eksemplar()->create([
                    'nomor_eksemplar' => $nextNo,
                    'qr_token' => 'bk_'.bin2hex(random_bytes(16)),
                    'kondisi' => $b->kondisi ?? 'Baik',
                    'status' => 'Tersedia',
                ]);
                $b->load(['eksemplar' => fn ($e) => $e->where('status', 'Tersedia')]);
            }
        }

        $selectedMemberBorrowedBookIds = $selectedMember ? $selectedMember->daftarIdBukuAktif() : [];

        return view('barcode.scan', compact('selectedMember', 'activeLoans', 'availableBooks', 'kodeBarcode', 'buku', 'booking', 'error', 'selectedMemberBorrowedBookIds'));
    }

    /**
     * API Identifikasi Anggota untuk Sirkulasi Langsung Meja Sirkulasi
     */
    public function apiScanMemberWalkin(Request $request)
    {
        $raw = trim((string) $request->input('code', ''));
        if (empty($raw)) {
            return response()->json(['success' => false, 'message' => 'Kode QR anggota tidak boleh kosong.'], 422);
        }

        $memberQuery = User::where('role', 'member');
        $member = null;

        if (str_starts_with($raw, 'usr_')) {
            $member = (clone $memberQuery)->where('qr_token', $raw)->first();
        } elseif (preg_match('/^AG-\d{4}-(\d+)$/i', $raw, $m)) {
            $member = (clone $memberQuery)->where('id', (int) $m[1])->first();
        } elseif (filter_var($raw, FILTER_VALIDATE_EMAIL)) {
            $member = (clone $memberQuery)->where('email', $raw)->first();
        } else {
            $member = (clone $memberQuery)->where('qr_token', $raw)
                ->orWhere('kode_anggota', $raw)
                ->orWhere('nik', $raw)
                ->orWhere('id', is_numeric($raw) ? (int) $raw : 0)
                ->first();

            if (! $member) {
                $likeOp = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
                $member = (clone $memberQuery)->where('name', $likeOp, "%{$raw}%")->first();
            }
        }

        if (! $member) {
            return response()->json([
                'success' => false,
                'message' => "Anggota dengan kode/identitas '{$raw}' tidak ditemukan.",
            ], 404);
        }

        if ($member->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => "Akun anggota '{$member->name}' sedang NONAKTIF.",
            ], 403);
        }

        // Ambil daftar pinjaman buku aktif
        $today = Carbon::now();
        $activeLoans = DetailPeminjaman::with(['buku.kategori', 'buku.barcode', 'eksemplar', 'peminjaman'])
            ->whereHas('peminjaman', fn ($q) => $q->where('idUserMember', $member->id))
            ->whereIn('statusBuku', ['Dipinjam', 'Diajukan Kembali'])
            ->latest('id')
            ->get()
            ->map(function ($d) use ($today) {
                $batas = Carbon::parse($d->peminjaman->batasKembali);
                $isOverdue = $today->greaterThan($batas);
                $hariTelat = $isOverdue ? max(1, $batas->diffInDays($today)) : 0;
                $mingguTelat = (int) ceil($hariTelat / 7);
                $hargaBuku = (float) ($d->buku->harga ?? 0);
                $dendaTelat = $isOverdue ? ($hargaBuku * min($mingguTelat, 10) * 0.10) : 0;

                return [
                    'idDetail' => $d->id,
                    'idPeminjaman' => $d->idPeminjaman,
                    'idBuku' => $d->idBuku,
                    'judul' => $d->buku->judul ?? 'Buku',
                    'penulis' => $d->buku->penulis ?? '-',
                    'kategori' => $d->buku->kategori?->namaKategori ?? 'Umum',
                    'rak' => $d->buku->rak ?? '-',
                    'kodeBuku' => $d->eksemplar?->kode_barcode ?? $d->buku->barcode?->kodeBarcode ?? ('BK-'.$d->idBuku),
                    'nomor_eksemplar' => $d->eksemplar?->nomor_eksemplar ?? 1,
                    'tanggalPinjam' => Carbon::parse($d->peminjaman->tanggalPinjam)->translatedFormat('d M Y'),
                    'batasKembali' => $batas->translatedFormat('d M Y'),
                    'isOverdue' => $isOverdue,
                    'hariTerlambat' => $hariTelat,
                    'estDenda' => $dendaTelat,
                ];
            });

        $sedangDipinjam = $activeLoans->count();
        $sisaKuota = max(0, Peminjaman::BATAS_MAKSIMAL_BUKU - $sedangDipinjam);

        return response()->json([
            'success' => true,
            'data' => [
                'member' => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'kodeAnggota' => $member->kode_anggota ?? sprintf('AG-%s-%05d', date('Y'), $member->id),
                    'email' => $member->email,
                    'noTelepon' => $member->noTelepon ?? '-',
                    'alamat' => $member->alamat ?? '-',
                    'foto' => $member->foto ? asset('storage/'.$member->foto) : null,
                    'inisial' => $member->inisial ?? 'AG',
                    'sedangDipinjam' => $sedangDipinjam,
                    'sisaKuota' => $sisaKuota,
                    'kuotaPenuh' => $sedangDipinjam >= Peminjaman::BATAS_MAKSIMAL_BUKU,
                    'borrowedBookIds' => $member->daftarIdBukuAktif(),
                ],
                'pinjamanAktif' => $activeLoans,
            ],
            'message' => "Anggota {$member->name} berhasil diidentifikasi.",
        ]);
    }

    /**
     * API Pencarian Buku untuk Tab Search Sirkulasi Langsung
     */
    public function apiCariBuku(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $memberId = $request->input('member_id');
        $borrowedBookIds = [];
        if ($memberId) {
            $mem = User::find($memberId);
            if ($mem) {
                $borrowedBookIds = $mem->daftarIdBukuAktif();
            }
        }

        $query = Buku::with(['kategori', 'barcode', 'eksemplar' => fn ($e) => $e->where('status', 'Tersedia')])
            ->where('stok', '>', 0);

        if ($q !== '') {
            $lower = mb_strtolower($q);
            $query->where(function ($sub) use ($lower) {
                $sub->whereRaw('LOWER(judul) LIKE ?', ["%{$lower}%"])
                    ->orWhereRaw('LOWER(penulis) LIKE ?', ["%{$lower}%"])
                    ->orWhereRaw('LOWER(penerbit) LIKE ?', ["%{$lower}%"])
                    ->orWhereHas('kategori', fn ($k) => $k->whereRaw('LOWER(namaKategori) LIKE ?', ["%{$lower}%"]))
                    ->orWhereHas('barcode', fn ($b) => $b->whereRaw('LOWER(kodeBarcode) LIKE ?', ["%{$lower}%"]))
                    ->orWhereHas('eksemplar', fn ($e) => $e->whereRaw('LOWER(kode_barcode) LIKE ?', ["%{$lower}%"]));
            });
        }

        $bukus = $query->latest('idBuku')->limit(16)->get();

        $results = $bukus->map(function ($b) use ($borrowedBookIds) {
            $firstEksemplar = $b->eksemplar->first();
            if (! $firstEksemplar && $b->stok > 0) {
                $nextNo = ($b->eksemplar()->max('nomor_eksemplar') ?? 0) + 1;
                $firstEksemplar = $b->eksemplar()->create([
                    'nomor_eksemplar' => $nextNo,
                    'qr_token' => 'bk_'.bin2hex(random_bytes(16)),
                    'kondisi' => $b->kondisi ?? 'Baik',
                    'status' => 'Tersedia',
                ]);
            }
            $kode = $firstEksemplar?->qr_token ?? $firstEksemplar?->kode_barcode ?? $b->barcode?->kodeBarcode ?? ('BK-'.$b->idBuku);

            return [
                'idBuku' => $b->idBuku,
                'judul' => $b->judul,
                'penulis' => $b->penulis,
                'kategori' => $b->kategori?->namaKategori ?? 'Umum',
                'rak' => $b->rak ?? '-',
                'stok' => $b->stok,
                'harga' => (float) $b->harga,
                'sampul' => $b->sampul ? asset('storage/'.$b->sampul) : null,
                'kodeBarcode' => $kode,
                'idEksemplar' => $firstEksemplar?->idEksemplar,
                'isBorrowedByMember' => in_array($b->idBuku, $borrowedBookIds),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    /**
     * Selesaikan pengembalian buku langsung di meja sirkulasi (bisa satu atau sekaligus/batch)
     */
    public function kembalikanLangsung(Request $request)
    {
        $request->validate([
            'idUserMember' => ['required', 'exists:users,id'],
            'detail_ids' => ['required', 'array', 'min:1'],
            'detail_ids.*' => ['required', 'integer'],
            'kondisi' => ['nullable', 'array'],
            'kondisi.*' => ['nullable', 'in:Baik,Rusak,Hilang'],
        ], [
            'detail_ids.required' => 'Pilih minimal satu buku yang ingin diselesaikan pengembaliannya.',
            'detail_ids.min' => 'Pilih minimal satu buku yang ingin diselesaikan pengembaliannya.',
        ]);

        $member = User::findOrFail($request->idUserMember);
        $detailIds = $request->input('detail_ids', []);
        $kondisiMap = $request->input('kondisi', []);
        $petugasId = auth()->id();
        $today = Carbon::now();

        try {
            $result = DB::transaction(function () use ($member, $detailIds, $kondisiMap, $petugasId, $today) {
                $details = DetailPeminjaman::with(['peminjaman', 'buku', 'eksemplar', 'denda'])
                    ->whereIn('id', $detailIds)
                    ->lockForUpdate()
                    ->get();

                if ($details->isEmpty()) {
                    throw new \DomainException('Data buku yang dipilih tidak ditemukan.');
                }

                $processedCount = 0;
                $totalDendaAccum = 0;
                $peminjamanIds = [];

                foreach ($details as $detail) {
                    $peminjaman = Peminjaman::lockForUpdate()->find($detail->idPeminjaman);
                    if (! $peminjaman || (int) $peminjaman->idUserMember !== (int) $member->id) {
                        throw new \DomainException("Buku '{$detail->buku?->judul}' bukan merupakan pinjaman milik anggota {$member->name}.");
                    }

                    if ($detail->statusBuku === 'Kembali') {
                        continue;
                    }

                    $peminjamanIds[$peminjaman->idPeminjaman] = $peminjaman;
                    $buku = Buku::lockForUpdate()->find($detail->idBuku);
                    $eksemplar = $detail->idEksemplar ? BukuEksemplar::lockForUpdate()->find($detail->idEksemplar) : null;

                    $kondisiFinal = $kondisiMap[$detail->id] ?? 'Baik';
                    if (! in_array($kondisiFinal, ['Baik', 'Rusak', 'Hilang'])) {
                        $kondisiFinal = 'Baik';
                    }

                    $hargaBuku = (float) ($buku?->harga ?? 0);
                    $batasKembali = Carbon::parse($peminjaman->batasKembali);

                    // 1. Denda Keterlambatan
                    $dendaTelat = 0;
                    $mingguTerlambat = 0;
                    if ($today->greaterThan($batasKembali)) {
                        $hariTelat = max(1, $batasKembali->diffInDays($today));
                        $mingguTerlambat = (int) ceil($hariTelat / 7);
                        $faktorMinggu = min($mingguTerlambat, 10);
                        $dendaTelat = $hargaBuku * ($faktorMinggu * 0.10);
                    }

                    // 2. Denda Kondisi Fisik
                    $dendaKondisi = 0;
                    $jenisKondisi = null;
                    if ($kondisiFinal === 'Rusak') {
                        $dendaKondisi = $hargaBuku;
                        $jenisKondisi = 'Kerusakan (100% Harga Buku)';
                    } elseif ($kondisiFinal === 'Hilang') {
                        $dendaKondisi = $hargaBuku;
                        $jenisKondisi = 'Kehilangan (100% Harga Buku)';
                    }

                    $totalDendaItem = $dendaTelat + $dendaKondisi;
                    $totalDendaAccum += $totalDendaItem;

                    // 3. Simpan Transaksi Pengembalian
                    $pengembalian = Pengembalian::create([
                        'idPeminjaman' => $peminjaman->idPeminjaman,
                        'idUserPetugas' => $petugasId,
                        'tanggalKembali' => $today->toDateString(),
                        'kondisiBuku' => $kondisiFinal,
                    ]);

                    // 4. Catat Denda jika ada
                    if ($totalDendaItem > 0) {
                        $keteranganDenda = [];
                        if ($dendaTelat > 0) {
                            $persen = min($mingguTerlambat * 10, 100);
                            $keteranganDenda[] = "Terlambat {$mingguTerlambat} Minggu ({$persen}%)";
                        }
                        if ($dendaKondisi > 0) {
                            $keteranganDenda[] = $jenisKondisi;
                        }

                        if ($detail->id_denda) {
                            $existingDenda = Denda::find($detail->id_denda);
                            if ($existingDenda) {
                                $existingDenda->update([
                                    'idPengembalian' => $pengembalian->idPengembalian,
                                    'jenisDenda' => implode(' + ', $keteranganDenda),
                                    'jumlah' => $totalDendaItem,
                                ]);
                            }
                        } else {
                            $dendaBaru = Denda::create([
                                'idPengembalian' => $pengembalian->idPengembalian,
                                'jenisDenda' => implode(' + ', $keteranganDenda),
                                'jumlah' => $totalDendaItem,
                                'status' => 'Belum Dibayar',
                            ]);
                            $detail->update(['id_denda' => $dendaBaru->idDenda]);
                        }
                    }

                    // 5. Update Status Detail
                    $detail->update(['statusBuku' => 'Kembali']);

                    // 6. Update Eksemplar Fisik & Sinkronkan Stok Buku
                    if ($eksemplar) {
                        $statusBaru = ($kondisiFinal === 'Hilang') ? 'Hilang' : 'Tersedia';
                        $eksemplar->update([
                            'status' => $statusBaru,
                            'kondisi' => $kondisiFinal,
                        ]);
                    }

                    if ($buku) {
                        $buku->syncStok();
                    }

                    $processedCount++;
                }

                // 7. Update status transaksi peminjaman jika seluruh buku sudah tuntas dikembalikan
                foreach ($peminjamanIds as $pjId => $pj) {
                    $sisaBuku = DetailPeminjaman::where('idPeminjaman', $pjId)
                        ->where('statusBuku', '!=', 'Kembali')
                        ->count();

                    if ($sisaBuku === 0) {
                        $pj->update(['status' => 'Selesai']);
                    }
                }

                return [
                    'count' => $processedCount,
                    'totalDenda' => $totalDendaAccum,
                ];
            });
        } catch (\DomainException $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return redirect()->route('barcode.scan', ['member_id' => $member->id, 'tab' => 'kembali'])
                ->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return redirect()->route('barcode.scan', ['member_id' => $member->id, 'tab' => 'kembali'])
                ->with('error', 'Gagal memproses pengembalian: '.$e->getMessage());
        }

        $msg = "Pengembalian {$result['count']} buku untuk anggota {$member->name} berhasil diselesaikan.";
        if ($result['totalDenda'] > 0) {
            $msg .= ' Total denda tercatat: Rp '.number_format($result['totalDenda'], 0, ',', '.').' (dapat dilunasi pada menu Kelola Denda).';
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'count' => $result['count'],
                'totalDenda' => $result['totalDenda'],
            ]);
        }

        return redirect()->route('barcode.scan', ['member_id' => $member->id, 'tab' => 'kembali'])
            ->with('success', $msg);
    }
}
