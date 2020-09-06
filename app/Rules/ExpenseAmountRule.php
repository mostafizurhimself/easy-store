<?php

namespace App\Rules;

use App\Models\Expenser;
use Illuminate\Contracts\Validation\Rule;

class ExpenseAmountRule implements Rule
{
    /**
     * @var \App\Models\Expenser
     */
    protected $expenser;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($expenser)
    {
        $this->expenser = Expenser::find($expenser);
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
       return $this->expenser->remainingBalance >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot expense more than '. $this->expenser->remainingBalance ." BDT";
    }
}
