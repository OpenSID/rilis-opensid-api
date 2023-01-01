<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\ArsipSuratEntity;
use App\Http\Repository\FormatSuratEntity;
use App\Http\Repository\PermohonanSuratEntity;
use App\Http\Repository\SyaratSuratEntity;
use App\Http\Transformers\ArsipSuratTransformer;
use App\Http\Transformers\JenisFormatSuratTransformer;
use App\Http\Transformers\PermohonanSuratTransformer;
use App\Http\Transformers\SyaratSuratTransformer;
use App\Models\FormatSurat;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class SuratController extends Controller
{
    /** @var PermohonanSuratEntity */
    protected $permohonan;

    /** @var ArsipSuratEntity */
    protected $arsip;

    /** @var FormatSuratEntity */
    protected $format;

    /** @var SyaratSuratEntity */
    protected $syarat;

    public function __construct(
        PermohonanSuratEntity $permohonan,
        ArsipSuratEntity $arsip,
        FormatSuratEntity $format,
        SyaratSuratEntity $syarat
    ) {
        $this->permohonan = $permohonan;
        $this->arsip = $arsip;
        $this->format = $format;
        $this->syarat = $syarat;
    }

    public function arsip()
    {
        return $this->fractal($this->arsip->get(), new ArsipSuratTransformer(), 'arsip');
    }

    public function permohonan()
    {
        return $this->fractal($this->permohonan->get(), new PermohonanSuratTransformer(), 'permohonan');
    }

    public function jenis()
    {
        return $this->fractal($this->format->jenis(), new JenisFormatSuratTransformer(), 'jenis-surat');
    }

    public function syaratSurat()
    {
        return $this->fractal($this->syarat->get(), new SyaratSuratTransformer(), 'syarat');
    }

    public function store(Request $request, string $slug)
    {
        // cek tinymce
        $format_surat = FormatSurat::where('id', $request->id_surat)->first();
        if (in_array($format_surat->jenis, FormatSurat::TINYMCE)) {
            $rules =[];
            foreach ($format_surat->form_surat as $value) {
                $rules[underscore($value['name'])] = $value['required'] ? 'required' : 'sometimes';
            }
            $request->validate($rules);
        } else {
            app('surat')->driver($slug)->rules();
        }

        try {
            $permohonan = $this->permohonan->insert($request->merge(['slug' => $slug]));
        } catch (Throwable $e) {
            return $this->fail($e->getMessage(), 400);
        }

        return $this->fractal($permohonan, new PermohonanSuratTransformer(), 'permohonan');
    }

    public function update(int $id)
    {
        try {
            $permohonan = $this->permohonan->find($id);
            $permohonan->update(['status' => 5]);
        } catch (Exception $e) {
            return $this->fail($e->getMessage(), 400);
        }

        return $this->response('Permohonan surat berhasil dibatalkan.', 200);
    }
}
