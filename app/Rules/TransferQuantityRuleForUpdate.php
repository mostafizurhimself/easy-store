<?php

namespace App\Rules;

use App\Models\Asset;
use Illuminate\Contracts\Validation\Rule;

class TransferQuantityRuleForUpdate implements Rule
{
    /**
     * @var mixed
     */
    protected $item;

    /**
     * @var double
     */
    protected $previousQuantity;

     /**
     * @var double
     */
    protected $allowedQuantity;


    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($uriKey, $itemId, $previousQuantity)
    {
        if($uriKey == \App\Nova\AssetTransferItem::uriKey()){
            $this->item = Asset::find($itemId);
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
        //Calculte the allowed quantity
        $this->allowedQuantity = $this->item->stock + $this->previousQuantity;

        //Validate the quantity
        return $this->allowedQuantity >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You can not transfer more than ".$this->allowedQuantity." {$this->item->unit->name}";
    }
}
