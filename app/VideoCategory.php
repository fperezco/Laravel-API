<?php

namespace App;

use App\Traits\OrderByArray;
use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
    protected $table = 'videocategories';
    protected $fillable = ['user_id', 'name'];
    use OrderByArray; //traits para acomodar los request a la api que piden ordenacion de campos

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
