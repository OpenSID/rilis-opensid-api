<?php

namespace App\Models;

use App\Models\Config;
use Illuminate\Database\Eloquent\Model;

class SettingAplikasi extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting_aplikasi';



    public function getDesaAttribute()
    {
        return Config::first();
    }

}
