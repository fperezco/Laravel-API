<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $embedRelationships = [];

    public function setEmbedRelationships($embed)
    {
        //llega un listado separado por comas tal que user,category o no, o solo viene uno
        $this->embedRelationships = explode(',', $embed);
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $basic = parent::toArray($request);

        // for any posibles relationships of this model
        if (in_array('videocategories', $this->embedRelationships)) {
            $basic['videocategories'] = $this->videocategories->toArray();
        }

        if (in_array('videos', $this->embedRelationships)) {
            $basic['videos'] = $this->videos->toArray();
        }

        return $basic;
    }

    public static function collection($resource)
    {
        return new UserResourceCollection($resource);
    }
}
