<?php

namespace Tests\Feature\Layanan\Pesan;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PesanTest extends TestCase
{
    use DatabaseTransactions;

    private $baseApiUrl = '/api/v1/layanan-mandiri/pesan';

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        // Asumsikan bahwa method Penduduk() mengatur user dan mendapatkan token
        $this->Penduduk();
    }

    public function testUnauthorizedAccess()
    {
        //tipe get
        $endpoints = [
            "$this->baseApiUrl/tipe/masuk",
            "$this->baseApiUrl/detail/12",
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->json('GET', $endpoint, ['Accept' => 'application/json']);
            $response->assertStatus(401);
        }

        // tipe post
        $endpoints = [
            $this->baseApiUrl,
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->json('Post', $endpoint, [], ['Accept' => 'application/json']);
            $response->assertStatus(401);
        }
    }

    /**
     * @dataProvider authorizedAccessDataProvider
     */
    public function testAuthorizedAccess($method, $url, $data, $expectedStatus)
    {

        if ($method == 'get') {
            $response = $this->$method($url, ['Authorization' => "Bearer $this->token", 'Accept' => 'application/json']);
        } else {
            $response = $this->$method($url, $data, ['Authorization' => "Bearer $this->token", 'Accept' => 'application/json']);
        }

        $response->assertStatus($expectedStatus);
    }

    public function authorizedAccessDataProvider()
    {

        return [
            ['get', '/api/v1/layanan-mandiri/pesan/tipe/masuk', [], 200],
            ['get', '/api/v1/layanan-mandiri/pesan/tipe/keluar', [], 200],
            ['get', '/api/v1/layanan-mandiri/pesan/detail/12', [], 200],
            ['post', '/api/v1/layanan-mandiri/pesan', ['subjek' => 'subjek', 'pesan' => 'isi pesan'], 200],
        ];
    }

    public function testJsonStructureForMessages()
    {
        $this->withHeaders(['Authorization' => "Bearer $this->token"])
            ->get("$this->baseApiUrl/tipe/masuk")
            ->assertJsonStructure([
                "data" => [
                    '*' => [
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
    }
}
