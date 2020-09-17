<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ReceiverRule implements Rule
{
    /**
     * @var int
     */
    protected $locationId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($locationId)
    {
        $this->locationId = $locationId;
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
        return $this->locationId != $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The location and receiver can not be same.';
    }
}
