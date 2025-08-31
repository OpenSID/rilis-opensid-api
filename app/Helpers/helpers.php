<?php

use App\Models\Config;
use App\Models\RefJabatan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

if (!function_exists('opensid_api_version')) {
    /**
     * Get version opensid-api.
     *
     * @return string
     */
    function opensid_api_version()
    {
        return "v2509.0.0";
    }
}

if (!function_exists('underscore')) {
    /**
     * Membuat spasi menjadi underscore atau sebaliknya
     *
     * @param string $str           string yang akan dibuat spasi
     * @param bool   $to_underscore true jika ingin membuat spasi menjadi underscore, false jika sebaliknya
     * @param bool   $lowercase     true jika ingin mengubah huruf menjadi kecil semua
     *
     * @return string string yang sudah dibuat spasi
     */
    function underscore($str, $to_underscore = true, $lowercase = true)
    {
        // membersihkan string di akhir dan di awal
        $str = trim($str);

        // membuat text lowercase jika diperlukan
        if ($lowercase) {
            $str = $lowercase ? strtolower($str) : $str;
        }

        if ($to_underscore) {
            // mengganti spasi dengan underscore
            $str = str_replace(' ', '_', $str);
        } else {
            // mengganti underscore dengan spasi
            $str = str_replace('_', ' ', $str);
        }

        // menyajikan hasil akhir
        return $str;
    }
}

// identitas('nama_desa');
if (!function_exists('get_app_key')) {
    /**
     * Get identitas desa.
     *
     * @return object|string
     */
    function get_app_key()
    {
        return Cache::get('APP_KEY');
    }
}

// identitas('nama_desa');
if (!function_exists('identitas')) {
    /**
     * Get identitas desa.
     *
     * @return object|string
     */
    function identitas(?string $params = null)
    {
        $identitas = null;
        if (Schema::hasColumn('config', 'app_key') && DB::table('config')->where('app_key', get_app_key())->exists()) {
            $identitas = Config::appKey()->first();
        }


        if ($params && $identitas) {
            return $identitas->{$params};
        }

        return $identitas;
    }
}

// setting('sebutan_desa');
if (!function_exists('setting')) {
    function setting($params = null)
    {
        $getSetting =  config('aplikasi');
        if ($params && !empty($getSetting)) {
            if (isset($getSetting[$params])) {
                return $getSetting[$params];
            }

            return null;
        }

        return $getSetting;
    }
}

function tgl_indo($tgl, $replace_with = '-', $with_day = '')
{
    if (date_is_empty($tgl)) {
        return $replace_with;
    }
    $tanggal = substr($tgl, 8, 2);
    $bulan   = getBulan(substr($tgl, 5, 2));
    $tahun   = substr($tgl, 0, 4);
    if ($with_day != '') {
        $tanggal = $with_day . ', ' . date('j', strtotime($tgl));
    }

    return $tanggal . ' ' . $bulan . ' ' . $tahun;
}

function tgl_indo2($tgl, $replace_with = '-')
{
    if (date_is_empty($tgl)) {
        return $replace_with;
    }
    $tanggal = substr($tgl, 8, 2);
    $jam     = substr($tgl, 11, 8);
    $bulan   = getBulan(substr($tgl, 5, 2));
    $tahun   = substr($tgl, 0, 4);

    return $tanggal . ' ' . $bulan . ' ' . $tahun . ' ' . $jam;
}

function date_is_empty($tgl)
{
    return empty($tgl) || substr($tgl, 0, 10) == '0000-00-00';
}

