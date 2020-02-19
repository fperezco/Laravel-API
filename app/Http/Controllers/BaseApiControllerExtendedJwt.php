<?php

namespace App\Http\Controllers;

use App\Interfaces\BaseRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Controlador con depedencia de un usuario JWT, los controladores que extienden de él necesitan
 * un user para su listado y creacion ( no update y delete por ids unicos)
 */
class BaseAPIControllerExtendedJwt extends BaseAPIControllerExtended
{
    protected $repository;
    protected $resourceClass;
    protected $resourceName;
    protected $user;

    public function __construct(BaseRepositoryInterface $repo, $resourceClass, $resourceName)
    {
        $this->repository = $repo;
        $this->resourceClass = $resourceClass;
        $this->resourceName = $resourceName;
        // asumo que todo el que hereda de esta clase lo hace para manejar un usuario y recibe peticiones con token
        $this->getUserFromToken();
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
            // añado el id del usuario para filtrar los videos por el
            $request->merge(['user_id' => $this->user->id]);
            $objects = $this->repository->all($request->all());
            return $this->sendResponse($this->resourceClass::collection($objects)->setEmbedRelationships($request->get('embed')), $this->resourceName . ' retrieved successfully');
        } catch (Exception $e) {
            return $this->sendError('Get ' . $this->resourceName . ' error', $e->getMessage());
        }
    }

    /**
      * Store haciendo uso de excepciones validando en el modelo con
      * https://github.com/dwightwatson/validating
      */
    public function store(Request $request)
    {
        try {
            // añado el user_id propietario decodificado del token
            $request->merge(['user_id' => $this->user->id]);
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
            // como prevengo que un usuario haga peticiones a recursos que no son suyos?? => cambio el find por un where e inyecto como querie el user_id
            // asi un usuario no puede ver los objetos de otro
            // añado el user_id propietario decodificado del token
            $object = $this->repository->findByUserId($id, $this->user->id);
            //$object = $this->repository->find($id);
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
            // añado el user_id propietario decodificado del token, asi evito que haga updates de recursos no suyos
            $request->merge(['user_id' => $this->user->id]);
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
            // como prevengo que un usuario borre un recurso que no es suyo? => cambio el delete por un deleteByUserId e inyecto como querie el user_id
            // asi un usuario no puede ver los objetos de otro
            // añado el user_id propietario decodificado del token
            $this->repository->deleteByUserId($id, $this->user->id);
            //$this->repository->delete($id);
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
    public function sendResponseWithExtraObject($result, $extraObjectName, $extraObject, $message)
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

    public function getUserFromToken()
    {
        try {
            $this->user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return $this->sendError('Token error', $e->getMessage());
        }
    }
}
