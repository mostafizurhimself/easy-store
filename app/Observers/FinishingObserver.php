<?php

namespace App\Observers;

use Exception;
use App\Models\Product;
use App\Facades\Settings;
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
        if($finishing->invoice->finishings()->count() > Settings::maxInvoiceItem()){
            throw new Exception('Maximum item exceeded.');
        }

        //Get the product
        $product = Product::find($finishing->productId);

        //Set the amount
        $finishing->rate = $product->costPrice;

        if(empty($finishing->unitId)){
            $finishing->unitId = $product->unitId;
        }
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
