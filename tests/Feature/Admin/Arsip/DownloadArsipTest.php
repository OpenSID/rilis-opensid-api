<?php

namespace Tests\Feature\Admin\Arsip;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadArsipTest extends TestCase
{
    public function test_operator()
    {
        $this->Admin_user();

        Storage::fake('ftp');

        Storage::disk('ftp')->put('desa/arsip/surat-keterangan-pindah-penduduk_3275014601977005_2023-08-01_1.pdf', 'test');

        Storage::disk('ftp')->put('desa/arsip/surat-keterangan-pindah-penduduk_3275014601977005_2023-08-01_1.pdf', 'test');
        $response = $this->post("/api/admin/surat/download/8", [], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);


        // jika surat dengan rtf
        Storage::disk('ftp')->put('desa/arsip/surat_ket_domisili_5201141506856997_2023-08-01_2.rtf', 'test');
        $response = $this->post("/api/admin/surat/download/16", [], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(400);

        $data = $response->decodeResponseJson();


        $this->assertEquals(false, $data['success']);

        // jika id tidak ditemukan

        $id = base64_encode(1); // base64 id log surat
        $response = $this->post("/api/admin/surat/download/{$id}", [], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(404);
    }

    public function test_sekdes()
    {
        $this->Sekdes_user();

        Storage::fake('ftp');

        Storage::disk('ftp')->put('desa/arsip/surat-keterangan-pindah-penduduk_3275014601977005_2023-08-01_1.pdf', 'test');

        Storage::disk('ftp')->put('desa/arsip/surat-keterangan-pindah-penduduk_3275014601977005_2023-08-01_1.pdf', 'test');
        $response = $this->post("/api/admin/surat/download/8", [], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        // jika surat dengan rtf
        Storage::disk('ftp')->put('desa/arsip/surat_ket_domisili_5201141506856997_2023-08-01_2.rtf', 'test');
        $response = $this->post("/api/admin/surat/download/16", [], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(400);
        $data = $response->decodeResponseJson();
        $this->assertEquals(false, $data['success']);

        // jika id tidak ditemukan

        $id = base64_encode(7); // base64 id log surat
        $response = $this->post("/api/admin/surat/download/{$id}", [], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(404);
    }

    public function test_kades()
    {
        $this->Kades_user();

        Storage::fake('ftp');

        Storage::disk('ftp')->put('desa/arsip/surat-keterangan-pindah-penduduk_3275014601977005_2023-08-01_1.pdf', 'test');

        Storage::disk('ftp')->put('desa/arsip/surat-keterangan-kematian_0520114200500001_2023-08-05_2.pdf', 'test');
        $response = $this->post("/api/admin/surat/download/23", [], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);

        // jika surat dengan rtf

        Storage::disk('ftp')->put('desa/arsip/surat_ket_domisili_3275014601977005_2023-08-01_1.rtf', 'test');
        $response = $this->post("/api/admin/surat/download/11", [], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(400);
        $data = $response->decodeResponseJson();
        $this->assertEquals(false, $data['success']);

        // jika id tidak ditemukan

        $id = base64_encode(7); // base64 id log surat
        $response = $this->post("/api/admin/surat/download/{$id}", [], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(404);
    }
}
