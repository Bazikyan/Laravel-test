<?php

namespace App\Console\Commands;

use App\Project;
use Illuminate\Console\Command;

class AddProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add random projects';

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
        $projects = factory(Project::class, 300)->make();

        foreach ($projects as $project) {
            $project->save();
        }
    }
}
