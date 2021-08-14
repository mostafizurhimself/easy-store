<?php

namespace App\Console\Commands;

use App\Models\Shift;
use App\Models\Employee;
use Illuminate\Console\Command;

class AddShiftIdToEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:shift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add shift id to employees';

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
        $employees = Employee::whereNull('shift_id')->get();

        foreach ($employees as $employee) {
            $employee->shiftId = Shift::where('location_id', $employee->locationId)->first()->id;
            $employee->save();
            $this->info("Employee - {$employee->id} shift updated");
        }
    }
}