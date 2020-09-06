<?php

namespace App\Rules;

use App\Models\Expenser;
use Illuminate\Contracts\Validation\Rule;

class ExpenseAmountRuleForUpdate implements Rule
{
    /**
     * @var \App\Models\Expenser
     */
    protected $expenser;

    /**
     * @var double
     */
    protected $previousAmount;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($expenser, $previousAmount)
    {
        $this->expenser = Expenser::find($expenser);
        $this->previousAmount = $previousAmount;
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
       return $this->expenser->remainingBalance + $this->previousAmount >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot expense more than '. ($this->expenser->remainingBalance + $this->previousAmount) ." BDT";
    }
}
