<?php

namespace App\Nova\Actions\AssetDistributionItems;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use App\Enums\DistributionStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutoReceive extends Action
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
            if ($model->invoice->receiverId == Auth::user()->locationId || Auth::user()->isSuperAdmin()) {
                // Check already received or not
                if (!$model->receiveItems()->exists() && $model->status != DistributionStatus::DRAFT()) {
                    $model->receiveItems()->create([
                        "date"                 => Carbon::now(),
                        'invoice_id'           => $model->invoiceId,
                        'distribution_item_id' => $model->id,
                        'asset_id'             => $model->assetId,
                        'quantity'             => $model->distributionQuantity,
                        'rate'                 => $model->distributionRate,
                        'amount'               => $model->distributionQuantity * $model->distributionRate,
                        'unit_id'              => $model->unitId,
                    ]);
                }
            } else {
                return Action::danger("Sorry you are unauthorized!");
            }
        }

        return Action::message("Auto receive items are generated.");
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
