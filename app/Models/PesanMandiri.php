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

use App\Enums\StatusEnum;
use App\Http\Traits\ConfigId;
use App\Http\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesanMandiri extends Model
{
    use HasFactory;
    use ConfigId;
    use Uuid;

    protected $table = 'pesan_mandiri';
    protected $primaryKey = 'uuid';
    protected $fillable = ['uuid', 'config_id', 'owner', 'penduduk_id', 'subjek', 'komentar', 'status', 'tipe', 'is_archived'];

    public const READ = 1;
    public const UNREAD = 2;
    public const MASUK = 1;
    public const KELUAR = 2;

    /**
     * Scope a query to only include unread outgoing messages for a specific resident.
     */
    public function scopeBelumDibaca($query, $pendudukId)
    {
        return $query->where('penduduk_id', $pendudukId)
            ->where('status', self::UNREAD)
            ->where('tipe', self::KELUAR);
    }

    /**
     * Check if the message is read.
     */
    public function isRead()
    {
        return $this->attributes['status'] == self::READ;
    }

    /**
     * Check if the message is archived.
     */
    public function isArchive()
    {
        return $this->attributes['is_archived'] == StatusEnum::YA;
    }

    /**
     * Determine if there is a delay before a new message can be sent.
     */
    public static function hasDelay($penduduk_id = '', $tipe = 1)
    {
        return self::where('penduduk_id', $penduduk_id)
            ->where('tipe', $tipe)
            ->where('tgl_upload', '>', Carbon::now()->subSeconds(config('constans.rentang_kirim_pesan')))
            ->exists();
    }

    /**
     * Get the penduduk that owns the PesanMandiri
     */
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }

    /**
     * Scope query untuk tipe pesan masuk.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePesanPengguna($query)
    {
        return $query->where('penduduk_id', auth('jwt')->user()->penduduk->id);
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
            ? self::MASUK
            : self::KELUAR;

        return $query->where('tipe', $tipePesan);
    }
}