function bulan()
{
    return [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
}

function getBulan(int $bln)
{
    $bulan = bulan();

    return $bulan[(int) $bln];
}
if (!function_exists('kades')) {
    /**
     * - Fungsi untuk mengambil data jabatan kades.
     *
     * @return array|object
     */
    function kades()
    {
        return RefJabatan::getKades();
    }
}

if (!function_exists('sekdes')) {
    /**
     * - Fungsi untuk mengambil data jabatan sekdes.
     *
     * @return array|object
     */
    function sekdes()
    {
        return RefJabatan::getSekdes();
    }
}

if (!function_exists('bulan_romawi')) {
    function bulan_romawi($bulan)
    {
        if ($bulan < 1 || $bulan > 12) {
            return false;
        }

        $bulan_romawi = [
            1  => 'I',
            2  => 'II',
            3  => 'III',
            4  => 'IV',
            5  => 'V',
            6  => 'VI',
            7  => 'VII',
            8  => 'VIII',
            9  => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        return $bulan_romawi[$bulan];
    }
}

if (!function_exists('gambar_desa')) {
    function gambar_desa($nama_file, $type = false)
    {

        if (Storage::disk('ftp')->exists("desa/logo/{$nama_file}")) {
            return  Storage::disk('ftp')->url("desa/logo/{$nama_file}");
        }
        // type FALSE = logo, TRUE = kantor
        $default = ($type) ? 'opensid_kantor.jpg' : 'opensid_logo.png';

        return  Storage::disk('ftp')->url("desa/logo/{$default}");
    }
}

if (!function_exists('case_replace')) {
    function case_replace($dari, $ke, $str)
    {
        $replacer = static function ($matches) use ($ke) {
            $matches = array_map(static function ($match) {
                return preg_replace('/[\\[\\]]/', '', $match);
            }, $matches);

            // Huruf kecil semua
            if (ctype_lower($matches[0][0])) {
                return strtolower($ke);
            }

            // Huruf besar semua
            if (ctype_upper($matches[0][0]) && ctype_upper($matches[0][1])) {
                return strtoupper($ke);
            }

            // Huruf besar diawal kata
            if (ctype_upper($matches[0][0]) && ctype_upper($matches[0][2])) {
                return ucwords(strtolower($ke));
            }

            // Normal
            if (ctype_upper($matches[0][0]) && ctype_upper($matches[0][strlen($matches) - 1])) {
                return $ke;
            }

            // Huruf besar diawal kalimat
            if (ctype_upper($matches[0][0])) {
                return ucfirst(strtolower($ke));
            }
        };

        $dari = str_replace('[', '\\[', $dari);

        $result = preg_replace_callback('/(' . $dari . ')/i', $replacer, $str);

        if (preg_match('/pendidikan/i', strtolower($dari))) {
            $result = kasus_lain('pendidikan', $result);
        } elseif (preg_match('/pekerjaan/i', strtolower($dari))) {
            $result = kasus_lain('pekerjaan', $result);
        }

        return $result;
    }
}

// Kalau angka romawi jangan ubah
function set_ucwords($data)
{
    $exp = explode(' ', $data);

    $data = '';

    for ($i = 0; $i < count($exp); $i++) {
        $data .= ' ' . (is_angka_romawi($exp[$i]) ? $exp[$i] : ucwords(strtolower($exp[$i])));
    }

    return trim($data);
}

function session_error() // hanya syarat supaya tidak error
{
    return null;
}


function qrcode_generate(array $qrcode = [], $base64 = false)
{
    $sizeqr = $qrcode['sizeqr'];
    $foreqr = $qrcode['foreqr'];

    $barcodeobj = new TCPDF2DBarcode($qrcode['isiqr'], 'QRCODE,H');

    if (!empty($foreqr)) {
        if ($foreqr[0] == '#') {
            $foreqr = substr($foreqr, 1);
        }
        $split = str_split($foreqr, 2);
        $r     = hexdec($split[0]);
        $g     = hexdec($split[1]);
        $b     = hexdec($split[2]);
    }

    //Hasilkan QRCode
    $imgData  = $barcodeobj->getBarcodePngData($sizeqr, $sizeqr, [$r, $g, $b]);
    $filename = sys_get_temp_dir() . '/qrcode_' . date('Y_m_d_H_i_s') . '_temp.png';
    file_put_contents($filename, $imgData);

    //Ubah backround transparan ke warna putih supaya terbaca qrcode scanner
    $src_qr    = imagecreatefrompng($filename);
    $sizeqrx   = imagesx($src_qr);
    $sizeqry   = imagesy($src_qr);
    $backcol   = imagecreatetruecolor($sizeqrx, $sizeqry);
    $newwidth  = $sizeqrx;
    $newheight = ($sizeqry / $sizeqrx) * $newwidth;
    $color     = imagecolorallocatealpha($backcol, 255, 255, 255, 1);
    imagefill($backcol, 0, 0, $color);
    imagecopyresampled($backcol, $src_qr, 0, 0, 0, 0, $newwidth, $newheight, $sizeqrx, $sizeqry);
    imagepng($backcol, $filename);
    imagedestroy($src_qr);
    imagedestroy($backcol);

    //Tambah Logo
    $logopath = $qrcode['logoqr']; // Logo yg tampil di tengah QRCode
    $QR       = imagecreatefrompng($filename);
    $logo     = imagecreatefromstring(file_get_contents($logopath));
    imagecolortransparent($logo, imagecolorallocatealpha($logo, 0, 0, 0, 127));
    imagealphablending($logo, false);
    imagesavealpha($logo, true);
    $QR_width       = imagesx($QR);
    $QR_height      = imagesy($QR);
    $logo_width     = imagesx($logo);
    $logo_height    = imagesy($logo);
    $logo_qr_width  = $QR_width / 4;
    $scale          = $logo_width / $logo_qr_width;
    $logo_qr_height = $logo_height / $scale;
    $from_width     = ($QR_width - $logo_qr_width) / 2;
    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
    imagepng($QR, $filename);
    imagedestroy($QR);

    if ($base64) {
        return 'data:image/png;base64,' . base64_encode(file_get_contents($filename));
    }

    return $filename;
}

function is_angka_romawi($roman)
{
    $roman_regex = '/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/';

    return preg_match($roman_regex, $roman) > 0;
}

function kasus_lain($kategori = null, $str = null)
{
    $pendidikan = [
        ' Tk',
        ' Sd',
        ' Sltp',
        ' Slta',
        ' Slb',
        ' Iii/s',
        ' Iii',
        ' Ii',
        ' Iv',
    ];

    $pekerjaan = [
        '(pns)',
        '(tni)',
        '(polri)',
        ' Ri ',
        'Dpr-ri',
        'Dpd',
        'Bpk',
        'Dprd',
    ];

    $daftar_ganti = ${$kategori};

    if (null === $kategori || count($daftar_ganti) <= 0) {
        return $str;
    }

    return str_ireplace($daftar_ganti, array_map('strtoupper', $daftar_ganti), $str);
}

if (!function_exists('getFormatIsian')) {
    /**
     * - Fungsi untuk mengembalikan format kode isian.
     *
     * @param mixed $kode_isian
     *
     * @return array|object
     */
    function getFormatIsian($kode_isian)
    {
        $strtolower = strtolower($kode_isian);
        $ucfirst    = ucfirst($strtolower);

        return [
            'normal'  => '[' . ucfirst(uclast($kode_isian)) . ']',
            'lower'   => '[' . $strtolower . ']',
            'ucfirst' => '[' . $ucfirst . ']',
            'ucwords' => '[' . substr_replace($ucfirst, strtoupper(substr($ucfirst, 2, 1)), 2, 1) . ']',
            'upper'   => '[' . substr_replace($ucfirst, strtoupper(substr($ucfirst, 1, 1)), 1, 1) . ']',
        ];
    }
}

function uclast($str)
{
    return strrev(ucfirst(strrev(strtolower($str))));
}

function cek_koneksi_internet($sCheckHost = 'www.google.com')
{
    if (!setting('notifikasi_koneksi')) {
        return true;
    }

    $connected = @fsockopen($sCheckHost, 80, $errno, $errstr, 5);

    if ($connected) {
        fclose($connected);

        return true;
    }

    return false;
}
// function to convert 9319032003 to xx.xx.xx.xxxx
if (!function_exists('formatKodeDesa')) {
    function formatKodeDesa($nomor)
    {
        $nomor = preg_replace('/[^0-9]/', '', $nomor);
        if (strlen($nomor) < 4) {
            return $nomor;
        }

        return substr($nomor, 0, 2) . '.' . substr($nomor, 2, 2) . '.' . substr($nomor, 4, 2) . '.' . substr($nomor, 6);
    }
}
