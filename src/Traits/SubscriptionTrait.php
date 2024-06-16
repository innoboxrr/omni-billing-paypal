<?php

namespace Innoboxrr\OmniBillingPaypal\Traits;

use Illuminate\Support\Facades\Http;
use Innoboxrr\OmniBillingPaypal\Responses\SubscriptionResponse;

trait SubscriptionTrait
{
    public function createSubscription(array $data): SubscriptionResponse
    {
        $billingCycles = [
            [
                'frequency' => [
                    'interval_unit' => mb_strtoupper($data['recurring']['interval']), // DAY, WEEK, MONTH, YEAR
                    'interval_count' => $data['recurring']['interval_count']
                ],
                'tenure_type' => 'REGULAR',
                'sequence' => 1,
                'total_cycles' => 0,
                'pricing_scheme' => [
                    'fixed_price' => [
                        'value' => $data['amount'],
                        'currency_code' => mb_strtoupper($data['currency'])
                    ]
                ]
            ]
        ];

        if (!empty($data['recurring']['free_trial'])) {
            $trialDays = $data['recurring']['trial_days'];
            $billingCycles[] = [
                'frequency' => [
                    'interval_unit' => 'DAY',
                    'interval_count' => $trialDays
                ],
                'tenure_type' => 'TRIAL',
                'sequence' => 1,
                'total_cycles' => 1,
                'pricing_scheme' => [
                    'fixed_price' => [
                        'value' => 0,
                        'currency_code' => mb_strtoupper($data['currency'])
                    ]
                ]
            ];
        }

        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getUrl('/v1/billing/subscriptions'), [
                'plan' => [
                    'billing_cycles' => $billingCycles,
                    'payment_preferences' => [
                        'auto_bill_outstanding' => true,
                        'setup_fee_failure_action' => 'CONTINUE',
                        'payment_failure_threshold' => 3
                    ],
                ],
                'start_time' => $data['start_time'] ?? now()->toISOString(),
                'subscriber' => [
                    'name' => [
                        'given_name' => $data['user_name'],
                    ],
                    'email_address' => $data['email'],
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'locale' => 'en-US',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'SUBSCRIBE_NOW',
                    'payment_method' => [
                        'payer_selected' => 'PAYPAL',
                        'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
                    ],
                    'return_url' => $this->getSuccessRedirect($data),
                    'cancel_url' => $this->cancelRedirect . '?id=' . $data['id'],
                ],
            ]);

        if ($response->failed()) {
            throw new \Exception('Failed to create subscription');
        }

        return new SubscriptionResponse($response->json());
    }

    public function cancelSubscription($subscriptionId)
    {
        dd('Paypal: cancelSubscription');
    }

    public function pauseSubscription($subscriptionId)
    {
        dd('Paypal: pauseSubscription');
    }

    public function resumeSubscription($subscriptionId)
    {
        dd('Paypal: resumeSubscription');
    }

    public function updateSubscriptionPlan($subscriptionId, $newPlan)
    {
        dd('Paypal: updateSubscriptionPlan');
    }

    public function getSubscriptionDetails($subscriptionId)
    {
        dd('Paypal: getSubscriptionDetails');
    }

}