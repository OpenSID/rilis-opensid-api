<?php

namespace App\Services\Surat\Layanan;

class SuratKetPengantar extends SuratAbstract
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->validate(
            $this->request,
            $this->defaultRules([
                'keperluan' => 'required|string',
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
            ['type' => 'textarea', 'required' => false, 'label' => 'Keperluan', 'name' => 'keperluan', 'subtype' => 'textarea'],
            ['type' => 'textarea', 'required' => false, 'label' => 'Keterangan', 'name' => 'keterangan', 'subtype' => 'textarea'],
        ]);
    }
}
