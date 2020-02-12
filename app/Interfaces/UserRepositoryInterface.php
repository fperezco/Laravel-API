<?php

namespace App\Interfaces;

/**
 * Interfaz vacia, la uso para , via service provider, concretarla en el CocheRepositoryEloquent
 * si el dia de mañana cambia el coche repositoryEloquet por otro => solo he de modificar
 * el service provider donde se hace referencia
 */
interface UserRepositoryInterface extends BaseRepositoryInterface
{

}
