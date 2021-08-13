<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\GatePassStatus;
use App\Models\GoodsGatePass;
use App\Models\ManualGatePass;
use App\Models\VisitorGatePass;
use App\Models\EmployeeGatePass;
use App\Http\Controllers\Controller;
use App\Models\GiftGatePass;

class GatePassController extends Controller
{
    /**
     * Generate goods gate pass invoice
     *
     * @param  \App\Models\GiftGatePass  $pass
     * @return \Illuminate\Http\Response
     */
    public function gifts(Request $request, GiftGatePass $pass)
    {
        if ($request->user()->hasPermissionTo('can generate gift gate passes') && $pass->status != GatePassStatus::DRAFT()) {
            return view('passes.pages.gift', compact('pass'));
        } else {
            abort(403);
        }
    }

    /**
     * Generate goods gate pass invoice
     *
     * @param  \App\Models\GoodsGatePass  $pass
     * @return \Illuminate\Http\Response
     */
    public function goods(Request $request, GoodsGatePass $pass)
    {
        if ($request->user()->hasPermissionTo('can generate goods gate passes') && $pass->status != GatePassStatus::DRAFT()) {
            return view('passes.pages.goods', compact('pass'));
        } else {
            abort(403);
        }
    }

    /**
     * Generate employee gate pass invoice
     *
     * @param  \App\Models\EmployeeGatePass  $pass
     * @return \Illuminate\Http\Response
     */
    public function employee(Request $request, EmployeeGatePass $pass)
    {
        if ($request->user()->hasPermissionTo('can generate employee gate passes') && $pass->status != GatePassStatus::DRAFT()) {
            return view('passes.pages.employee', compact('pass'));
        } else {
            abort(403);
        }
    }

    /**
     * Generate employee gate pass invoice
     *
     * @param  \App\Models\ManualGatePass  $pass
     * @return \Illuminate\Http\Response
     */
    public function manual(Request $request, ManualGatePass $pass)
    {
        if ($request->user()->hasPermissionTo('can generate manual gate passes') && $pass->status != GatePassStatus::DRAFT()) {
            return view('passes.pages.manual', compact('pass'));
        } else {
            abort(403);
        }
    }

    /**
     * Generate visitor gate pass invoice
     *
     * @param  \App\Models\VisitorGatePass  $pass
     * @return \Illuminate\Http\Response
     */
    public function visitor(Request $request, VisitorGatePass $pass)
    {
        if ($request->user()->hasPermissionTo('can generate visitor gate passes') && $pass->status != GatePassStatus::DRAFT()) {
            return view('passes.pages.visitor', compact('pass'));
        } else {
            abort(403);
        }
    }
}