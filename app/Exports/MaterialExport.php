<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MaterialExport implements FromView
{
    /**
     * @var collection
     */
    public $models;

    /**
     * Set the models
     *
     * @return void
     */
    public function __construct($models)
    {
        $this->models = $models;
    }

    /**
    * @return \Illuminate\Contracts\View\View;
    */
    public function view(): View
    {
        return view('excel.pages.materials')->with('models', $this->models);
    }
}
