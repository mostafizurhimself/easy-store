<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\ActiveStatus;
use Illuminate\Support\Facades\Auth;

class ActiveUser
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
        if(Auth::user()->status == ActiveStatus::ACTIVE()){
            return $next($request);
        }

        abort(403);
    }
}
