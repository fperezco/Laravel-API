<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends BaseAPIControllerExtended
{
    public $user;

    public function __construct(UserRepositoryInterface $repo)
    {
        parent::__construct($repo, UserResource::class, 'User');
        $this->user = JWTAuth::parseToken()->authenticate();
        //dd($this->user);
    }
}
