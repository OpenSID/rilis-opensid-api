<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Illuminate\Database\Eloquent\Model;

class Bantuan extends Model
{
    use ConfigId;

    private $configCanNull = true;
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

    /**
     * Get the value of configCanNull
     */
    public function getConfigCanNull()
    {
        return $this->configCanNull;
    }
}
