<?php

namespace App\Nova\Actions\FabricTransferInvoices;

use App\Enums\TransferStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateInvoice extends Action
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
        if($models->first()->status != TransferStatus::DRAFT()){
            return Action::openInNewTab(route('invoices.fabric-transfers', $models->first()));
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
