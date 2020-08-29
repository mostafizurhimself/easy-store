<?php

namespace App\Observers;

use App\Models\Style;
use App\Models\ProductOutput;

class ProductOutputObserver
{
    /**
     * Handle the product output "saving" event.
     *
     * @param  \App\Models\ProductOutput  $productOutput
     * @return void
     */
    public function saving(ProductOutput $productOutput)
    {
        //Get the style
        $style = Style::find($productOutput->styleId);

        //Set the rate & amount
        $productOutput->rate = $style->rate;
        $productOutput->amount = $style->rate * $productOutput->quantity;
    }

    /**
     * Handle the product output "updated" event.
     *
     * @param  \App\Models\ProductOutput  $productOutput
     * @return void
     */
    public function updated(ProductOutput $productOutput)
    {
        //
    }

    /**
     * Handle the product output "deleted" event.
     *
     * @param  \App\Models\ProductOutput  $productOutput
     * @return void
     */
    public function deleted(ProductOutput $productOutput)
    {
        //
    }

    /**
     * Handle the product output "restored" event.
     *
     * @param  \App\Models\ProductOutput  $productOutput
     * @return void
     */
    public function restored(ProductOutput $productOutput)
    {
        //
    }

    /**
     * Handle the product output "force deleted" event.
     *
     * @param  \App\Models\ProductOutput  $productOutput
     * @return void
     */
    public function forceDeleted(ProductOutput $productOutput)
    {
        //
    }
}
