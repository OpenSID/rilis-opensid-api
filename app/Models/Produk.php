<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produk extends Model
{
    use ConfigId;

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'produk';

    /** {@inheritdoc} */
    protected $casts = [
       'foto' => 'json',
    ];

    public function pelapak()
    {
        return $this->hasOne(Pelapak::class, 'id');
    }

    public function getUrlFotoAttribute()
    {
        $foto = [];
        if ($this->foto == null) {
            return $foto;
        }
        foreach ($this->foto as $value) {
            $foto [] = Storage::disk('ftp')->url("desa/upload/produk/{$value}");
        }
        return $foto;
    }
}
