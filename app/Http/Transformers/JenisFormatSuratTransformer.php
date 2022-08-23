<?php

namespace App\Http\Transformers;

use App\Models\FormatSurat;
use League\Fractal\TransformerAbstract;

class JenisFormatSuratTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(FormatSurat $surat)
    {
        return [
            'id' => $surat['id'],
            'kode' => $surat['kode_surat'],
            'nama' => $surat['nama'],
            'slug' => $surat['url_surat'],
            'form_surat' => $surat['form_surat'],
        ];
    }
}
