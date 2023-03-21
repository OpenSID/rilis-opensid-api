<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LogSurat extends Model
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_surat';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['formatSurat', 'penduduk', 'pamong'];

    public function formatSurat()
    {
        return $this->belongsTo(FormatSurat::class, 'id_format_surat');
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pend');
    }

    public function pamong()
    {
        return $this->belongsTo(Pamong::class, 'id_pamong');
    }

    /**
     * Scope query untuk pengguna.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePengguna($query)
    {
        return $query->where('id_pend', auth('jwt')->user()->penduduk->id);
    }
}
