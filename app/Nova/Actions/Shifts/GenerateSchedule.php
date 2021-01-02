<?php

namespace App\Nova\Actions\Shifts;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateSchedule extends Action
{
    use InteractsWithQueue, Queueable;

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
        foreach ($models as $model) {
            return Action::openInNewTab(route('generate.schedule', [$model->id, 'start' => $fields->start, 'end' => $fields->end]));
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Date::make('Start')
                ->default(Carbon::now())
                ->rules('required'),

            Date::make('End')
                ->default(Carbon::now()->endOfYear())
                ->rules('required'),
        ];
    }
}
