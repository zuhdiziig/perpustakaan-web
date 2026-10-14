<?php

namespace App\Services;

use App\Models\Buku;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use ZipArchive;

class BukuExportExcelService
{
    /**
     * Membuat file Excel (.xlsx) resmi untuk Stok Opname Koleksi Buku.
     *
     * @param  Collection<int, Buku>  $bukus
     * @param  array<string, mixed>  $activeFilters
     */
    public function generate(Collection $bukus, ?User $petugas = null, array $activeFilters = []): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'booknest_stok_opname_').'.xlsx';

        $zip = new ZipArchive;
        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Gagal membuat file temporary Excel (.xlsx).');
        }

        // 1. [Content_Types].xml
        $zip->addFromString('[Content_Types].xml', $this->getContentTypesXml());

        // 2. _rels/.rels
        $zip->addFromString('_rels/.rels', $this->getRootRelsXml());

        // 3. xl/_rels/workbook.xml.rels
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->getWorkbookRelsXml());

        // 4. xl/workbook.xml
        $zip->addFromString('xl/workbook.xml', $this->getWorkbookXml());

        // 5. xl/styles.xml
        $zip->addFromString('xl/styles.xml', $this->getStylesXml());

        // 6. xl/worksheets/sheet1.xml
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->getSheetXml($bukus, $petugas, $activeFilters));

        $zip->close();

        return $tempFile;
    }

    /**
     * Nama file unduhan default dengan timestamp.
     */
    public function getDownloadFilename(array $activeFilters = []): string
    {
        $date = Carbon::now()->format('Ymd_His');
        $suffix = '';
        if (! empty($activeFilters['lokasi'])) {
            $suffix .= '_'.strtolower((string) $activeFilters['lokasi']);
        }
        if (! empty($activeFilters['kondisi'])) {
            $suffix .= '_'.strtolower((string) $activeFilters['kondisi']);
        }

        return 'Stok_Opname_Buku_'.$date.$suffix.'.xlsx';
    }

    private function getContentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'.
            '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'.
            '<Default Extension="xml" ContentType="application/xml"/>'.
            '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'.
            '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'.
            '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'.
            '</Types>';
    }

    private function getRootRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'.
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'.
            '</Relationships>';
    }

    private function getWorkbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'.
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'.
            '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'.
            '</Relationships>';
    }

    private function getWorkbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'.
            '<sheets><sheet name="Stok Opname Buku" sheetId="1" r:id="rId1"/></sheets>'.
            '</workbook>';
    }

    private function getStylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'.
            '<fonts count="7">'.
            '  <font><sz val="10"/><name val="Calibri"/></font>'. // 0: Normal
            '  <font><b/><sz val="15"/><color rgb="FF0F766E"/><name val="Calibri"/></font>'. // 1: Main Title Teal
            '  <font><b/><sz val="11"/><color rgb="FF1E293B"/><name val="Calibri"/></font>'. // 2: Section Subtitle
            '  <font><sz val="9.5"/><color rgb="FF64748B"/><name val="Calibri"/></font>'. // 3: Meta Muted
            '  <font><b/><sz val="10"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'. // 4: Header White Bold
            '  <font><b/><sz val="10"/><color rgb="FF166534"/><name val="Calibri"/></font>'. // 5: Green Bold (Tersedia / Baik)
            '  <font><b/><sz val="10"/><color rgb="FF991B1B"/><name val="Calibri"/></font>'. // 6: Red/Amber Bold (Dipinjam / Rusak)
            '</fonts>'.
            '<fills count="6">'.
            '  <fill><patternFill patternType="none"/></fill>'. // 0
            '  <fill><patternFill patternType="gray125"/></fill>'. // 1
            '  <fill><patternFill patternType="solid"><fgColor rgb="FF0F766E"/></patternFill></fill>'. // 2: Primary Teal
            '  <fill><patternFill patternType="solid"><fgColor rgb="FFF0FDFA"/></patternFill></fill>'. // 3: Soft Teal
            '  <fill><patternFill patternType="solid"><fgColor rgb="FFF8FAFC"/></patternFill></fill>'. // 4: Soft Gray (Zebra)
            '  <fill><patternFill patternType="solid"><fgColor rgb="FFDCFCE7"/></patternFill></fill>'. // 5: Soft Green
            '</fills>'.
            '<borders count="3">'.
            '  <border><left/><right/><top/><bottom/></border>'. // 0: No border
            '  <border>'. // 1: Thin border light gray
            '    <left style="thin"><color rgb="FFE2E8F0"/></left>'.
            '    <right style="thin"><color rgb="FFE2E8F0"/></right>'.
            '    <top style="thin"><color rgb="FFE2E8F0"/></top>'.
            '    <bottom style="thin"><color rgb="FFE2E8F0"/></bottom>'.
            '  </border>'.
            '  <border>'. // 2: Header border
            '    <left style="thin"><color rgb="FF115E59"/></left>'.
            '    <right style="thin"><color rgb="FF115E59"/></right>'.
            '    <top style="thin"><color rgb="FF115E59"/></top>'.
            '    <bottom style="medium"><color rgb="FF0F766E"/></bottom>'.
            '  </border>'.
            '</borders>'.
            '<cellStyleXfs count="1">'.
            '  <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>'.
            '</cellStyleXfs>'.
            '<cellXfs count="10">'.
            '  <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'. // 0: Normal
            '  <xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>'. // 1: Main Title
            '  <xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1"/>'. // 2: Section Subtitle
            '  <xf numFmtId="0" fontId="3" fillId="0" borderId="0" xfId="0" applyFont="1"/>'. // 3: Meta Muted
            '  <xf numFmtId="0" fontId="4" fillId="2" borderId="2" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'. // 4: Table Header Center
            '  <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment vertical="center"/></xf>'. // 5: Table Cell Normal Left
            '  <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'. // 6: Table Cell Center
            '  <xf numFmtId="0" fontId="0" fillId="4" borderId="1" xfId="0" applyFill="1" applyBorder="1" applyAlignment="1"><alignment vertical="center"/></xf>'. // 7: Table Cell Zebra Left
            '  <xf numFmtId="0" fontId="0" fillId="4" borderId="1" xfId="0" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'. // 8: Table Cell Zebra Center
            '  <xf numFmtId="0" fontId="2" fillId="3" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment vertical="center"/></xf>'. // 9: Summary Box
            '</cellXfs>'.
            '</styleSheet>';
    }

    private function getSheetXml(Collection $bukus, ?User $petugas, array $activeFilters): string
    {
        $nowFormatted = Carbon::now()->locale('id')->translatedFormat('d F Y, H:i').' WIB';
        $namaPetugas = $petugas ? ($petugas->name.' ('.ucfirst($petugas->role).')') : 'Staf Perpustakaan';

        // Hitung statistik koleksi
        $totalJudul = $bukus->count();
        $totalEksemplar = 0;
        $totalDiRak = 0;
        $totalDiGudang = 0;
        $totalDipinjam = 0;
        $totalKondisiBaik = 0;
        $totalKondisiRusak = 0;

        foreach ($bukus as $buku) {
            foreach ($buku->eksemplar as $eks) {
                $totalEksemplar++;
                if ($eks->lokasi === 'Rak') {
                    $totalDiRak++;
                } else {
                    $totalDiGudang++;
                }

                if (in_array($eks->status, ['Dipinjam', 'Dibooking'], true)) {
                    $totalDipinjam++;
                }

                if ($eks->kondisi === 'Baik') {
                    $totalKondisiBaik++;
                } else {
                    $totalKondisiRusak++;
                }
            }
        }

        // Teks deskripsi filter aktif
        $filterTexts = [];
        if (! empty($activeFilters['search'])) {
            $filterTexts[] = 'Pencarian: "'.$activeFilters['search'].'"';
        }
        if (! empty($activeFilters['lokasi'])) {
            $filterTexts[] = 'Lokasi: '.$activeFilters['lokasi'];
        }
        if (! empty($activeFilters['kategori'])) {
            $filterTexts[] = 'Kategori ID: '.$activeFilters['kategori'];
        }
        if (! empty($activeFilters['status'])) {
            $filterTexts[] = 'Status: '.$activeFilters['status'];
        }
        if (! empty($activeFilters['kondisi'])) {
            $filterTexts[] = 'Kondisi: '.$activeFilters['kondisi'];
        }
        if (! empty($activeFilters['perlu_restock'])) {
            $filterTexts[] = 'Filter: Perlu Restock Rak';
        }
        $keteranganFilter = ! empty($filterTexts) ? implode(' | ', $filterTexts) : 'Semua Koleksi (Tanpa Filter)';

        // Mulai rakit Worksheet XML
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'."\n";
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'."\n";

        // Lebar kolom custom (A sampai N)
        $xml .= '<cols>'."\n";
        $xml .= '  <col min="1" max="1" width="6" customWidth="1"/>'."\n";    // A: No
        $xml .= '  <col min="2" max="2" width="13" customWidth="1"/>'."\n";   // B: ID Eksemplar
        $xml .= '  <col min="3" max="3" width="22" customWidth="1"/>'."\n";   // C: Barcode / QR
        $xml .= '  <col min="4" max="4" width="36" customWidth="1"/>'."\n";   // D: Judul Buku
        $xml .= '  <col min="5" max="5" width="16" customWidth="1"/>'."\n";   // E: Kategori
        $xml .= '  <col min="6" max="6" width="22" customWidth="1"/>'."\n";   // F: Penulis
        $xml .= '  <col min="7" max="7" width="24" customWidth="1"/>'."\n";   // G: Penerbit & Tahun
        $xml .= '  <col min="8" max="8" width="14" customWidth="1"/>'."\n";   // H: Eksemplar Ke
        $xml .= '  <col min="9" max="9" width="18" customWidth="1"/>'."\n";   // I: Penempatan Fisik
        $xml .= '  <col min="10" max="10" width="18" customWidth="1"/>'."\n"; // J: Status Sistem
        $xml .= '  <col min="11" max="11" width="15" customWidth="1"/>'."\n"; // K: Kondisi Sistem
        $xml .= '  <col min="12" max="12" width="16" customWidth="1"/>'."\n"; // L: Cek Fisik [ V/X ]
        $xml .= '  <col min="13" max="13" width="20" customWidth="1"/>'."\n"; // M: Kondisi Riil Fisik
        $xml .= '  <col min="14" max="14" width="28" customWidth="1"/>'."\n"; // N: Catatan Petugas
        $xml .= '</cols>'."\n";

        $xml .= '<sheetData>'."\n";

        // Row 1: Header Judul Laporan
        $xml .= '  <row r="1" ht="26" customHeight="1">'."\n";
        $xml .= '    <c r="A1" s="1" t="inlineStr"><is><t>'.$this->escapeXml('LEMBAR KERJA STOK OPNAME INVENTARIS BUKU - BOOKNEST').'</t></is></c>'."\n";
        $xml .= '  </row>'."\n";

        // Row 2: Metadata Waktu & Petugas
        $xml .= '  <row r="2" ht="18" customHeight="1">'."\n";
        $xml .= '    <c r="A2" s="3" t="inlineStr"><is><t>'.$this->escapeXml('Waktu Cetak: '.$nowFormatted.'  |  Dicetak Oleh: '.$namaPetugas).'</t></is></c>'."\n";
        $xml .= '  </row>'."\n";

        // Row 3: Parameter Filter
        $xml .= '  <row r="3" ht="18" customHeight="1">'."\n";
        $xml .= '    <c r="A3" s="3" t="inlineStr"><is><t>'.$this->escapeXml('Parameter Filter: '.$keteranganFilter).'</t></is></c>'."\n";
        $xml .= '  </row>'."\n";

        // Row 4: Ringkasan Metrik Cepat
        $ringkasanText = sprintf(
            'TOTAL: %d Judul Buku | %d Eksemplar Fisik (Di Rak: %d | Di Gudang: %d | Dipinjam/Booking: %d | Kondisi Baik: %d | Rusak/Hilang: %d)',
            $totalJudul,
            $totalEksemplar,
            $totalDiRak,
            $totalDiGudang,
            $totalDipinjam,
            $totalKondisiBaik,
            $totalKondisiRusak
        );
        $xml .= '  <row r="4" ht="22" customHeight="1">'."\n";
        $xml .= '    <c r="A4" s="9" t="inlineStr"><is><t>'.$this->escapeXml($ringkasanText).'</t></is></c>'."\n";
        $xml .= '  </row>'."\n";

        // Row 5: Kosong
        $xml .= '  <row r="5" ht="10" customHeight="1"></row>'."\n";

        // Row 6: Table Headers
        $headers = [
            'A6' => 'No',
            'B6' => 'ID Eksemplar',
            'C6' => 'Barcode / QR',
            'D6' => 'Judul Buku',
            'E6' => 'Kategori',
            'F6' => 'Penulis',
            'G6' => 'Penerbit & Tahun',
            'H6' => 'Eksemplar Ke',
            'I6' => 'Penempatan Fisik',
            'J6' => 'Status Sistem',
            'K6' => 'Kondisi Sistem',
            'L6' => 'Cek Fisik [ V / X ]',
            'M6' => 'Kondisi Riil Fisik',
            'N6' => 'Catatan / Paraf Petugas',
        ];

        $xml .= '  <row r="6" ht="26" customHeight="1">'."\n";
        foreach ($headers as $cellRef => $headerText) {
            $xml .= '    <c r="'.$cellRef.'" s="4" t="inlineStr"><is><t>'.$this->escapeXml($headerText).'</t></is></c>'."\n";
        }
        $xml .= '  </row>'."\n";

        // Row 7+: Data Rows (1 Baris per Eksemplar Fisik)
        $currentRow = 7;
        $nomorUrut = 1;

        foreach ($bukus as $buku) {
            $kategoriName = $buku->kategori?->namaKategori ?? '-';
            $penulis = $buku->penulis ?? '-';
            $penerbitTahun = trim(($buku->penerbit ?? '-').' ('.($buku->tahunTerbit ?? '-').')');
            $lokasiRakBuku = $buku->rak ? 'Rak '.$buku->rak : '-';

            if ($buku->eksemplar->isEmpty()) {
                // Buku belum memiliki data eksemplar fisik individual
                $isZebra = ($nomorUrut % 2 === 0);
                $styleText = $isZebra ? 7 : 5;
                $styleCenter = $isZebra ? 8 : 6;

                $barcodeBuku = $buku->barcode?->kodeBarcode ?? ('BK-'.str_pad((string) $buku->idBuku, 5, '0', STR_PAD_LEFT));

                $xml .= '  <row r="'.$currentRow.'" ht="20" customHeight="1">'."\n";
                $xml .= '    <c r="A'.$currentRow.'" s="'.$styleCenter.'"><v>'.$nomorUrut.'</v></c>'."\n";
                $xml .= '    <c r="B'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>-</t></is></c>'."\n";
                $xml .= '    <c r="C'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>'.$this->escapeXml($barcodeBuku).'</t></is></c>'."\n";
                $xml .= '    <c r="D'.$currentRow.'" s="'.$styleText.'" t="inlineStr"><is><t>'.$this->escapeXml($buku->judul).'</t></is></c>'."\n";
                $xml .= '    <c r="E'.$currentRow.'" s="'.$styleText.'" t="inlineStr"><is><t>'.$this->escapeXml($kategoriName).'</t></is></c>'."\n";
                $xml .= '    <c r="F'.$currentRow.'" s="'.$styleText.'" t="inlineStr"><is><t>'.$this->escapeXml($penulis).'</t></is></c>'."\n";
                $xml .= '    <c r="G'.$currentRow.'" s="'.$styleText.'" t="inlineStr"><is><t>'.$this->escapeXml($penerbitTahun).'</t></is></c>'."\n";
                $xml .= '    <c r="H'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>0 (Belum ada)</t></is></c>'."\n";
                $xml .= '    <c r="I'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>'.$this->escapeXml($lokasiRakBuku).'</t></is></c>'."\n";
                $xml .= '    <c r="J'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>Belum Ada Stok</t></is></c>'."\n";
                $xml .= '    <c r="K'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>-</t></is></c>'."\n";
                $xml .= '    <c r="L'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>[   ]</t></is></c>'."\n";
                $xml .= '    <c r="M'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t></t></is></c>'."\n";
                $xml .= '    <c r="N'.$currentRow.'" s="'.$styleText.'" t="inlineStr"><is><t></t></is></c>'."\n";
                $xml .= '  </row>'."\n";

                $currentRow++;
                $nomorUrut++;
            } else {
                foreach ($buku->eksemplar as $eks) {
                    $isZebra = ($nomorUrut % 2 === 0);
                    $styleText = $isZebra ? 7 : 5;
                    $styleCenter = $isZebra ? 8 : 6;

                    $barcodeEks = $eks->kode_barcode ?: ($buku->barcode?->kodeBarcode ?? ('EKS-'.$eks->idEksemplar));
                    $nomorEks = 'Eksemplar #'.($eks->nomor_eksemplar ?: '1');

                    // Posisi Fisik: Lokasi + Rak jika di Rak
                    if ($eks->lokasi === 'Rak') {
                        $penempatan = $buku->rak ? 'Rak: '.$buku->rak : 'Di Rak Display';
                    } else {
                        $penempatan = 'Gudang Cadangan';
                    }

                    // Status Rinci
                    $statusText = $eks->status;
                    if ($eks->status === 'Dipinjam' && $eks->activeDetail?->peminjaman?->member) {
                        $statusText .= ' ('.$eks->activeDetail->peminjaman->member->name.')';
                    }

                    $kondisiText = $eks->kondisi ?: 'Baik';

                    $xml .= '  <row r="'.$currentRow.'" ht="20" customHeight="1">'."\n";
                    $xml .= '    <c r="A'.$currentRow.'" s="'.$styleCenter.'"><v>'.$nomorUrut.'</v></c>'."\n";
                    $xml .= '    <c r="B'.$currentRow.'" s="'.$styleCenter.'"><v>'.$eks->idEksemplar.'</v></c>'."\n";
                    $xml .= '    <c r="C'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>'.$this->escapeXml($barcodeEks).'</t></is></c>'."\n";
                    $xml .= '    <c r="D'.$currentRow.'" s="'.$styleText.'" t="inlineStr"><is><t>'.$this->escapeXml($buku->judul).'</t></is></c>'."\n";
                    $xml .= '    <c r="E'.$currentRow.'" s="'.$styleText.'" t="inlineStr"><is><t>'.$this->escapeXml($kategoriName).'</t></is></c>'."\n";
                    $xml .= '    <c r="F'.$currentRow.'" s="'.$styleText.'" t="inlineStr"><is><t>'.$this->escapeXml($penulis).'</t></is></c>'."\n";
                    $xml .= '    <c r="G'.$currentRow.'" s="'.$styleText.'" t="inlineStr"><is><t>'.$this->escapeXml($penerbitTahun).'</t></is></c>'."\n";
                    $xml .= '    <c r="H'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>'.$this->escapeXml($nomorEks).'</t></is></c>'."\n";
                    $xml .= '    <c r="I'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>'.$this->escapeXml($penempatan).'</t></is></c>'."\n";
                    $xml .= '    <c r="J'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>'.$this->escapeXml($statusText).'</t></is></c>'."\n";
                    $xml .= '    <c r="K'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>'.$this->escapeXml($kondisiText).'</t></is></c>'."\n";
                    $xml .= '    <c r="L'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t>[   ]</t></is></c>'."\n";
                    $xml .= '    <c r="M'.$currentRow.'" s="'.$styleCenter.'" t="inlineStr"><is><t></t></is></c>'."\n";
                    $xml .= '    <c r="N'.$currentRow.'" s="'.$styleText.'" t="inlineStr"><is><t></t></is></c>'."\n";
                    $xml .= '  </row>'."\n";

                    $currentRow++;
                    $nomorUrut++;
                }
            }
        }

        $xml .= '</sheetData>'."\n";
        $xml .= '</worksheet>';

        return $xml;
    }

    private function escapeXml(string $string): string
    {
        return htmlspecialchars($string, ENT_XML1, 'UTF-8');
    }
}
