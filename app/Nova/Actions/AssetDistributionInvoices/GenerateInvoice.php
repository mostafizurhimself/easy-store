<?php

namespace App\Nova\Actions\AssetDistributionInvoices;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use App\Enums\DistributionStatus;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateInvoice extends Action
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
        if($models->first()->status != DistributionStatus::DRAFT()){
            return Action::openInNewTab(route('invoices.asset-distributions', $models->first()));
        }

        return Action::danger('You can not generate invoice now.');
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
