<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;

class UserController extends BaseAPIControllerExtended
{
    public $user;

    public function __construct(UserRepositoryInterface $repo)
    {
        parent::__construct($repo, UserResource::class, 'User');
        parent::getUserFromToken();
        //dd($this->user);
    }
}
