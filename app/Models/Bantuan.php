<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Illuminate\Database\Eloquent\Model;

class Bantuan extends Model
{
    use ConfigId;

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
