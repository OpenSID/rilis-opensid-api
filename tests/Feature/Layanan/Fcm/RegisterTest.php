<?php

namespace Tests\Feature\Layanan\Fcm;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function test_register()
    {
        $this->Penduduk();

        // Generate fake data using Faker for testing
        $data = [
            'device' => $this->faker->uuid, // Assuming 'device' field requires a UUID
            'token' => $this->faker->sha256, // Assuming 'token' field requires a SHA256 hash
            'id_user_mandiri' => 7
        ];

        $response = $this->post('api/v1/fcm/register', $data, [
            'Authorization' => "Bearer $this->token",
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
    }
}
