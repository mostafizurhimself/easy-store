<?php

namespace App\Nova\Actions\AssetConsumes;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Discard extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

     /**
     * Return the CSS classes for the Action.
     *
     * @return string
     */
    public function actionClass()
    {
        return 'btn-danger';
    }

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
            // Increate belongs to asset quantity
            $model->asset->increment('quantity', $model->quantity);
            // Force delete the model
            $model->forceDelete();
        }

        return Action::message('Asset Consume discard successfully.');
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
