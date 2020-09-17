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
     * @var double
     */
    protected $previousExpenser;

    /**
     * @var double
     */
    protected $allowedAmount;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($expenser, $previousAmount, $previousExpenser)
    {
        $this->expenser = Expenser::find($expenser);
        $this->previousAmount = $previousAmount;
        $this->previousExpenser =  Expenser::find($previousExpenser);
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
        $this->allowedAmount = $this->expenser->remainingBalance;

        if($this->expenser->id == $this->previousExpenser->id){
            $this->allowedAmount = $this->expenser->remainingBalance + $this->previousAmount;
        }

        return $this->allowedAmount >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot expense more than '. ($this->allowedAmount) ." BDT";
    }
}
