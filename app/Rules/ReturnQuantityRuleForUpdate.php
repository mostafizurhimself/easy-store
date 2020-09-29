<?php

namespace App\Rules;

use App\Models\Asset;
use App\Models\Fabric;
use App\Models\Material;
use Illuminate\Contracts\Validation\Rule;

class ReturnQuantityRuleForUpdate implements Rule
{
    /**
     * @var mixed
     */
    protected $item;

    /**
     * @var mixed
     */
    protected $previousItemId;

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
    public function __construct($uriKey, $itemId, $previousQuantity, $previousItemId)
    {
        if($uriKey == \App\Nova\FabricReturnItem::uriKey()){
            $this->item = Fabric::find($itemId);
            $this->previousItem = Fabric::find($previousItemId);
        }

        if($uriKey == \App\Nova\MaterialReturnItem::uriKey()){
            $this->item = Material::find($itemId);
            $this->previousItem = Material::find($previousItemId);
        }

        // if($uriKey == \App\Nova\AssetDistributionItem::uriKey()){
        //     $this->item = Asset::find($itemId);
        //     $this->previousItem = Asset::find($previousItemId);
        // }

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
        if($this->item->id == $this->previousItem->id){
            $this->allowedQuantity = $this->item->stock + $this->previousQuantity;
        }else{
            $this->allowedQuantity = $this->item->stock;
        }

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
        return "You can not return more than ".$this->allowedQuantity." {$this->item->unit->name}";
    }
}
