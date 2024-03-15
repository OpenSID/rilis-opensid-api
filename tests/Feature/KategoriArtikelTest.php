<?php

namespace Tests\Feature;

use Tests\TestCase;

class KategoriArtikelTest extends TestCase
{
    public function test_route()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/artikel/kategori', ['Authorization' => "Bearer $this->token"]);
        //test route
        $response->assertStatus(200);
    }

    public function test_daftar_kategori()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/artikel/kategori', ['Authorization' => "Bearer $this->token"]);
        //test route
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                [
                    "type",
                    "id",
                    "attributes" => [
                        "slug",
                        "name",
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
        $this->assertCount(3, $data);
    }

    public function test_slug_kategori()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/artikel/kategori/peraturan-desa', ['Authorization' => "Bearer $this->token"]);
        //test route
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "type",
                "id",
                "attributes" => [
                    "slug",
                    "name",
                ]
            ],
        ]);
    }
}
