<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepositoryEloquent implements BaseRepositoryInterface
{
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
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
        if (null == $model = $this->model->find($id)) {
            throw new Exception('Not found');
        } else {
            return $this->model->destroy($id);
        }
    }

    public function find($id)
    {
        if (null == $model = $this->model->find($id)) {
            throw new ModelNotFoundException('Not found');
        }

        return $model;
    }

    public function random()
    {
        return $this->model->all()->random();
    }
}
