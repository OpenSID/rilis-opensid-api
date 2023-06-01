<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BantuanPeserta extends Model
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'program_peserta';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['bantuan'];

    public function bantuan()
    {
        return $this->belongsTo(Bantuan::class, 'program_id');
    }

    /**
     * Scope query untuk peserta.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePeserta($query)
    {
        return $query->where('peserta', auth('jwt')->user()->penduduk->nik);
    }
}
