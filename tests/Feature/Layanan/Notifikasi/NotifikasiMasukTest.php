<?php

namespace Tests\Feature\Layanan\Notifikasi;

use Tests\TestCase;
use App\Models\LogSurat;
use App\Models\PermohonanSurat;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotifikasiMasukTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

    }

    public function test_masuk()
    {
        $this->Admin_user();
        //Setujui surat mandiri
        $response = $this->post('api/admin/surat/mandiri/setuju', ['id' => 72, 'password' => $this->Get_password()], [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);
        $response->assertStatus(200);

        // cek jumlah log
        $this->penduduk();
        $response = $this->get('api/admin/surat/mandiri/setuju', ['Authorization' => "Bearer $this->token"]);

        PermohonanSurat::where('id', 72)->update(['status' => 1]);

        $data = LogSurat::orderBy('id', 'desc')->first();
        LogSurat::where('id', $data->id)->delete();
         
    }
}
