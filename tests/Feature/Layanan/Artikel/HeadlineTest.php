<?php

namespace Tests\Feature\Layanan\Artikel;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeadlineTest extends TestCase
{
    // use RefreshDatabase;
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
    }

    public function test_route()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/artikel/headline', ['Authorization' => "Bearer $this->token"]);
        //test route
        $response->assertStatus(200);
    }

    public function test_daftar_headline()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/artikel/headline', ['Authorization' => "Bearer $this->token"]);
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
        $this->assertCount(1, $data);
    }
}
