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

namespace App\Libraries;

use Exception;
use App\Models\FcmToken;
use App\Models\UserAuth;
use App\Enums\FirebaseEnum;
use App\Models\LogNotifikasiAdmin;
use Illuminate\Support\Facades\Log;

class Firebase
{
    public static function kirim_notifikasi_admin($next, $pesan, $judul, $payload = '')
    {
        $allToken = FcmToken::whereHas('user', static function ($user) use ($next) {
            return $user->WhereHas('pamong', static function ($query) use ($next) {
                if ($next == 'verifikasi_sekdes') {
                    return $query->where('jabatan_id', '=', sekdes()->id);
                }
                if ($next == 'verifikasi_kades') {
                    return $query->where('jabatan_id', '=', kades()->id);
                }
                return $query->where('jabatan_id', '!=', kades()->id)->where('jabatan_id', '!=', sekdes()->id);
            })->when($next != 'verifikasi_sekdes' && $next != 'verifikasi_kades', static function ($query) {

                return $query->orWhereNull('pamong_id');
            });
        })->get();

        if (cek_koneksi_internet()) {
            // kirim ke aplikasi android admin.
            try {
                $client       = new \Fcm\FcmClient(FirebaseEnum::SERVER_KEY, FirebaseEnum::SENDER_ID);
                $notification = new \Fcm\Push\Notification();

                $notification
                    ->addRecipient($allToken->pluck('token')->all())
                    ->setTitle($judul)
                    ->setBody($pesan)
                    ->addData('payload', $payload);
                $client->send($notification);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }

        $isi = [
            'judul'      => $judul,
            'isi'        => $pesan,
            'payload'    => $payload,
            'read'       => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        self::create_log_notifikasi_admin($next, $isi);
    }

    public static function create_log_notifikasi_admin($next, $isi)
    {

        $users = UserAuth::whereHas('pamong', static function ($query) use ($next) {
            if ($next == 'verifikasi_sekdes') {
                return $query->where('jabatan_id', '=', sekdes()->id);
            }
            if ($next == 'verifikasi_kades') {
                return $query->where('jabatan_id', '=', kades()->id);
            }

            return $query->where('jabatan_id', '!=', kades()->id)->where('jabatan_id', '!=', sekdes()->id);
        })
            ->when($next != 'verifikasi_sekdes' && $next != 'verifikasi_kades', static function ($query) {
                return $query->orWhereNull('pamong_id');
            })
            ->get();

        if (is_array($isi) && $users->count() > 0) {
            $logs = $users->map(static function ($user) use ($isi) {
                $data_user = ['id_user' => $user->id, 'config_id' => $user->config_id];

                return array_merge($data_user, $isi);
            });
            LogNotifikasiAdmin::insert($logs->toArray());
        }
    }


}
