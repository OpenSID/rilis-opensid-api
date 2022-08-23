<?php

namespace App\Services\Surat\Layanan;

use App\Models\FormatSurat;
use App\Services\Surat\Contracts\SuratInterface;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class SuratAbstract implements SuratInterface
{
    use ValidatesRequests;

    /** @var \Illuminate\Http\Request */
    protected $request;

    /** @var FormatSurat */
    protected $surat;

    public function __construct()
    {
        $this->request = request();
        $this->surat = new FormatSurat();
    }

    public function defaultRules(array $rules = [])
    {
        return array_merge([
            'keterangan' => 'required|string',
            'isian_form' => 'required|json',
            'no_hp_aktif' => ['required', 'regex:/^(^\+62|62|^08)(\d{3,4}-?){2}\d{3,4}$/i'],
            'syarat' => 'required|json',
        ], $rules);
    }

    public function defaultForm(array $data = [])
    {
        return array_merge([
            ['type' => 'textarea', 'required' => false, 'label' => 'Keterangan', 'name' => 'keterangan', 'subtype' => 'textarea'],
            ['type' => 'number', 'required' => false, 'label' => 'No hp aktif', 'name' => 'no_hp_aktif'],
            [
                'type' => 'select',
                'required' => true,
                'label' => 'Syarat Surat',
                'name' => 'syarat',
                'multiple' => false,
                'values' => $this->surat->get()->map(function ($attribute) {
                    return $attribute->list_syarat_surat;
                })->first(),
            ],
        ], $data);
    }
}
