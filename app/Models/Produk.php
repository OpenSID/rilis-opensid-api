<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produk extends Model
{
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
        foreach ($this->foto as  $value) {
            $foto [] = Storage::disk('ftp')->url("desa/upload/pengaduan/{$value}");
        }
        return $foto;
    }
}
