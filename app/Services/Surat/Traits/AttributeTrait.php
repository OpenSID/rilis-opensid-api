<?php

namespace App\Services\Surat\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait AttributeTrait
{
    /**
     * Default form masa berlaku.
     *
     * @param string $format Format date output
     * @return array
     */
    protected function formMasaBerlaku(string $format = 'd-m-Y')
    {
        $value = $this->surat
            ->where('url_surat', $this->getSlugSurat())
            ->first();

        if (is_null($value)) {
            return [];
        }

        $dari = Carbon::yesterday()->add($value->masa_berlaku, 'day')->format($format);

        switch ($value->satuan_masa_berlaku) {
            case 'd':
                $sampai = Carbon::now()->addDay()->format($format);
                break;
            case 'w':
                $sampai = Carbon::now()->addWeek()->format($format);
                break;
            case 'M':
                $sampai = Carbon::now()->addMonth()->format($format);
                break;
            case 'y':
                $sampai = Carbon::now()->addYear()->format($format);
                break;
            default:
                $sampai = null;
                break;
        }

        return [
            [
              'type'     => 'date',
              'format'   => 'dd/mm/yyyy',
              'required' => true,
              'label'    => 'Berlaku Dari',
              'name'     => 'berlaku_dari',
              'disabled' => true,
              'value'    => $dari,
            ],
            [
              'type'     => 'date',
              'format'   => 'dd/mm/yyyy',
              'required' => true,
              'label'    => 'Berlaku Sampai',
              'name'     => 'berlaku_sampai',
              'disabled' => true,
              'value'    => $sampai,
            ]
        ];
    }

    /**
     * Get url surat dari class name.
     *
     * @return string
     */
    protected function getSlugSurat()
    {
        return Str::snake((new \ReflectionClass($this))->getShortName());
    }
}
