<?php

namespace App\Http\Transformers;

use App\Models\SettingAplikasi;
use League\Fractal\TransformerAbstract;

class PengaturanTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(SettingAplikasi $settingAplikasi)
    {
        return [
            'id' => $settingAplikasi->id,
            'value' => $settingAplikasi->value,
            'logo' => $settingAplikasi->desa->urlLogo,
            'nama_desa' => $settingAplikasi->desa->nama_desa,
            'nama_kecamatan' => $settingAplikasi->desa->nama_kecamatan,
            'nama_kabupaten' => $settingAplikasi->desa->nama_kabupaten,
            'nama_provinsi' => $settingAplikasi->desa->nama_propinsi,
            'sebutan_kabupaten' => ucfirst(config('aplikasi.sebutan_kabupaten')),
            'sebutan_kabupaten_singkat' => ucfirst(config('aplikasi.sebutan_kabupaten_singkat')),
            'sebutan_kecamatan' => ucfirst(config('aplikasi.sebutan_kecamatan')),
            'sebutan_kecamatan_singkat' => ucfirst(config('aplikasi.sebutan_kecamatan_singkat')),
            'sebutan_desa' => ucfirst(config('aplikasi.sebutan_desa')),
            'sebutan_dusun' => ucfirst(config('aplikasi.sebutan_dusun')),

        ];
    }
}
