<?php

namespace App\Services\Surat\Layanan;

class SuratKetJualBeli extends SuratAbstract
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->validate(
            $this->request,
            $this->defaultRules([
                'jenis' => 'required|string',
                'barang' => 'required|string',
                'identitas' => 'required|string',
                'nama' => 'required|string',
                'tempatlahir' => 'required|string',
                'tanggallahir' => 'required',
                'sex' => 'required|string',
                'pekerjaan' => 'required|string',
                'keterangan' => 'required|string',
                'ketua_adat' => 'required|string',
            ])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function form()
    {
        return $this->defaultForm([
            ['type' => 'text', 'required' => true, 'label' => 'Jenis Barang', 'name' => 'jenis', 'subtype' => 'text',],
            ['type' => 'text', 'required' => true, 'label' => 'Rincian Barang', 'name' => 'barang', 'subtype' => 'text',],
            ['type' => 'text', 'required' => true, 'label' => 'Nomor Indentitas Pembeli', 'name' => 'identitas', 'subtype' => 'text',],
            ['type' => 'text', 'required' => true, 'label' => 'Nama Pembeli', 'name' => 'nama', 'subtype' => 'text',],
            ['type' => 'text', 'required' => true, 'label' => 'Tempat Lahir Pembeli', 'name' => 'tempatlahir', 'subtype' => 'text',],
            ['type' => 'date', 'required' => true, 'label' => 'Tanggal Lahir Pembeli', 'name' => 'tanggallahir',],
            ['type' => 'text', 'required' => true, 'label' => 'Jenis Kelamin Pembeli', 'name' => 'sex', 'subtype' => 'text',],
            ['type' => 'text', 'required' => true, 'label' => 'Pekerjaan Pembeli', 'name' => 'pekerjaan', 'subtype' => 'text',],
            ['type' => 'textarea', 'required' => true, 'label' => 'Keterangan', 'name' => 'keterangan', 'subtype' => 'textarea',],
            ['type' => 'text', 'required' => false, 'label' => 'Nama Ketua Adat', 'name' => 'ketua_adat', 'subtype' => 'text',],
        ]);
    }
}
