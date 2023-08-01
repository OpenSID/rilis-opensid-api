<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    /**
    * Scope query untuk admin.
    *
    * @param Builder $query
    * @return Builder
    */
    public function scopeAdmin($query)
    {
        // jika kepdesa

        $user = auth('admin')->user()->load('pamong');

        if ($user->pamong != null && $user->pamong->jabatan_id == kades()->id && config('aplikasi.verifikasi_kades') == 1) {

            return $query->where(function ($q) {
                return $q->whereIn('verifikasi_kades', ['1', '0']);
            })
            ->selectRaw('verifikasi_kades as verifikasi')
            ->selectRaw('CASE when verifikasi_kades = 1 THEN IF(tte is null,verifikasi_kades,2) ELSE 0 end AS status_periksa');

        } elseif ($user->pamong != null &&  $user->pamong->jabatan_id == sekdes()->id && config('aplikasi.verifikasi_sekdes') == 1) {
            return $query->where(function ($q) {
                return $q->whereIn('verifikasi_sekdes', ['1', '0'])
                ->orWhereNull('verifikasi_operator');
            })
            ->selectRaw('verifikasi_sekdes as verifikasi')
            ->selectRaw('CASE WHEN verifikasi_sekdes = 1 THEN IF(tte is null,IF(verifikasi_kades is null,1 , verifikasi_kades), tte)
            ELSE 0 end AS status_periksa');
        } else {
            return $query->selectRaw('verifikasi_operator as verifikasi')
            ->selectRaw('CASE when verifikasi_operator = 1 THEN IF(tte is null,IF(verifikasi_kades is null,IF(verifikasi_sekdes is null, 1, verifikasi_sekdes),verifikasi_kades),tte) ELSE 0 end AS status_periksa');
        }
    }

    /**
     * Getter untuk menambahkan url file.
     *
     * @return string
     */
    public function getUrlFileAttribute()
    {
        try {
            return Storage::disk('ftp')->exists("desa/arsip/{$this->nama_surat}")
                ? Storage::disk('ftp')->url("desa/arsip/{$this->nama_surat}")
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
    public function getDownloadSuratAttribute()
    {
        try {
            return Storage::disk('ftp')->exists("desa/arsip/{$this->nama_surat}")
                ? Storage::disk('ftp')->download("desa/arsip/{$this->nama_surat}")
                : null;
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
