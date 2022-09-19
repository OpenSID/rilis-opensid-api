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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pamong extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tweb_desa_pamong';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'pamong_id';

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
    protected $with = ['penduduk', 'jabatan'];

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    public function penduduk()
    {
        return $this->hasOne(Penduduk::class, 'id', 'id_pend');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function jabatan()
    {
        return $this->hasOne(RefJabatan::class, 'id', 'jabatan_id');
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'pamong_id', 'pamong_id');
    }

    public function scopeSelectData($query)
    {
        return $query
            ->select(['pamong_id', 'pamong_nama', 'jabatan_id', 'ref_jabatan.nama AS pamong_jabatan', 'pamong_nip', 'pamong_niap', 'pamong_ttd', 'pamong_ub'])
            ->selectRaw('IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nama, tweb_penduduk.nama) AS pamong_nama')
            ->leftJoin('tweb_penduduk', 'tweb_penduduk.id', '=', 'tweb_desa_pamong.id_pend')
            ->leftJoin('ref_jabatan', 'ref_jabatan.id', '=', 'tweb_desa_pamong.jabatan_id');
    }

    /**
     * Scope query untuk status pamong
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeStatus($query, $value = 1)
    {
        return $query->where('pamong_status', $value);
    }

    /**
     * Scope query untuk kepala desa
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeKepalaDesa($query)
    {
        return $this->scopeSelectData($query)
            ->where('jabatan_id', 1)
            ->where('pamong_status', 1);
    }

    /**
     * Scope query untuk sekretaris desa
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeSekretarisDesa($query)
    {
        return $this->scopeSelectData($query)
            ->where('jabatan_id', 2)->where('pamong_status', 1);
    }

    /**
     * Scope query untuk Penanda Tangan
     *
     * Ket :
     * - a.n => untuk sekretaris yang dipilih
     * - u.b => untuk pamong selain kades dan sekretaris yang dipilih
     *
     * @param Builder    $query
     * @param mixed      $value
     * @param mixed|null $jenis
     *
     * @return Builder
     */
    public function scopeTtd($query, $jenis = null)
    {
        if ($jenis === 'a.n') {
            $query->where('pamong_ttd', 1)->where('jabatan_id', 2);
        } elseif ($jenis === 'u.b') {
            $query->where('pamong_ub', 1)->whereNotIn('jabatan_id', RefJabatan::EXCLUDE_DELETE);
        }

        return $this->scopeSelectData($query)
            ->where('pamong_status', 1);
    }

    /**
     * Scope query untuk daftar penanda tangan
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopePenandaTangan($query)
    {
        return $this->scopeSelectData($query)
            ->where(static function ($query) {
                $query->whereIn('jabatan_id', RefJabatan::EXCLUDE_DELETE)
                    ->orWhere('pamong_ttd', '1')
                    ->orWhere('pamong_ub', '1');
            })
            ->where('pamong_status', 1)
            ->orderBy('jabatan_id')
            ->orderBy('urut');
    }

    public function scopeKehadiranPamong($query)
    {
        return $query
            ->leftJoin('kehadiran_perangkat_desa as k', 'tweb_desa_pamong.pamong_id', '=', 'k.pamong_id')
            ->leftJoin('kehadiran_pengaduan as p', 'tweb_desa_pamong.pamong_id', '=', 'p.id_pamong');
    }

    /**
     * Scope query untuk daftar kehadiran pamong
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeDaftar($query, $value = 1)
    {
        return $query->where('pamong_status', 1)
            ->where('kehadiran', $value);
    }
}
