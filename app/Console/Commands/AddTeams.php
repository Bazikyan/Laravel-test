<?php

namespace App\Console\Commands;

use App\Team;
use Illuminate\Console\Command;

class AddTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teams:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add random teams';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $teams = factory(Team::class, 500)->make();

        foreach ($teams as $team)
        {
            $team->save();
        }
        $users = \App\User::get();
        foreach ($users as $user) {
            $team = new Team();
            $team->owner_id = $user->id;
            $team->employer_id = $user->id;
            $team->save();
        }
    }
}
