<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Traits\ProcessRequestArray;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepositoryEloquent implements BaseRepositoryInterface
{
    protected $model;
    use ProcessRequestArray;

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
    public function all($arrayParameters = null)
    {
        $sortArray = [];
        $fieldsArray = '*';
        $filtersArray = [];
        //1 => where
        //2 => sort
        //3 => fields
        //where se aplica a todo lo k venga menos sort y field

        if (!empty($arrayParameters['sort'])) {
            $sortArray = $arrayParameters['sort'];
            $sortArray = $this->adaptSortArray($sortArray);
            unset($arrayParameters['sort']);
        }

        if (!empty($arrayParameters['fields'])) {
            $fieldsArray = $this->commaSeparatedToArray($arrayParameters['fields']);
            unset($arrayParameters['fields']);
        }

        $filtersArray = $arrayParameters;

        return $this->model::where($filtersArray)->orderByArray($sortArray)->get($fieldsArray);
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

    // METODOS PRIVADOS MOVIDOS A UN TRAIT
    /*private function adaptSortArray($stringSeparatedByComma)
    {
        $elements = explode(',', $stringSeparatedByComma);
        $sortArray = [];
        foreach ($elements as $elto) {
            $sortArray[$elto] = 'asc';
        }
        return $sortArray;
    }*/

    /*private function commaSeparatedToArray($stringSeparatedByComma)
    {
        $elements = explode(',', $stringSeparatedByComma);
        $array = [];
        foreach ($elements as $elto) {
            array_push($array, $elto);
        }
        return $array;
    }*/
}
