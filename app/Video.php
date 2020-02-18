<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\OrderByArray;

class Video extends Model
{
    //uso softdeletes
    use SoftDeletes;
    use OrderByArray; //traits para acomodar los request a la api que piden ordenacion de campos

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'videos';
    protected $fillable = ['user_id', 'videocategory_id', 'name', 'url', 'picture', 'description'];

    /**
     * Validacion en los setters de los atributos en laravel no tienen sentido los setters y getters se usa directamente el operador -> y si debemos usar setters y
     * getters se usa esto
     *
     * @param string $name
     * @return void
     */
    public function setNameAttribute($name)
    {
        if ($name == '' || $name == null) {
            throw new Exception('Name cant be empty or null');
        } else {
            $this->attributes['name'] = $name;
        }
    }

    public function setUrlAttribute($url)
    {
        if ($url == '' || $url == null) {
            throw new Exception('Url cant be empty or null');
        } else {
            $this->attributes['url'] = $url;
        }
    }
}
