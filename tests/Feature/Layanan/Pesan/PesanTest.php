<?php

namespace Tests\Pesan;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PesanTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
    }

    public function test_layanan_mandiri_pesan_index()
    {
        $response = $this->get('/api/v1/layanan-mandiri/pesan/tipe/masuk', ['Accept' => "application/json"]);
        $response->assertStatus(401);
        $this->Penduduk();
        $response = $this->get('/api/v1/layanan-mandiri/pesan/tipe/masuk', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                [
                    "type",
                    "id",
                    "attributes" => [
                        "id_artikel",
                        "owner",
                        "email",
                        "phone",
                        "subject",
                        "comment",
                        "created_at"
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
        $this->assertCount(1, $data);

        $response = $this->get('/api/v1/layanan-mandiri/pesan/tipe/keluar', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
    }

    public function test_layanan_mandiri_pesan_show()
    {
        // test route
        $response = $this->get('/api/v1/layanan-mandiri/pesan/detail/12', ['Accept' => "application/json"]);
        $response->assertStatus(401);

        $this->Penduduk();
        $response = $this->get('/api/v1/layanan-mandiri/pesan/detail/12', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" =>
            [
                "type",
                "id",
                "attributes" => [
                    "id_artikel",
                    "owner",
                    "email",
                    "phone",
                    "subject",
                    "comment",
                    "created_at"
                ]
            ]

        ]);
    }

    public function test_layanan_mandiri_pesan_store()
    {

        $response = $this->post('/api/v1/layanan-mandiri/pesan', [],  ['Accept' => "application/json"]);
        $response->assertStatus(401);

        $this->Penduduk();
        $response = $this->post('/api/v1/layanan-mandiri/pesan', [], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(302);

        $response = $this->post('/api/v1/layanan-mandiri/pesan', [
            'subjek' => 'subjek',
            'pesan' => 'isi',
        ], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
    }
}
