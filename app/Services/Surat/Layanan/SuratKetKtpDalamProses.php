<?php

namespace App\Services\Surat\Layanan;

use Illuminate\Validation\Rule;

class SuratKetKtpDalamProses extends SuratAbstract
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->validate(
            $this->request,
            $this->defaultRules([
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
