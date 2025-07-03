<?php

namespace Tests\Feature\Layanan\Pesan;

use Tests\TestCase;

class DaftarPesanTest extends TestCase
{
    public function test_daftar()
    {
        $this->Penduduk();
        $response = $this
        ->withHeaders(['Authorization' => "Bearer $this->token"])
        ->post('api/v1/layanan-mandiri/pesan/', [
            'subjek' => 'Test Subjek',
            'pesan' => 'Ini isi pesan',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "type",
                    "id",
                    "attributes" => [
                        "owner",
                        "email",
                        "phone",
                        "subject",
                        "comment",
                    ]
            ]
        ]);
    }
}
