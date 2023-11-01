<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use App\Libraries\TinyMCE;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LogSurat extends Model
{
    use ConfigId;

    /**
     * Static data status verifikasi.
     *
     * @var array
     */
    public const STATUS_PERIKSA = [
        0 => 'Menunggu Verifikasi',
        1 => 'Siap Cetak',
        2 => 'Menunggu TTD',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_surat';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['formatSurat', 'penduduk', 'pamong'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'id_format_surat',
        'id_pend',
        'id_pamong',
        'nama_pamong',
        'nama_jabatan',
        'id_user',
        'tanggal',
        'bulan',
        'tahun',
        'no_surat',
        'nama_surat',
        'lampiran',
        'nik_non_warga',
        'nama_non_warga',
        'keterangan',
        'lokasi_arsip',
        'urls_id',
        'status',
        'log_verifikasi',
        'tte',
        'verifikasi_operator',
        'verifikasi_kades',
        'verifikasi_sekdes',
        'isi_surat',
        'kecamatan',
    ];

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
            });
        } elseif ($user->pamong != null &&  $user->pamong->jabatan_id == sekdes()->id && config('aplikasi.verifikasi_sekdes') == 1) {
            return $query->where(function ($q) {
                return $q->whereIn('verifikasi_sekdes', ['1', '0'])
                    ->orWhereNull('verifikasi_operator');
            });
        }
        return $query;
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

    public function getFormatPenomoranSuratAttribute()
    {
        $thn                = $this->tahun ?? date('Y');
        $bln                = $this->bulan ?? date('m');
        $format_nomor_surat = ($this->formatSurat->format_nomor == '') ? config('aplikasi.format_nomor_surat') : $this->formatSurat->format_nomor;

        $tinymce            = new TinyMCE();
        $format_nomor_surat = $tinymce->substitusiNomorSurat($this->no_surat, $format_nomor_surat);
        $array_replace      = [
            '[kode_surat]'   => $this->formatSurat->kode_surat,
            '[tahun]'        => $thn,
            '[bulan_romawi]' => bulan_romawi((int) $bln),
            '[kode_desa]'    => identitas()->kode_desa,
        ];

        return str_replace(array_keys($array_replace), array_values($array_replace), $format_nomor_surat);
    }

}
