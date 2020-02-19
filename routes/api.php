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

use App\User;
use Illuminate\Http\Request;

Route::group(['prefix' => 'v1'], function () {
    // RUTAS LIBRES
    Route::post('login', 'LoginController@login');
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

    /* Route::get('videos', function (Request $request) {
         dd(User::find(5)->videocategories);
     });*/
});
