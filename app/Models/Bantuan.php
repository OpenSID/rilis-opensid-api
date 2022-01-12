<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bantuan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'program';

    public function peserta()
    {
        return $this->hasMany(BantuanPeserta::class, 'program_id');
    }
}
