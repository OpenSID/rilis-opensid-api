<?php

namespace Tests\Feature\Admin\Notifikasi;

use App\Models\LogNotifikasiAdmin;
use Tests\TestCase;

class NotifikasiTest extends TestCase
{
    public function test_admin()
    {
        $this->Admin_user();

        /* cek daftar surat
         alamat router '/api/admin/notifikasi'
         - struktur data
         - jumlah data
        */
        $response = $this->get('/api/admin/notifikasi', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200)->assertJsonStructure([
            "data" => [
                [
                    "type" ,
                    "id",
                    "attributes" => [
                        "judul",
                        "isi",
                        "payload",
                        "read"
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
        $this->assertCount(15, $data);

        /* cek jumlah surat
         alamat router '/api/admin/notifikasi/jumlah'
        */

        $response = $this->get('/api/admin/notifikasi/jumlah', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertEquals(17, $data['jumlah']);


        /* cek data notifikasi
         alamat router '/api/admin/notifikasi/show'
        */

        // test jika id salah
        $response = $this->get('/api/admin/notifikasi/show?id=2', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertNull($data);

        // test jika id benar tetapi id user berbeda
        $response = $this->get('/api/admin/notifikasi/show?id=28', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertNull($data);

        // test jika id benar
        $response = $this->get('/api/admin/notifikasi/show?id=7', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200)->assertJsonStructure([
            "success",
            "message",
            "data" => [
                "id",
                "id_user",
                "config_id",
                "judul",
                "isi",
                "image",
                "payload",
                "read",
                "created_at",
                "updated_at"
            ]
        ]);

        $data = $response->decodeResponseJson()['data'];
        $this->assertIsArray($data);

        /* cek data saat baca notifikasi
         alamat router '/api/admin/notifikasi/read'
        */

        // cek seblum di read
        $response = $this->get('/api/admin/notifikasi/show?id=12', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertEquals(0, $data['read']);

        // lakukan read
        $response = $this->post('/api/admin/notifikasi/read', ['id' => 12], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        // cek setelah read
        $response = $this->get('/api/admin/notifikasi/show?id=12', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];

        LogNotifikasiAdmin::where('id', 12)->update(['read' => 0]); // kembalikan read menjadi 0


        // read id salah
        $response = $this->post('/api/admin/notifikasi/read', ['id' => 2], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(404);

        // read id dari user id lain
        $response = $this->post('/api/admin/notifikasi/read', ['id' => 29], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(404);
    }

    public function test_sekdes()
    {
        $this->Sekdes_user();

        /* cek daftar surat
         alamat router '/api/admin/notifikasi'
         - struktur data
         - jumlah data
        */
        $response = $this->get('/api/admin/notifikasi', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200)->assertJsonStructure([
            "data" => [
                [
                    "type" ,
                    "id",
                    "attributes" => [
                        "judul",
                        "isi",
                        "payload",
                        "read"
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
        $this->assertCount(15, $data);

        /* cek jumlah surat
         alamat router '/api/admin/notifikasi/jumlah'
        */

        $response = $this->get('/api/admin/notifikasi/jumlah', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertEquals(11, $data['jumlah']);

        /* cek data notifikasi
         alamat router '/api/admin/notifikasi/show'
        */

        // test jika id salah
        $response = $this->get('/api/admin/notifikasi/show?id=2', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertNull($data);

        // test jika id benar tetapi id user berbeda
        $response = $this->get('/api/admin/notifikasi/show?id=10', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertNull($data);

        // test jika id benar
        $response = $this->get('/api/admin/notifikasi/show?id=29', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200)->assertJsonStructure([
            "success",
            "message",
            "data" => [
                "id",
                "id_user",
                "config_id",
                "judul",
                "isi",
                "image",
                "payload",
                "read",
                "created_at",
                "updated_at"
            ]
        ]);

        $data = $response->decodeResponseJson()['data'];
        $this->assertIsArray($data);

        /* cek data saat baca notifikasi
         alamat router '/api/admin/notifikasi/read'
        */

        // cek seblum di read
        $response = $this->get('/api/admin/notifikasi/show?id=38', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertEquals(0, $data['read']);

        // lakukan read
        $response = $this->post('/api/admin/notifikasi/read', ['id' => 38], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        // cek setelah read
        $response = $this->get('/api/admin/notifikasi/show?id=38', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];

        LogNotifikasiAdmin::where('id', 38)->update(['read' => 0]); // kembalikan read menjadi 0

        // read id salah
        $response = $this->post('/api/admin/notifikasi/read', ['id' => 2], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(404);

        // read id dari user id lain
        $response = $this->post('/api/admin/notifikasi/read', ['id' => 10], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(404);
    }

    public function test_kades()
    {
        $this->Kades_user();

        /* cek daftar surat
         alamat router '/api/admin/notifikasi'
         - struktur data
         - jumlah data
        */
        $response = $this->get('/api/admin/notifikasi', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200)->assertJsonStructure([
            "data" => [
                [
                    "type" ,
                    "id",
                    "attributes" => [
                        "judul",
                        "isi",
                        "payload",
                        "read"
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
        $this->assertCount(13, $data);

        /* cek jumlah surat
         alamat router '/api/admin/notifikasi/jumlah'
        */

        $response = $this->get('/api/admin/notifikasi/jumlah', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertEquals(11, $data['jumlah']);

        /* cek data notifikasi
         alamat router '/api/admin/notifikasi/show'
        */

        // test jika id salah
        $response = $this->get('/api/admin/notifikasi/show?id=2', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertNull($data);

        // test jika id benar tetapi id user berbeda
        $response = $this->get('/api/admin/notifikasi/show?id=10', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $data = $response->decodeResponseJson()['data'];
        $this->assertNull($data);

        // test jika id benar
        $response = $this->get('/api/admin/notifikasi/show?id=51', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200)->assertJsonStructure([
            "success",
            "message",
            "data" => [
                "id",
                "id_user",
                "config_id",
                "judul",
                "isi",
                "image",
                "payload",
                "read",
                "created_at",
                "updated_at"
            ]
        ]);

        $data = $response->decodeResponseJson()['data'];
        $this->assertIsArray($data);

        /* cek data saat baca notifikasi
         alamat router '/api/admin/notifikasi/read'
        */

        // cek seblum di read
        $response = $this->get('/api/admin/notifikasi/show?id=55', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertEquals(0, $data['read']);

        // lakukan read
        $response = $this->post('/api/admin/notifikasi/read', ['id' => 55], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        // cek setelah read
        $response = $this->get('/api/admin/notifikasi/show?id=55', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];

        LogNotifikasiAdmin::where('id', 55)->update(['read' => 0]); // kembalikan read menjadi 0

        // read id salah
        $response = $this->post('/api/admin/notifikasi/read', ['id' => 2], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(404);

        // read id dari user id lain
        $response = $this->post('/api/admin/notifikasi/read', ['id' => 10], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(404);
    }
}
