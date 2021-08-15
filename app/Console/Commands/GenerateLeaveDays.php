<?php

namespace App\Console\Commands;

use App\Models\Leave;
use App\Facades\Helper;
use Illuminate\Console\Command;

class GenerateLeaveDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:leave-days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate leave days for all leaves if doesn't exists";

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
        $leaves = Leave::doesntHave('leaveDays')->get();

        foreach ($leaves as $leave) {
            // Get days
            $dates = Helper::getAllDates($leave->from, $leave->to);

            // Insert leave days
            foreach ($dates as $date) {
                $leave->leaveDays()->create([
                    'date'        => $date,
                    'employee_id' => $leave->employeeId
                ]);
            }

            $this->info("Leave #{$leave->id} leave days generated");
        }
    }
}