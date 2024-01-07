<?php

namespace Tests\Feature\Layanan\Dokumen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DaftarDokumenTest extends TestCase
{

    public function test_daftar()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/layanan-mandiri/dokumen/', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                [
                    "type",
                    "id",
                    "attributes" => [
                        "nama",
                        "jenis_dokumen" => [
                            "id",
                            "nama"
                        ],
                        "file",
                        "tanggal_upload" 
                    ]
                ]
            ]
        ]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(1, $data);
    }
}
