<?php

namespace App\Services\Surat\Layanan;

class SuratKetBedaNama extends SuratAbstract
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->validate(
            $this->request,
            $this->defaultRules([
                'kartu' => 'required|string',
                'identitas' => 'required|string',
                'nama' => 'required|string',
                'tempatlahir' => 'required|string',
                'tanggallahir' => 'required',
            ])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function form()
    {
        return $this->defaultForm([
            ['type' => 'text','required' => true,'label' => 'Identitas dalam (nama kartu)','name' => 'kartu','subtype' => 'text',],
            ['type' => 'text','required' => true,'label' => 'Nomor Identitas','name' => 'identitas','subtype' => 'text',],
            ['type' => 'text','required' => true,'label' => 'Nama','name' => 'nama','subtype' => 'text',],
            ['type' => 'text','required' => true,'label' => 'Tempat Lahir','name' => 'tempatlahir','subtype' => 'text',],
            ['type' => 'date','required' => true,'label' => 'Tanggal Lahir','name' => 'tanggallahir',],
            ['type' => 'text','required' => true,'label' => 'Jenis Kelamin','name' => 'sex','subtype' => 'text',],
            ['type' => 'textarea','required' => true,'label' => 'Alamat','name' => 'alamat','subtype' => 'textarea',],
            ['type' => 'text','required' => true,'label' => 'Agama','name' => 'agama','subtype' => 'text',],
            ['type' => 'text','required' => true,'label' => 'Pekerjaan','name' => 'pekerjaan','subtype' => 'text',],
            ['type' => 'textarea','required' => true,'label' => 'Keterangan','name' => 'keterangan','subtype' => 'textarea',],
            ['type' => 'text','required' => true,'label' => 'Perbedaan','name' => 'perbedaan','subtype' => 'text',],
        ]);
    }
}
