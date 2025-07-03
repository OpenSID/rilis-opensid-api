<?php

namespace Database\Factories;

use App\Models\PermohonanSurat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PermohonanSurat>
 */
class PermohonanSuratFactory extends Factory
{
    protected $model = PermohonanSurat::class;

    public function definition(): array
    {
        return [
            'id_pemohon' => 1, // Sesuaikan atau gunakan factory relasi jika perlu
            'id_surat' => 1,
            'isian_form' => '{}',
            'status' => 1,
            'keterangan' => 'Pengajuan surat',
            'no_hp_aktif' => '08123456789',
            'syarat' => '[]',
        ];
    }
}
