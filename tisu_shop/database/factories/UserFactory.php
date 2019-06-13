<?php

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

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('123456'),
        'remember_token' => str_random(10),
        'status' => 1,
        'bool_admin' => 0,
        'user_type' => \App\Models\User::USER_TYPE_ADMIN,
        'phone' => '1' . rand(10, 99) . rand(100, 999) . rand(100, 999) . rand(10, 99),
    ];
});
