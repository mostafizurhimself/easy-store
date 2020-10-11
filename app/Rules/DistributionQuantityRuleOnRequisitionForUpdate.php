<?php

namespace App\Rules;

use App\Models\AssetDistributionInvoice;
use Illuminate\Contracts\Validation\Rule;

class DistributionQuantityRuleOnRequisitionForUpdate implements Rule
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
     * @var mixed
     */
    protected $previousRequisitionItem;

    /**
     * @var mixed
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
    public function __construct($viaResource, $viaResourceId, $itemId, $previousItemId, $previousQuantity)
    {

        if($viaResource == \App\Nova\AssetDistributionInvoice::uriKey()){
            $this->invoice = AssetDistributionInvoice::find($viaResourceId);
            if($this->invoice->requisitionId){
                $this->requisitionItem = $this->invoice->requisition->requisitionItems->where('asset_id', $itemId)->first();
                $this->previousRequisitionItem = $this->invoice->requisition->requisitionItems->where('asset_id', $previousItemId)->first();
                $this->previousQuantity = $previousQuantity;
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
            $this->allowedQuantity = $this->requisitionItem->remainingQuantity;

            if($this->requisitionItem->id == $this->previousRequisitionItem->id){

                $this->allowedQuantity = $this->requisitionItem->remainingQuantity + $this->previousQuantity;
            }
            return  $this->allowedQuantity >= $value;
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
        return "You can not distribute more than requisition quantity. Remaining quantity is {$this->allowedQuantity} {$this->requisitionItem->unit->name}";
    }
}
