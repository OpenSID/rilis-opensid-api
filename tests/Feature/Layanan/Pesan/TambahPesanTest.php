<?php

namespace Tests\Feature\Layanan\Pesan;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Tests\TestCase;

class TambahPesanTest extends TestCase
{
    // use RefreshDatabase;
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
    }


    public function test_tambah()
    {
        $this->Penduduk();
        $kk = File::image('logo.png', 400, 100);
        $data = [
            'nama_pesan' => 'fk KK',
            'syarat' => 2,
            'file' => $kk
        ];

        $response = $this->post('api/v1/layanan-mandiri/pesan/store', $data, [
            'Authorization' => "Bearer $this->token",
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];

        $response = $this->get('api/v1/layanan-mandiri/pesan/'.$data['id'].'/download', [
            'Authorization' => "Bearer $this->token",
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);

        $response = $this->get('api/v1/layanan-mandiri/pesan', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);
    }
}
