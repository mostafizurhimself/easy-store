<?php

namespace App\Nova\Actions\FabricPurchaseOrders;

use App\Enums\PurchaseStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GeneratePurchaseOrder extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if($models->first()->status != PurchaseStatus::DRAFT()){
            return Action::openInNewTab(route('purchase-orders.fabrics', $models->first()));
        }

        return Action::danger('You can not generate purchase order not.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
