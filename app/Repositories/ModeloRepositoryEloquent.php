<?php

namespace App\Repositories;

use App\Modelo;
use App\Interfaces\ModeloRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModeloRepositoryEloquent implements ModeloRepositoryInterface
{
    protected $model;

    /**
     * ModeloRepository constructor.
     *
     * @param Modelo $modelo
     */
    public function __construct(Modelo $modelo)
    {
        $this->model = $modelo;
    }

    //all acepta filtros
    public function all($arrayFilters = null)
    {
        return $this->model::where($arrayFilters)->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $this->model = $this->model::find($id)->fill($data);
        return ($this->model->update()) ? $this->model : false;
    }

    public function delete($id)
    {
        if (null == $modelo = $this->model->find($id)) {
            throw new Exception('Model not found');
        } else {
            return $this->model->destroy($id);
        }
    }

    public function find($id)
    {
        if (null == $modelo = $this->model->find($id)) {
            throw new ModelNotFoundException('Model not found');
        }

        return $modelo;
    }

    public function random()
    {
        return $this->model->all()->random();
    }
}
