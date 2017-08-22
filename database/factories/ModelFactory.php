<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make(str_random(20)),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});

$factory->define(App\Patient::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});

$factory->define(App\Album::class, function ($faker) {
    return [
        'title' => str_random(20)
    ];
});

$factory->state(App\Album::class, 'default', function ($faker) {
    return [
        'is_default' => true,
        // TODO remove when patient_id is nullable
        'patient_id' =>  1
    ];
});

$factory->define(App\Heritage::class, function () {
    static $albumId;

    return [
        'description' => str_random(30)
    ];
});
