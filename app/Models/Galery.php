<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Galery extends Model
{
    /** {@inheritdoc} */
    protected $table = 'gambar_gallery';

    /** {@inheritdoc} */
    public $timestamps = false;

    /** {@inheritdoc} */
    protected $appends = ['url_gambar'];

    /** {@inheritdoc} */
    protected $hidden = [
        'parrent', 'enabled', 'tgl_upload', 'tipe', 'slider', 'urut',
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'parrent');
    }

    public function getUrlGambarAttribute()
    {
        try {
            return Storage::disk('ftp')->exists("desa/upload/galeri/kecil_{$this->gambar}")
                ? Storage::disk('ftp')->url("desa/upload/galeri/kecil_{$this->gambar}")
                : null;
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
