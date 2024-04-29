<?php

namespace Tests\Feature\Layanan\Dokumen;

use App\Models\Dokumen;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HapusDokumenTest extends TestCase
{
    // use RefreshDatabase;
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
    }

    public function test_hapus()
    {
        $this->Penduduk();
        $id = Dokumen::where('id_pend', 20)->first()->id;
        
        $response = $this->delete("api/v1/layanan-mandiri/dokumen/{$id}/delete", [], [
            'Authorization' => "Bearer $this->token",
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
    }
}
