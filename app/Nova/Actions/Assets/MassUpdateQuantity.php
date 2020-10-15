<?php

namespace App\Nova\Actions\Assets;

use App\Imports\AssetQuantityUpdate;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MassUpdateQuantity extends Action
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
        Excel::import(new AssetQuantityUpdate, $fields->file);

        return Action::message('Asset quantity updated successfully.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            File::make('File')
                ->rules('required'),
        ];
    }
}
