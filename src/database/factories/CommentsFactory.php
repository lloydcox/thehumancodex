<?php

use Faker\Generator as Faker;

$factory->define(App\Comments::class, function (Faker $faker) {

    return [
        'user_id' => App\User::inRandomOrder()->first()->id,
        'post_id' => App\Post::inRandomOrder()->first()->id,
        'content' => $faker->sentence($nbWords = 50, $variableNbWords = true)
    ];
});