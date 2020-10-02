<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\DistributionStatus;
use App\Http\Controllers\Controller;
use App\Models\AssetDistributionInvoice;

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
        if($request->user()->hasPermissionTo('can generate asset distribution invoices') && $invoice->status != DistributionStatus::DRAFT()){

            return view('invoices.pages.asset-distribution-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }
}
