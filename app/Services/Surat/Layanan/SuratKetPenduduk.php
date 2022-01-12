<?php

namespace App\Services\Surat\Layanan;

class SuratKetPenduduk extends SuratAbstract
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
        ]);
    }
}
