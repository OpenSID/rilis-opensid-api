<?php

namespace App\Services\Surat\Layanan;

class SuratKetKehilangan extends SuratAbstract
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->validate(
            $this->request,
            $this->defaultRules([
                'barang' => 'required|string',
                'rincian' => 'required|string',
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
            ['type' => 'text', 'required' => true, 'label' => 'Barang yang Hilang', 'name' => 'barang', 'subtype' => 'text'],
            ['type' => 'text', 'required' => true, 'label' => 'Rincian', 'name' => 'rincian', 'subtype' => 'text'],
            ['type' => 'textarea', 'required' => false, 'label' => 'Keterangan Kejadian', 'name' => 'keterangan', 'subtype' => 'textarea'],
        ]);
    }
}
