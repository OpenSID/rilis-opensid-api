<?php

namespace App\Http\Controllers\Admin\Surat;

use App\Http\Controllers\Admin\BaseController ;
use App\Http\Requests\Admin\TteRequest;
use App\Libraries\OpenSID;
use Exception;

class TteController extends BaseController
{
    public function update($id, TteRequest $request)
    {
        try {
            $clientOpenSID = OpenSId::loginOpensid($request->get('password'));
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
}
