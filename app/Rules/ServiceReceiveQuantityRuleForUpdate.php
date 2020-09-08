<?php

namespace App\Rules;

use App\Models\ServiceDispatch;
use Illuminate\Contracts\Validation\Rule;

class ServiceReceiveQuantityRuleForUpdate implements Rule
{
    /**
     * @var \App\Models\ServiceDispatch
     */
    protected $dispatch;

    /**
     * @var double
     */
    protected $previousQuantity;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($viaResource, $dispatchId, $previousQuantity)
    {
        if($viaResource == \App\Nova\ServiceDispatch::uriKey() || $viaResource == \App\Nova\ServiceInvoice::uriKey()){
            $this->dispatch = ServiceDispatch::find($dispatchId);
        }

        $this->previousQuantity = $previousQuantity;
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
        return $this->dispatch->remainingQuantity + $this->previousQuantity >= $value ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You can not receive more than ".($this->dispatch->remainingQuantity + $this->previousQuantity)." {$this->dispatch->unit}";
    }
}
