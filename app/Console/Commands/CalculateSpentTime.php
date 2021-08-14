<?php

namespace App\Console\Commands;

use App\Models\EmployeeGatePass;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateSpentTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:spent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates employee gate pass spent time';

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
        $passes = EmployeeGatePass::whereNotNull('in')->get();

        foreach ($passes as $pass) {
            $pass->spent = Carbon::parse($pass->in)->diffInSeconds($pass->out);
            $pass->save();
            $this->info("{$pass->id} is updated");
        }
    }
}