<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados(string $atributos)
    {
        $this->model = $this->model->with($atributos);
    }

    public function filtro(string $filtros)
    {
        $filtros = explode(';', $filtros);
        foreach ($filtros as $condicao) {
            $partesCondicao = explode(':', $condicao);
            $this->model = $this->model->where($partesCondicao[0], $partesCondicao[1], $partesCondicao[2]);
        }
    }

    public function selectAtributos(string $atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado()
    {
        return $this->model->get();
    }
}
