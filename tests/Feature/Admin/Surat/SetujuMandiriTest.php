<?php

namespace Tests\Feature\Admin\Surat;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetujuMandiriTest extends TestCase
{
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
        $response = $this->post('api/admin/surat/mandiri/setuju',['id' => 72, 'password' => $this->Get_password()],[
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(200);
    }
}
