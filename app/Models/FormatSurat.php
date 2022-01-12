<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class FormatSurat extends Model
{
    public const MANDIRI = 1;
    public const MANDIRI_DISABLE = 0;
    public const KUNCI = 1;
    public const KUNCI_DISABLE = 0;

    /** {@inheritdoc} */
    protected $table = 'tweb_surat_format';

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
        return $this->syaratSurat->map(
            function ($syarat) {
                return [
                    'label' => $syarat->ref_syarat_nama,
                    'value' => $syarat->ref_syarat_id,
                    'form_surat' => [
                        [
                            'type' => 'select',
                            'required' => true,
                            'label' => 'Dokumen Syarat',
                            'name' => 'dokumen',
                            'multiple' => false,
                            'values' => $syarat->dokumen->map(function ($dokumen) {
                                return [
                                    'label' => $dokumen->nama,
                                    'value' => $dokumen->id,
                                ];
                            }),
                        ],
                    ]
                ];
            }
        );
    }

    /**
     * Getter form surat attribute.
     *
     * @return mixed
     */
    public function getFormSuratAttribute()
    {
        try {
            return app('surat')->driver($this->url_surat)->form();
        } catch (\Exception $e) {
            Log::error($e);

            return null;
        }
    }
}
