<?php

namespace App\Http\Controllers;

use App\Interfaces\BaseRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use ReflectionClass;

class BaseAPIControllerExtended extends Controller
{
    protected $repository;
    protected $resourceClass;
    protected $resourceName;

    public function __construct(BaseRepositoryInterface $repo, $resourceClass, $resourceName)
    {
        $this->repository = $repo;
        $this->resourceClass = $resourceClass;
        $this->resourceName = $resourceName;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // le paso el indice embed de
        // http://127.0.0.1:8000/api/v1/videos?fields=id,subject,customer_name,updated_at&state;=open&sort;=-updated_at&embed=user,category
        // que me indica que relaciones incluir en la devolución
        try {
            $objects = $this->repository->all($request->all());
            return $this->sendResponse($this->resourceClass::collection($objects)->setEmbedRelationships($request->get('embed')), $this->resourceName . ' retrieved successfully');
        } catch (Exception $e) {
            return $this->handleException('List ' . $this->resourceName . ' error', $e);
        }
    }

    /**
      * Store haciendo uso de excepciones validando en el modelo con
      * https://github.com/dwightwatson/validating
      */
    public function store(Request $request)
    {
        try {
            $object = $this->repository->create($request->all());
            return $this->sendResponse(new $this->resourceClass($object), $this->resourceName . ' stored successfully');
        } catch (Exception $e) {
            return $this->handleException('Store ' . $this->resourceName . ' error', $e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $object = $this->repository->find($id);
            return $this->sendResponse(new $this->resourceClass($object), $this->resourceName . ' retrieved successfully');
        } catch (Exception $e) {
            return $this->handleException('Show ' . $this->resourceName . ' error', $e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //estan en el index.php
        //'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
        //'Access-Control-Allow-Headers'=> 'Content-Type, X-Auth-Token, Origin'
        try {
            $object = $this->repository->update($request->all(), $id);
            return $this->sendResponse(new $this->resourceClass($object), $this->resourceName . ' updated successfully');
        } catch (Exception $e) {
            return $this->handleException('Update ' . $this->resourceName . ' error', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->repository->delete($id);
            return $this->sendResponse([], $this->resourceName . ' deleted successfully');
        } catch (Exception $e) {
            return $this->handleException('Delete ' . $this->resourceName . ' error', $e);
        }
    }

    protected function handleException($mainErrorMessage, Exception $exception)
    {
        $exceptionClass = get_class($exception);
        $reflect = new ReflectionClass($exception);
        $exceptionClass = $reflect->getShortName();
        //dd('hello', $exception);

        $code = 400;
        switch ($exceptionClass) {
            case 'QueryException':
                $detailErrorMessage = $exception->getPrevious()->errorInfo[2];
            break;
            case 'PDOException':
                $detailErrorMessage = $exception->getPrevious()->errorInfo[2];
            break;
            case 'Exception':
                $detailErrorMessage = $exception->getMessage();
            break;
            case 'ErrorException':
                if ($exception->getMessage() == "Trying to get property 'id' of non-object") {
                    $detailErrorMessage = 'Resource not found';
                }
            break;
            default:
                $detailErrorMessage = get_class($exception);
        }

        return $this->sendError($mainErrorMessage, $detailErrorMessage, $code);
    }

    /**
     * success response method.
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $code = 200)
    {
        $code = 200;
        // si no hay recursos => 204 => no permite body
        if ($result != null && $result->resource == null) {
            $code = 404;
            $message = 'No data available';
        }

        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
