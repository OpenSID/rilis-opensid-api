<?php

namespace App\Http\Transformers;

use App\Models\PesanMandiri;
use League\Fractal\TransformerAbstract;

class PesanMandiriTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(PesanMandiri $pesanMandiri)
    {
        return [
            'id' => $pesanMandiri->uuid,
            'owner' => $pesanMandiri->owner,
            'email' => $pesanMandiri->email,
            'phone' => $pesanMandiri->no_hp,
            'subject' => $pesanMandiri->subjek,
            'comment' => $pesanMandiri->komentar,
            'created_at' => $pesanMandiri->tgl_upload,
        ];
    }
}
