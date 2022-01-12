<?php

namespace App\Services\Surat;

use App\Services\Surat\Layanan\SuratBioPenduduk;
use App\Services\Surat\Layanan\SuratIzinKeramaian;
use App\Services\Surat\Layanan\SuratJalan;
use App\Services\Surat\Layanan\SuratKetBedaNama;
use App\Services\Surat\Layanan\SuratKetCatatanKriminal;
use App\Services\Surat\Layanan\SuratKetJualBeli;
use App\Services\Surat\Layanan\SuratKetKehilangan;
use App\Services\Surat\Layanan\SuratKetKtpDalamProses;
use App\Services\Surat\Layanan\SuratKetKurangMampu;
use App\Services\Surat\Layanan\SuratKetPenduduk;
use App\Services\Surat\Layanan\SuratKetPengantar;
use App\Services\Surat\Layanan\SuratKetPindahPenduduk;
use App\Services\Surat\Layanan\SuratKetUsaha;
use Illuminate\Support\Manager;

class SuratManager extends Manager
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return null;
    }

    public function createSuratKetPengantarDriver()
    {
        return new SuratKetPengantar();
    }

    public function createSuratKetPendudukDriver()
    {
        return new SuratKetPenduduk();
    }

    public function createSuratBioPendudukDriver()
    {
        return new SuratBioPenduduk();
    }

    public function createSuratKetPindahPendudukDriver()
    {
        return new SuratKetPindahPenduduk();
    }

    public function createSuratKetJualBeliDriver()
    {
        return new SuratKetJualBeli();
    }

    public function createSuratKetCatatanKriminalDriver()
    {
        return new SuratKetCatatanKriminal();
    }

    public function createSuratKetKtpDalamProsesDriver()
    {
        return new SuratKetKtpDalamProses();
    }

    public function createSuratKetBedaNamaDriver()
    {
        return new SuratKetBedaNama();
    }

    public function createSuratJalanDriver()
    {
        return new SuratJalan();
    }

    public function createSuratKetKurangMampuDriver()
    {
        return new SuratKetKurangMampu();
    }

    public function createSuratKetKehilanganDriver()
    {
        return new SuratKetKehilangan();
    }

    public function createSuratKetUsahaDriver()
    {
        return new SuratKetUsaha();
    }

    public function createSuratIzinKeramaianDriver()
    {
        return new SuratIzinKeramaian();
    }
}
