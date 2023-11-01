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
use Illuminate\Database\Eloquent\Model;

class LogPenduduk extends Model
{
    use ConfigId;

    /**
     * KETERANGAN kode_peristiwa di log_penduduk
     * 1 = insert penduduk baru dengan status lahir
     * 2 = penduduk mati
     * 3 = penduduk pindah keluar
     * 4 = penduduk hilang
     * 5 = insert penduduk baru pindah masuk
     * 6 = penduduk tidak tetap pergi
     */
    public const BARU_LAHIR = 1;

    public const MATI              = 2;
    public const PINDAH_KELUAR     = 3;
    public const HILANG            = 4;
    public const BARU_PINDAH_MASUK = 5;
    public const TIDAK_TETAP_PERGI = 6;
    public const PERISTIWA         = [1, 2, 3, 4];

    /**
     * Static data penolong mati.
     *
     * @var array
     */
    public const PENOLONG_MATI = [
        1 => 'Dokter',
        2 => 'Tenaga Kesehatan',
        3 => 'Kepolisian',
        4 => 'Lainnya',
    ];

    /**
     * Static data penyebab kematian.
     *
     * @var array
     */
    public const PENYEBAB_KEMATIAN = [
        1 => 'Sakit biasa / tua',
        2 => 'Wabah Penyakit',
        3 => 'Kecelakaan',
        4 => 'Kriminalitas',
        5 => 'Bunuh Diri',
        6 => 'Lainnya',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_penduduk';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'tgl_lapor'     => 'datetime:Y-m-d',
        'tgl_peristiwa' => 'datetime:Y-m-d',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pend', 'id');
    }

    /**
     * Getter penolong mati.
     *
     * @return string
     */
    public function getYangMenerangkanAttribute()
    {
        return static::PENOLONG_MATI[$this->penolong_mati] ?? '';
    }

    /**
     * Getter penolong mati.
     *
     * @return string
     */
    public function getPenyebabKematianAttribute()
    {
        return static::PENYEBAB_KEMATIAN[$this->sebab] ?? '';
    }
}
