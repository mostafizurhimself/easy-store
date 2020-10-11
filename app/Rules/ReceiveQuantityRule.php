<?php

namespace App\Rules;

use App\Models\AssetPurchaseItem;
use App\Models\FabricPurchaseItem;
use App\Models\MaterialPurchaseItem;
use App\Models\AssetDistributionItem;
use Illuminate\Contracts\Validation\Rule;

class ReceiveQuantityRule implements Rule
{

    /**
     * Purchase item instance
     */
    protected $item;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($viaResource, $itemId)
    {
        if($viaResource == \App\Nova\FabricPurchaseItem::uriKey()){
            $this->item = FabricPurchaseItem::find($itemId);
        }

        if($viaResource == \App\Nova\MaterialPurchaseItem::uriKey()){
            $this->item = MaterialPurchaseItem::find($itemId);
        }

        if($viaResource == \App\Nova\AssetPurchaseItem::uriKey()){
            $this->item = AssetPurchaseItem::find($itemId);
        }

        if($viaResource == \App\Nova\AssetDistributionItem::uriKey()){
            $this->item = AssetDistributionItem::find($itemId);
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
