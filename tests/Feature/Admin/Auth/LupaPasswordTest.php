<?php

namespace Tests\Feature\Api\Admin\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LupaPasswordTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_lupa()
    {
        // test berhasil
        $data = [
            'email' => 'afila@gmail.com',
        ];
        $response = $this->post('/api/admin/auth/forgot', $data );
        $response->assertStatus(200);

        // test gagal email tidak terdaftar di database
        $data = [
            'email' => 'afila22@gmail.com',
        ];
        $response = $this->post('/api/admin/auth/forgot', $data );
        $response->assertStatus(301);

         // test gagal format email salah
         $data = [
            'email' => 'afila22@gm',
        ];
        $response = $this->post('/api/admin/auth/forgot', $data );
        $response->assertStatus(301);
    }
}
