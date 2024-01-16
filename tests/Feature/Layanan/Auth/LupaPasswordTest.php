<?php

namespace Tests\Feature\Layanan\Auth;

use Tests\TestCase;

class LupaPasswordTest extends TestCase
{
    public function test_route()
    {

        $response = $this->post('api/v1/auth/forgot-password');

        $response->assertStatus(302);
    }

    public function test_kirim()
    {

        $response = $this->post('api/v1/auth/forgot-password', ['email' => 'info@opendesa.id']);

        $response->assertStatus(200);
    }

    public function test_kirim_kosong()
    {

        $response = $this->post('api/v1/auth/forgot-password');

        $response->assertStatus(302);
    }

    public function test_email_salah()
    {
        // case email tidak terdaftar
        $response = $this->post('api/v1/auth/forgot-password', ['email' => 'abc@opendesa.id']);

        $response->assertStatus(400);

        // kondisi format email salah
        $response = $this->post('api/v1/auth/forgot-password', ['email' => 'info@opendesa']);
        $response->assertStatus(400);
    }
}
