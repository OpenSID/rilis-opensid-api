<?php

namespace Tests\Feature\Admin\Surat;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TolakMandiriTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->Admin_user();
        $response = $this->put('/api/admin/surat/setujui');

        $response->assertStatus(200);
    }
}
