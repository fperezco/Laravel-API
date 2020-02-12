<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Repositories\UserRepositoryEloquent;
use App\User;

Route::get('/test', function () {
    $userRepo = app()->make('App\Interfaces\UserRepositoryInterface');
    // User::all
    $userRepo = new UserRepositoryEloquent(new User());

    dd($userRepo);
    $randomUser = $repo->random();

    dd($randomUser->id);
});
