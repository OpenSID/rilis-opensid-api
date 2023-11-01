<?php

namespace App\Http\Controllers\Admin\Surat;

use App\Http\Controllers\Admin\BaseController ;
use App\Http\Requests\Admin\TteRequest;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class TteController extends BaseController
{
    public function update($id, TteRequest $request)
    {
        try {
            $clientOpenSID = $this->loginOpensid($request->get('password'));
            $cookie = $clientOpenSID->getConfig('cookies');
            $csrf = $cookie->getCookieByName('sidcsrf');
            if($clientOpenSID) {
                $response = $clientOpenSID->post(
                    'index.php/api/tte/sign_visible',
                    [
                        'form_params' => ['id' => $id, 'passphrase' => $request->get('passphrase'), 'sidcsrf' => $csrf->getValue()]
                    ]
                );
            }

            $data_response = json_decode($response->getBody()->getContents());
            if ($data_response->status == false) {
                $throw = json_decode($data_response->pesan);
                throw new Exception($throw->error, $throw->status_code);
            }

            return $this->sendResponse($response->getBody(), 'Penandatanganan TTE berhasil');
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->sendError($e->getMessage(), 'Penandatanganan TTE gagal');
        }
    }

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
