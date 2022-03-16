<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\AgendaEntity;
use App\Http\Transformers\AgendaDesaTransformer;

class AgendaDesaController extends Controller
{
    /** @var AgendaEntity */
    protected $agenda;

    /**
     * Agenda controller constructor.
     */
    public function __construct(AgendaEntity $agenda)
    {
        $this->agenda = $agenda;
    }

    public function index()
    {
        return $this->fractal($this->agenda->get(), new AgendaDesaTransformer(), 'agenda');
    }
}
