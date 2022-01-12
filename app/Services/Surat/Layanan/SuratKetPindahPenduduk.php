<?php

namespace App\Services\Surat\Layanan;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SuratKetPindahPenduduk extends SuratAbstract
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->validate(
            $this->request,
            $this->defaultRules([
                'telepon' => ['required', 'regex:/^(^\+62|62|^08)(\d{3,4}-?){2}\d{3,4}$/i'],
                'pakai_format' => ['required', Rule::in(['f108', 'bukan_f108'])],
                'alasan_pindah_id' => ['required', Rule::in(['1', '2', '3', '4', '5', '6', '7'])],
                'klasifikasi_pindah_id' => ['required', Rule::in(['1', '2', '3', '4', '5'])],
                'alamat_tujuan' => 'required|string',
                'rt_tujuan' => 'required',
                'rw_tujuan' => 'required',
                'dusun_tujuan' => 'required|string',
                'desa_tujuan' => 'required|string',
                'kecamatan_tujuan' => 'required|string',
                'kabupaten_tujuan' => 'required|string',
                'provinsi_tujuan' => 'required|string',
                'kode_pos_tujuan' => 'required|int',
                'telepon_tujuan' => ['required', 'regex:/^(^\+62|62|^08)(\d{3,4}-?){2}\d{3,4}$/i'],
                'jenis_kepindahan_id' => ['required', Rule::in(['1', '2', '3', '4'])],
                'status_kk_tidak_pindah_id' => ['required', Rule::in(['1', '2', '3', '4'])],
                'status_kk_pindah_id' => ['required', Rule::in(['1', '2', '3'])],
                'id_cb' => Rule::requiredIf($this->listPengikut()),
                'tanggal_pindah' => 'required',
                'keterangan' => 'required|string',
            ])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function form()
    {
        return $this->defaultForm([
            ['type' => 'number', 'required' => true, 'label' => 'Telepon Pemohon', 'name' => 'telepon'],
            [
                'type' => 'select',
                'required' => true,
                'label' => 'Gunakan Format',
                'name' => 'pakai_format',
                'multiple' => false,
                'values' => [
                    [
                        'label' => 'F-1.08',
                        'value' => 'f108',
                        'selected' => false,
                    ],
                    [
                        'label' => 'F-1.23, F-1.25, F-1.29, F-1.34 (sesuai tujuan)',
                        'value' => 'bukan_f108',
                        'selected' => true,
                    ],
                ],
            ],
            [
                'type' => 'select',
                'required' => true,
                'label' => 'Alasan Pindah',
                'name' => 'alasan_pindah_id',
                'multiple' => false,
                'values' => [
                    [
                        'label' => 'Pekerjaan',
                        'value' => '1',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Pendidikan',
                        'value' => '2',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Keamanan',
                        'value' => '3',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Kesehatan',
                        'value' => '4',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Perumahan',
                        'value' => '5',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Keluarga',
                        'value' => '6',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Lainnya',
                        'value' => '7',
                        'selected' => false,
                    ],
                ],
            ],
            [
                'type' => 'select',
                'required' => true,
                'label' => 'Klasifikasi Pindah',
                'name' => 'klasifikasi_pindah_id',
                'multiple' => false,
                'values' => [
                    [
                        'label' => 'Dalam satu Desa/Kelurahan',
                        'value' => '1',
                        'selected' => true,
                    ],
                    [
                        'label' => 'Antar Desa/Kelurahan',
                        'value' => '2',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Antar Kecamatan',
                        'value' => '3',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Antar Kab/Kota',
                        'value' => '4',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Antar Provinsi',
                        'value' => '5',
                        'selected' => false,
                    ],
                ],
            ],
            ['type' => 'textarea', 'required' => true, 'label' => 'Alamat Tujuan', 'name' => 'alamat_tujuan', 'subtype' => 'textarea'],
            ['type' => 'number', 'required' => true, 'label' => 'RT', 'name' => 'rt_tujuan'],
            ['type' => 'number', 'required' => true, 'label' => 'RW', 'name' => 'rw_tujuan'],
            ['type' => 'text', 'required' => true, 'label' => 'Dusun Tujuan', 'name' => 'dusun_tujuan', 'subtype' => 'text'],
            ['type' => 'text', 'required' => true, 'label' => 'Desa/Kelurahan Tujuan', 'name' => 'desa_tujuan', 'subtype' => 'text'],
            ['type' => 'text', 'required' => true, 'label' => 'Kecamatan Tujuan', 'name' => 'kecamatan_tujuan', 'subtype' => 'text'],
            ['type' => 'text', 'required' => true, 'label' => 'Kabupaten Tujuan', 'name' => 'kabupaten_tujuan', 'subtype' => 'text'],
            ['type' => 'text', 'required' => true, 'label' => 'Provinsi Tujuan', 'name' => 'provinsi_tujuan', 'subtype' => 'text'],
            ['type' => 'text', 'required' => true, 'label' => 'Kode Pos', 'name' => 'kode_pos_tujuan', 'subtype' => 'text'],
            ['type' => 'text', 'required' => true, 'label' => 'Telpon', 'name' => 'telepon_tujuan', 'subtype' => 'text'],
            [
                'type' => 'select',
                'required' => true,
                'label' => 'Jenis Kepindahan',
                'name' => 'jenis_kepindahan_id',
                'multiple' => false,
                'values' => [
                    [
                        'label' => 'Kep. Keluarga',
                        'value' => '1',
                        'selected' => true,
                    ],
                    [
                        'label' => 'Kep. Keluarga dan Seluruh Angg. Keluarga',
                        'value' => '2',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Kep. Keluarga dan Sbg. Angg. Keluarga',
                        'value' => '3',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Angg. Keluarga',
                        'value' => '4',
                        'selected' => false,
                    ],
                ],
            ],
            [
                'type' => 'select',
                'required' => true,
                'label' => 'Status KK Bagi Yang Tidak Pindah',
                'name' => 'status_kk_tidak_pindah_id',
                'multiple' => false,
                'values' => [
                    [
                        'label' => 'Numpang KK',
                        'value' => '1',
                        'selected' => true,
                    ],
                    [
                        'label' => 'Membuat KK Baru',
                        'value' => '2',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Nomor KK Tetap',
                        'value' => '3',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Tidak Ada Angg. Keluarga Yang Ditinggal',
                        'value' => '4',
                        'selected' => false,
                    ],
                ],
            ],
            [
                'type' => 'select',
                'required' => true,
                'label' => 'Status KK Bagi Yang Pindah',
                'name' => 'status_kk_pindah_id',
                'multiple' => false,
                'values' => [
                    [
                        'label' => 'Numpang KK',
                        'value' => '1',
                        'selected' => true,
                    ],
                    [
                        'label' => 'Membuat KK Baru',
                        'value' => '2',
                        'selected' => false,
                    ],
                    [
                        'label' => 'Nomor KK Tetap',
                        'value' => '3',
                        'selected' => false,
                    ],
                ],
            ],
            [
                'type' => 'table',
                'required' => true,
                'multiple' => true,
                'label' => 'Pengikut',
                'name' => 'id_cb',
                'values' => $this->listPengikut(),
            ],
            [
                'type' => 'date',
                'format' => 'dd/mm/yyyy',
                'required' => true,
                'label' => 'Tanggal Pindah',
                'name' => 'tanggal_pindah',
            ],
            ['type' => 'text', 'required' => true, 'label' => 'Keterangan', 'name' => 'keterangan', 'subtype' => 'text'],
        ]);
    }

    protected function listPengikut()
    {
        return DB::table('tweb_penduduk as u')
            ->selectRaw("
                u.id,
                u.nik,
                u.nama,
                DATE_FORMAT(
                FROM_DAYS(
                    TO_DAYS(NOW())- TO_DAYS(`tanggallahir`)
                ),
                '%Y'
                )+ 0 AS umur,
                x.nama AS sex,
                h.nama AS hubungan
            ")
            ->leftJoin('tweb_penduduk_sex as x', 'u.sex', '=', 'x.id')
            ->leftJoin('tweb_penduduk_hubungan as h', 'u.kk_level', '=', 'h.id')
            ->leftJoin('tweb_keluarga as k', 'u.id_kk', '=', 'k.id')
            ->where('u.status_dasar', 1)
            ->where('u.id_kk', auth('jwt')->user()->penduduk->id_kk)
            ->get();
    }
}
