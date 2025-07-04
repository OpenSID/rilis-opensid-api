<?php

namespace Tests\Feature\Api\Admin\Auth;

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
        // test gagal email tidak terdaftar di database
        $data = [
            'email' => 'afila22@gmail.com',
        ];
        $response = $this->post('/api/admin/resetpassword', $data);
        $response->assertStatus(400);

        // test gagal format email salah
        $data = [
           'email' => 'afila22@gm',
        ];
        $response = $this->post('/api/admin/resetpassword', $data);
        $response->assertStatus(400);
    }
}
