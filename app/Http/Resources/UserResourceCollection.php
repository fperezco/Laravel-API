<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResourceCollection extends ResourceCollection
{
    protected $embedRelationships;

    public function setEmbedRelationships($embed)
    {
        $this->embedRelationships = $embed;
        return $this;
    }

    public function toArray($request)
    {
        return $this->collection->map(function (UserResource $resource) use ($request) {
            return $resource->setEmbedRelationships($this->embedRelationships)->toArray($request);
        })->all();

        // or use HigherOrderCollectionProxy
    // return $this->collection->each->foo($this->foo)->map->toArray($request)->all()

    // or simple
    // $this->collection->each->foo($this->foo);
    // return parent::toArray($request);
    }
}
