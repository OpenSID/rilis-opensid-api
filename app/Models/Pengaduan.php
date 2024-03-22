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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Http\Traits\ConfigId;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Pengaduan extends Model
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengaduan';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
    ];

     /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'child',
    ];

    protected $appends = [
        'url_foto',
    ];

    /**
    * Getter untuk menambahkan url file.
    *
    * @return string
    */
    public function getUrlFotoAttribute()
    {
        try {
           
            return Storage::disk('ftp')->exists("desa/upload/pengaduan/{$this->foto}")
                ? Storage::disk('ftp')->url("desa/upload/pengaduan/{$this->foto}")
                : null;
        } catch (Exception $e) {
           
            Log::error($e);
        }
    }

    /**
     * Scope query untuk status pengaduan
     *
     * @param mixed $query
     * @param mixed $status
     *
     * @return Builder
     */
    public function scopeStatus($query, $status = null)
    {
        if ($status) {
            $query->where('status', $status);
        }

        return $this->scopeTipe($query);
    }

    /**
     * Scope query untuk tipe pengaduan
     * Jika id_pengaduan null maka dari warga
     * Jika id_pengaduan tidak null maka balasan dari admin
     *
     * @param mixed      $query
     * @param mixed|null $id_pengaduan
     */
    public function scopeTipe($query, $id_pengaduan = null)
    {
        if ($id_pengaduan) {
            $query->where('id_pengaduan', $id_pengaduan);
        }

        return $query->where('id_pengaduan', null);
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function child()
    {
        return $this->hasMany(Pengaduan::class, 'id_pengaduan', 'id');
    }

    
}
