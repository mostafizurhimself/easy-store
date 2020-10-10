<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductRequisitionItem;

class ProductRequisitionItemObserver
{
    /**
     * Handle the product requisition item "saving" event.
     *
     * @param  \App\Models\ProductRequisitionItem  $productRequisitionItem
     * @return void
     */
    public function saving(ProductRequisitionItem $productRequisitionItem)
    {
        //Get the asset
        $product = Product::find($productRequisitionItem->productId);

        //Set the purchase amount
        $productRequisitionItem->requisitionRate = $product->salePrice;
        $productRequisitionItem->unitId = $product->unitId;
        $productRequisitionItem->requisitionAmount = $product->salePrice * $productRequisitionItem->requisitionQuantity;
    }

    /**
     * Handle the product requisition item "saved" event.
     *
     * @param  \App\Models\ProductRequisitionItem  $productRequisitionItem
     * @return void
     */
    public function saved(ProductRequisitionItem $productRequisitionItem)
    {
        //Update the total requisition amount
        $productRequisitionItem->requisition->updateRequisitionAmount();
    }

    /**
     * Handle the product requisition item "deleted" event.
     *
     * @param  \App\Models\ProductRequisitionItem  $productRequisitionItem
     * @return void
     */
    public function deleted(ProductRequisitionItem $productRequisitionItem)
    {
        //Update the total requisition amount
        $productRequisitionItem->requisition->updateRequisitionAmount();
    }

    /**
     * Handle the product requisition item "restored" event.
     *
     * @param  \App\Models\ProductRequisitionItem  $productRequisitionItem
     * @return void
     */
    public function restored(ProductRequisitionItem $productRequisitionItem)
    {
        //Update the total requisition amount
        $productRequisitionItem->requisition->updateRequisitionAmount();
    }

    /**
     * Handle the product requisition item "force deleted" event.
     *
     * @param  \App\Models\ProductRequisitionItem  $productRequisitionItem
     * @return void
     */
    public function forceDeleted(ProductRequisitionItem $productRequisitionItem)
    {
        //Update the total requisition amount
        $productRequisitionItem->requisition->updateRequisitionAmount();
    }
}
