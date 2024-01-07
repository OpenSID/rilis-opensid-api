<?php

namespace Tests\Feature\Layanan\Notifikasi;

use Tests\TestCase;
use App\Models\LogSurat;
use App\Models\PermohonanSurat;
use App\Models\LogNotifikasiMandiri;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotifikasiMasukTest extends TestCase
{
 
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
        $logNotifikasi = LogNotifikasiMandiri::where('id_user_mandiri', 20)->get();
        $this->assertCount(3, $logNotifikasi);

        // reset ulang data yang masuk
       
        PermohonanSurat::where('id', 72)->update(['status' => 1]);

        $notifikasi = LogNotifikasiMandiri::orderBy('id', 'desc')->first();
        LogNotifikasiMandiri::where('id', $notifikasi->id)->delete();
        $data = LogSurat::orderBy('id', 'desc')->first();
        LogSurat::where('id', $data->id)->delete();
         
    }
}
