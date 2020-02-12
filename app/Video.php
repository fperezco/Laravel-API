<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';
    protected $fillable = ['user_id', 'videocategory_id', 'name', 'url', 'picture', 'description'];

    protected $name;
    protected $url;
    protected $picture;
    protected $description;

    /**
     * Validacion en los setters de los atributos en laravel no tienen sentido los setters y getters se usa directamente el operador -> y si debemos usar setters y
     * getters se usa esto
     *
     * @param string $name
     * @return void
     */
    public function setNameAttribute($name)
    {
        if ($name = '' || $name == null) {
            throw new Exception('Name cant be empty or null');
        } else {
            $this->attributes['name'] = $name;
        }
    }

    public function setUrlAttribute($url)
    {
        if ($url = '' || $url == null) {
            throw new Exception('Url cant be empty or null');
        } else {
            $this->attributes['url'] = $url;
        }
    }
}
