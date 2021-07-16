<?php

namespace App\Console\Commands;

use App\Facades\Timesheet;
use App\Models\Attendance;
use Illuminate\Console\Command;

class UpdateAttendanceOpeningHour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:opening-hour';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update attendance opening hour';

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
        $attendances = Attendance::where('opening_hour', 'null')->get();

        foreach ($attendances as $attendance) {
            // Set the opening hour
            $attendance->openingHour = json_encode(Timesheet::getWorkingRange($attendance->shiftId, $attendance->date));
            $attendance->save();
            $this->info("{$attendance->id} opening hour updated.");
        }
    }
}