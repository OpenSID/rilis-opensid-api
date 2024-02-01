<?php

namespace Tests\Feature\Layanan\Surat;

use Tests\TestCase;

class ArsipSuratTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_daftar()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/layanan-mandiri/surat/permohonan/', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                [
                    "type",
                    "id",
                    "attributes" => [
                        "nama_penduduk",
                        "jenis_surat",
                        "status",
                        "tanggal_kirim"
                    ]
                ]
            ],
            "meta" => [
                "pagination" => [
                    "total",
                    "count",
                    "per_page",
                    "current_page",
                    "total_pages"
                ]
            ]
        ]);

        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(3, $data);
    }
}
