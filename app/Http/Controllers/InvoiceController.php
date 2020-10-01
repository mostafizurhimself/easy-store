<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AssetDistributionInvoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Generate asset distribution invoice
     *
     * @param  \Illuminate\Http\Request             $request
     * @param  \App\Models\AssetDistributionInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function assetDsitributionInvoice(Request $request, AssetDistributionInvoice $invoice )
    {
        return view('invoices.layout');
    }
}
