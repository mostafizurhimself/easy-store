<?php

namespace App\Nova\Actions\FabricPurchaseItems;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DownloadPdf extends Action
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
        $filename = "fabric_purchase_order_".Carbon::now()->format('Y_m_d_h_i').".pdf";
        $pdf = \PDF::loadView('pdf.pages.fabric-purchase-items', compact('models'));
        $pdf->save(storage_path($filename));

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
