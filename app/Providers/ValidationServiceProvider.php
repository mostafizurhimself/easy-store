<?php

namespace App\Providers;

use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //Url validation
        Validator::extend('url', function ($attribute, $value, $parameters, $validator) {
            $url = $parameters[0];

            if (filter_var($url, FILTER_VALIDATE_URL)) {
                return true;
            }
        });

        Validator::replacer('url', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', str_replace('_', ' ', $attribute), 'The :field should be valid URL.');
        });

        //White Space Validation
        Validator::extend('space', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        Validator::replacer('space', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', str_replace('_', ' ', $attribute), 'The :field should not have white space.');
        });

        //Letter and space validation
        Validator::extend('alpha_space', function ($attr, $value) {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::replacer('alpha_space', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', str_replace('_', ' ', $attribute), 'The :field may only contain letters and white space.');
        });

        // Multiple white space validation
        Validator::extend('multi_space', function ($attr, $value) {
            return !preg_match('/[\s]{2,}/u', $value);
        });

        Validator::replacer('multi_space', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', str_replace('_', ' ', $attribute), 'The :field may not contain multiple spaces.');
        });

        // Letter, numbers and space validation
        Validator::extend('alpha_num_space', function ($attr, $value) {
            return preg_match('/^[\pL\d\s]+$/u', $value);
        });

        Validator::replacer('alpha_num_space', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', str_replace('_', ' ', $attribute), 'The :field may only contain letters, numbers and white space.');
        });

        // Super Admin Role Validation
        Validator::extend('super_admin', function ($attr, $value) {
            return Str::kebab($value) != Role::SUPER_ADMIN;
        });

        Validator::replacer('super_admin', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', str_replace('_', ' ', $attribute), 'You are not allowed to use this name.');
        });
    }
}