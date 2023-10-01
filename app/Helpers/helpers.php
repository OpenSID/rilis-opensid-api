<?php

use App\Models\Config;
use App\Models\RefJabatan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (!function_exists('opensid_api_version')) {
    /**
     * Get version opensid-api.
     *
     * @return string
     */
    function opensid_api_version()
    {
        return "v2310.0.0";
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
