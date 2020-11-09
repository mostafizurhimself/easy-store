<?php

namespace App\Nova\Actions\AssetReceiveItems;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Nova\Fields\ActionFields;
use App\Exports\AssetReceiveItemExport;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DownloadExcel extends Action
{
    use InteractsWithQueue, Queueable;

        /**
     * The number of models that should be included in each chunk.
     *
     * @var int
     */
    public static $chunkCount = 200000000;

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
         // Store on default disk
         $filename = "asset_receive_items_".time().".xlsx";
         Excel::store(new AssetReceiveItemExport($models), $filename, 'local');

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
