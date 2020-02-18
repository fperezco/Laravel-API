<?php

namespace App\Traits;

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

    public function adaptSortArray($stringSeparatedByComma)
    {
        $elements = explode(',', $stringSeparatedByComma);
        $sortArray = [];
        foreach ($elements as $elto) {
            $sortArray[$elto] = 'asc';
        }
        return $sortArray;
    }
}
