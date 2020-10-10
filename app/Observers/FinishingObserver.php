<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Finishing;

class FinishingObserver
{
    /**
     * Handle the finishing "saving" event.
     *
     * @param  \App\Models\Finishing  $finishing
     * @return void
     */
    public function saving(Finishing $finishing)
    {
        //Get the product
        $product = Product::find($finishing->productId);

        //Set the amount
        $finishing->rate = $product->costPrice;
        $finishing->unitId = $product->unitId;
        $finishing->amount = $product->costPrice * $finishing->quantity;
    }

    /**
     * Handle the finishing "saved" event.
     *
     * @param  \App\Models\Finishing  $finishing
     * @return void
     */
    public function saved(Finishing $finishing)
    {
        $finishing->invoice->updateTotalQuantity();
        $finishing->invoice->updateTotalAmount();
    }

    /**
     * Handle the finishing "updated" event.
     *
     * @param  \App\Models\Finishing  $finishing
     * @return void
     */
    public function updated(Finishing $finishing)
    {
        $finishing->invoice->updateTotalQuantity();
        $finishing->invoice->updateTotalAmount();
    }

    /**
     * Handle the finishing "deleted" event.
     *
     * @param  \App\Models\Finishing  $finishing
     * @return void
     */
    public function deleted(Finishing $finishing)
    {
        $finishing->invoice->updateTotalQuantity();
        $finishing->invoice->updateTotalAmount();
    }

    /**
     * Handle the finishing "restored" event.
     *
     * @param  \App\Models\Finishing  $finishing
     * @return void
     */
    public function restored(Finishing $finishing)
    {
        $finishing->invoice->updateTotalQuantity();
        $finishing->invoice->updateTotalAmount();
    }

    /**
     * Handle the finishing "force deleted" event.
     *
     * @param  \App\Models\Finishing  $finishing
     * @return void
     */
    public function forceDeleted(Finishing $finishing)
    {
        $finishing->invoice->updateTotalQuantity();
        $finishing->invoice->updateTotalAmount();
    }
}
