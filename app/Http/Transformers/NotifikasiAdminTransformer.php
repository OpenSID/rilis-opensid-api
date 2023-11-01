<?php

namespace App\Http\Transformers;

use App\Models\LogNotifikasiAdmin;
use League\Fractal\TransformerAbstract;

class NotifikasiAdminTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(LogNotifikasiAdmin $log)
    {
        return [
            'id' => $log->id,
            'judul' => $log->judul,
            'isi' => $log->isi,
            'payload' => $log->payload,
            'read' => $log->read ?? 0,
        ];
    }
}
