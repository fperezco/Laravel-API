<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Video;
use Faker\Generator as Faker;

$videoCategoryRepo = app()->make('App\Interfaces\VideoCategoryRepositoryInterface');
$userRepo = app()->make('App\Interfaces\UserRepositoryInterface');

$factory->define(Video::class, function (Faker $faker) use ($userRepo, $videoCategoryRepo) {
    $randomUser = $userRepo->random();
    $randomCategory = $videoCategoryRepo->random();
    return [
        'user_id' => $randomUser->id,
        'videocategory_id' => $randomCategory->id,
        'name' => $faker->name,
        'url' => 'https://www.youtube.com/watch?v=9O4_awEHh1g',
        'picture' => 'https://images-na.ssl-images-amazon.com/images/I/71oHVs0E%2B3L._SL1425_.jpg',
        'description' => $faker->text,
    ];
});
