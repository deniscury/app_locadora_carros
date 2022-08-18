<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository{

    protected $model;

    public function __construct(Model $model){
        $this->setModel($model);
    }

    public function selectRelacionados($atributos){
        $this->setModel($this->getModel()->with($atributos));
    }

    public function filtrar($filtros){
        $condicoes = explode(';', $filtros);

        foreach ($condicoes as $condicao){
            $filtro = explode(':', $condicao);

            $this->setModel($this->getModel()->where($filtro[0], $filtro[1], $filtro[2]));
        }
    }

    public function selectAtributos($atributos){
        $this->setModel($this->getModel()->selectRaw($atributos));
    }

    public function getModel(){
        return $this->model;
    }

    public function setModel($model){
        $this->model = $model;
    }

    public function getResultado(){
        return $this->getModel()->get();
    }

    public function getResultadoPaginado($registros_por_pagina){
        return $this->getModel()->paginate($registros_por_pagina);
    }
}

?>