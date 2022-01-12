<?php

namespace App\Services\Surat\Layanan;

use Illuminate\Validation\Rule;

class SuratKetCatatanKriminal extends SuratAbstract
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->validate(
            $this->request,
            $this->defaultRules([
                'keterangan' => 'required|string',
                'tampil_foto' => ['required', Rule::in([1, 0])],
            ])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function form()
    {
        return $this->defaultForm([
            ['type' => 'textarea', 'required' => false, 'label' => 'Keterangan', 'name' => 'keterangan', 'subtype' => 'textarea'],
            [
                'type' => 'radio-group',
                'required' => true,
                'label' => 'Tampilkan Foto Penduduk di Surat',
                'name' => 'tampil_foto',
                'values' => [
                    [
                        'label' => 'Ya',
                        'value' => '1',
                        'selected' => true,
                    ],
                    [
                        'label' => 'Tidak',
                        'value' => '0',
                        'selected' => false,
                    ],
                ],
            ]
        ]);
    }
}
