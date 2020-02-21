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

    /**
     * All method. Could filter, sort and paginate resources
     *
     * @param [type] $arrayParameters
     * @return void
     * @throws Exception
     */
    public function all($arrayParameters = null)
    {
        $sortArray = [];
        $fieldsArray = '*';
        $filtersArray = [];
        $embedArray = [];
        //1 => where
        //2 => sort
        //3 => fields
        //where se aplica a todo lo k venga menos sort y field

        //ordenation
        if (!empty($arrayParameters['sort'])) {
            $sortArray = $arrayParameters['sort'];
            $sortArray = $this->adaptSortArray($sortArray);
            unset($arrayParameters['sort']);
        }

        //pagination
        if (!empty($arrayParameters['limit'])) {
            $limit = $arrayParameters['limit'];
            unset($arrayParameters['limit']);
        }
        if (!empty($arrayParameters['offset'])) {
            $offset = $arrayParameters['offset'];
            unset($arrayParameters['offset']);
        }

        // OJO, IMPORTANTE, SI NO  VIENE UN EMBED  => PUEDO FILTRAR LOS CAMPOS PERO
        // SI VIENE UN EMBED => HE DE MOSTRAR POR FUERZA TODOS LOS CAMPOS YA QUE SI DESCARTO ALGUNOS
        //PUEDO INFLUIR Y QUITAR LOS CAMPOS PIVOTES DE LAS RELACIONES
        if (empty($arrayParameters['embed'])) {
            //fields
            if (!empty($arrayParameters['fields'])) {
                $fieldsArray = $this->commaSeparatedToArray($arrayParameters['fields']);
                unset($arrayParameters['fields']);
            }
        } else { //si viene un embed => lo elimino del vector y elimino el posible fields
            unset($arrayParameters['embed'], $arrayParameters['fields']);
        }

        //rest = filters
        $filtersArray = $arrayParameters;

        //dd($this->model);//->embedRelationships = 'holaaa';
        if (isset($limit) && isset($offset)) {
            return $this->model::where($filtersArray)->orderByArray($sortArray)->offset($offset)->limit($limit)->get($fieldsArray);
        } else {
            return $this->model::where($filtersArray)->orderByArray($sortArray)->get($fieldsArray);
            //return $this->model::with('videoCategory')->where($filtersArray)->orderByArray($sortArray)->get($fieldsArray);
           // return $this->model::with('videoCategory')->get();
        }
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Undocumented function
     *
     * @param array $data
     * @param [type] $id
     * @return void
     * @throws Exception
     */
    public function update(array $data, $id)
    {
        $this->model = $this->model::find($id);
        if ($this->model) {
            $this->model->fill($data);
        } else {
            throw new ModelNotFoundException('Not found');
        }
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

    public function findByUserId($id, $userId)
    {
        // return $this->model::where(['id' => $id, 'user_id' => $userId])->first();

        if (null == $model = $this->model::where(['id' => $id, 'user_id' => $userId])->first()) {
            throw new ModelNotFoundException('Not found');
        }

        return $model;
    }

    public function updateByUserId($id, $userId, $data)
    {
        // return $this->model::where(['id' => $id, 'user_id' => $userId])->first();

        if (null == $model = $this->model::where(['id' => $id, 'user_id' => $userId])->first()) {
            throw new ModelNotFoundException('Not found');
        } else {
            return $this->update($data, $id);
        }
    }

    public function deleteByUserId($id, $userId)
    {
        return $this->delete($this->findByUserId($id, $userId)->id);
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
