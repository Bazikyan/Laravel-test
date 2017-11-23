<?php

use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    $project = \App\Project::inRandomOrder()->first();
    $owner_id = $project->user->id;
//    dd($owner_id);
//    $owner_id = \App\
    return [
        'name' => $faker->streetName,
        'project_id' => $project->id,
        'user_id' => \App\Team::where('owner_id', '=', $owner_id)->inRandomOrder()->first()->employer_id,
    ];
});
