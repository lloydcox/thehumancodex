<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {

    return [
        'user_id' => App\User::inRandomOrder()->first()->id,
        'title' => $faker->sentence($nbWords = 3, $variableNbWords = true),
        'content' => $faker->sentence($nbWords = 50, $variableNbWords = true),
        'location' => $faker->city,
        'date' => $faker->date(),
        'lng' => $faker->longitude,
        'lat' => $faker->latitude,
        'image' => '/storage/images/posts/seed/' . $faker->image('public/storage/images/posts/seed', 800,600, 'city', false)
    ];
});