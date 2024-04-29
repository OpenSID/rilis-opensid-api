<?php

namespace Tests\Feature\Admin\Pengaduan;

use Tests\TestCase;

class PengaduanTest extends TestCase
{
    public function test_route()
    {
        // route tanpa token
        $response = $this->get('/api/admin/pengaduan');
        $response->assertStatus(500);

        $response = $this->get('/api/admin/pengaduan/foto?id=2');
        $response->assertStatus(500);

        $response = $this->get('/api/admin/pengaduan/badge');
        $response->assertStatus(500);

        // route dengan token
        $this->Admin_user();
        $response = $this->get('/api/admin/pengaduan', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        $response = $this->get('/api/admin/pengaduan/foto?id=2', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        $response = $this->get('/api/admin/pengaduan/badge', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);


    }

    public function test_list_pengaduan()
    {
        $this->Admin_user();
        $response = $this->get('/api/admin/pengaduan', ['Authorization' => "Bearer $this->token"]);

        $response->assertJsonStructure([
            "data" => [
                [
                    "type",
                    "id",
                    "attributes" => [
                        "id_pengaduan",
                        "email",
                        "nama",
                        "telepon",
                        "judul",
                        "isi",
                        "status",
                        "foto",
                        "created_at",
                        "child"
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
        $this->assertCount(2, $data);
    }

    public function test_badge()
    {
        $this->Admin_user();
        $response = $this->get('api/admin/pengaduan/badge', ['Authorization' => "Bearer $this->token"]);

        $response->assertJsonStructure([
            "success",
            "data",
            "message"
        ]);

        $data = $response->decodeResponseJson()['data'];
        $this->assertEquals(1, $data);
    }

    public function test_show()
    {
        $this->Admin_user();
        $response = $this->get('/api/admin/pengaduan/show?id=2', ['Authorization' => "Bearer $this->token"]);

        $response->assertJsonStructure([
            "data" => [
                "id",
                "config_id",
                "id_pengaduan",
                "nik",
                "nama",
                "email",
                "telepon",
                "judul",
                "isi",
                "status",
                "foto",
                "ip_address",
                "created_at",
                "updated_at",
                "url_foto",
                "child" => [
                    [
                        "id",
                        "config_id",
                        "id_pengaduan",
                        "nik",
                        "nama",
                        "email",
                        "telepon",
                        "judul",
                        "isi",
                        "status",
                        "foto",
                        "ip_address",
                        "created_at",
                        "updated_at",
                        "url_foto",
                        "child",
                    ]
                ]
            ],
            "success",
            "message"
        ]);

        $response = $this->get('/api/admin/pengaduan/show?id=6', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);

    }

    public function test_foto()
    {
        $this->Admin_user();
        $response = $this->get('/api/admin/pengaduan/foto?id=2', ['Authorization' => "Bearer $this->token"]);

        $response->assertJsonStructure([
            "success",
            "data",
            "message"
        ]);

        $response = $this->get('/api/admin/pengaduan/foto?id=6', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(500);
    }

    public function test_tangapi()
    {
        $this->Admin_user();
        $response = $this->post('/api/admin/pengaduan/tanggapi', ['status' => 2 ], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(302);

        $response = $this->post('/api/admin/pengaduan/tanggapi', ['status' => 2, 'tanggapan' => 'test', 'id_pengaduan' =>  1], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
    }

}
