<?php
use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    $gender = $faker->randomElements(['male', 'female'])[0];
    $mail = rand(1111, 9999) . $faker->unique()->safeEmail;
    return [
        'first_name' => $faker->firstName($gender),
        'last_name' => $faker->lastName,
        'email' => $mail,
        'password' => bcrypt('secret'),
        'location' => $faker->city,
        'gender' => $gender,
        'age' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'username' => explode('@',$mail)[0],
        'validated' => 0,
        'active' => 0,
        'reported' => 0,
        'email_token' => ''
    ];
});