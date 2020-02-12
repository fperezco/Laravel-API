<?php

namespace App\Http\Controllers;

use App\Interfaces\BaseRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        $objects = $this->repository->all($request->all());
        return $this->sendResponse($this->resourceClass::collection($objects), $this->resourceName . ' retrieved successfully');
    }

    /**
      * Store haciendo uso de excepciones validando en el modelo con
      * https://github.com/dwightwatson/validating
      */
    public function store(Request $request)
    {
        //$data = json_decode($request->getContent(), true);
        try {
            $object = $this->repository->create($request->all());
            return $this->sendResponse(new $this->resourceClass($object), $this->resourceName . ' stored successfully');
            /* } catch (\Watson\Validating\ValidationException $e) {
                 return $this->sendError('Store ' . $this->resourceName . ' input error', $e->getErrors()->all());
             } catch (Exception $e) {
                 return $this->sendError('Store ' . $this->resourceName . ' error', $e->getMessage());
             }*/
        } catch (ValidationException $e) { //TRABAJANDO SIN TRAITS VALIDATION EN MODELO Y
            return $this->sendError('Store ' . $this->resourceName . ' input error', $e->errors());
        } catch (Exception $e) {
            return $this->sendError('Store ' . $this->resourceName . ' error', $e->getMessage());
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
            return $this->sendError('Error obteniendo ' . $this->resourceName, $e->getMessage());
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
        try {
            $object = $this->repository->update($request->all(), $id);
            return $this->sendResponse(new $this->resourceClass($object), $this->resourceName . ' updated successfully');
            /* } catch (\Watson\Validating\ValidationException $e) {
                 return $this->sendError('Update ' . $this->resourceName . ' input error', $e->getErrors()->all());*/
        } catch (Exception $e) {
            return $this->sendError('Update ' . $this->resourceName . ' error', $e->getMessage());
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
            return $this->sendError('Delete ' . $this->resourceName . ' error', $e->getMessage());
        }
    }

    /**
     * success response method.
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    /**
     * success response method.
     * @return \Illuminate\Http\Response
     */
    public function sendResponseWithExtraObject($result, $extraObjectName,$extraObject, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            $extraObjectName => $extraObject,
            'message' => $message,
        ];
        return response()->json($response, 200);
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
