<?php

namespace Tests\Feature\Layanan\Artikel;

use App\Models\Komentar;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KomentarTest extends TestCase
{
    // use RefreshDatabase;
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
    }

    public function test_route()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/artikel/komentar', ['Authorization' => "Bearer $this->token"]);
        //test route
        $response->assertStatus(200);

        $this->Penduduk();
        $response = $this->get('api/v1/artikel/komentar/95', ['Authorization' => "Bearer $this->token"]);
        //test route
        $response->assertStatus(200);
    }

    public function test_daftar()
    {
        $this->Penduduk();
        $response = $this->get('api/v1/artikel/komentar', ['Authorization' => "Bearer $this->token"]);
        //test route
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
                        "created_at",
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
            ],
            "links" => [
                "self",
                "first",
                "last"
            ]
        ]);

        $this->Penduduk();
        $response = $this->get('api/v1/artikel/komentar/95', ['Authorization' => "Bearer $this->token"]);

        $response->assertJsonStructure([
            "status",
            "data" => [
                "current_page",
                "data" => [
                    [
                        "id",
                        "config_id",
                        "id_artikel",
                        "owner",
                        "email",
                        "subjek",
                        "komentar",
                        "tgl_upload",
                        "status",
                        "tipe",
                        "no_hp",
                        "updated_at",
                        "is_archived",
                        "permohonan",
                        "jenis"
                    ]
                ]
            ]
        ]);
    }

    public function test_tambah()
    {
        $this->Penduduk();
        $data = [
            'id_artikel' => 95,
            'komentar' => 'test',
        ];
        $response = $this->post('api/v1/artikel/komentar', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        $response = $this->get('api/v1/artikel/komentar', ['Authorization' => "Bearer $this->token"]);

        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(1, $data);

        //setujui
        Komentar::where('komentar', 'test')->update(['status' => 1]);

        $response = $this->get('api/v1/artikel/komentar', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);
    }
}
