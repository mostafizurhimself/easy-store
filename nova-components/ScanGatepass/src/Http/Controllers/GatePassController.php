<?php

namespace Easystore\ScanGatepass\Http\Controllers;

use Carbon\Carbon;
use App\Enums\GatepassType;
use Illuminate\Support\Str;
use App\Models\GiftGatePass;
use Illuminate\Http\Request;
use App\Enums\GatePassStatus;
use App\Models\GoodsGatePass;
use App\Models\ManualGatePass;
use App\Models\VisitorGatePass;
use App\Models\EmployeeGatePass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GatePassController extends Controller
{
    /**
     * Get the gate pass
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'pass' => 'required|string'
        ]);

        return $this->getGatePass($request->pass);
    }

    /**
     * Pass the gate pass
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $this->validate($request, [
            'pass' => 'required|string'
        ]);

        $model = $this->getGatePass($request->pass);
        if ($model) {
            if ($model->type != GatepassType::EMPLOYEE() && $model->status == GatePassStatus::CONFIRMED()) {
                $model->passedAt = Carbon::now();
                $model->passedBy = Auth::user()->id;
                $model->status   = GatePassStatus::PASSED();
                $model->save();

                return response()->json([
                    'message' => "Gatepass passed successfully",
                    'data'    => $model
                ]);
            } elseif ($model->type == GatepassType::EMPLOYEE() && $model->status == GatePassStatus::CONFIRMED()) {
                $model->passedAt = Carbon::now();
                $model->out      = Carbon::now();
                $model->passedBy = Auth::user()->id;
                $model->status   = GatePassStatus::PASSED();
                $model->save();

                return response()->json([
                    'message' => "Gatepass passed successfully",
                    'data'    => $model
                ]);
            } elseif ($model->type == GatepassType::EMPLOYEE() && $model->status == GatePassStatus::PASSED() && empty($model->in)) {
                $model->in     = Carbon::now();
                $model->save();

                return response()->json([
                    'message' => "Checked in successfully",
                    'data'    => $model
                ]);
            } else {
                return response()->json([
                    'message' => "Not permitted now",
                ], 404);
            }
        }
    }

    /**
     * Get the gate pass from given readable id
     *
     * @return mixed
     */
    public function getGatePass($pass)
    {
        $result = collect();
        // Check manual gate pass
        if (Str::startsWith($pass, ManualGatePass::readableIdPrefix())) {
            if (request()->user()->isSuperAdmin()) {
                $result = ManualGatePass::where('readable_id', $pass)->first();
            } else {
                $result = ManualGatePass::where('readable_id', $pass)->where('location_id', request()->user()->locationId)->first();
            }
        }

        // Check goods gate pass
        if (Str::startsWith($pass, GoodsGatePass::readableIdPrefix())) {
            if (request()->user()->isSuperAdmin()) {
                $result = GoodsGatePass::where('readable_id', $pass)->first();
            } else {
                $result = GoodsGatePass::where('readable_id', $pass)->where('location_id', request()->user()->locationId)->first();
            }
        }

        // Check visitior pass
        if (Str::startsWith($pass, VisitorGatePass::readableIdPrefix())) {
            if (request()->user()->isSuperAdmin()) {
                $result = VisitorGatePass::where('readable_id', $pass)->first();
            } else {
                $result = VisitorGatePass::where('readable_id', $pass)->where('location_id', request()->user()->locationId)->first();
            }
        }

        // Check employee gate pass
        if (Str::startsWith($pass, EmployeeGatePass::readableIdPrefix())) {
            if (request()->user()->isSuperAdmin()) {
                $result = EmployeeGatePass::where('readable_id', $pass)->first();
            } else {
                $result = EmployeeGatePass::where('readable_id', $pass)->where('location_id', request()->user()->locationId)->first();
            }
        }

        // Check gift gate pass
        if (Str::startsWith($pass, GiftGatePass::readableIdPrefix())) {
            if (request()->user()->isSuperAdmin()) {
                $result = GiftGatePass::where('readable_id', $pass)->first();
            } else {
                $result = GiftGatePass::where('readable_id', $pass)->where('location_id', request()->user()->locationId)->first();
            }
        }


        if ($result->count()) {
            return $result;
        }


        return response()->json([
            'message' => "No data found",
        ], 404);
    }
}