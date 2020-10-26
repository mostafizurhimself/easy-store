<?php

namespace App\Http\Controllers;

use App\Enums\ReturnStatus;
use Illuminate\Http\Request;
use App\Enums\DispatchStatus;
use App\Enums\TransferStatus;
use App\Models\ServiceInvoice;
use App\Enums\RequisitionStatus;
use App\Models\FinishingInvoice;
use App\Enums\DistributionStatus;
use App\Models\AssetReturnInvoice;
use App\Models\FabricDistribution;
use App\Models\FabricReturnInvoice;
use App\Http\Controllers\Controller;
use App\Models\FabricTransferInvoice;
use App\Models\MaterialReturnInvoice;
use App\Models\ServiceTransferInvoice;
use App\Models\MaterialTransferInvoice;
use App\Models\AssetDistributionInvoice;

class InvoiceController extends Controller
{

    /**
     * Generate service fabric return invoice
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\FabricReturnInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function fabricReturnInvoice(Request $request, FabricReturnInvoice $invoice )
    {
        if(($request->user()->hasPermissionTo('can generate fabric return invoices') || $request->user()->isSuperAdmin()) && $invoice->status != ReturnStatus::DRAFT()){

            return view('invoices.pages.fabric-return-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }

      /**
     * Generate service fabric return invoice
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\FabricTransferInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function fabricTransferInvoice(Request $request, FabricTransferInvoice $invoice )
    {
        if(($request->user()->hasPermissionTo('can generate fabric transfer invoices') || $request->user()->isSuperAdmin()) && $invoice->status != TransferStatus::DRAFT()){

            return view('invoices.pages.fabric-transfer-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }

       /**
     * Generate service finishing distribution invoice
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\FabricDistribution $invoice
     * @return \Illuminate\Http\Response
     */
    public function fabricDistributionInvoice(Request $request, FabricDistribution $invoice )
    {
        if($request->user()->hasPermissionTo('can generate fabric distributions') || $request->user()->isSuperAdmin()){

            return view('invoices.pages.fabric-distribution-invoice', compact('invoice'));
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
        if(($request->user()->hasPermissionTo('can generate material return invoices') || $request->user()->isSuperAdmin()) && $invoice->status != ReturnStatus::DRAFT()){

            return view('invoices.pages.material-return-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }

    /**
     * Generate service material return invoice
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\MaterialTransferInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function materialTransferInvoice(Request $request, MaterialTransferInvoice $invoice )
    {
        if(($request->user()->hasPermissionTo('can generate material transfer invoices') || $request->user()->isSuperAdmin()) && $invoice->status != TransferStatus::DRAFT()){

            return view('invoices.pages.material-transfer-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }

    /**
     * Generate asset distribution invoice
     *
     * @param  \Illuminate\Http\Request             $request
     * @param  \App\Models\AssetDistributionInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function assetDistributionInvoice(Request $request, AssetDistributionInvoice $invoice )
    {
        if(($request->user()->hasPermissionTo('can generate asset distribution invoices') || $request->user()->isSuperAdmin()) && $invoice->status != DistributionStatus::DRAFT()){

            return view('invoices.pages.asset-distribution-invoice', compact('invoice'));
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
        if(($request->user()->hasPermissionTo('can generate asset return invoices') || $request->user()->isSuperAdmin()) && $invoice->status != ReturnStatus::DRAFT()){

            return view('invoices.pages.asset-return-invoice', compact('invoice'));
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
        if(($request->user()->hasPermissionTo('can generate service invoices') || $request->user()->isSuperAdmin()) && $invoice->status != DispatchStatus::DRAFT()){

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
        if(($request->user()->hasPermissionTo('can generate service transfer invoices') || $request->user()->isSuperAdmin()) && $invoice->status != TransferStatus::DRAFT()){

            return view('invoices.pages.service-transfer-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }

    /**
     * Generate service finishing distribution invoice
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\FinishingInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function finishingInvoice(Request $request, FinishingInvoice $invoice )
    {
        if($request->user()->hasPermissionTo('can generate finishing invoices') || $request->user()->isSuperAdmin()){

            return view('invoices.pages.finishing-invoice', compact('invoice'));
        }else{
            abort(403);
        }
    }
}
