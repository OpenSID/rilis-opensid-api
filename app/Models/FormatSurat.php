<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use App\Libraries\TinyMCE;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class FormatSurat extends Model
{
    use ConfigId;

    public const MANDIRI               = 1;
    public const MANDIRI_DISABLE       = 0;
    public const KUNCI                 = 1;
    public const KUNCI_DISABLE         = 0;
    public const FAVORIT               = 1;
    public const FAVORIT_DISABLE       = 0;
    public const RTF_SISTEM            = 1;
    public const RTF_DESA              = 2;
    public const TINYMCE_SISTEM        = 3;
    public const TINYMCE_DESA          = 4;
    public const RTF                   = [1, 2];
    public const TINYMCE               = [3, 4];
    public const SISTEM                = [1, 3];
    public const DESA                  = [2, 4];
    public const DEFAULT_ORIENTATAIONS = 'Potrait';
    public const DEFAULT_SIZES         = 'F4';

    /**
     * Static data masa berlaku surat.
     *
     * @var array
     */
    public const MASA_BERLAKU = [
        'd' => 'Hari',
        'w' => 'Minggu',
        'M' => 'Bulan',
        'y' => 'Tahun',
    ];

    /**
     * Static data jenis surat.
     *
     * @var array
     */
    public const JENIS_SURAT = [
        self::RTF_SISTEM     => 'Surat Sistem RTF',
        self::RTF_DESA       => 'Surat [Desa] RTF',
        self::TINYMCE_SISTEM => 'Surat Sistem TinyMCE',
        self::TINYMCE_DESA   => 'Surat [Desa] TinyMCE',
    ];

    /**
     * Static data margin surat.
     *
     * @var array
     */
    public const MARGINS = [
        'kiri'  => 1.78,
        'atas'  => 0.63,
        'kanan' => 1.78,
        'bawah' => 1.37,
    ];

    /**
     * Static data orientation surat.
     *
     * @var array
     */
    public const ORIENTATAIONS = [
        'Potrait',
        'Lanscape',
    ];

    /**
     * Static data Size surat.
     *
     * @var array
     */
    public const SIZES = [
        'A1',
        'A2',
        'A3',
        'A4',
        'A5',
        'A6',
        'F4',
    ];

    /**
     * Static data atribut surat.
     *
     * @var array
     */
    public const ATTRIBUTES = [
        'text'            => 'Input Teks',
        'number'          => 'Input Angka',
        'email'           => 'Input Email',
        'url'             => 'Input Url',
        'date'            => 'Input Tanggal',
        'time'            => 'Input Jam',
        'textarea'        => 'Text Area',
        'select-manual'   => 'Select (Manual)',
        'select-otomatis' => 'Select (Otomatis)',
        'hari'            => 'Input Hari',
        'hari-tanggal'    => 'Input Hari dan Tanggal',
    ];

    /**
     * {@inheritDoc}
     */
    protected $table = 'tweb_surat_format';

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'nama',
        'url_surat',
        'kode_surat',
        'lampiran',
        'kunci',
        'favorit',
        'jenis',
        'mandiri',
        'masa_berlaku',
        'satuan_masa_berlaku',
        'qr_code',
        'logo_garuda',
        'kecamatan',
        'syarat_surat',
        'template',
        'template_desa',
        'form_isian',
        'kode_isian',
        'orientasi',
        'ukuran',
        'margin',
        'margin_global',
        'header',
        'footer',
        'format_nomor',
        'created_by',
        'updated_by',
    ];

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $appends = [
        'judul_surat',
        'margin_cm_to_mm',
        'url_surat_sistem',
        'url_surat_desa',
        // 'syarat_surat'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'masa_berlaku' => 'integer',
        'kunci'        => 'boolean',
        'favorit'      => 'boolean',
        'mandiri'      => 'boolean',
        'qr_code'      => 'boolean',
        'logo_garuda'  => 'boolean',
        'syarat_surat' => 'json',
        // 'kode_isian'   => 'json',
        // 'margin'       => 'json',
    ];

    /**
     * Define a many-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function syaratSurat()
    {
        return $this->belongsToMany(SyaratSurat::class, 'syarat_surat', 'surat_format_id', 'ref_syarat_id');
    }

    /**
     * Scope query untuk layanan mandiri.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeMandiri($query)
    {
        return $query->where('mandiri', static::MANDIRI);
    }

    /**
     * Scope query untuk list surat yang tidak dikunci.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    // public function scopeKunci($query)
    // {
    //     return $query->where('kunci', static::KUNCI_DISABLE);
    // }

    /**
     * Getter list surat dan dokumen attribute.
     *
     * @return array
     */
    // public function getListSyaratSuratAttribute()
    // {
    //     return $this->syaratSurat->map(
    //         static function ($syarat) {
    //             return [
    //                 'label'      => $syarat->ref_syarat_nama,
    //                 'value'      => $syarat->ref_syarat_id,
    //                 'form_surat' => [
    //                     [
    //                         'type'     => 'select',
    //                         'required' => true,
    //                         'label'    => 'Dokumen Syarat',
    //                         'name'     => 'dokumen',
    //                         'multiple' => false,
    //                         'values'   => $syarat->dokumen->map(static function ($dokumen) {
    //                             return [
    //                                 'label' => $dokumen->nama,
    //                                 'value' => $dokumen->id,
    //                             ];
    //                         }),
    //                     ],
    //                 ],
    //             ];
    //         }
    //     );
    // }

    /**
     * Getter form surat attribute.
     *
     * @return mixed
     */
    public function getFormSuratAttribute()
    {
        // try {
        //     return app('surat')->driver($this->url_surat)->form();
        // } catch (Exception $e) {
        //     Log::error($e);

        //     return null;
        // }
    }

    /**
     * Setter untuk url_surat.
     *
     * @return void
     */
    // public function setUrlSuratAttribute()
    // {
    //     $this->attributes['url_surat'] = 'surat_' . strtolower(str_replace([' ', '-'], '_', $this->attributes['nama']));
    // }

    /**
     * Getter untuk lokasi_surat
     *
     * @return string
     */
    public function getLokasiSuratAttribute()
    {
        return LOKASI_SURAT_DESA . $this->url_surat;
    }

    /**
     * Getter untuk judul_surat
     *
     * @return string
     */
    public function getJudulSuratAttribute()
    {
        return 'Surat ' . $this->nama;
    }

    /**
     * Getter untuk kode_isian
     *
     * @return string
     */
    public function getKodeIsianAttribute()
    {
        if (in_array($this->jenis, self::RTF)) {
            return kode_isian($this->url_surat);
        }

        $kode_isian = json_decode($this->attributes['kode_isian']);
        $non_warga  = json_decode(TinyMCE::getKodeIsianNonWarga());
        if (isset($this->getFormIsianAttribute()->data) && $this->getFormIsianAttribute()->data == '2') {
            if (null !== $kode_isian) {
                return [...$non_warga, ...$kode_isian];
            }

            return $non_warga;
        }

        return $kode_isian;
    }

    /**
     * Getter untuk form_isian
     *
     * @return mixed
     */
    public function getFormIsianAttribute()
    {
        if (in_array($this->jenis, self::RTF)) {
            return null;
        }

        return json_decode($this->attributes['form_isian']);
    }

    /**
     * Getter untuk judul_surat
     *
     * @return string
     */
    public function getMarginCmToMmAttribute()
    {
        $margin = json_decode($this->margin);

        return [
            $margin->kiri * 10,
            $margin->atas * 10,
            $margin->kanan * 10,
            $margin->bawah * 10,
        ];
    }

    /**
     * Getter untuk url surat sistem
     *
     * @return string
     */
    public function getUrlSuratSistemAttribute()
    {

        return null;
    }

    /**
     * Getter untuk url surat desa
     *
     * @return string
     */
    public function getUrlSuratDesaAttribute()
    {


        return null;
    }

    /**
     * Scope query untuk IsExist
     *
     * @param mixed $query
     * @param mixed $value
     *
     * @return Builder
     */
    public function scopeIsExist($query, $value)
    {
        return $query->where('url_surat', $value)->exists();
    }

    /**
     * Scope query untuk Kunci Surat
     *
     * @param mixed $query
     * @param mixed $value
     *
     * @return Builder
     */
    public function scopeKunci($query, $value = self::KUNCI)
    {
        return $query->where('kunci', $value);
    }

    /**
     * Scope query untuk Favorit Surat
     *
     * @param mixed $query
     * @param mixed $value
     *
     * @return Builder
     */
    public function scopeFavorit($query, $value = self::FAVORIT)
    {
        return $query->where('favorit', $value);
    }

    /**
     * Scope query untuk Jenis Surat
     *
     * @param mixed $query
     * @param mixed $value
     *
     * @return Builder
     */
    public function scopeJenis($query, $value)
    {
        if (empty($value)) {
            return $query->whereNotNull('jenis');
        }

        if (is_array($value)) {
            return $query->whereIn('jenis', $value);
        }

        return $query->where('jenis', $value);
    }

    /**
     * Scope query untuk layanan mandiri.
     *
     * @param Builder    $query
     * @param mixed|null $url
     *
     * @return Builder
     */
    public function scopeCetak($query, $url = null)
    {
        return $this->scopeKunci($query, self::KUNCI_DISABLE)->where('url_surat', $url);
    }
}
