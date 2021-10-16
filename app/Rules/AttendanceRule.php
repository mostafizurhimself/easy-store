<?php

namespace App\Rules;

use App\Models\Attendance;
use Illuminate\Contracts\Validation\Rule;

class AttendanceRule implements Rule
{
    /**
     * @var integer
     */
    public $employeeId;

    /**
     * @var string
     */
    public $date;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($employeeId, $date)
    {
        $this->employeeId = $employeeId;
        $this->date = $date;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Attendance::where('employee_id', $this->employeeId)->where('date', $this->date)->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Attendance already taken';
    }
}