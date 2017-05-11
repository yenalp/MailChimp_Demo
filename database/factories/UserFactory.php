<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Illuminate\Support\Facades\Hash;

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {

    return [
        'first_name' => $faker->unique()->firstName,
        'last_name' => $faker->lastName,
        'user_name' => $faker->unique()->userName,
        'email' => $faker->unique()->email,
        'password' => Hash::make('password'),
        'user_type' => $faker->randomElement(config('constants.USER.TYPE')),
        'disabled' => $faker->randomElement($array = ['1', '0']),
        'no_active_users' =>  0,
        'permissions' => []
    ];
});
