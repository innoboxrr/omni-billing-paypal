<?php

namespace Innoboxrr\OmniBillingPaypal\Traits;

use Innoboxrr\OmniBillingPaypal\Responses\SubscriptionResponse;

trait SubscriptionTrait
{
    public function createSubscription(array $data): SubscriptionResponse
    {
        dd('Paypal: createSubscription');
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