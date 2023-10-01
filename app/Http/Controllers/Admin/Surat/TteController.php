<?php

namespace App\Http\Controllers\Admin\Surat;

use App\Http\Controllers\Admin\BaseController as BaseController;
use App\Http\Requests\Admin\TteRequest;
use GuzzleHttp\Client;

class TteController extends BaseController
{
    public function update($id, TteRequest $request)
    {
        try {
            $clientOpenSID = $this->loginOpensid($request->get('password'));
            $cookie = $clientOpenSID->getConfig('cookies');
            $csrf = $cookie->getCookieByName('sidcsrf');
            if($clientOpenSID) {
                $response = $clientOpenSID->post('api/tte/sign_visible', ['id' => $id, 'passphrase' => $request->get('passphrase'), 'sidcsrf' => $csrf->getValue()]);
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
        $client->request('GET', 'siteman/auth');
        $cookie = $client->getConfig('cookies');
        $csrf = $cookie->getCookieByName('sidcsrf');

        $response = $client->request('POST', 'siteman/auth', [
            'timeout' => 30,
            'form_params' => [
                'sidcsrf' => $csrf->getValue(),
                'username' => auth('admin')->user()->username,
                'password' => $password,
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            return $client;
        }
    }
}
