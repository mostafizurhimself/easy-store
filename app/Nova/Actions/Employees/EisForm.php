<?php

namespace App\Nova\Actions\Employees;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EisForm extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = "EIS Form";

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
        // return Action::openInNewTab(route('employees.eis-form', $models->first()->id));
        $employee = $models->first();
        $filename = "eis_form_{$employee->readableId}.pdf";

        ini_set("pcre.backtrack_limit", "10000000000");
        $pdf = \PDF::loadView('others.eis-form', compact('employee'), [], [
            'mode' => 'utf-8',
        ]);
        $pdf->save(Storage::path($filename));

        return Action::redirect(route('dump-download', compact('filename')));
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