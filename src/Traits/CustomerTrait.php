<?php

namespace Innoboxrr\OmniBillingPaypal\Traits;

trait CustomerTrait 
{
    public function createCustomer(array $customerData)
    {
        abort(404, 'Method not implemented');
    }

    public function addPaymentMethodToCustomer($customerId, array $paymentMethod)
    {
        abort(404, 'Method not implemented');
    }


}