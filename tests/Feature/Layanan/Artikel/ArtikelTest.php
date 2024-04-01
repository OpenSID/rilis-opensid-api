<?php

namespace Tests\Feature\Layanan\Artikel;

use Tests\TestCase;

class ArtikelTest extends TestCase
{
    public function test_router()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/artikel/', ['Authorization' => "Bearer $this->token"]);
        //test route
        $response->assertStatus(200);

        $response = $this->get('api/v1/artikel/read/perdes-phbs', ['Authorization' => "Bearer $this->token"]);
        //test route
        $response->assertStatus(200);
    }

    public function test_unauthenticated()
    {
        $response = $this->get('api/v1/artikel/');
        //test route
        $response->assertStatus(500);
    }

    public function test_daftar_artikel()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/artikel/', ['Authorization' => "Bearer $this->token"]);
        //test route
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                [
                    "type",
                    "id",
                    "attributes" => [
                        "slug",
                        "title",
                        "text",
                        "image",
                        "image1",
                        "image2",
                        "iamge3",
                        "url",
                        "read_count",
                        "estimate_reading",
                        "created_at",
                        "jumlah_komentar",
                        "komentar" => []
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
        $this->assertCount(4, $data);
    }

    public function test_read_artikel()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/artikel/read/perdes-phbs', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "type",
                "id",
                "attributes" => [
                    "slug",
                    "title",
                    "text",
                    "image",
                    "image1",
                    "image2",
                    "iamge3",
                    "url",
                    "read_count",
                    "estimate_reading",
                    "created_at",
                    "jumlah_komentar",
                    "komentar" => []
                ]
            ],
        ]);

        $response = $this->get('api/v1/artikel/read/test', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data",
        ]);
    }
}
