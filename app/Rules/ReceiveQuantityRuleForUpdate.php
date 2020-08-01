<?php

namespace App\Rules;

use App\Models\FabricPurchaseItem;
use App\Models\MaterialPurchaseItem;
use Illuminate\Contracts\Validation\Rule;

class ReceiveQuantityRuleForUpdate implements Rule
{

    /**
     * Purchase item instance
     */
    protected $purchaseItem;

    /**
     * @var double
     */
    protected $previousQuantity;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($viaResource, $purchaseItemId, $previousQuantity)
    {
        if($viaResource == \App\Nova\FabricPurchaseItem::uriKey() || $viaResource == \App\Nova\FabricPurchaseOrder::uriKey()){
            $this->purchaseItem = FabricPurchaseItem::find($purchaseItemId);
        }

        if($viaResource == \App\Nova\MaterialPurchaseItem::uriKey() || $viaResource == \App\Nova\MaterialPurchaseOrder::uriKey()){
            $this->purchaseItem = MaterialPurchaseItem::find($purchaseItemId);
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
        return $this->purchaseItem->remainingQuantity >= $value - $this->previousQuantity  ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The receive quantity is greated than the purchase qunatity.';
    }
}
