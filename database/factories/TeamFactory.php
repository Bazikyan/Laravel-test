<?php

use Faker\Generator as Faker;

$factory->define(\App\Team::class, function (Faker $faker) {
    $owner_id = \App\User::inRandomOrder()->first();
    return [
        'owner_id' => $owner_id,
        'employer_id' => \App\User::where('id', '<>', $owner_id)->inRandomOrder()->first()
    ];
});
