<?php
use Faker\Generator as Faker;

$factory->define(App\UserData::class, function (Faker $faker) {


    return [
        'code' => 'avatar',
        'value' => '/storage/images/user/' . $faker->image('public/storage/images/user', 400,400, 'people', false )
    ];
});