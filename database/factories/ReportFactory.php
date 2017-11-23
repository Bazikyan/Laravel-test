<?php

use Faker\Generator as Faker;

$factory->define(App\Report::class, function (Faker $faker) {
    return [
        'duration' => $faker->numberBetween(1, 48) * 10,
        'task_id' => \App\Task::inRandomOrder()->first(),
        'created_at' => $faker->dateTimeThisDecade(),
    ];
});
