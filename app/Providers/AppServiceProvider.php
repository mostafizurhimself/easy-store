<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Model;
use App\Facades\Helper;
use Illuminate\Support\Str;
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
            return str_replace(':field', str_replace('_', ' ', $attribute), 'The :field should be valid URL.');
        });

        Validator::extend('space', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });

        Validator::replacer('space', function($message, $attribute, $rule, $parameters) {
            return str_replace(':field', str_replace('_', ' ', $attribute), 'The :field should not have white space.');
        });
    }
}
