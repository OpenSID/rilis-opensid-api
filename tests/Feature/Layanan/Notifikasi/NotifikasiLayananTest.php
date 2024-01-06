<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotifikasiLayananTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
    }

    public function test_unauthentication()
    {
        $response = $this->get('api/v1/layanan-mandiri/notifikasi', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(500);
    }
    public function test_ambil_data()
    {
        $this->Penduduk();

        // test route
        $response = $this->get('api/v1/layanan-mandiri/notifikasi', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        // cek struktur data
        $response->assertJsonStructure([
            "data" => [
                [
                    "type",
                    "id",
                    "attributes" => [
                        "judul",
                        "isi",
                        "payload",
                        "read"
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

    public function test_hitung_jumlah()
    {
        $this->Penduduk();

        // test route
        $response = $this->get('api/v1/layanan-mandiri/notifikasi', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);
    }

    public function test_gagal_show()
    {
        $this->Penduduk();
        // Cek perubahan 
        $response = $this->get('api/v1/layanan-mandiri/notifikasi/show?id=10', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(404);
    }

    public function test_gagal_show_2()
    {
        $this->Penduduk2();
        // Cek perubahan 
        $response = $this->get('api/v1/layanan-mandiri/notifikasi/show?id=1', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(404);
    }

    public function test_berhasil_show()
    {
        $this->Penduduk();
        // Cek perubahan 
        $response = $this->get('api/v1/layanan-mandiri/notifikasi/show?id=1', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        // cek struktur data
        $response->assertJsonStructure([
            "success",
            "data" => [
                "id",
                "id_user_mandiri",
                "config_id",
                "judul",
                "isi",
                "image",
                "payload",
                "read",
                "created_at",
                "updated_at"
            ],
            "message"
        ]);

    }

    public function test_berhasil_read()
    {
        $this->Penduduk();
        // baca notifikasi
        $response = $this->post('api/v1/layanan-mandiri/notifikasi/read', ['id' => 1], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        // Cek perubahan 
        $response = $this->get('api/v1/layanan-mandiri/notifikasi/show?id=1', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertEquals(1, $data['read']);
    }
}
