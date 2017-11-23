<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'name' => $faker->city,
        'status' => rand(0, 1) ? \App\Project::STATUS_ACTIVE : \App\Project::STATUS_PASSIVE,
        'user_id' => \App\User::inRandomOrder()->first(),
    ];
});
