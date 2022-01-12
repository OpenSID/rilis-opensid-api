<?php

namespace App\Services\Surat\Layanan;

use App\Services\Surat\Traits\AttributeTrait;

class SuratJalan extends SuratAbstract
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
                    ['type' => 'textarea', 'required' => false, 'label' => 'Keterangan', 'name' => 'keterangan', 'subtype' => 'textarea']
                ]
            )
        );
    }
}
