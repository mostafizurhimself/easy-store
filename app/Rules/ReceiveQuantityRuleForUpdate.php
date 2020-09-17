<?php

namespace App\Rules;

use App\Models\AssetPurchaseItem;
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
     * @var double
     */
    protected $allowedQuantity;

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

        if($viaResource == \App\Nova\AssetPurchaseItem::uriKey() || $viaResource == \App\Nova\AssetPurchaseOrder::uriKey()){
            $this->purchaseItem = AssetPurchaseItem::find($purchaseItemId);
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
        $this->allowedQuantity = $this->purchaseItem->remainingQuantity + $this->previousQuantity;
        return $this->allowedQuantity >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You can not receive more than {$this->allowedQuantity} {$this->purchaseItem->unit}";
    }
}
