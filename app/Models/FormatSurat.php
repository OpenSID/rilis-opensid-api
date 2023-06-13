<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

    /** {@inheritdoc} */
    protected $table = 'tweb_surat_format';

    /** {@inheritdoc} */
    protected $casts = [
       'kode_isian' => 'json',
    ];

    /**
     * Define a many-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getSyaratSuratAttribute($value)
    {
        if ($value == null || $value == 'null') {
            return [];
        }

        return SyaratSurat::whereIn('ref_syarat_id', json_decode($value))->with('dokumen')->get();
    }

    /**
     * Scope query untuk layanan mandiri.
     *
     * @param Builder $query
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
     * @return Builder
     */
    public function scopeKunci($query)
    {
        return $query->where('kunci', static::KUNCI_DISABLE);
    }

    /**
     * Getter list surat dan dokumen attribute.
     *
     * @return array
     */
    public function getListSyaratSuratAttribute()
    {
        if ($this->syarat_surat == false) {
            return [];
        }

        return $this->syarat_surat->map(function ($syarat) {
            return [
               'label' => $syarat->ref_syarat_nama,
               'value' => $syarat->ref_syarat_id,
               'form_surat' => [[
                   'type' => 'select',
                   'required' => true,
                   'label' => 'Dokumen Syarat',
                   'name' => 'dokumen',
                   'multiple' => false,
                   'values' => $syarat->dokumen->map(
                       function ($dokumen) {
                           return [
                               'label' => $dokumen->nama,
                               'value' => $dokumen->id,
                           ];
                       }
                   )
                ]]
            ];
        });
    }

    /**
     * Getter form surat attribute.
     *
     * @return mixed
     */
    public function getFormSuratAttribute()
    {
        try {
            if (in_array($this->jenis, FormatSurat::TINYMCE)) {
                $kode_isian =  collect($this->kode_isian)->map(function ($value) {
                    $kode = [
                        'type' => $value['tipe'],
                        'required' => Str::contains($value['atribut'], 'required'),
                        'label' => $value['nama'],
                        'name' => underscore($value['nama']),
                        'choice' => $value['pilihan']
                    ];
                    return $kode;
                });
                $kode_isian->push(
                    [
                    "type"=> "textarea",
                    "required"=> false,
                    "label"=> "Keterangan",
                    "name"=> "keterangan",
                    "subtype"=> "textarea"
                ],
                    [
                    "type"=> "number",
                    "required"=> false,
                    "label"=>"No hp aktif",
                    "name"=> "no_hp_aktif"
                ],
                    [
                        'type' => 'select',
                        'required' => true,
                        'label' => 'Syarat Surat',
                        'name' => 'syarat',
                        'multiple' => false,
                        'values' => $this->list_syarat_surat,
                    ]
                );

                // dd($default);
                return $kode_isian;
            }

            return app('surat')->driver($this->url_surat)->form();
        } catch (\Exception $e) {
            Log::error($e);

            return null;
        }
    }
}
