<?php

namespace Innoboxrr\OmniBillingPaypal\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

trait PaymentTrait 
{
    public function charge(array $data)
    {
        // Validar data
            // ...

        // Normalizar data
            // ...

        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getUrl('/v2/checkout/orders'), $data);

            dd($response->json());

        if ($response->failed()) {
            throw new \Exception('Failed to create order');
        }

        dd($response->json());
    }

    public function refund($transactionId, $amount = null)
    {
        dd('Paypal: refund');
    }

    public function authorize(array $data)
    {
        dd('Paypal: authorize');
    }

    public function capture($authorizationId, $amount = null)
    {
        dd('Paypal: capture');
    }

    public function void($authorizationId)
    {
        dd('Paypal: void');
    }

}