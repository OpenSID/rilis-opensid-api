<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\PesanMasukEntity;
use App\Http\Transformers\KomentarTransformer;
use Exception;
use Illuminate\Http\Request;

class PesanController extends Controller
{
    /** @var PesanMasukEntity */
    protected $pesan;

    public function __construct(PesanMasukEntity $pesan)
    {
        $this->pesan = $pesan;
    }

    public function index(string $tipe)
    {
        return $this->fractal($this->pesan->get($tipe), new KomentarTransformer(), "pesan {$tipe}");
    }

    public function show(int $id)
    {
        return $this->fractal($this->pesan->find($id), new KomentarTransformer(), 'pesan');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subjek' => 'required',
            'pesan' => 'required',
        ]);

        try {
            $pesan = $this->pesan->insert($request);
        } catch (Exception $e) {
            return $this->fail($e->getMessage(), 400);
        }

        return $this->fractal($pesan, new KomentarTransformer(), 'pesan');
    }
}
