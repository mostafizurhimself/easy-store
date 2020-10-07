<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\RequisitionStatus;
use App\Models\AssetRequisition;
use App\Models\ProductRequisition;
use App\Http\Controllers\Controller;

class RequisitionController extends Controller
{
    /**
     * Generate asset requisitions
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  \App\Models\AssetRequisition $requisition
     * @return \Illuminate\Http\Response
     */
    public function assetRequisition(Request $request, AssetRequisition $requisition )
    {
        if($request->user()->hasPermissionTo('can generate asset requisitions') && $requisition->status != RequisitionStatus::DRAFT()){

            return view('requisitions.pages.asset-requisition', compact('requisition'));
        }else{
            abort(403);
        }
    }

    /**
     * Generate product requisitions
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  \App\Models\ProductRequisition $requisition
     * @return \Illuminate\Http\Response
     */
    public function productRequisition(Request $request, ProductRequisition $requisition )
    {
        if($request->user()->hasPermissionTo('can generate product requisitions') && $requisition->status != RequisitionStatus::DRAFT()){

            return view('requisitions.pages.product-requisition', compact('requisition'));
        }else{
            abort(403);
        }
    }
}
