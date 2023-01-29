<?php

use Faker\Generator as Faker;

$factory->define(App\Connection::class, function (Faker $faker) {

    return [
        'connected_user_id' => App\User::inRandomOrder()->first()->id,
        'accepted' => $faker->randomElements([0, 1])[0],
        'type' => 'public',
        'dom' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});