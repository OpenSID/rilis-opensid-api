<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    public const ACTIVE = 1;
    public const NONACTIVE = 2;
    public const TIPE_MASUK = 2;
    public const TIPE_KELUAR = 1;
    public const NOT_IN_ARTIKEL = 775;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'komentar';

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00',
        'updated_at'  => 'datetime:Y-m-d H:00',
        'tgl_upload'  => 'datetime:Y-m-d H:00',
    ];

    /**
     * The name of the "created at" column.
     *
     * @var string|null
     */
    public const CREATED_AT = 'tgl_upload';

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    public const UPDATED_AT = 'updated_at';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'id_artikel' => self::NOT_IN_ARTIKEL,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'owner', 'subjek', 'komentar', 'tipe', 'status', 'no_hp', 'id_artikel'];

    /**
     * Scope a query to only enable category.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeEnable($query)
    {
        return $query->where('status', static::ACTIVE);
    }

    /**
     * Scope query untuk tipe pesan masuk.
     *
     * @param Builder $query
     * @param string $tipe
     * @return Builder
     */
    public function scopeTipePesan($query, string $type)
    {
        $tipePesan = $type === 'masuk'
            ? self::TIPE_MASUK
            : self::TIPE_KELUAR;

        return $query->where('tipe', $tipePesan);
    }

    /**
     * Scope query untuk tipe pesan masuk.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePesanPengguna($query)
    {
        return $query->where('email', auth('jwt')->user()->penduduk->nik);
    }
}
