<?php

namespace Tests\Feature\Layanan\Artikel;

use Tests\TestCase;

class AgendaTest extends TestCase
{
    public function test_router()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/artikel/agenda-desa', ['Authorization' => "Bearer $this->token"]);
        //test route
        $response->assertStatus(200);
    }

    public function test_daftar_agenda()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/artikel/agenda-desa', ['Authorization' => "Bearer $this->token"]);

        $response->assertJsonStructure([
            "data" => [
                [
                    "type",
                    "id",
                    "attributes" => [
                        "id_artikel",
                        "nama_agenda",
                        "tgl_agenda",
                        "koordinator_kegiatan",
                        "lokasi_kegiatan",
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
            ],
            "links" => [
                "self",
                "first",
                "last"
            ]
        ]);

        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);
    }
}
