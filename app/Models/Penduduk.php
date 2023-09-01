<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Penduduk extends Model
{
    use ConfigId;

    /**
     * Static data tempat lahir.
     *
     * @var array
     */
    public const TEMPAT_LAHIR = [
        1 => 'RS/RB',
        2 => 'Puskesmas',
        3 => 'Polindes',
        4 => 'Rumah',
        5 => 'Lainnya',
    ];

    /**
     * Static data jenis kelahiran.
     *
     * @var array
     */
    public const JENIS_KELAHIRAN = [
        1 => 'Tunggal',
        2 => 'Kembar 2',
        3 => 'Kembar 3',
        4 => 'Kembar 4',
    ];

    /**
     * Static data penolong kelahiran.
     *
     * @var array
     */
    public const PENOLONG_KELAHIRAN = [
        1 => 'Dokter',
        2 => 'Bidan Perawat',
        3 => 'Dukun',
        4 => 'Lainnya',
    ];

    /** {@inheritdoc} */
    protected $table = 'tweb_penduduk';

    /** {@inheritdoc} */
    protected $fillable = [
        'email',
    ];

    /** {@inheritdoc} */
    protected $with = [
        'jenisKelamin',
        'agama',
        'pendidikan',
        'pekerjaan',
        'wargaNegara',
        'golonganDarah',
        'cacat',
        'statusKawin',
        'pendudukStatus',
    ];

    /** {@inheritdoc} */
    protected $casts = [
        'tanggallahir' => 'datetime',
    ];

    /**
     * Define a one-to-one relationship.
     *
     * @return HasOne
     */
    public function mandiri()
    {
        return $this->hasOne(PendudukMandiri::class, 'id_pend');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function jenisKelamin()
    {
        return $this->belongsTo(Sex::class, 'sex')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function agama()
    {
        return $this->belongsTo(Agama::class, 'agama_id')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_sedang_id')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function pendidikanKK()
    {
        return $this->belongsTo(PendidikanKK::class, 'pendidikan_kk_id')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_id')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function wargaNegara()
    {
        return $this->belongsTo(WargaNegara::class, 'warganegara_id')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function golonganDarah()
    {
        return $this->belongsTo(GolonganDarah::class, 'golongan_darah_id')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function cacat()
    {
        return $this->belongsTo(Cacat::class, 'cacat_id')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function sakitMenahun()
    {
        return $this->belongsTo(SakitMenahun::class, 'sakit_menahun_id')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function kb()
    {
        return $this->belongsTo(KB::class, 'cara_kb_id')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function statusKawin()
    {
        return $this->belongsTo(StatusKawin::class, 'status_kawin')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function pendudukHubungan()
    {
        return $this->belongsTo(PendudukHubungan::class, 'kk_level')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function pendudukStatus()
    {
        return $this->belongsTo(PendudukStatus::class, 'status')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'id_kk')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function clusterDesa()
    {
        return $this->belongsTo(ClusterDesa::class, 'id_cluster')->withDefault(['id' => null, 'nama' => null]);
    }

    /**
     * Getter wajib ktp attribute.
     *
     * @return string
     */
    public function getWajibKTPAttribute()
    {
        return (($this->tanggallahir->age > 16) || (! empty($this->status_kawin) && $this->status_kawin != 1))
            ? 'WAJIB KTP'
            : 'BELUM';
    }

    /**
     * Getter tempat dilahirkan attribute.
     *
     * @return string
     */
    public function getDiLahirkanAttribute()
    {
        return isset(static::TEMPAT_LAHIR[$this->tempat_dilahirkan])
            ? static::TEMPAT_LAHIR[$this->tempat_dilahirkan]
            : '';
    }

    /**
     * Getter jenis lahir attribute.
     *
     * @return string
     */
    public function getJenisLahirAttribute()
    {
        return isset(static::JENIS_KELAHIRAN[$this->jenis_kelahiran])
            ? static::JENIS_KELAHIRAN[$this->jenis_kelahiran]
            : '';
    }

    /**
     * Getter jenis lahir attribute.
     *
     * @return string
     */
    public function getPenolongLahirAttribute()
    {
        return isset(static::PENOLONG_KELAHIRAN[$this->penolong_kelahiran])
            ? static::PENOLONG_KELAHIRAN[$this->penolong_kelahiran]
            : '';
    }

    /**
     * Getter status perkawinan attribute.
     *
     * @return string
     */
    public function getStatusPerkawinanAttribute()
    {
        return ! empty($this->status_kawin) && $this->status_kawin != 2
            ? $this->statusKawin->nama
            : (
                empty($this->akta_perkawinan)
                    ? 'KAWIN BELUM TERCATAT'
                    : 'KAWIN TERCATAT'
            );
    }

    /**
     * Getter status hamil attribute.
     *
     * @return string
     */
    public function getStatusHamilAttribute()
    {
        return empty($this->hamil) ? 'TIDAK HAMIL' : 'HAMIL';
    }

    /**
     * Getter nama asuransi attribute.
     *
     * @return string
     */
    public function getNamaAsuransiAttribute()
    {
        return ! empty($this->id_asuransi) && $this->id_asuransi != 1
            ? (($this->id_asuransi == 99)
                ? "Nama/No Asuransi : {$this->no_asuransi}"
                : "No Asuransi : {$this->no_asuransi}")
            : '';
    }

    /**
     * Getter url foto attribute.
     *
     * @return string
     */
    public function getUrlFotoAttribute()
    {
        try {
            return Storage::disk('ftp')->exists("desa/upload/user_pict/{$this->foto}")
                ? Storage::disk('ftp')->url("desa/upload/user_pict/{$this->foto}")
                : null;
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
