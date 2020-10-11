<?php

namespace App\Rules;

use App\Models\AssetPurchaseItem;
use App\Models\FabricPurchaseItem;
use App\Models\MaterialPurchaseItem;
use App\Models\AssetDistributionItem;
use Illuminate\Contracts\Validation\Rule;

class ReceiveQuantityRuleForUpdate implements Rule
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
    public function __construct($viaResource, $itemId, $previousQuantity)
    {
        if($viaResource == \App\Nova\FabricPurchaseItem::uriKey() || $viaResource == \App\Nova\FabricPurchaseOrder::uriKey()){
            $this->item = FabricPurchaseItem::find($itemId);
        }

        if($viaResource == \App\Nova\MaterialPurchaseItem::uriKey() || $viaResource == \App\Nova\MaterialPurchaseOrder::uriKey()){
            $this->item = MaterialPurchaseItem::find($itemId);
        }

        if($viaResource == \App\Nova\AssetPurchaseItem::uriKey() || $viaResource == \App\Nova\AssetPurchaseOrder::uriKey()){
            $this->item = AssetPurchaseItem::find($itemId);
        }

        if($viaResource == \App\Nova\AssetDistributionItem::uriKey() || $viaResource == \App\Nova\AssetDistributionInvoice::uriKey()){
            $this->item = AssetDistributionItem::find($itemId);
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
        $this->allowedQuantity = $this->item->remainingQuantity + $this->previousQuantity;
        return $this->allowedQuantity >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You can not receive more than {$this->allowedQuantity} {$this->item->unit->name}";
    }
}
