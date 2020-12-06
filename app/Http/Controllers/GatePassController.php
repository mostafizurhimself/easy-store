<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\GatePassStatus;
use App\Models\GoodsGatePass;
use App\Http\Controllers\Controller;

class GatePassController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GoodsGatePass  $pass
     * @return \Illuminate\Http\Response
     */
    public function goods(Request $request, GoodsGatePass $pass)
    {
        if($request->user()->hasPermissionTo('can generate goods gate passes') && $pass->status != GatePassStatus::DRAFT()){
            return view('passes.pages.goods', compact('pass'));
        }else{
            abort(403);
        }
    }
}
