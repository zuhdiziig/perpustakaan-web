<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use ZipArchive;

class BukuExportExcelTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $petugas;

    private User $member;

    private Buku $buku;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $this->member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $kategori = Kategori::create([
            'namaKategori' => 'Fiksi & Sastra',
            'deskripsi' => 'Kategori fiksi',
        ]);

        $this->buku = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'penerbit' => 'Bentang Pustaka',
            'tahunTerbit' => '2005',
            'rak' => 'Rak F-01',
            'stok' => 3,
            'kondisi' => 'Baik',
        ]);

        $eks1 = $this->buku->eksemplar()->where('nomor_eksemplar', 1)->first();
        if ($eks1) {
            $eks1->update([
                'kode_barcode' => 'BK-0001-01',
                'lokasi' => 'Rak',
                'status' => 'Tersedia',
                'kondisi' => 'Baik',
            ]);
        }

        $eks2 = $this->buku->eksemplar()->where('nomor_eksemplar', 2)->first();
        if ($eks2) {
            $eks2->update([
                'kode_barcode' => 'BK-0001-02',
                'lokasi' => 'Gudang',
                'status' => 'Tersedia',
                'kondisi' => 'Baik',
            ]);
        }
    }

    public function test_petugas_can_view_export_excel_button_on_inventory_page(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('buku.index'));

        $response->assertOk();
        $response->assertSee('Cetak Excel (Stok Opname)');
        $response->assertSee(route('buku.export-excel'));
    }

    public function test_petugas_can_download_stok_opname_excel(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('buku.export-excel'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $contentDisposition = $response->headers->get('content-disposition');
        $this->assertStringContainsString('attachment', $contentDisposition);
        $this->assertStringContainsString('Stok_Opname_Buku_', $contentDisposition);
        $this->assertStringContainsString('.xlsx', $contentDisposition);

        // Verifikasi bahwa isi response adalah arsip zip XLSX yang valid
        $streamContent = $response->streamedContent();
        $this->assertNotEmpty($streamContent);

        $tempPath = tempnam(sys_get_temp_dir(), 'test_verify_').'.xlsx';
        file_put_contents($tempPath, $streamContent);

        $zip = new ZipArchive;
        $openResult = $zip->open($tempPath);
        $this->assertTrue($openResult === true);

        // Verifikasi struktur internal OpenXML Spreadsheet
        $this->assertNotFalse($zip->locateName('[Content_Types].xml'));
        $this->assertNotFalse($zip->locateName('xl/workbook.xml'));
        $this->assertNotFalse($zip->locateName('xl/styles.xml'));
        $this->assertNotFalse($zip->locateName('xl/worksheets/sheet1.xml'));

        // Cek data buku ada dalam worksheet
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $this->assertStringContainsString('Laskar Pelangi', $sheetXml);
        $this->assertStringContainsString('Andrea Hirata', $sheetXml);
        $this->assertStringContainsString('BK-0001-01', $sheetXml);
        $this->assertStringContainsString('LEMBAR KERJA STOK OPNAME INVENTARIS BUKU', $sheetXml);

        $zip->close();
        @unlink($tempPath);
    }

    public function test_admin_can_also_download_stok_opname_excel(): void
    {
        $response = $this->actingAs($this->admin)->get(route('buku.export-excel'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_member_is_forbidden_from_exporting_excel(): void
    {
        $response = $this->actingAs($this->member)->get(route('buku.export-excel'));

        $response->assertForbidden();
    }

    public function test_export_excel_with_filters(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('buku.export-excel', [
            'search' => 'Laskar',
            'lokasi' => 'Rak',
            'kondisi' => 'Baik',
        ]));

        $response->assertOk();

        $contentDisposition = $response->headers->get('content-disposition');
        $this->assertStringContainsString('rak', strtolower($contentDisposition));

        $streamContent = $response->streamedContent();
        $tempPath = tempnam(sys_get_temp_dir(), 'test_verify_').'.xlsx';
        file_put_contents($tempPath, $streamContent);

        $zip = new ZipArchive;
        $zip->open($tempPath);
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');

        $this->assertStringContainsString('Laskar Pelangi', $sheetXml);
        $this->assertStringContainsString('Pencarian: "Laskar"', $sheetXml);

        $zip->close();
        @unlink($tempPath);
    }
}
