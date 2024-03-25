<?php

namespace Innoboxrr\OmniBillingPaypal\Traits;

trait CustomerTrait 
{
    public function createCustomer(array $customerData)
    {
        dd('Paypal: createCustomer');
    }

    public function addPaymentMethodToCustomer($customerId, array $paymentMethod)
    {
        dd('Paypal: addPaymentMethodToCustomer');
    }


}