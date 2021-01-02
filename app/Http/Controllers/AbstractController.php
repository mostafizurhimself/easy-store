<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shift;
use App\Facades\Helper;
use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\OpeningHours\OpeningHours;

class AbstractController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shift         $shift
     * @return \Illuminate\Http\Response
     */
    public function generateSchedule(Request $request, Shift $shift)
    {
        $holidays = Holiday::orderBy('start', 'desc')->get();

        $exceptions = [];

        // Merge the holidays
        foreach($holidays as $holiday){
            $days = Helper::getAllDates($holiday->start, $holiday->end);

            foreach($days as $day){
                $exceptions[$day] = [
                    'hours' => [],
                    'data'  => $holiday->name
                ];
            }

        }

        // Creating an opening hour object
        $openingHours = OpeningHours::create(array_merge($shift->openingHours, ['exceptions' => $exceptions]));

        // Get the dates form the range
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);
        $dates = Helper::getAllDates($start, $end);
    }
}
