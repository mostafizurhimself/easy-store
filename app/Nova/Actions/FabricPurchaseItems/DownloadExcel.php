<?php

namespace App\Nova\Actions\FabricPurchaseItems;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use App\Exports\FabricPurchaseItemExport;
use Illuminate\Contracts\Queue\ShouldQueue;

class DownloadExcel extends Action
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
         // Store on default disk
         $filename = "fabric_purchase_items_".time().".xlsx";
         Excel::store(new FabricPurchaseItemExport($models), $filename, 'local');

         return Action::redirect( route('dump-download', compact('filename')) );
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
