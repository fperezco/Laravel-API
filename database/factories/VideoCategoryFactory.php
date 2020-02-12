<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Repositories\UserRepositoryEloquent;
use App\User;
use App\VideoCategory;
use Faker\Generator as Faker;

$userRepo = app()->make('App\Interfaces\UserRepositoryInterface');

$factory->define(VideoCategory::class, function (Faker $faker) use ($userRepo) {
    $randomUser = $userRepo->random();
    return [
        'user_id' => $randomUser->id,
        'name' => $faker->company,
    ];
});
