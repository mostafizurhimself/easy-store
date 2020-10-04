<?php

namespace App\Rules;

use App\Models\AssetDistributionInvoice;
use Illuminate\Contracts\Validation\Rule;

class DistributionQuantityRuleOnRequisition implements Rule
{
    /**
     * @var mixed
     */
    protected $invoice;

    /**
     * @var mixed
     */
    protected $requisitionItem;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($viaResource, $viaResourceId, $itemId)
    {

        if($viaResource == \App\Nova\AssetDistributionInvoice::uriKey()){
            $this->invoice = AssetDistributionInvoice::find($viaResourceId);
            if($this->invoice->requisitionId){
                $this->requisitionItem = $this->invoice->requisition->requisitionItems->where('asset_id', $itemId)->first();
            }
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
        if($this->invoice->requisitionId){
            return $this->requisitionItem->remainingQuantity >= $value;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You can not distribute more than requisition quantity. Remaining quantity is {$this->requisitionItem->remainingQuantity} {$this->requisitionItem->unit}";
    }
}
