<?php

namespace App\Http\Transformers;

use App\Models\LogNotifikasiMandiri;
use League\Fractal\TransformerAbstract;

class NotifikasiMandiriTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(LogNotifikasiMandiri $log)
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
