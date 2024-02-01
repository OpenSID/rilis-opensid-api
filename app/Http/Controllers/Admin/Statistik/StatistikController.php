<?php

namespace App\Http\Controllers\Admin\Statistik;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Traits\QueryBuilderTrait;
use App\Models\Penduduk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends BaseController
{
    use QueryBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kependudukan(Request $request)
    {
        $this->validate($request, [
            'bulan' => 'required|int',
            'tahun' => 'required|int',
        ]);

        $data['kelahiran']         = $this->mutasi_peristiwa(1);
        $data['kematian']          = $this->mutasi_peristiwa(2);
        $data['pendatang']         = $this->mutasi_peristiwa(5);
        $data['pindah']            = $this->mutasi_peristiwa(3);
        $data['hilang']            = $this->mutasi_peristiwa(4);
        $data['penduduk_awal']     = $this->penduduk_awal($request);

        $data['penduduk_akhir']     = [];
        $kategori = ['WNI_L', 'WNI_P', 'WNA_L', 'WNA_P', 'KK', 'KK_L', 'KK_P'];

        foreach ($kategori as $k) {
            $data['penduduk_akhir'][$k] = $data['penduduk_awal'][$k] +  $data['kelahiran'][$k] + $data['pendatang'][$k] -  $data['kematian'][$k] -  $data['pindah'][$k] -  $data['hilang'][$k];
        }

        return $this->sendResponse($data, 'success');
    }

    private function penduduk_awal($request)
    {
        $bln      = $request->bulan;
        $thn      = $request->tahun;
        $pad_bln  = str_pad($bln, 2, '0', STR_PAD_LEFT);
        $lastDate = Carbon::createFromDate($thn, $bln, 1)->endOfMonth();
        $startDate = Carbon::createFromDate($thn, $bln, 1)->startOfMonth();
        $id_config = identitas('id');

        // penduduk awal

        $penduduk_mutasi_sql =  DB::table('tweb_penduduk AS p')
            ->join(DB::raw('(SELECT MAX(id) as max_id, id_pend FROM log_penduduk WHERE tgl_lapor < "' .  $lastDate->toDateString() . '" GROUP BY id_pend) log_max'), 'log_max.id_pend', '=', 'p.id')
            ->join('log_penduduk as l', function ($join) {
                $join->on('log_max.max_id', '=', 'l.id')
                    ->whereNotIn('l.kode_peristiwa', [2, 3, 4])
                    ->where('l.config_id', '=', identitas('id'));
            })
            ->select('p.*', 'l.kode_peristiwa');

        $penduduk_mutasi = collect(DB::table(DB::raw('(' . $this->getCompiledQueryWithBindings($penduduk_mutasi_sql) . ') as m'))
            ->selectRaw('sum(case when sex = 1 and warganegara_id <> 2 and kode_peristiwa in (1,5) then 1 else 0 end) AS WNI_L_PLUS')
            ->selectRaw('sum(case when sex = 2 and warganegara_id <> 2 and kode_peristiwa in (1,5) then 1 else 0 end) AS WNI_P_PLUS')
            ->selectRaw('sum(case when sex = 1 and warganegara_id = 2 and kode_peristiwa in (1,5) then 1 else 0 end) AS WNA_L_PLUS')
            ->selectRaw('sum(case when sex = 2 and warganegara_id = 2 and kode_peristiwa in (1,5) then 1 else 0 end) AS WNA_P_PLUS')
            ->selectRaw('sum(case when sex = 1 and warganegara_id <> 2 and kode_peristiwa in (2, 3, 4) then 1 else 0 end) AS WNI_L_MINUS')
            ->selectRaw('sum(case when sex = 2 and warganegara_id <> 2 and kode_peristiwa in (2, 3, 4) then 1 else 0 end) AS WNI_P_MINUS')
            ->selectRaw('sum(case when sex = 1 and warganegara_id = 2 and kode_peristiwa in (2, 3, 4) then 1 else 0 end) AS WNA_L_MINUS')
            ->selectRaw('sum(case when sex = 2 and warganegara_id = 2 and kode_peristiwa in (2, 3, 4) then 1 else 0 end) AS WNA_P_MINUS')
            ->first())->toArray();

        $keluarga_mutasi_sql = DB::table('log_keluarga as l')
        ->join(DB::raw('(SELECT MAX(id) as id FROM log_keluarga WHERE id_kk IS NOT NULL AND config_id = 1 AND tgl_peristiwa < "'.$startDate->toDateString().'" GROUP BY id_kk) log_max_keluarga'), 'log_max_keluarga.id', '=', 'l.id')
        ->join('tweb_keluarga as k', 'k.id', '=', 'l.id_kk')
        ->join(DB::raw('(SELECT MAX(id) as max_id, id_pend FROM log_penduduk WHERE tgl_lapor < "' .  $lastDate->toDateString() . '" AND config_id = 1 GROUP BY id_pend) log_max'), 'log_max.id_pend', '=', 'k.nik_kepala')
        ->join('log_penduduk as lp', function ($join) {
            $join->on('log_max.max_id', '=', 'lp.id')
                ->whereNotIn('lp.kode_peristiwa', [2, 3, 4]);
        })
        ->join('tweb_penduduk as p', function ($join) {
            $join->on('lp.id_pend', '=', 'p.id')
                ->where('p.kk_level', '=', 1);
        })
        ->select('p.*', 'l.id_peristiwa')
        ->where('l.config_id', '=', $id_config)
        ->whereRaw('l.tgl_peristiwa < "'.$startDate->toDateString().'"')
        ->whereNotIn('l.id_peristiwa', [2, 3, 4]);

        $keluarga_mutasi = collect(DB::table(DB::raw('(' . $this->getCompiledQueryWithBindings($keluarga_mutasi_sql) . ') as m'))
            ->selectRaw('sum(case when id_peristiwa in (1, 12) then 1 else 0 end) AS KK_PLUS')
            ->selectRaw('sum(case when sex = 1 and id_peristiwa in (1, 12) then 1 else 0 end) AS KK_L_PLUS')
            ->selectRaw('sum(case when sex = 2 and id_peristiwa in (1, 12) then 1 else 0 end) AS KK_P_PLUS')
            ->selectRaw('sum(case when id_peristiwa in (2, 3, 4) then 1 else 0 end) AS KK_MINUS')
            ->selectRaw('sum(case when sex = 1 and id_peristiwa in (2, 3, 4) then 1 else 0 end) AS KK_L_MINUS')
            ->selectRaw('sum(case when sex = 2 and id_peristiwa in (2, 3, 4) then 1 else 0 end) AS KK_P_MINUS')
            ->first())->toArray();

        $penduduk_mutasi = array_merge($penduduk_mutasi, $keluarga_mutasi);

        $data     = [];
        $kategori = ['WNI_L', 'WNI_P', 'WNA_L', 'WNA_P', 'KK', 'KK_L', 'KK_P'];

        foreach ($kategori as $k) {
            $data[$k] = $penduduk_mutasi[$k . '_PLUS'] - $penduduk_mutasi[$k . '_MINUS'];
        }

        return $data;
    }

    private function kelahiran($request)
    {
        $lahir = $this->mutasi_peristiwa(1);
    }


    private function mutasi_peristiwa(int $peristiwa, $rincian = null, $tipe = null)
    {
        // Jika rincian dan tipe di definisikan, maka akan masuk kedetil laporan
        if ($rincian && $tipe) {
            return $this->rincian_peristiwa($peristiwa, $tipe);
        }

        $bln      = request()->bulan;
        $thn      = request()->tahun;
        $id_config = identitas('id');

        // Mutasi penduduk
        $mutasi_pada_bln_thn_sql = DB::table('log_penduduk as l')
            ->join('tweb_penduduk as p', 'l.id_pend', '=', 'p.id')
            ->select('p.*', 'l.ref_pindah', 'l.kode_peristiwa')
            ->where('l.config_id', '=', $id_config)
            ->whereYear('l.tgl_lapor', '=', $thn)
            ->whereMonth('l.tgl_lapor', '=', $bln)
            ->where('l.kode_peristiwa', '=', $peristiwa);
        $mutasi_pada_bln_thn = $this->getCompiledQueryWithBindings($mutasi_pada_bln_thn_sql);

        $data = collect(DB::table(DB::raw('(' . $mutasi_pada_bln_thn . ') as m'))
            ->selectRaw('sum(case when sex = 1 and warganegara_id <> 2 then 1 else 0 end) AS WNI_L')
            ->selectRaw('sum(case when sex = 2 and warganegara_id <> 2 then 1 else 0 end) AS WNI_P')
            ->selectRaw('sum(case when sex = 1 and warganegara_id = 2 then 1 else 0 end) AS WNA_L')
            ->selectRaw('sum(case when sex = 2 and warganegara_id = 2 then 1 else 0 end) AS WNA_P')
            ->first())
            ->toArray();

        // Mutasi keluarga
        $mutasi_keluarga_bln_thn_sql = DB::table('log_keluarga as l')
            ->join('tweb_keluarga as k', 'k.id', '=', 'l.id_kk')
            ->join('tweb_penduduk as p', 'p.id', '=', 'k.nik_kepala')
            ->leftJoin('log_penduduk as lp', 'lp.id', '=', 'l.id_log_penduduk')
            ->select('p.*', 'l.id_peristiwa')
            ->where('l.config_id', '=', $id_config)
            ->where(function ($query) use ($thn, $bln) {
                $query->where(function ($subquery) use ($thn) {
                    $subquery->whereNotNull('lp.tgl_lapor')->whereYear('lp.tgl_lapor', '=', $thn);
                })->orWhere(function ($subquery) use ($thn) {
                    $subquery->whereNull('lp.tgl_lapor')->whereYear('l.tgl_peristiwa', '=', $thn);
                });
            })
            ->where(function ($query) use ($bln) {
                $query->where(function ($subquery) use ($bln) {
                    $subquery->whereNotNull('lp.tgl_lapor')->whereMonth('lp.tgl_lapor', '=', $bln);
                })->orWhere(function ($subquery) use ($bln) {
                    $subquery->whereNull('lp.tgl_lapor')->whereMonth('l.tgl_peristiwa', '=', $bln);
                });
            })
            ->where('l.id_peristiwa', '=', $peristiwa);
        $mutasi_keluarga_bln_thn = $this->getCompiledQueryWithBindings($mutasi_keluarga_bln_thn_sql);
        $kel = collect(DB::table(DB::raw('(' . $mutasi_keluarga_bln_thn . ') as m'))
            ->selectRaw('sum(case when kk_level = 1 then 1 else 0 end) AS KK')
            ->selectRaw('sum(case when kk_level = 1 and sex = 1 then 1 else 0 end) AS KK_L')
            ->selectRaw('sum(case when kk_level = 1 and sex = 2 then 1 else 0 end) AS KK_P')
            ->first())
            ->toArray();

        return array_merge($data, $kel);
    }
}
