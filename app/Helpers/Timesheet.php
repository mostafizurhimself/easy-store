<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Shift;
use App\Facades\Helper;
use App\Models\Holiday;
use Illuminate\Support\Str;
use Spatie\OpeningHours\OpeningHours;

class Timesheet
{
    /**
     * Get the opening our object
     *
     * @param \App\Models\Location
     * @param \App\Models\Shift
     * @return \Spatie\OpeningHours\OpeningHours
     */
    public function getOpeningHours($locationId, $shiftId)
    {
        $shift = Shift::find($shiftId);
        $holidays = Holiday::where('location_id', $locationId)->orderBy('start', 'desc')->get();
        $exceptions = [];

        // Merge the holidays
        foreach ($holidays as $holiday) {
            $days = Helper::getAllDates($holiday->start, $holiday->end);

            foreach ($days as $day) {
                $exceptions[$day] = [
                    'hours' => [],
                    'data'  => $holiday->name
                ];
            }
        }

        // Creating an opening hour object
        $openingHours = OpeningHours::create(array_merge($shift->openingHours, ['exceptions' => $exceptions]));

        return $openingHours;
    }

    /**
     * Get the working hour range of a date
     *
     * @param  $shiftId
     * @param  $date
     * @return array
     */
    public function getWorkingRange($shiftId, $date)
    {
        $shift = Shift::find($shiftId);
        $range = $shift->openingHours[Str::lower($date->format('l'))];
        if($range){
            return explode('-', $range[0]);
        }
        return null;
    }

    /**
     * Get the working hours of a date
     *
     * @param  $shiftId
     * @param  $date
     * @return float
     */
    public function getWorkingHours($shiftId, $date)
    {
        $range = $this->getWorkingRange($shiftId, $date);
        if($range){
            return Carbon::parse($range[1])->diffInSeconds(Carbon::parse($range[0]));
        }

        return null;
    }

    /**
     * Get total rate in seconds
     *
     * @param  $shiftId
     * @param  $date
     * @param  $in
     * @return float
     */
    public function getLate($shiftId, $date, $in)
    {
        $range = $this->getWorkingRange($shiftId, $date);
        if($range){
            $start = Carbon::parse($range[0]);
            $inTime = Carbon::parse($in);
            if($inTime->greaterThan($start)){
                return $inTime->diffInSeconds($start);
            }
        }

        return false;
    }

    /**
     * Get total rate in seconds
     *
     * @param  $shiftId
     * @param  $date
     * @param  $out
     * @return float
     */
    public function getEarlyLeave($shiftId, $date, $out)
    {
        $range = $this->getWorkingRange($shiftId, $date);
        if($range){
            $end = Carbon::parse($range[1]);
            $outTime = Carbon::parse($out);
            if($outTime->lessThan($end)){
                return $outTime->diffInSeconds($end);
            }
        }

        return false;
    }
}
