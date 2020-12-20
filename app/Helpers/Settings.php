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
     * Get the gate pass approvers
     *
     * @return array
     */
    public function gatePassApprovers()
    {
        return !empty(self::application()->gate_pass_approvers) ? self::application()->gate_pass_approvers : [];
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
     * Get the expense module settings
     *
     * @return bool
     */
    public function isExpenseModuleEnabled()
    {
        return !empty(self::application()->enable_expense_module) ? self::application()->enable_expense_module : false;
    }

    /**
     * Get the gate pass module settings
     *
     * @return bool
     */
    public function isGatePassModuleEnabled()
    {
        return !empty(self::application()->enable_gate_pass_module) ? self::application()->enable_gate_pass_module : false;
    }

    /**
     * Get the vendor module settings
     *
     * @return bool
     */
    public function isVendorModuleEnabled()
    {
        return !empty(self::application()->enable_vendor_module) ? self::application()->enable_vendor_module : false;
    }

    /**
     * Get the product module settings
     *
     * @return bool
     */
    public function isProductModuleEnabled()
    {
        return !empty(self::application()->enable_product_module) ? self::application()->enable_product_module : false;
    }

    /**
     * Get the timesheet module settings
     *
     * @return bool
     */
    public function isTimesheetModuleEnabled()
    {
        return !empty(self::application()->enable_timesheet_module) ? self::application()->enable_timesheet_module : false;
    }

    /**
     * Get the payroll module settings
     *
     * @return bool
     */
    public function isPayrollModuleEnabled()
    {
        return !empty(self::application()->enable_payroll_module) ? self::application()->enable_payroll_module : false;
    }
}
