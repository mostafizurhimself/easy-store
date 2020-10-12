<?php

namespace App\Rules;

use App\Models\ServiceDispatch;
use App\Models\ServiceTransferItem;
use Illuminate\Contracts\Validation\Rule;

class ServiceReceiveQuantityRule implements Rule
{
    /**
     * @var mixed
     */
    protected $item;


    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($viaResource, $itemId)
    {
        if($viaResource == \App\Nova\ServiceDispatch::uriKey()){
            $this->item = ServiceDispatch::find($itemId);
        }

        if($viaResource == \App\Nova\ServiceTransferItem::uriKey()){
            $this->item = ServiceTransferItem::find($itemId);
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
        return $this->item->remainingQuantity >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You can not receive more than {$this->item->remainingQuantity} {$this->item->unit->name}";
    }
}
