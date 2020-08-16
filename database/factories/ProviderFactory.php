<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Provider;

$factory->define(Provider::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'mobile' => $faker->phoneNumber,
        'email'  => $faker->email,
        'opening_balance' => 0,
        'balance' => 0,
        'active' => 1,
    ];
});

