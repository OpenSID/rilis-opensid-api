<?php

namespace Tests\Feature\Api\Admin\Profil;

use Tests\TestCase;

class TampilProfilTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_data()
    {
        $this->Admin_user();

        $response = $this->get('/api/admin/profil', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
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
                'pamong' => [
                    'jabatan' => [
                        'config_id',
                        'nama',
                        'jenis',
                        'tupoksi',
                    ]
                ]
            ],
            'message',
        ]);
    }
}
