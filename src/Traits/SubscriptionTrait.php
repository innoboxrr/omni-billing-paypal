<?php

namespace Innoboxrr\OmniBillingPaypal\Traits;

use Illuminate\Support\Facades\Http;
use Innoboxrr\OmniBillingPaypal\Responses\SubscriptionResponse;

trait SubscriptionTrait
{

    public function createSubscription(array $data): SubscriptionResponse
    {
        if (empty($data['paypal_plan_id'])) {
            throw new \Exception('Missing required PayPal plan_id in createSubscription()');
        }

        $payload = [
            'plan_id' => $data['paypal_plan_id'],
            'start_time' => $data['start_time'] ?? now()->toISOString(),
            'subscriber' => [
                'name' => [
                    'given_name' => $data['user_name'] ?? 'Guest',
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
        ];

        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getUrl('/v1/billing/subscriptions'), $payload);

        if ($response->failed()) {
            throw new \Exception('Failed to create PayPal subscription: ' . json_encode($response->json()));
        }

        return new SubscriptionResponse($response->json());
    }

    public function cancelSubscription($subscriptionId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getUrl("/v1/billing/subscriptions/{$subscriptionId}/cancel"), [
                'reason' => 'Cancelled by user'
            ]);

        if ($response->failed()) {
            throw new \Exception("Failed to cancel PayPal subscription: " . json_encode($response->json()));
        }

        return true;
    }

    public function pauseSubscription($subscriptionId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getUrl("/v1/billing/subscriptions/{$subscriptionId}/suspend"), [
                'reason' => 'Paused by system'
            ]);

        if ($response->failed()) {
            throw new \Exception("Failed to pause PayPal subscription: " . json_encode($response->json()));
        }

        return true;
    }

    public function resumeSubscription($subscriptionId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getUrl("/v1/billing/subscriptions/{$subscriptionId}/activate"), [
                'reason' => 'Resumed by system'
            ]);

        if ($response->failed()) {
            throw new \Exception("Failed to resume PayPal subscription: " . json_encode($response->json()));
        }

        return true;
    }

    public function updateSubscriptionPlan($subscriptionId, $newPlan)
    {
        throw new \Exception("PayPal does not support changing the plan_id of an existing subscription. You must cancel and create a new one.");
    }

    public function getSubscriptionDetails($subscriptionId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->getUrl("/v1/billing/subscriptions/{$subscriptionId}"));

        if ($response->failed()) {
            throw new \Exception("Failed to fetch PayPal subscription details: " . json_encode($response->json()));
        }

        return $response->json();
    }

}