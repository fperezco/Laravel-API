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

Route::group(['prefix' => 'v1'], function () {
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

    Route::apiResource('videocategories', 'VideoCategoryController');

    Route::group(['prefix' => 'videocategories'], function () {
        Route::get('/{id}/videos', [
            'uses' => 'VideoController@getVideosByVideoCategoryId',
            'as' => 'videocategories.videos',
        ]);
    });

    Route::apiResource('videos', 'VideoController');
});
