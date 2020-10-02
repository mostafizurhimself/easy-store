<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\PurchaseStatus;
use App\Models\AssetPurchaseOrder;
use App\Models\FabricPurchaseOrder;
use App\Http\Controllers\Controller;
use App\Models\MaterialPurchaseOrder;

class PurchaseOrderController extends Controller
{

    /**
     * Generates purchase order for fabric.
     *
     * @param  \Illuminate\Http\Request        $request
     * @param  \App\Models\FabricPurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function fabricPurchaseOrder(Request $request, FabricPurchaseOrder $purchaseOrder)
    {
        if($request->user()->hasPermissionTo('can generate fabric purchase orders') && $purchaseOrder->status != PurchaseStatus::DRAFT()){

            return view('purchase-orders.pages.fabric-purchase-order', compact('purchaseOrder'));
        }else{
            abort(403);
        }
    }

    /**
     * Generates purchase order for material.
     *
     * @param  \Illuminate\Http\Request          $request
     * @param  \App\Models\MaterialPurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function materialPurchaseOrder(Request $request, MaterialPurchaseOrder $purchaseOrder)
    {
        if($request->user()->hasPermissionTo('can generate material purchase orders') && $purchaseOrder->status != PurchaseStatus::DRAFT()){

            return view('purchase-orders.pages.material-purchase-order', compact('purchaseOrder'));
        }else{
            abort(403);
        }
    }


    /**
     * Generates purchase order for asset.
     *
     * @param  \Illuminate\Http\Request       $request
     * @param  \App\Models\AssetPurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function assetPurchaseOrder(Request $request, AssetPurchaseOrder $purchaseOrder)
    {
        if($request->user()->hasPermissionTo('can generate asset purchase orders') && $purchaseOrder->status != PurchaseStatus::DRAFT()){

            return view('purchase-orders.pages.asset-purchase-order', compact('purchaseOrder'));
        }else{
            abort(403);
        }
    }


}
