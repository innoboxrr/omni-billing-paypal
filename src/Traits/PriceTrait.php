<?php

/**
 * In the context of PayPal, the price is the same as the Plan.
 */

namespace Innoboxrr\OmniBillingPaypal\Traits;

use Illuminate\Support\Facades\Http;

trait PriceTrait 
{
    public function createPrice(array $data)
    {
        $billingCycles = [];

        // Plan regular
        $billingCycles[] = [
            'frequency' => [
                'interval_unit' => mb_strtoupper($data['interval']),
                'interval_count' => $data['interval_count'],
            ],
            'tenure_type' => 'REGULAR',
            'sequence' => 2,
            'total_cycles' => 0,
            'pricing_scheme' => [
                'fixed_price' => [
                    'value' => number_format($data['amount'], 2, '.', ''),
                    'currency_code' => mb_strtoupper($data['currency']),
                ],
            ],
        ];

        // Plan de prueba (opcional)
        if (!empty($data['free_trial']) && !empty($data['trial_days'])) {
            $billingCycles[] = [
                'frequency' => [
                    'interval_unit' => 'DAY',
                    'interval_count' => $data['trial_days'],
                ],
                'tenure_type' => 'TRIAL',
                'sequence' => 1,
                'total_cycles' => 1,
                'pricing_scheme' => [
                    'fixed_price' => [
                        'value' => '0',
                        'currency_code' => mb_strtoupper($data['currency']),
                    ],
                ],
            ];
        }

        $payload = [
            'product_id' => $data['product_id'],
            'name' => $data['name'],
            'billing_cycles' => $billingCycles,
            'payment_preferences' => [
                'auto_bill_outstanding' => true,
                'setup_fee_failure_action' => 'CONTINUE',
                'payment_failure_threshold' => 3,
            ],
        ];

        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getUrl('/v1/billing/plans'), $payload);

        if ($response->failed()) {
            throw new \Exception('Failed to create PayPal plan (price): ' . json_encode($response->json()));
        }

        return (object) $response->json();
    }

    public function getPrice(string $priceId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->getUrl("/v1/billing/plans/{$priceId}"));

        if ($response->failed()) {
            throw new \Exception('Failed to fetch PayPal plan (price): ' . json_encode($response->json()));
        }

        return (object) $response->json();
    }

    public function updatePrice(string $priceId, array $data)
    {
        $patchData = [];

        if (isset($data['name'])) {
            $patchData[] = [
                'op' => 'replace',
                'path' => '/name',
                'value' => $data['name'],
            ];
        }

        $response = Http::withHeaders($this->getHeaders())
            ->patch($this->getUrl("/v1/billing/plans/{$priceId}"), $patchData);

        if ($response->failed()) {
            throw new \Exception('Failed to update PayPal plan (price): ' . json_encode($response->json()));
        }

        return true;
    }

    public function deletePrice(string $priceId)
    {
        // PayPal no permite eliminar planes, solo inactivarlos
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getUrl("/v1/billing/plans/{$priceId}/deactivate"));

        if ($response->failed()) {
            throw new \Exception('Failed to deactivate PayPal plan (price): ' . json_encode($response->json()));
        }

        return true;
    }
}
