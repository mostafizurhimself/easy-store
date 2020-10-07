<?php

namespace App\Nova\Actions\AssetReceiveItems;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

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
        $filename = "asset_receive_items_".time().".pdf";
        $subtitle = $fields->subtitle;

        $pdf = \PDF::loadView('pdf.pages.asset-receive-items', compact('models', 'subtitle'), [], [
            'mode' => 'utf-8',
            'orientation' => 'L'
        ]);
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
        return [
            Text::make('Subtitle', 'subtitle')
                ->rules('nullable', 'string', 'max:100')
        ];
    }
}
