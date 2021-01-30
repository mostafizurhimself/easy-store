<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\License;
use App\Enums\LicenseStatus;

class VerifyLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $license = License::first();

        if($license->expiration_date->lessThan(Carbon::now())){
            $license->status = LicenseStatus::INACTIVE();
            $license->save();
        }

        if($license->expiration_date->lessThan(Carbon::now()) || ($license->expiration_date->greaterThan(Carbon::now()) && $license->status == LicenseStatus::INACTIVE())){
            return redirect('/license');
        }

        return $next($request);
    }
}
