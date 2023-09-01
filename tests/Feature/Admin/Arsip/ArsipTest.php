<?php

namespace Tests\Feature\Admin\Arsip;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ArsipTest extends TestCase
{
    // use RefreshDatabase;
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();

        // $this->seed(StafPemerintahDesaSeeder::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_route()
    {
        $this->Admin_user();

        $response = $this->get('/api/admin/surat/arsip', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
    }

    public function test_arsip_operator()
    {
        $this->Admin_user();
        $response = $this->get('/api/admin/surat/arsip', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'type',
                        'id',
                        'attributes' => [
                            'nama_penduduk',
                            'cetak',
                            'nama_surat',
                            'tanggal',
                            'download'
                        ]
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages'
                    ]
                ],
                'links' => [
                    'self',
                    'first',
                    'last'
                ]
            ]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(11, $data);
    }

    public function test_arsip_sekdes()
    {
        $this->Sekdes_user();
        $response = $this->get('/api/admin/surat/arsip', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'type',
                        'id',
                        'attributes' => [
                            'nama_penduduk',
                            'cetak',
                            'nama_surat',
                            'tanggal',
                            'download'
                        ]
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages'
                    ]
                ],
                'links' => [
                    'self',
                    'first',
                    'last'
                ]
            ]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(11, $data);
    }

    public function test_arsip_kades()
    {
        $this->Kades_user();
        $response = $this->get('/api/admin/surat/arsip', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'type',
                        'id',
                        'attributes' => [
                            'nama_penduduk',
                            'cetak',
                            'nama_surat',
                            'tanggal',
                            'download'
                        ]
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages'
                    ]
                ],
                'links' => [
                    'self',
                    'first',
                    'last'
                ]
            ]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(1, $data);
    }
}
