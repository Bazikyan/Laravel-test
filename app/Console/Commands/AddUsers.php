<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class AddUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add random users';

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
        $users = factory(User::class, 100)->make();

        foreach ($users as $user)
        {
            $user->save();
        }

    }

}
