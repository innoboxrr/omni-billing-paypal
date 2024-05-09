<?php

namespace Innoboxrr\OmniBillingPaypal\Traits;

use Illuminate\Support\Facades\Http;
use Innoboxrr\OmniBillingPaypal\Responses\PaymentResponse;

trait PaymentTrait 
{
    public function charge(array $data) : PaymentResponse
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getUrl('/v2/checkout/orders'), [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $data['id'],
                        'amount' => [
                            'currency_code' => mb_strtoupper($data['currency']),
                            'value' => $data['amount'],
                        ],
                    ],
                ],
                'payment_source' => [
                    'paypal' => [
                        'experience_context' => [
                            'user_action' => 'PAY_NOW',
                            'return_url' => $this->getSuccessRedirect($data),
                            'cancel_url' => $this->cancelRedirect . '?id=' . $data['id'],
                        ],
                    ],
                ],
            ]);
        if ($response->failed()) {
            throw new \Exception('Failed to create order');
        }
        return new PaymentResponse($response->json());
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