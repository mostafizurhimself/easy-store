<?php

namespace App\Nova\Actions\Assets;

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
        $filename = "assets.pdf";
        $subtitle = $fields->subtitle;

        ini_set("pcre.backtrack_limit", "10000000000");
        $pdf = \PDF::loadView('pdf.pages.assets', compact('models', 'subtitle'), [], [
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
        return [
            Text::make('Subtitle', 'subtitle')
                ->rules('nullable', 'string', 'max:100')
        ];
    }
}