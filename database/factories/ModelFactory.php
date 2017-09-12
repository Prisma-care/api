<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    static $userType;

    return [
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make(str_random(20)),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'user_type' => $userType ?: 'family'
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

$factory->define(App\Heritage::class, function () {
    static $albumId;

    return [
        'album_id' => $albumId,
        'description' => str_random(30)
    ];
});
