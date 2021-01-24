<?php

namespace App\Nova\Actions\AssetReceiveItems;

use App\Enums\PurchaseStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkAsDraft extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Disables action log events for this action.
     *
     * @var bool
     */
    public $withoutActionEvents = true;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            if ($model->status == PurchaseStatus::CONFIRMED()) {
                $model->asset->decrement('quantity', $model->quantity);
                $model->status = PurchaseStatus::DRAFT();
                $model->save();

                // Update the purchase item status
                $model->purchaseItem->updateStatus();
                // Update the purchase order status
                $model->purchaseOrder->updateStatus();
            } else {
                return Action::danger('Already Confirmed');
            }
        }

        return Action::message('Mark as draft successfully.');
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
