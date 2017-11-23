<?php

namespace App\Console\Commands;

use App\Task;
use Illuminate\Console\Command;

class AddTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add random tasks';

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
        $tasks = factory(\App\Task::class, 1000)->make();

        foreach ($tasks as $task) {
            $task->save();
        }
    }
}
