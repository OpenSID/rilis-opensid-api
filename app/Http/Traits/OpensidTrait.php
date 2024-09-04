<?php

namespace App\Http\Traits;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

trait OpensidTrait
{
    private function loginOpensid($password)
    {
        $urlOpensid = env('FTP_URL');
        $client = new Client(['cookies' => true, 'base_uri' => $urlOpensid ]);
        $client->request('GET', 'siteman');
        $cookie = $client->getConfig('cookies');
        $csrf = $cookie->getCookieByName('sidcsrf');

        $response = $client->request('POST', 'index.php/siteman/auth', [
            'timeout' => 30,
            'form_params' => [
                'sidcsrf' => $csrf->getValue(),
                'username' => auth('admin')->user()->username,
                'password' => $password,
            ],
            'allow_redirects' => [
                'max'             => 2,        // allow at most 10 redirects.
                'strict'          => true,      // use "strict" RFC compliant redirects.
                'referer'         => true,      // add a Referer header
                'track_redirects' => true
            ]
        ]);

        $url_redirect = $response->getHeaderLine('X-Guzzle-Redirect-History');
        if (!Str::contains($url_redirect, 'siteman')) {
            return $client;
        } else {
            throw new Exception('Gagal Login ke Server OpenSid');
        }
    }
}
