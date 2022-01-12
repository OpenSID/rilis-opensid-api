<?php

namespace App\Services\Surat\Layanan;

use App\Services\Surat\Traits\AttributeTrait;

class SuratKetUsaha extends SuratAbstract
{
    use AttributeTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->validate(
            $this->request,
            $this->defaultRules([
                'usaha' => 'required|string',
                'keterangan' => 'required|string',
            ])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function form()
    {
        return $this->defaultForm(
            array_merge(
                $this->formMasaBerlaku(),
                [
                    ['type' => 'text', 'required' => true, 'label' => 'Nama / Jenis Usaha', 'name' => 'usaha', 'subtype' => 'text'],
                    ['type' => 'textarea', 'required' => false, 'label' => 'Keterangan', 'name' => 'keterangan', 'subtype' => 'textarea'],
                ]
            )
        );
    }
}
