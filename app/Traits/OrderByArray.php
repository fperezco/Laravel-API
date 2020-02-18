<?php

namespace App\Traits;

/**
 * Trait o trozo de código para reutilizar la funcionalidad de pasar un array de elementos de ordenacion tal que
 *$sort = [‘id’ => ‘asc, ‘user_id => ‘desc’]  a una consulta $objecto::orderby(......);
 */
trait OrderByArray
{
    public function scopeOrderByArray($query, $order)
    {
        foreach ($order as $column => $sort) {
            $query = $query->orderBy($column, $sort);
        }

        return $query;
    }
}
