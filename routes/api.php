<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Repositories\UserRepositoryEloquent;
use App\User;
use Illuminate\Support\Facades\Hash;

Route::group(['prefix' => 'v1'], function () {
    // RUTAS LIBRES
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout');
    Route::get('about', function () {
        return json_encode('REST vacio con info del servidor accesible para todos');
    });

    // RUTAS AUTENTICADAS CON JWT

    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::apiResource('users', 'UserController');

        Route::group(['prefix' => 'users'], function () {
            Route::get('/{id}/videos', [
                'uses' => 'VideoController@getVideosByUserId',
                'as' => 'users.videos',
            ]);
            Route::get('/{id}/videocategories', [
                'uses' => 'VideoCategoryController@getVideoCategoriesByUserId',
                'as' => 'users.videocategories',
            ]);
        });

        Route::apiResource('videos', 'VideoController');
    });

    Route::apiResource('videocategories', 'VideoCategoryController');

    Route::group(['prefix' => 'videocategories'], function () {
        Route::get('/{id}/videos', [
            'uses' => 'VideoController@getVideosByVideoCategoryId',
            'as' => 'videocategories.videos',
        ]);
    });

    //Route::apiResource('videos', 'VideoController');

    Route::get('test', function () {
        $user = new User();
        $user->name = 'testuser';
        $user->surname = 'testuser';
        $user->email = 'test@user.com';
        $user->password = Hash::make('password');
        //lo almacenamos via repositorio
        $repo = new UserRepositoryEloquent(new User());
        $repo->create($user->toArray());
    });
});
