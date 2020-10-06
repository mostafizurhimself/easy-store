<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FabricPurchaseItem;
use App\Http\Controllers\Controller;

class PdfController extends Controller
{

    /**
     * Generate fabric purchase items pdf report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fabricPurchaseItem($models)
    {
        dd($models);
    }
}
