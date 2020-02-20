<?php

namespace App\Traits;

use Exception;

/**
 * Trait o trozo de código para reutilizar limpiar el controlador BaseRepository eloquent y no dejar alli dos metodos privados
 * ensuciandolo
 */
trait ProcessRequestArray
{
    public function commaSeparatedToArray($stringSeparatedByComma)
    {
        $elements = explode(',', $stringSeparatedByComma);
        $array = [];
        foreach ($elements as $elto) {
            array_push($array, $elto);
        }
        return $array;
    }

    /**
     * Convert and separated by comma sort array like: sort=email.asc,date.desc into ['email' => 'ASC','date','DESC']
     *
     * @param [type] $stringSeparatedByComma
     * @return void
     * @throws Exception
     */
    public function adaptSortArray($stringSeparatedByComma)
    {
        $elements = explode(',', $stringSeparatedByComma);
        $sortArray = [];
        foreach ($elements as $elto) {
            $order = explode('.', $elto);
            $field = $order[0];
            $order = $order[1];

            if ($order != 'asc' && $order != 'desc') {
                throw new Exception('Incorrect sort fields:' . $order);
            } else {
                // strip first element must be - or +
                $sortArray[$field] = $order;
            }
        }
        return $sortArray;
    }
}
