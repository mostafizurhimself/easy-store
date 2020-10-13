<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FabricExport implements FromView
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
        return view('excel.pages.fabrics')->with('models', $this->models);
    }
}
