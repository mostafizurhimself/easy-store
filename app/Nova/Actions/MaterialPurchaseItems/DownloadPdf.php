<?php

namespace App\Nova\Actions\MaterialPurchaseItems;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Facades\Storage;
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
        $filename = "material_purchase_items_".time().".pdf";
        $subtitle = $fields->subtitle;

        $pdf = \PDF::loadView('pdf.pages.material-purchase-items', compact('models', 'subtitle'), [], [
            'mode' => 'utf-8',
            'orientation' => 'L'
        ]);
        $pdf->save(Storage::path($filename));

        return Action::redirect( route('dump-download', compact('filename')) );
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('Subtitle', 'subtitle')
            ->rules('nullable', 'string', 'max:100')
        ];
    }
}
