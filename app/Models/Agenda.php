<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agenda';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_artikel', 'tgl_agenda', 'koordinator_kegiatan', 'lokasi_kegiatan'];

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'id_artikel');
    }
}
