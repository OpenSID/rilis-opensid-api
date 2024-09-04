<?php

namespace App\Http\Transformers;

use App\Models\PendudukMandiri;
use League\Fractal\TransformerAbstract;

class PendudukMandiriTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(PendudukMandiri $mandiri)
    {
        return array_merge(
            $mandiri->token ? [
                'id' => $mandiri->id_pend,
                'access_token' => [
                    'token' => $mandiri->token,
                    'token_type' => 'Bearer',
                    'expires_in' => auth('jwt')->factory()->getTTL() * 60,
                ],
            ] : [],
            [
                'id' => $mandiri->id_pend,
                'ganti_pin' => $mandiri->ganti_pin,
                'dasar' => [
                    'foto' => $mandiri->penduduk->url_foto,
                    'nik' => $mandiri->penduduk->nik,
                    'nama' => $mandiri->penduduk->nama,
                    'status_kepemilikan_ktp' => [
                        'wajib_ktp' => $mandiri->penduduk->wajib_ktp,
                        'ktp_el' => $mandiri->penduduk->ktp_el,
                        'status_rekam' => $mandiri->penduduk->status_rekam,
                        'tag_id_card' => $mandiri->penduduk->tag_id_card,
                    ],
                    'no_kk' => $mandiri->penduduk->keluarga->no_kk,
                    'no_kk_sebelumnya' => $mandiri->penduduk->no_kk_sebelumnya,
                    'hubungan_dalam_keluarga' => $mandiri->penduduk->pendudukHubungan->nama,
                    'jenis_kelamin' => $mandiri->penduduk->jenisKelamin->nama,
                    'agama' => $mandiri->penduduk->agama->nama,
                    'status_penduduk' => $mandiri->penduduk->pendudukStatus->nama,
                ],
                'kelahiran' => [
                    'akta_kelahiran' => $mandiri->penduduk->akta_lahir,
                    'tempat_lahir' => $mandiri->penduduk->tempatlahir,
                    'tanggal_lahir' => $mandiri->penduduk->tanggallahir->format('d-m-Y'),
                    'tempat_dilahirkan' => $mandiri->penduduk->dilahirkan,
                    'jenis_kelahiran' => $mandiri->penduduk->jenis_lahir,
                    'kelahiran_anak_ke' => $mandiri->penduduk->kelahiran_anak_ke,
                    'penolong_kelahiran' => $mandiri->penduduk->penolong_lahir,
                    'berat_lahir' => $mandiri->penduduk->berat_lahir,
                    'panjang_lahir' => $mandiri->penduduk->panjang_lahir,
                ],
                'pendidikan_pekerjaan' => [
                    'pendidikan_kk' => $mandiri->penduduk->pendidikanKK->nama,
                    'pendidikan_sedang_ditempuh' => $mandiri->penduduk->pendidikan->nama,
                    'pekerjaan' => $mandiri->penduduk->pekerjaan->nama,
                ],
                'kewarganegaraan' => [
                    'warga_negara' => $mandiri->penduduk->wargaNegara->nama,
                    'no_paspor' => $mandiri->penduduk->dokumen_pasport,
                    'tanggal_akhir_paspor' => $mandiri->penduduk->tanggal_akhir_paspor,
                    'no_kitas' => $mandiri->penduduk->dokumen_kitas,
                ],
                'orang_tua' => [
                    'nik_ayah' => $mandiri->penduduk->ayah_nik,
                    'nama_ayah' => $mandiri->penduduk->nama_ayah,
                    'nik_ibu' => $mandiri->penduduk->ibu_nik,
                    'nama_ibu' => $mandiri->penduduk->nama_ibu,
                ],
                'alamat' => [
                    'no_telepon' => $mandiri->penduduk->telepon,
                    'email' => $mandiri->email,
                    'alamat' => $mandiri->penduduk->alamat_sekarang,
                    'dusun' => $mandiri->penduduk->clusterDesa->dusun,
                    'rt' => $mandiri->penduduk->clusterDesa->rt,
                    'rw' => $mandiri->penduduk->clusterDesa->rw,
                    'alamat_sebelumnya' => $mandiri->penduduk->alamat_sebelumnya,
                ],
                'perkawinan' => [
                    'status' => $mandiri->penduduk->statusPerkawinan,
                    'akta' => $mandiri->penduduk->akta_perkawinan,
                    'tanggal' => $mandiri->penduduk->tanggalperkawinan,
                ],
                'kesehatan' => [
                    'golongan_darah' => $mandiri->penduduk->golonganDarah->nama,
                    'cacat' => $mandiri->penduduk->cacat->nama,
                    'sakit_menahun' => $mandiri->penduduk->sakitMenahun->nama,
                    'kb' => $mandiri->penduduk->kb->nama,
                    'status_kehamilan' => $mandiri->penduduk->status_hamil,
                    'asuransi' => $mandiri->penduduk->nama_asuransi,
                ],
            ]
        );
    }
}
