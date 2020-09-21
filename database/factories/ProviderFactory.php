<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Provider;
use App\Enums\ActiveStatus;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'mobile' => $faker->phoneNumber,
        'email'  => $faker->email,
        'opening_balance' => 0,
        'balance' => 0,
        'status' => ActiveStatus::ACTIVE(),
    ];
});

