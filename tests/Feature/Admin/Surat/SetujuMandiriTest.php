<?php

namespace Tests\Feature\Admin\Surat;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetujuMandiriTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_terima()
    {
        $this->Admin_user();

        // gagal karena tidak ada id
        $response = $this->post('api/admin/surat/mandiri/setuju',[],[
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(422);

        //sukses
        $response = $this->post('api/admin/surat/mandiri/setuju',['id' => 72],[
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        dd($response->decodeResponseJson());

        $response->assertStatus(200);
    }
}
