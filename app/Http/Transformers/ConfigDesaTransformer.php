<?php

namespace App\Http\Transformers;

use App\Models\Config;
use League\Fractal\TransformerAbstract;

class ConfigDesaTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(Config $config)
    {
        return [
            'id' => $config->id,
            'logo' => $config->urlLogo,
            'email_desa' => $config->email_desa,
            'telepon' => $config->telepon,
            'website' => $config->website,
            'galeri' => $config->galeri ? $config->galeri->children : [],
            'perangkat_desa' => [
                'nama_kepala_desa' => $config->nama_kepala_desa,
                'nip_kepala_desa' => $config->nip_kepala_desa,
                'nama_kepala_camat' => $config->nama_kepala_camat,
                'nip_kepala_camat' => $config->nip_kepala_camat,
            ],
            'alamat' => [
                'kode_pos' => $config->kode_pos,
                'alamat_kantor' => $config->alamat_kantor,
                'kantor_desa' => $config->kantor_desa,
                'kode_desa' => $config->kode_desa,
                'kode_kecamatan' => $config->kode_kecamatan,
                'kode_kabupaten' => $config->kode_kabupaten,
                'kode_provinsi' => $config->kode_propinsi,
                'nama_desa' => $config->nama_desa,
                'nama_kecamatan' => $config->nama_kecamatan,
                'nama_kabupaten' => $config->nama_kabupaten,
                'nama_provinsi' => $config->nama_propinsi,
                'lat' => $config->lat,
                'lng' => $config->lng,
            ],
            'config' => [
                'sebutan_kabupaten' => ucfirst(config('aplikasi.sebutan_kabupaten')),
                'sebutan_kabupaten_singkat' => ucfirst(config('aplikasi.sebutan_kabupaten_singkat')),
                'sebutan_kecamatan' => ucfirst(config('aplikasi.sebutan_kecamatan')),
                'sebutan_kecamatan_singkat' => ucfirst(config('aplikasi.sebutan_kecamatan_singkat')),
                'sebutan_desa' => ucfirst(config('aplikasi.sebutan_desa')),
                'sebutan_dusun' => ucfirst(config('aplikasi.sebutan_dusun')),
            ],
        ];
    }
}
