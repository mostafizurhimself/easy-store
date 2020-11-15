<?php

namespace App\Helpers;

use App\Models\Setting;

class Settings
{
    /**
     * Get the application name
     *
     * @return object
     */
    public function application()
    {
        return json_decode(Setting::where('name', Setting::APPLICATION_SETTINGS)->first()->settings);
    }

    /**
     * Get the company details
     *
     * @return object
     */
    public function company()
    {
        return json_decode(Setting::where('name', Setting::COMPANY_SETTINGS)->first()->settings);
    }

    /**
     * Get the company logo
     *
     * @return object
     */
    public function companyLogo()
    {
        return Setting::where('name', Setting::COMPANY_SETTINGS)->first()->getMedia('settings')->first() ? Setting::where('name', Setting::COMPANY_SETTINGS)->first()->getMedia('settings')->first()->getUrl()  : null;
    }

     /**
     * Get the application logo
     *
     * @return object
     */
    public function applicationLogo()
    {
        return Setting::where('name', Setting::APPLICATION_SETTINGS)->first()->getMedia('settings')->first() ? Setting::where('name', Setting::APPLICATION_SETTINGS)->first()->getMedia('settings')->first()->getUrl() : null;
    }

    /**
     * Get the prefix settings
     *
     * @return object
     */
    public function prefix()
    {
        return json_decode(Setting::where('name', Setting::PREFIX_SETTINGS)->first()->settings);
    }

    /**
     * Get the approver employees
     *
     * @return array
     */
    public function approvers()
    {
        return !empty(self::application()->approvers) ? self::application()->approvers : [];
    }

    /**
     * Get the super admin notification settings
     *
     * @return array
     */
    public function superAdminNotification()
    {
        return !empty(self::application()->super_admin_notification) ? self::application()->super_admin_notification : false;
    }

    /**
     * Get the max invoice item
     *
     * @return float
     */
    public function maxInvoiceItem()
    {
        return !empty(self::application()->max_invoice_item) ? self::application()->max_invoice_item : -1;
    }

    /**
     * Get the product module settings
     *
     * @return array
     */
    public function isProductModuleEnabled()
    {
        return !empty(self::application()->enable_product_module) ? self::application()->enable_product_module : false;
    }
}
