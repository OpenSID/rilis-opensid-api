<?php

namespace Tests\Feature\Layanan\Lapak;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LapakTest extends TestCase
{
    
    public function test_auth()
    {
        $response = $this->get('/api/v1/layanan-mandiri/lapak', ['']);

        $response->assertStatus(500);
    }

    public function test_daftar()
    {
        $this->Penduduk();
        $response = $this->get('/api/v1/layanan-mandiri/lapak', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        
        $response->assertJsonStructure([
            'data' => [
                [
                    'type',
                    'id',
                    'attributes' => [
                        'nama',
                        'harga',
                        'potongan',
                        'hargapotongan',
                        'dekripsi',
                        'foto',
                        'telepon'
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

    public function test_detail(){
        $this->Penduduk();
        $response = $this->get('/api/v1/layanan-mandiri/lapak/detail?id=1', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        $data = $response->decodeResponseJson()['data'];
        dd($data);
    }

    public function test_detail_salah(){
        $this->Penduduk();
        $response = $this->get('/api/v1/layanan-mandiri/lapak/detail?id=10', ['Authorization' => "Bearer $this->token"]);
         
        $response->assertStatus(404);
    }
    
    
}
