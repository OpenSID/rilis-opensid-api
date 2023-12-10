<?php

namespace Tests\Feature\Admin\Profil;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->Admin_user();

        $response = $this->post( 'api/admin/logout',[], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        $response = $this->post( 'api/admin/logout',[], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(500);


        $response = $this->get( 'api/admin/surat/jumlah_arsip', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(500);
    }
}
