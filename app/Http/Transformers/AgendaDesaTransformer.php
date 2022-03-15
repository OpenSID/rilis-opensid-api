<?php

namespace App\Http\Transformers;

use App\Models\Agenda;
use League\Fractal\TransformerAbstract;

class AgendaDesaTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(Agenda $agenda)
    {
        return [
            'id' => $agenda->id,
            'id_artikel' => $agenda->id_artikel,
            'tgl_agenda' => $agenda->tgl_agenda,
            'koordinator_kegiatan' => $agenda->koordinator_kegiatan,
            'lokasi_kegiatan' => $agenda->lokasi_kegiatan,
        ];
    }
}
