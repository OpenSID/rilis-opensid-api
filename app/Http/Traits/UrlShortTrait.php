<?php

namespace App\Http\Traits;

use App\Models\Urls;
use Illuminate\Support\Facades\Storage;

trait UrlShortTrait
{
    public function url_pendek($log_surat = [])
    {

        $url = Storage::disk('ftp')->url("c1/{$log_surat['id']}");
        $data  = $this->add_url($url);

        return [
            'isiqr'   => Storage::disk('ftp')->url('v/' . $data->alias),
            'urls_id' =>  $data->id,
        ];
    }



    public function add_url($url)
    {
        $data = [
            'config_id' => identitas('id'),
            'url'       => (string) $url,
            'alias'     => (string) $this->random_code(6),
            'created'   => date('Y-m-d H:i:s'),
        ];
        $data = Urls::create($data);

        return $data;
    }

    public function random_code($length)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
    }
}
