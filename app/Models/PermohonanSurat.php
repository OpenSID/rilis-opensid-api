<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanSurat extends Model
{
    use ConfigId;
    use HasFactory;

    public const STATUS_PERMOHONAN = [
        0 => "Belum Lengkap",
        1 => "Sedang Diperiksa",
        2 => "Menunggu Tandatangan",
        3 => "Siap Diambil",
        4 => "Sudah Diambil",
        5 => "Dibatalkan",
    ];

    /** {@inheritdoc} */
    protected $table = 'permohonan_surat';

    /** {@inheritdoc} */
    protected $fillable = [
        'id_pemohon',
        'id_surat',
        'isian_form',
        'status',
        'keterangan',
        'no_hp_aktif',
        'syarat',
    ];

    /** {@inheritdoc} */
    protected $casts = [
        'isian_form' => 'json',
        'syarat' => 'json',
    ];

    /** {@inheritdoc} */
    protected $with = ['formatSurat', 'penduduk'];

    public $appends = ['syarat_surat'];

    /**
     * Getter untuk mapping status permohonan.
     *
     * @return string
     */
    public function getStatusPermohonanAttribute()
    {
        return static::STATUS_PERMOHONAN[$this->status];
    }

    /**
     * Setter untuk id surat permohonan.
     *
     * @param string $slug
     * @return void
     */
    public function setIdSuratAttribute(string $slug)
    {
        $this->attributes['id_surat'] = FormatSurat::where('url_surat', $slug)->first()->id;
    }

    /**
     * Scope query untuk pengguna.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePengguna($query)
    {
        return $query->where('id_pemohon', auth('jwt')->user()->penduduk->id);
    }

    /**
     * Getter untuk mapping syartsurat permohonan.
     *
     * @return string
     */
    public function getSyaratSuratAttribute()
    {
        if ($this->syarat == null || $this->syarat == '{}') {
            return null;
        }


        $dokumen = Dokumen::where('id_pend', $this->id_pemohon)->whereIn('id', $this->syarat)->get();

        return $dokumen->map(static function ($syarat) {
            $syarat->nama_syarat = $syarat->jenisDokumen->ref_syarat_nama;

            return $syarat;
        });
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pemohon');
    }

    public function formatSurat()
    {
        return $this->belongsTo(FormatSurat::class, 'id_surat');
    }
}
