<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
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
}
