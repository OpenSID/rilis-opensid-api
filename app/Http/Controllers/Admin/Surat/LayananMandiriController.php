<?php

namespace App\Http\Controllers\Admin\Surat;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Repository\PermohonanSuratEntity;
use App\Http\Transformers\PermohonanMandiriTransformer;
use App\Libraries\OpenSID;
use App\Models\Dokumen;
use App\Models\Komentar;
use App\Models\Penduduk;
use App\Models\PermohonanSurat;
use Exception;
use Illuminate\Http\JsonResponse;
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


            // kirim ke notifikasi
            $periksa = $clientOpenSID->get('index.php/permohonan_surat_admin/periksa/'. $id);
            $cookie = $clientOpenSID->getConfig('cookies');
            $csrf = $cookie->getCookieByName('sidcsrf');
            // kirim ke notifikasi
            $clientOpenSID->get('index.php/permohonan_surat_admin/periksa/'. $id);
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



            if ($clientOpenSID) {

                $pratinjau = $clientOpenSID->post(
                    'index.php/surat/pratinjau/'.$permohonan->formatSurat['url_surat'].'/'. $id,
                    ["form_params" => $formdata]
                );
            }



            $html = $pratinjau->getBody()->getContents();
            $crawler = new Crawler($html);
            $form_pratinjau = $crawler->filter('#validasi')->form();
            $kirim_cetak = $form_pratinjau->getPhpValues();


            if ($pratinjau) {
                $cetak = $clientOpenSID->post(
                    'index.php/surat/pdf',
                    ["form_params" => $kirim_cetak]
                );
            }

            $id_arsip = $cetak->getHeaderLine('id_arsip');

            if ($cetak) {
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

    public function tolak(Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'id' => 'required|integer',
            'pesan' => 'string',
        ]);

        $komentar = $data['pesan'] ?? '';

        try {
            $permohonan = PermohonanSurat::where('id', $data['id'])->first();
            if ($permohonan->status == 2) {
                throw new Exception("Surat sedang dalam proses ", 404);
            }

            if ($permohonan->status > 2) {
                throw new Exception("Surat Sudah Siap Diambil ", 404);
            }

            $pemohon = Penduduk::where('id', $permohonan['id_pemohon'])->first();
            $pesan = [
                'subjek'     => 'Permohonan Surat ' . $permohonan->formatSurat->nama . ' Perlu Dilengkapi',
                'komentar'   => $komentar,
                'owner'      => $pemohon['nama'], // TODO : Gunakan id_pend
                'email'      => $pemohon['nik'], // TODO : Gunakan id_pend
                'permohonan' => $permohonan['id_pemohon'], // Menyimpan id_permohonan untuk link
                'tipe'       => 2,
                'status'     => 2,
                'tgl_upload' =>  date('Y-m-d H:i:s'),
            ];
            Komentar::create($pesan);
            PermohonanSurat::where('id', $data['id'])->update(['status' => 0]);
            return $this->sendResponse([], 'Permohonan surat berhasil dikembalikan');
        } catch (Exception $e) {
            \Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }


    public function downloadDokumen(Request $request)
    {
        $data =  $this->validate($request, [
             'id_dokumen' => 'required|int',
         ]);

        $dokumen = Dokumen::where('id', $data['id_dokumen'])->first();

        return $dokumen->get_dokumen;
    }

}
