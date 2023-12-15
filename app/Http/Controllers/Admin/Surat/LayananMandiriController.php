<?php

namespace App\Http\Controllers\Admin\Surat;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Repository\PermohonanSuratEntity;
use App\Http\Transformers\PermohonanMandiriTransformer;

use App\Libraries\OpenSID;
use App\Models\PermohonanSurat;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class LayananMandiriController extends BaseController
{
    public function index()
    {
        $mandiri = new PermohonanSuratEntity();
        return $this->fractal($mandiri->permohonanMandiri(), new PermohonanMandiriTransformer(), 'surat');
    }

    public function show(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|int',
        ]);
        $id = (int) $request->id;
        $permohonan = PermohonanSurat::where('id', $id)->first();
        $permohonan->ttd = kades()->nama . ' ' .config('desa.nama_desa');

        return $this->sendResponse($permohonan, 'success');
    }

    public function setuju(Request $request)
    {

        $data = $this->validate($request, [
            'id' => 'required|integer',
            'password' =>  'required'
        ]);
        $id = $data['id'];

        try {

            $clientOpenSID = OpenSId::loginOpensid($data['password']);

            $cookie = $clientOpenSID->getConfig('cookies');
            $csrf = $cookie->getCookieByName('sidcsrf');
            $permohonan = PermohonanSurat::where('id', $id)->first();
            $isian_form = $permohonan->isian_form;
            $add = [
                "berlaku_dari" => $request->berlaku_dari,
                "berlaku_sampai" => $request->berlaku_sampai,
                "nomor" => $request->nomor,
                "pilih_atas_nama" => $request->pilih_atas_nama, // 'an / ''
                "sidcsrf" => $csrf->getValue()
            ];

            $formdata = [...$add, ...$isian_form];



            if($clientOpenSID) {
                $pratinjau = $clientOpenSID->post(
                    'index.php/surat/pratinjau/'.$permohonan->formatSurat['url_surat'].'/'. $id,
                    ["form_params" => $formdata]
                );
            }



            $html = $pratinjau->getBody()->getContents();
            $crawler = new Crawler($html);
            $form_pratinjau = $crawler->filter('#validasi')->form();
            $kirim_cetak = $form_pratinjau->getPhpValues();


            if($pratinjau) {
                $cetak = $clientOpenSID->post(
                    'index.php/surat/pdf',
                    ["form_params" => $kirim_cetak]
                );
            }

            $id_arsip = $cetak->getHeaderLine('id_arsip');

            if($cetak) {
                $clientOpenSID = OpenSId::loginOpensid($request->get('password'));
                $cookie = $clientOpenSID->getConfig('cookies');

                $csrf = $cookie->getCookieByName('sidcsrf');
                $clientOpenSID->post(
                    'index.php/keluar/verifikasi',
                    ["form_params" => [
                    'sidcsrf' => $csrf->getValue(),
                    'id' => $id_arsip
                ]]
                );
            }

            // langsung setujui surat
            return $this->sendResponse([], 'Permohonan surat berhasil disetujui');
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }


}
