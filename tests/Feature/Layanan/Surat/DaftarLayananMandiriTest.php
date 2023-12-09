<?php

namespace Tests\Feature\Layanan\Surat;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DaftarLayananMandiriTest extends TestCase
{
    public function test_akses_surat()
    {
        $response = $this->get('/api/v1/layanan-mandiri/surat/jenis-permohonan');
        $response->assertStatus(500);

        $this->Layanan_user();
        $response = $this->get('/api/v1/layanan-mandiri/surat/jenis-permohonan', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
    }

    public function test_daftar_surat()
    {
        $this->Layanan_user();
        $response = $this->get('/api/v1/layanan-mandiri/surat/jenis-permohonan', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'type',
                    'id',
                    'attributes' => [
                        'kode',
                        'nama',
                        'slug',
                        'form_surat' => [
                            [
                                'type',
                                'required',
                                'label',
                                'name'
                            ]
                        ]
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
    }
}
