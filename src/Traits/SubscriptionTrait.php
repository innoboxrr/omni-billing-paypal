<?php

namespace Innoboxrr\OmniBillingPaypal\Traits;

trait SubscriptionTrait
{
    public function createSubscription($customer, $plan)
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