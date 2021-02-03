<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Facades\Settings;

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
        if(Settings::isLicenseEnabled() || $request->user()->isSystemAdmin()){
            return $next($request);
        }

        return abort(403, 'License Expired');
    }
}
