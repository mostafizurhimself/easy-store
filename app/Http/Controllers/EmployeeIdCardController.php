<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeIdCardController extends Controller
{
    /**
     * Generate the id cards
     */
    public function __invoke(Request $request)
    {
        if ($request->user()->hasPermissionTo('can download employees')) {
            $ids = decrypt($request->ids);
            return view('others.id-cards', [
                'employees' => Employee::with('designation', 'shift', 'location', 'section', 'media')->whereIn('id', explode(',', $ids))->get()
            ]);
        }
        return abort(403);
    }
}