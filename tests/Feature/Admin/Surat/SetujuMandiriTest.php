<?php

namespace Tests\Feature\Admin\Surat;

use App\Models\LogNotifikasiMandiri;
use App\Models\LogSurat;
use App\Models\PermohonanSurat;
use Tests\TestCase;

class SetujuMandiriTest extends TestCase
{
    public function test_terima()
    {
        $this->Admin_user();

        // gagal karena tidak ada id
        $response = $this->post('api/admin/surat/mandiri/setuju', [], [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(422);

        PermohonanSurat::where('id', 72)->update(['status' => 1]);

        $notifikasi = LogNotifikasiMandiri::orderBy('id', 'desc')->first();
        LogNotifikasiMandiri::where('id', $notifikasi->id)->delete();
        $data = LogSurat::orderBy('id', 'desc')->first();
        LogSurat::where('id', $data->id)->delete();
    }
}
