<?php

namespace App\Providers;

use App\Models\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        URL::forceScheme('https');
        // dd(\App\Models\Fabric::select('id', 'name')
        //     ->where('status', 'active')
        //     ->select('code', 'quantity')
        //     ->toSql());
    }
}
