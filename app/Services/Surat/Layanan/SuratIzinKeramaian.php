<?php

namespace App\Services\Surat\Layanan;

use App\Services\Surat\Traits\AttributeTrait;

class SuratIzinKeramaian extends SuratAbstract
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
                'keperluan' => 'required|string',
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
                    ['type' => 'textarea', 'required' => false, 'label' => 'Keperluan', 'name' => 'keperluan', 'subtype' => 'textarea'],
                ]
            )
        );
    }
}
