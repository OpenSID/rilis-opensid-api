<?php

namespace Tests\Feature\Api\Admin;

use Tests\TestCase;

class AuthenticationAdminTest extends TestCase
{
    public function testSuccessLogin()
    {
        $response = $this->post('/api/admin/login', [
            'username' => 'admin',
            'password' => $this->Get_password(),
        ]);

        $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'config_id',
                'username',
                'id_grup',
                'pamong_id',
                'email',
                'last_login',
                'email_verified_at',
                'active',
                'nama',
                'id_telegram',
                'token',
                'token_exp',
                'telegram_verified_at',
                'company',
                'phone',
                'foto',
                'session',
                'pamong'
            ],
            'message',
        ]);

    }

    public function testFailedLoginCredential()
    {
        $response = $this->post('/api/admin/login', [
            'username' => 'admin',
            'password' => '12345',
        ]);

        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'code',
                'messages',
            ]);
    }

    public function testSuccesslogout()
    {
        $this->Admin_user();

        $response = $this->post('/api/admin/logout', [], [
            'Authorization' => "Bearer $this->token",
        ]);

        $response->assertStatus(200);
    }

    public function testFailedlogout()
    {
        $response = $this->post('/api/admin/logout', [], [
            'Authorization' => "Bearer",
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(401);
    }
}
