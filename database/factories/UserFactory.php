<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'username' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'), // password
        'remember_token' => Str::random(10),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'address' => $faker->address,
        'house_number' => $faker->buildingNumber,
        'postal_code' => $faker->postcode,
        'city' => $faker->city,
        'telephone_number' => $faker->phoneNumber,
        'is_active' => 1,
    ];
});
