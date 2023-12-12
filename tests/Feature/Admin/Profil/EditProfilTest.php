<?php

namespace Tests\Feature\Admin\Surat;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditProfilTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_updateData()
    {
        $response = $this->post('/api/admin/profil');

        $response->assertStatus(200);
    }
}
