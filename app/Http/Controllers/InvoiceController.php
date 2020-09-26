<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\DistributionStatus;
use App\Http\Controllers\Controller;
use App\Models\AssetDistributionInvoice;

class InvoiceController extends Controller
{
    /**
     * Generate Asset Distribution Invoice
     *
     * @return mixed
     */
    public function assetDistributionInvoice(Request $request, AssetDistributionInvoice $invoice)
    {
        if($request->user()->hasPermissionTo('can generate asset distribution invoices') && $invoice->status == DistributionStatus::CONFIRMED()){

            return view('invoices.pages.distribution', compact('invoice'));
        }else{
            abort(403);
        }
    }
}
