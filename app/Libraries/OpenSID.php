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

use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

/**
 * Class Date_conv
 *
 * Class for date conversion in Gregorian-Julian-Hijri calendar.
 * This class originally adopted from hijri-dates library (https://github.com/GeniusTS/hijri-dates/blob/master/src/Converter.php)
 *
 * @license MIT
 */
class OpenSID
{
    public static function loginOpensid($password, $username = null)
    {
        $urlOpensid = env('FTP_URL');
        $client = new Client(['cookies' => true, 'base_uri' => $urlOpensid ]);
        $client->request('GET', 'siteman');
        $cookie = $client->getConfig('cookies');
        $csrf = $cookie->getCookieByName('sidcsrf');
        $userLogin = auth('admin')->user()?->username ?? $username;
        $originalPassword = User::where('username', $userLogin)->first()->password ?? $password;
        $secretCode = substr($originalPassword, rand(0, strlen($originalPassword) - 10), 10);
        $response = $client->request('POST', 'index.php/siteman/auth', [
            'timeout' => 30,
            'form_params' => [
                'sidcsrf' => $csrf->getValue(),
                'username' => $userLogin,
                'password' => $password,
                'secret_code' => $secretCode,
            ],
            'allow_redirects' => [
                'max'             => 2,        // allow at most 10 redirects.
                'strict'          => true,      // use "strict" RFC compliant redirects.
                'referer'         => true,      // add a Referer header
                'track_redirects' => true
            ],
            'headers' => [
                'Referer' => url()->current()
            ]
        ]);

        $url_redirect = $response->getHeaderLine('X-Guzzle-Redirect-History');
        if (Str::contains($url_redirect, 'beranda')) {
            return $client;
        } else {
            throw new Exception('Gagal Login ke Server OpenSid');
        }
    }
}
