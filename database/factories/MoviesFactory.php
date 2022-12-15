<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Movies;
use Faker\Generator as Faker;

$factory->define(Movies::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->title,
        'description' => $faker->description,
        'rating' => $faker->rating,
        'image' => $faker->image
    ];
});