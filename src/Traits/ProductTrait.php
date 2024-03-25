<?php

namespace Innoboxrr\OmniBillingPaypal\Traits;

trait ProductTrait 
{
    public function createProduct(array $data)
    {
        dd('Paypal: createProduct');
    }

    public function getProduct($productId)
    {
        dd('Paypal: getProduct');
    }

    public function updateProduct($productId, array $data)
    {
        dd('Paypal: updateProduct');
    }

    public function deleteProduct($productId)
    {
        dd('Paypal: deleteProduct');
    }
}