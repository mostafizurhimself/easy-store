<?php

namespace App\Http\Controllers;

use App\Enums\ReturnStatus;
use Illuminate\Http\Request;
use App\Enums\DispatchStatus;
use App\Enums\TransferStatus;
use App\Models\ServiceInvoice;
use App\Enums\RequisitionStatus;
use App\Enums\DistributionStatus;
use App\Models\AssetReturnInvoice;
use App\Models\FabricReturnInvoice;
use App\Http\Controllers\Controller;
use App\Models\MaterialReturnInvoice;
use App\Models\ServiceTransferInvoice;
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
    public function assetDistributionInvoice(Request $request, AssetDistributionInvoice $invoice )
    {
        if($request->user()->hasPermissionTo('can generate asset distribution invoices') && $invoice->status != DistributionStatus::DRAFT()){

            return view('invoices.pages.asset-distribution-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }

    /**
     * Generate service dispatch invoice
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\ServiceInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function serviceInvoice(Request $request, ServiceInvoice $invoice )
    {
        if($request->user()->hasPermissionTo('can generate service invoices') && $invoice->status != DispatchStatus::DRAFT()){

            return view('invoices.pages.service-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }

     /**
     * Generate service transfer invoice
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\ServiceTransferInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function serviceTransferInvoice(Request $request, ServiceTransferInvoice $invoice )
    {
        if($request->user()->hasPermissionTo('can generate service transfer invoices') && $invoice->status != TransferStatus::DRAFT()){

            return view('invoices.pages.service-transfer-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }

    /**
     * Generate service fabric return invoice
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\FabricReturnInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function fabricReturnInvoice(Request $request, FabricReturnInvoice $invoice )
    {
        if($request->user()->hasPermissionTo('can generate fabric return invoices') && $invoice->status != ReturnStatus::DRAFT()){

            return view('invoices.pages.fabric-return-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }

    /**
     * Generate service material return invoice
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\MaterialReturnInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function materialReturnInvoice(Request $request, MaterialReturnInvoice $invoice )
    {
        if($request->user()->hasPermissionTo('can generate material return invoices') && $invoice->status != ReturnStatus::DRAFT()){

            return view('invoices.pages.material-return-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }

    /**
     * Generate service asset return invoice
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\AssetReturnInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function assetReturnInvoice(Request $request, AssetReturnInvoice $invoice )
    {
        if($request->user()->hasPermissionTo('can generate asset return invoices') && $invoice->status != ReturnStatus::DRAFT()){

            return view('invoices.pages.asset-return-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }
}
