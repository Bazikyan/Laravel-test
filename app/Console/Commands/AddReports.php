<?php

namespace App\Console\Commands;

use App\Report;
use Illuminate\Console\Command;

class AddReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add random reports';

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
        $reports = factory(Report::class, 100000)->make();

        foreach ($reports as $report) {
            $report->save();
        }
    }
}
