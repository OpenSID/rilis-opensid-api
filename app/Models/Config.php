<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Config extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'config';

    /**
     * Getter untuk menambahkan url logo.
     *
     * @return string
     */
    public function getUrlLogoAttribute()
    {
        try {
            return Storage::disk('ftp')->exists("desa/logo/{$this->logo}")
                ? Storage::disk('ftp')->url("desa/logo/{$this->logo}")
                : null;
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function getGaleriAttribute()
    {
        return Galery::with('children')->where(['slider' => 1, 'enabled' => 1])->first();
    }
}
