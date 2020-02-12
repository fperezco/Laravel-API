<?php

namespace App\Repositories;

use App\Marca;
use App\Interfaces\MarcaRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MarcaRepositoryEloquent implements MarcaRepositoryInterface
{
    protected $model;

    /**
     * MarcaRepository constructor.
     *
     * @param Marca $marca
     */
    public function __construct(Marca $marca)
    {
        $this->model = $marca;
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
        if (null == $marca = $this->model->find($id)) {
            throw new Exception('TradeMark not found');
        } else {
            return $this->model->destroy($id);
        }
    }

    public function find($id)
    {
        if (null == $marca = $this->model->find($id)) {
            throw new ModelNotFoundException('TradeMark not found');
        }

        return $marca;
    }

    public function random()
    {
        return $this->model->all()->random();
    }
}
