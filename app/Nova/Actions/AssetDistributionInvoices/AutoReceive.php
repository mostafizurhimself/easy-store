<?php

namespace App\Nova\Actions\AssetDistributionInvoices;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use App\Enums\DistributionStatus;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutoReceive extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The number of models that should be included in each chunk.
     *
     * @var int
     */
    public static $chunkCount = 200000000;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach($models as $model){
            if($model->receiverId == request()->user()->locationId || request()->user()->isSuperAdmin()){
                foreach($model->distributionItems as $distributionItem ){
                    // Check already received or not
                    if(!$distributionItem->receiveItems()->exists() && $distributionItem->status != DistributionStatus::DRAFT()){
                        $distributionItem->receiveItems()->create([
                            "date"                 => Carbon::now(),
                            'invoice_id'           => $distributionItem->invoiceId,
                            'distribution_item_id' => $distributionItem->id,
                            'asset_id'             => $distributionItem->assetId,
                            'quantity'             => $distributionItem->distributionQuantity,
                            'rate'                 => $distributionItem->distributionRate,
                            'amount'               => $distributionItem->distributionQuantity * $distributionItem->distributionRate,
                            'unit_id'              => $distributionItem->unitId,
                        ]);
                    }
                }
                return Action::message("Auto receive items are generated.");
            }else{
                return Action::danger("Sorry you are unauthorized!");
            }
        }
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
