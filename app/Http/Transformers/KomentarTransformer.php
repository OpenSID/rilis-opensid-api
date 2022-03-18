<?php

namespace App\Http\Transformers;

use App\Models\Komentar;
use League\Fractal\TransformerAbstract;

class KomentarTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(Komentar $comment)
    {
        return [
            'id' => $comment->id,
            'id_artikel' => $comment->id_artikel,
            'owner' => $comment->owner,
            'email' => $comment->email,
            'phone' => $comment->no_hp,
            'subject' => $comment->subjek,
            'comment' => $comment->komentar,
            'created_at' => $comment->tgl_upload,
        ];
    }
}
