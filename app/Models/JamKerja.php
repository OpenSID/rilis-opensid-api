<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Http\Traits\ConfigId;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class JamKerja extends Model
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kehadiran_jam_kerja';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'jam_masuk',
        'jam_keluar',
        'status',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    public function scopeLibur($query)
    {
        return $query->where('status', 0)->where('nama_hari', $this->getNamaHari());
    }

    public function scopeJamKerja($query)
    {
        $waktu  = date('H:i');
        $masuk  = SettingAplikasi::where('key', 'rentang_waktu_masuk')->first()->value ?? SettingAplikasi::RENTANG_WAKTU_MASUK;
        $keluar = SettingAplikasi::where('key', 'rentang_waktu_keluar')->first()->value ?? SettingAplikasi::RENTANG_WAKTU_KELUAR;


        return $query
            ->selectRaw('id, nama_hari, status, keterangan')
            ->selectRaw('jam_masuk as jam_masuk_normal, jam_keluar as jam_keluar_normal')
            ->selectRaw(sprintf('date_sub(jam_masuk, interval %s minute) as jam_masuk', $masuk))
            ->selectRaw(sprintf('date_add(jam_keluar, interval %s minute) as jam_keluar', $keluar))
            ->where('nama_hari', $this->getNamaHari())
            ->where(static function ($query) use ($masuk, $keluar, $waktu): void {
                $query
                    ->whereRaw('date_sub(jam_masuk, interval ? minute) > ?', [$masuk, $waktu])
                    ->orWhereRaw('date_add(jam_keluar, interval ? minute) < ?', [$keluar, $waktu]);
            });
    }


    protected function getNamaHari()
    {
        return Carbon::now()->dayName;
    }
}
