<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Facades\Helper;
use App\Enums\PurchaseStatus;
use App\Models\PurchaseFabric;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        Validator::extend('url', function($attribute, $value, $parameters, $validator) {
            $url = $parameters[0];

            if (filter_var($url, FILTER_VALIDATE_URL)) {
                return true;
            }
        });

        Validator::replacer('url', function($message, $attribute, $rule, $parameters) {
            return str_replace(':field', Str::title(str_replace('_', ' ', $attribute)), ':field shold be valid URL.');
        });

        // Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
        //     return preg_match('regex:/^(+8801)[1,5,6,7,8,9,3,4]{1}[0-9]{8}$/', $value);
        // });

        // Validator::replacer('phone', function($message, $attribute, $rule, $parameters) {
        //         return 'Invalid phone number';
        // });

        // dd(PurchaseFabric::find(4)->purchaseItems()->pluck('status')->unique()->first());
    }
}
