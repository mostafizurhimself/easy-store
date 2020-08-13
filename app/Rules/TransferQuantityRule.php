<?php

namespace App\Rules;

use App\Models\Asset;
use Illuminate\Contracts\Validation\Rule;

class TransferQuantityRule implements Rule
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
    public function __construct($uriKey, $itemId)
    {
        if($uriKey == \App\Nova\AssetTransferItem::uriKey()){
            $this->item = Asset::find($itemId);
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
        return $this->item->stock >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You can not transfer more than {$this->item->stock} {$this->item->unit->name}";
    }
}
