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
    public function index(Employee $employee)
    {
        return view('others.eis-form')->with('employee', $employee);
    }
}