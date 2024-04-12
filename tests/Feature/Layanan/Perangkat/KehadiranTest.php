<?php

namespace Tests\Kehadiran;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class KehadiranTest extends TestCase
{
    use DatabaseTransactions;

    private $baseApiUrl = '/api/v1/layanan-mandiri/perangkat';

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
            "$this->baseApiUrl/kehadiran",
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->json('GET', $endpoint,  ['Accept' => 'application/json']);
            $response->assertStatus(401);
        }

        // tipe post
        $endpoints = [
            "$this->baseApiUrl/laporkan",
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->json('Post', $endpoint, [],  ['Accept' => 'application/json']);
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
            ['get',  "$this->baseApiUrl/kehadiran", [], 200],
            ['post',  "$this->baseApiUrl/laporkan", ['id' => 20], 200],
        ];
    }

    public function testJsonStructureForMessages()
    {
        $this->withHeaders(['Authorization' => "Bearer $this->token"])
            ->get("$this->baseApiUrl/kehadiran")
            ->assertJsonStructure([
                "data" => [
                    '*' => [
                        "type",
                        "id",
                        "attributes" => [
                            "nama",
                            "sex",
                            "foto",
                            "jabatan",
                            "status_kehadiran",
                            "status",
                        ]
                    ]
                ],
                
            ]);
    }
}
