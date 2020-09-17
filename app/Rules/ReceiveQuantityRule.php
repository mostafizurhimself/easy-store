<?php

namespace App\Rules;

use App\Models\AssetPurchaseItem;
use App\Models\FabricPurchaseItem;
use App\Models\MaterialPurchaseItem;
use Illuminate\Contracts\Validation\Rule;

class ReceiveQuantityRule implements Rule
{

    /**
     * Purchase item instance
     */
    protected $purchaseItem;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($viaResource, $purchaseItemId)
    {
        if($viaResource == \App\Nova\FabricPurchaseItem::uriKey()){
            $this->purchaseItem = FabricPurchaseItem::find($purchaseItemId);
        }

        if($viaResource == \App\Nova\MaterialPurchaseItem::uriKey()){
            $this->purchaseItem = MaterialPurchaseItem::find($purchaseItemId);
        }

        if($viaResource == \App\Nova\AssetPurchaseItem::uriKey()){
            $this->purchaseItem = AssetPurchaseItem::find($purchaseItemId);
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
        return $this->purchaseItem->remainingQuantity >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You can not receive more than {$this->purchaseItem->remainingQuantity} {$this->purchaseItem->unit}";
    }
}
