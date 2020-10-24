<?php

namespace App\Rules;

use App\Models\Asset;
use App\Models\Fabric;
use App\Models\Material;
use Illuminate\Contracts\Validation\Rule;

class DistributionQuantityRule implements Rule
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
        if($uriKey == \App\Nova\FabricDistribution::uriKey()){
            $this->item = Fabric::find($itemId);
        }

        if($uriKey == \App\Nova\MaterialDistribution::uriKey()){
            $this->item = Material::find($itemId);
        }

        if($uriKey == \App\Nova\MaterialTransferItem::uriKey()){
            $this->item = Material::find($itemId);
        }

        if($uriKey == \App\Nova\AssetDistributionItem::uriKey()){
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
        return "You can not distribute more than {$this->item->stock} {$this->item->unit->name}";
    }
}
