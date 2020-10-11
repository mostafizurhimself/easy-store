<?php

namespace App\Rules;

use App\Models\ServiceDispatch;
use Illuminate\Contracts\Validation\Rule;

class ServiceReceiveQuantityRule implements Rule
{
    /**
     * @var \App\Models\ServiceDispatch
     */
    protected $dispatch;


    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($viaResource, $dispatchId)
    {
        if($viaResource == \App\Nova\ServiceDispatch::uriKey()){
            $this->dispatch = ServiceDispatch::find($dispatchId);
        }
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
        return $this->dispatch->remainingQuantity >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You can not receive more than {$this->dispatch->remainingQuantity} {$this->dispatch->unit->name}";
    }
}
