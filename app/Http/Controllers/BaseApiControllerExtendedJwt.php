<?php

namespace App\Http\Controllers;

use App\Interfaces\BaseRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Controlador con depedencia de un usuario JWT, los controladores que extienden de él necesitan
 * un user para su listado y creacion, update, delete y show ya que he de controlar que el user_id del token
 * es realmente el propietario del recurso
 */
class BaseAPIControllerExtendedJwt extends BaseAPIControllerExtended
{
    protected $repository;
    protected $resourceClass;
    protected $resourceName;
    protected $user;

    public function __construct(BaseRepositoryInterface $repo, $resourceClass, $resourceName)
    {
        parent::__construct($repo, $resourceClass, $resourceName);
        // asumo que todo el que hereda de esta clase lo hace para manejar un usuario y recibe peticiones con token
        $this->getUserFromToken();
    }

    public function getUserFromToken()
    {
        try {
            $this->user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return $this->sendError('Token error', $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // añado el id del usuario para filtrar los videos por el
        $request->merge(['user_id' => $this->user->id]);
        return parent::index($request);
    }

    /**
      * Store haciendo uso de excepciones validando en el modelo con
      * https://github.com/dwightwatson/validating
      */
    public function store(Request $request)
    {
        // añado el user_id propietario decodificado del token
        $request->merge(['user_id' => $this->user->id]);
        return parent::store($request);
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
        $request->merge(['user_id' => $this->user->id]);
        return parent::update($request, $id);
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
}
