<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EisFormController extends Controller
{
    /**
     * Generate employee eis form
     * 
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Employee $employee)
    {
        if ($request->user()->hasPermissionTo('can download employees')) {
            return view('others.eis-form')->with('employee', $employee);
        }
        return abort(403);
    }
}