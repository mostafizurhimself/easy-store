<?php

namespace App\Enums;

class AddressType extends Enum
{
    private const LOCATION_ADDRESS  = 'location address';
    private const PRESENT_ADDRESS   = 'present address';
    private const PERMANENT_ADDRESS = 'permanent address';
    private const BILLING_ADDRESS   = 'billing address';
    private const SHIPMENT_ADDRESS  = 'shipment address';
    private const PAYMENT_ADDRESS   = 'payment address';
}
