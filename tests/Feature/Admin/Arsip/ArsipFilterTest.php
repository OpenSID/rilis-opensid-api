<?php

namespace Tests\Feature;

use Tests\TestCase;

class ArsipFilterTest extends TestCase
{
    public function test_route()
    {
        $this->Admin_user();

        $response = $this->get('/api/admin/surat/arsip', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        $response = $this->get('/api/admin/surat/arsip?filter[verifikasi]=1', ['Authorization' => "Bearer $this->token"]);
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
                            'tanggal'
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

        $response = $this->get('/api/admin/surat/arsip?filter[no_suratan]', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(400);
    }

    public function test_arsip_operator()
    {
        $this->Admin_user();

        // test case nomor surat like '%2%'
        $response = $this->get('/api/admin/surat/arsip?filter[no_surat]=2', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);

        // test case nomor surat like '%satu%'
        $response = $this->get('/api/admin/surat/arsip?filter[no_surat]=satu', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(0, $data);

        // test case nomor surat like '%Muhammad Ilham%'
        $response = $this->get('/api/admin/surat/arsip?filter[nama_pamong]=Muhammad Ilham', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(7, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[tanggal]=2023-08-02', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(1, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[tanggal]=2023', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(7, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[verifikasi]=0', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(4, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[verifikasi]=2', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(1, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[verifikasi]=1', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);
    }

    public function test_arsip_sekdes()
    {
        $this->Sekdes_user();

        $response = $this->get('/api/admin/surat/arsip', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(3, $data);

        // test case nomor surat 2'
        $response = $this->get('/api/admin/surat/arsip?filter[no_surat]=2', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);

        // test case nomor surat like '80'
        $response = $this->get('/api/admin/surat/arsip?filter[no_surat]=80', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(0, $data);

        // test case nomor surat like '%Muhammad Ilham%'
        $response = $this->get('/api/admin/surat/arsip?filter[nama_pamong]=Muhammad Ilham', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(3, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[tanggal]=2023-08-05', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(1, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[tanggal]=2023', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(3, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[verifikasi]=0', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(0, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[verifikasi]=2', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(1, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[verifikasi]=1', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);
    }

    public function test_arsip_kades()
    {
        $this->Kades_user();

        $response = $this->get('/api/admin/surat/arsip', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);

        // test case nomor surat 2'
        $response = $this->get('/api/admin/surat/arsip?filter[no_surat]=2', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(1, $data);

        // test case nomor surat like '80'
        $response = $this->get('/api/admin/surat/arsip?filter[no_surat]=80', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(0, $data);

        // test case nomor surat like '%Muhammad Ilham%'
        $response = $this->get('/api/admin/surat/arsip?filter[nama_pamong]=Muhammad Ilham', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[tanggal]=2023-08-05', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(0, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[tanggal]=2023', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[verifikasi]=0', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(0, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[verifikasi]=2', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(0, $data);

        $response = $this->get('/api/admin/surat/arsip?filter[verifikasi]=1', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);
    }
}
