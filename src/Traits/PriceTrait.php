<?php

/**
 * In the context of PayPal, the price is the same as the Plan.
 */

namespace Innoboxrr\OmniBillingPaypal\Traits;

trait PriceTrait 
{
    public function createPrice(array $data)
    {
        dd('Paypal: createPrice');
    }

    public function getPrice($priceId)
    {
        dd('Paypal: getPrice');
    }

    public function updatePrice($priceId, array $data)
    {
        dd('Paypal: updatePrice');
    }

    public function deletePrice($priceId)
    {
        dd('Paypal: deletePrice');
    }
}