<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;

class UserController extends BaseAPIControllerExtended
{
    public function __construct(UserRepositoryInterface $repo)
    {
        parent::__construct($repo, UserResource::class, 'User');
    }
}
