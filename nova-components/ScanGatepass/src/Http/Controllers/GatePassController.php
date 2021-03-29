<?php

namespace Easystore\ScanGatepass\Http\Controllers;

use Carbon\Carbon;
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
        $model->passedAt = Carbon::now();
        $model->passedBy = Auth::user()->id;
        $model->status   = GatePassStatus::PASSED();
        $model->save();

        return response()->json([
            'message' => "Gatepass passed successfully",
            'data'    => $model
        ]);
    }

    /**
     * Get the gate pass from given readable id
     *
     * @return mixed
     */
    public function getGatePass($pass)
    {
        // Check manual gate pass
        $result = ManualGatePass::where('readable_id', $pass)->first();

        // Check goods gate pass
        if (empty($result)) {
            $result = GoodsGatePass::where('readable_id', $pass)->first();
        }

        // Check visitior pass
        if (empty($result)) {
            $result = VisitorGatePass::where('readable_id', $pass)->first();
        }

        // Check employee gate pass
        if (empty($result)) {
            $result = EmployeeGatePass::where('readable_id', $pass)->first();
        }

        return $result;
    }
}
