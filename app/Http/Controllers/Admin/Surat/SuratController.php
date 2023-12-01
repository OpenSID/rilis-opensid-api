<?php

namespace App\Http\Controllers\Admin\Surat;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Repository\ArsipSuratEntity;
use App\Http\Repository\PermohonanSuratEntity;
use App\Http\Repository\SuratEntity;
use App\Http\Traits\QrcodeTrait;
use App\Http\Transformers\PermohonanMandiriTransformer;
use App\Http\Transformers\SuratAdminTransformer;
use App\Http\Transformers\SuratPermohonanTransformer;
use App\Libraries\OpenSID;
use App\Libraries\TinyMCE;
use App\Models\Dokumen;
use App\Models\FormatSurat;
use App\Models\LogSurat;
use App\Models\Pamong;

use App\Models\PermohonanSurat;
use App\Models\RefJabatan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratController extends BaseController
{
    use QrcodeTrait;

    public function jumlah()
    {
        $user = auth()->user()->load('pamong');

        $permohonan = LogSurat::whereNull('deleted_at')
            ->when($user->pamong && $user->pamong->jabatan_id == kades()->id, static function ($q) {
                return $q->when(config('aplikasi.tte') == 1, static function ($tte) {
                    return $tte->where(static function ($r) {
                        // kondisi verifikasi operator 1
                        return $r->where('verifikasi_kades', '=', 0)->orWhere('tte', '=', 0);
                    });
                })
                    ->when(config('aplikasi.tte') == 0, static function ($tte) {
                        return $tte->where('verifikasi_kades', '=', '0');
                    });
            })

            ->when($user->pamong && $user->pamong->jabatan_id == sekdes()->id, static function ($q) {
                return $q->where('verifikasi_sekdes', '=', '0');
            })->when($user->pamong == null || !in_array($user->pamong->jabatan_id, RefJabatan::getKadesSekdes()), static function ($q) {
                return $q->where('verifikasi_operator', '=', '0');
            })->count();

        $mandiri = 0;
        $tolak = 0;
        if ($user->pamong == null || !in_array($user->pamong->jabatan_id, RefJabatan::getKadesSekdes())) {
            $mandiri = PermohonanSurat::where('status', 1)->count();
        }
        $tolak = LogSurat::whereNull('deleted_at')->where('verifikasi_operator', '=', '-1')->count();

        $arsip = LogSurat::whereNull('deleted_at')
            ->when($user->pamong && $user->pamong->jabatan_id == kades()->id, static function ($q) {
                return $q->where(function ($query) {
                    return $query->where('verifikasi_kades', 1)->where('tte', 1);
                })->orWhere(function ($query) {
                    return $query->where('verifikasi_kades', 1)->whereNull('tte');
                })->orWhere(function ($query) {
                    return $query->where('verifikasi_sekdes', 1)->whereNull('verifikasi_kades');
                })->orWhere(function ($query) {
                    return $query->where('verifikasi_operator', 1)->whereNull('verifikasi_kades')->whereNull('verifikasi_sekdes');
                });
            })->when($user->pamong && $user->pamong->jabatan_id == sekdes()->id, static function ($q) {
                return $q->where('verifikasi_sekdes', '=', '1')
                    ->orWhere(function ($query) {
                        return $query->where('verifikasi_operator', 1)->whereNull('verifikasi_sekdes');
                    });
            })
            ->when($user->pamong == null || !in_array($user->pamong->jabatan_id, RefJabatan::getKadesSekdes()), static function ($q) {
                return $q->where('verifikasi_operator', '=', '1')->orWhereNull('verifikasi_operator');
            })

            ->selectRaw("CASE WHEN log_verifikasi LIKE 'Verifikasi%' THEN 'menunggu verifikasi' WHEN log_verifikasi = 'TTE' THEN 'menunggu TTD' ELSE 'siap cetak' END as status_verifikasi")
            ->selectRaw("Count(*) as jumlah")
            ->groupBy('status_verifikasi')
            ->get()->pluck('jumlah', 'status_verifikasi');



        $data = [
            'permohonan' => $permohonan + $mandiri,
            'arsip' => [
                'siap cetak' => $arsip['siap cetak'] ?? 0,
                'menunggu verifikasi' =>  $arsip['menunggu verifikasi'] ?? 0,
                'menunggu TTD' =>  $arsip['menunggu TTD'] ?? 0,
            ],
            'tolak' => $tolak
        ];

        return $this->sendResponse($data, 'success');
    }

    public function arsip()
    {
        $arsip = new ArsipSuratEntity();
        return $this->fractal($arsip->getAdmin(), new SuratAdminTransformer(), 'arsip');
    }

    public function arsiptolak()
    {

        $arsip = new ArsipSuratEntity();
        return $this->fractal($arsip->GetAdminTolak(), new SuratAdminTransformer(), 'arsip');
    }

    public function show(Request $request)
    {
        $id = (int) $request->id;
        $user = auth()->user()->load('pamong');
        // deklarasi jabatan
        $jabatan = $user->jabatan;

        $logSurat = LogSurat::with(['formatSurat'])->find($id);
        if ($logSurat == null) {
            return $this->sendError('Surat Tidak Ditemukan');
        }

        $dokumen = Dokumen::hidup()->where('id_pend', $logSurat->id_pend)->get();
        switch ($user->jabatan) {
            case 'kades':
                if (config('aplikasi.tte')) {
                    $next = 'TTE';
                } else {
                    $next = null;
                }
                break;

            case 'kades':
                $next = config('aplikasi.verifikasi_kades') ? config('aplikasi.sebutan_kepala_desa') : null;
                break;

            default:
                if (config('aplikasi.verifikasi_sekdes')) {
                    $next = config('aplikasi.sebutan_sekretaris_desa');
                } elseif (config('aplikasi.verifikasi_kades')) {
                    $next = config('aplikasi.sebutan_kepala_desa');
                } else {
                    $next = null;
                }
                break;
        }

        $surat = [
            'id' => $logSurat->id,
            'nama_pamong' => $logSurat->nama_pamong,
            'nama_jabatan' => $logSurat->nama_jabatan,
            'tanggal' => $logSurat->tanggal,
            'lampiran' => $logSurat->lampiran,
            'nik_non_warga' => $logSurat->nik_non_warga,
            'nama_non_warga' => $logSurat->nama_non_warga,
            'keterangan' => $logSurat->keterangan,
            'status' => $logSurat->status,
            'log_verifikasi' => $logSurat->log_verifikasi,
            'lampitteran' => $logSurat->tte,
            'verifikasi_operator' => $logSurat->verifikasi_operator,
            'verifikasi_kades' => $logSurat->verifikasi_kades,
            'verifikasi_sekdes' => $logSurat->verifikasi_sekdes,
            'tte' => $logSurat->tte,
            'kecamatan' => $logSurat->kecamatan,
            'jenis' => $logSurat->formatSurat->jenis,
            'penduduk' => $logSurat->penduduk,
            'pamong' => $logSurat->pamong,
            'format_surat' => $logSurat->formatSurat,
            'nomor_surat' => $logSurat->format_penomoran_surat,
        ];

        $data = [
            'surat' =>  $surat,
            'dokumen' => $dokumen,
            'next' => $next
            // 'operator' => $operator,
        ];
        return $this->sendResponse($data, 'success');
    }

    public function tolak(Request $request)
    {
        $data = $this->validate($request, [
            'password' => 'required|String',
            'id' => 'required|integer',
            'alasan' => 'sometimes|String'
        ]);

        try {
            $clientOpenSID = OpenSId::loginOpensid($data['password']);
            $cookie = $clientOpenSID->getConfig('cookies');
            $csrf = $cookie->getCookieByName('sidcsrf');

            if($clientOpenSID) {
                $response = $clientOpenSID->post(
                    'index.php/keluar/tolak',
                    [
                        'form_params' => ['id' => $data['id'], 'alasan' => $data['alasan'], 'sidcsrf' => $csrf->getValue()]
                    ]
                );
            } else {
                throw new Exception('Gagal login Ke OpenSID', 1);
            }

            if ($response->getStatusCode() == 200) {
                return $this->sendResponse([], 'success');
            }

        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), [], 200);
        }
    }

    public function kembalikan(Request $request)
    {
        try {
            $id        = $request->id;
            $alasan    = $request->alasan;
            $surat   = LogSurat::find($id);
            $mandiri = PermohonanSurat::where('id_surat', $surat->id_format_surat)->where('isian_form->nomor', $surat->no_surat)->first();
            if ($mandiri == null) {
                return $this->sendError('Surat tidak ditemukan!', [], 200);
            }
            $mandiri->update(['status' => 0, 'alasan' => $alasan]);
            $surat->delete();

            return $this->sendResponse([], 'success');
        } catch (Exception $e) {
            \Log::error($e);
            return $this->sendError($e->getMessage(), [], 200);
        }
    }

    public function Setujui(Request $request)
    {
        $data = $this->validate($request, [
            'password' => 'required|String',
            'id' => 'required|integer'
        ]);
        try {
            $clientOpenSID = OpenSId::loginOpensid($data['password']);
            $cookie = $clientOpenSID->getConfig('cookies');
            $csrf = $cookie->getCookieByName('sidcsrf');

            if($clientOpenSID) {
                $response = $clientOpenSID->post(
                    'index.php/keluar/verifikasi',
                    [
                        'form_params' => ['id' => $data['id'], 'sidcsrf' => $csrf->getValue()]
                    ]
                );
            } else {
                throw new Exception('Gagal login Ke OpenSID', 1);
            }

            if ($response->getStatusCode() == 200) {
                return $this->sendResponse([], 'success');
            }
        } catch (Exception $e) {
            \Log::error($e);
            return $this->sendError($e->getMessage(), 'Verifikasi Surat Gagal');
        }


        return $this->sendResponse([], 'success');
    }
    public function permohonan()
    {
        $surat = new SuratEntity();
        return $this->fractal($surat->permohonan(), new SuratPermohonanTransformer(), 'surat');
    }

    public function mandiri()
    {
        $mandiri = new PermohonanSuratEntity();
        return $this->fractal($mandiri->permohonanMandiri(), new PermohonanMandiriTransformer(), 'surat');
    }

    public function download(int $id, Request $request)
    {
        $surat = LogSurat::find($id);
        if ($surat == null) {
            return $this->sendError('File tidak ditemukan');
        }

        if ($surat->download_surat == null) {
            try {
                $data = $this->validate($request, [
                    'password' => 'required|String',
                ]);
                $clientOpenSID = OpenSId::loginOpensid($data['password']);
                $cookie = $clientOpenSID->getConfig('cookies');


                if($clientOpenSID) {
                    // triger pembuatan pdf baru dengan mengklik tombol unduh di layanan arsip opensid
                    $clientOpenSID->get('index.php/keluar/unduh/tinymce/'.$id);
                    $surat = LogSurat::find($id);
                } else {
                    throw new Exception('Gagal login Ke OpenSID', 1);
                }

            } catch (Exception $e) {
                return $this->sendError($e->getMessage(), [], 400);
            }

        }

        if (in_array($surat->formatSurat->jenis, FormatSurat::TINYMCE)) {
            return $surat->download_surat;
        }

        return $this->sendError('Download tidak suport untuk jenis surat RTF', [], 400);
    }

    public function buatLampiran($id = null, $data = [], $view_surat = null)
    {
        // Catatan : untuk sekarang hanya bisa menggunakan 1 lampiran saja untuk surat TinyMCE
        if (empty($data['surat']['lampiran'])) {
            return $view_surat;
        }
        $tinymce = new TinyMCE();

        $surat         = $data['surat'];
        $input         = $data['input'];
        $config        = identitas('desa');
        $individu      = $this->get_data_surat($id);
        $penandatangan = $this->atas_nama($data);
        $lampiran      = explode(',', strtolower($surat['lampiran']));
        $format_surat  = $tinymce->substitusiNomorSurat($input['nomor'], config('aplikasi.format_nomor_surat'));
        $format_surat  = str_replace('[kode_surat]', $surat['kode_surat'], $format_surat);
        $format_surat  = str_replace('[kode_desa]', identitas()->kode_desa, $format_surat);
        $format_surat  = str_replace('[bulan_romawi]', bulan_romawi((int) (date('m'))), $format_surat);
        $format_surat  = str_replace('[tahun]', date('Y'), $format_surat);

        if (isset($input['gunakan_format'])) {
            unset($lampiran);

            switch (strtolower($input['gunakan_format'])) {
                case 'f-1.08 (pindah pergi)':
                    $lampiran[] = 'f-1.08';
                    break;

                case 'f-1.23, f-1.25, f-1.29, f-1.34 (sesuai tujuan)':
                    $lampiran[] = 'f-1.25';
                    break;

                case 'f-1.03 (pindah datang)':
                    $lampiran[] = 'f-1.03';
                    break;

                case 'f-1.27, f-1.31, f-1.39 (sesuai tujuan)':
                    $lampiran[] = 'f-1.27';
                    break;

                default:
                    $lampiran[] = null;
                    break;
            }
        }

        for ($i = 0; $i < count($lampiran); $i++) {
            // Cek lampiran desa
            $view_lampiran[$i] = config('constants.template-lampiran-surat') . $lampiran[$i] . '/view.php';

            if (!file_exists($view_lampiran[$i])) {
                $view_lampiran[$i] = config('constants.default-template-lampiran-surat') . $lampiran[$i] . '/view.php';
            }

            $data_lampiran[$i] = config('constants.template-lampiran-surat') . '/data.php';
            if (!file_exists($data_lampiran[$i])) {
                $data_lampiran[$i] = config('constants.default-template-lampiran-surat') . $lampiran[$i] . '/data.php';
            }

            // Data lampiran
            include $data_lampiran[$i];
        }

        ob_start();

        for ($j = 0; $j < count($lampiran); $j++) {
            // View Lampiran
            include $view_lampiran[$j];
        }

        $content = ob_get_clean();

        return $view_surat . $content;
    }

    public function atas_nama($data, $buffer = null)
    {
        //Data penandatangan
        $input     = $data['input'];
        $nama_desa = identitas()->nama_desa;

        //Data penandatangan
        $kades = Pamong::kepalaDesa()->first();

        $ttd         = $input['pilih_atas_nama'];
        $atas_nama   = $kades->pamong_jabatan . ' ' . $nama_desa;
        $jabatan     = $kades->pamong_jabatan;
        $nama_pamong = $kades->pamong_nama;
        $nip_pamong  = $kades->pamong_nip;
        $niap_pamong = $kades->pamong_niap;

        $sekdes = Pamong::ttd('a.n')->first();
        if (preg_match('/a.n/i', $ttd)) {
            $atas_nama   = 'a.n ' . $atas_nama . ' \par ' . $sekdes->pamong_jabatan;
            $jabatan     = $sekdes->pamong_jabatan;
            $nama_pamong = $sekdes->pamong_nama;
            $nip_pamong  = $sekdes->pamong_nip;
            $niap_pamong = $sekdes->pamong_niap;
        }

        if (preg_match('/u.b/i', $ttd)) {
            $pamong      = Pamong::ttd('u.b')->find($input['pamong_id']);
            $atas_nama   = 'a.n ' . $atas_nama . ' \par ' . $sekdes->pamong_jabatan . ' \par  u.b  \par ' . $pamong->jabatan->nama;
            $jabatan     = $pamong->pamong_jabatan;
            $nama_pamong = $pamong->pamong_nama;
            $nip_pamong  = $pamong->pamong_nip;
            $niap_pamong = $pamong->pamong_niap;
        }

        // Untuk lampiran
        if (null === $buffer) {
            return [
                'atas_nama' => str_replace('\par', '<br>', $atas_nama),
                'jabatan'   => $jabatan,
                'nama'      => $nama_pamong,
                'nip'       => $nip_pamong,
                'niap'      => $niap_pamong,
            ];
        }

        $buffer = str_replace('[penandatangan]', $atas_nama, $buffer);
        $buffer = str_replace('[jabatan]', "{$jabatan}", $buffer);
        $buffer = str_replace('[nama_pamong]', $nama_pamong, $buffer);

        if (strlen($nip_pamong) > 10) {
            $sebutan_nip_desa = 'NIP';
            $nip              = $nip_pamong;
            $pamong_nip       = $sebutan_nip_desa . ' : ' . $nip;
        } else {
            $sebutan_nip_desa = config('aplikasi.sebutan_nip_desa');
            if (!empty($niap_pamong)) {
                $nip        = $niap_pamong;
                $pamong_nip = $sebutan_nip_desa . ' : ' . $niap_pamong;
            } else {
                $pamong_nip = '';
            }
        }

        $buffer = str_replace('[sebutan_nip_desa]', $sebutan_nip_desa, $buffer);
        $buffer = str_replace('[pamong_nip]', $nip, $buffer);

        return str_replace('[form_pamong_nip]', $pamong_nip, $buffer);
    }

    public function get_data_surat($id = 0)
    {
        $sql = "SELECT u.*,
        case when substring(u.nik, 1, 1) = 0 then 0 ELSE u.nik END as nik,
        case when substring(k.no_kk, 1, 1) = 0 then 0 ELSE k.no_kk END as no_kk,
        g.nama AS gol_darah, x.nama AS sex, u.sex as sex_id,
        (select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(``tweb_penduduk``.``tanggallahir``))),'%Y') + 0)` from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
        w.nama AS status_kawin, u.status_kawin as status_kawin_id, f.nama AS warganegara, a.nama AS agama, d.nama AS pendidikan, h.nama AS hubungan, j.nama AS pekerjaan, c.rt AS rt, c.rw AS rw, c.dusun AS dusun, k.alamat, m.nama as cacat,
        (select tweb_penduduk.nik from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS nik_kk,
        (select tweb_penduduk.telepon from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS telepon_kk,
        (select tweb_penduduk.email from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS email_kk,
        (select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk,
        r.bdt
        from tweb_penduduk u
        left join tweb_penduduk_sex x on u.sex = x.id
        left join tweb_penduduk_kawin w on u.status_kawin = w.id
        left join tweb_penduduk_hubungan h on u.kk_level = h.id
        left join tweb_penduduk_agama a on u.agama_id = a.id
        left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
        left join tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
        left join tweb_cacat m on u.cacat_id = m.id
        left join tweb_wil_clusterdesa c on u.id_cluster = c.id
        left join tweb_keluarga k on u.id_kk = k.id
        left join tweb_rtm r on u.id_rtm = r.no_kk # TODO : ganti nilai tweb_penduduk id_rtm = id pd tweb_rtm dan ganti kolom no_kk menjadi no_rtm
        left join tweb_penduduk_warganegara f on u.warganegara_id = f.id
        left join tweb_golongan_darah g on u.golongan_darah_id = g.id
        WHERE u.id = ? AND u.config_id = " . identitas('id');
        $data                  = collect(DB::Raw($sql))->toArray();

        $data['alamat_wilayah'] = $this->get_alamat_wilayah($data);
        $this->format_data_surat($data);

        return $data;
    }

    public function get_alamat_wilayah($data)
    {
        $alamat_wilayah = "{$data['alamat']} RT {$data['rt']} / RW {$data['rw']} " . set_ucwords($this->setting->sebutan_dusun) . ' ' . set_ucwords($data['dusun']);

        return trim($alamat_wilayah);
    }

    public function format_data_surat(&$data)
    {
        // Asumsi kolom "alamat_wilayah" sdh dalam format ucwords
        $kolomUpper = [
            'tanggallahir', 'tempatlahir', 'dusun', 'pekerjaan', 'gol_darah', 'agama', 'sex',
            'status_kawin', 'pendidikan', 'hubungan', 'nama_ayah', 'nama_ibu', 'alamat', 'alamat_sebelumnya',
            'cacat',
        ];

        foreach ($kolomUpper as $kolom) {
            if (isset($data[$kolom])) {
                $data[$kolom] = set_ucwords($data[$kolom]);
            }
        }
        if (isset($data['pendidikan'])) {
            $data['pendidikan'] = kasus_lain('pendidikan', $data['pendidikan']);
        }

        if (isset($data['pekerjaan'])) {
            $data['pekerjaan'] = kasus_lain('pekerjaan', $data['pekerjaan']);
        }
    }
}
