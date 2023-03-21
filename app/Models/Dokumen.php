<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Dokumen extends Model
{
    use ConfigId;

    public const DOKUMEN_WARGA = 1;
    public const ENABLE = 1;
    public const DISABLE = 0;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dokumen';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'attr' => '[]',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'satuan',
        'nama',
        'enabled',
        'tgl_upload',
        'id_pend',
        'kategori',
        'id_syarat',
        'dok_warga',
    ];

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function jenisDokumen()
    {
        return $this->belongsTo(SyaratSurat::class, 'id_syarat');
    }

    /**
     * Scope a query to only users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePengguna($query)
    {
        return $query->where('id_pend', auth('jwt')->id());
    }

    /**
     * Getter untuk menambahkan url file.
     *
     * @return string
     */
    public function getUrlFileAttribute()
    {
        try {
            return Storage::disk('ftp')->exists("desa/upload/dokumen/{$this->satuan}")
                ? Storage::disk('ftp')->url("desa/upload/dokumen/{$this->satuan}")
                : null;
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    /**
     * Getter untuk donwload file.
     *
     * @return string
     */
    public function getDownloadDokumenAttribute()
    {
        try {
            return Storage::disk('ftp')->exists("desa/upload/dokumen/{$this->satuan}")
                ? Storage::disk('ftp')->download("desa/upload/dokumen/{$this->satuan}")
                : null;
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
