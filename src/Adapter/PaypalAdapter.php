<?php

namespace Innoboxrr\OmniBillingPaypal\Adapter;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Innoboxrr\OmniBilling\Common\Adapter;
use Innoboxrr\OmniBilling\Contracts\{
    ProductInterface,
    PriceInterface,
    CustomerInterface,
    PaymentInterface,
    SubscriptionInterface,
};

use Innoboxrr\OmniBillingPaypal\Traits\{
    ProductTrait,
    PriceTrait,
    CustomerTrait,
    PaymentTrait,
    SubscriptionTrait,
};

class PaypalAdapter extends Adapter implements ProductInterface, PriceInterface, CustomerInterface, PaymentInterface, SubscriptionInterface
{
    use ProductTrait,
        PriceTrait,
        CustomerTrait,
        PaymentTrait,
        SubscriptionTrait;

    protected $paypal;

    protected $token;
    
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    protected function setUp($config = [])
    {   
        parent::setUp($config);

        $this->authenticate();
    }

    protected function authenticate(): void
    {
        $response = Http::withBasicAuth($this->public, $this->secret)
                ->asForm()
                ->post($this->getUrl('/v1/oauth2/token'), [
                    'grant_type' => 'client_credentials',
                ]);

        $accessToken = $response->json()['access_token'];

        $this->token = $accessToken;
    }

    protected function getHeaders($excludeHeaders = []): array
    {
        $baseHeaders = [
            'Content-Type' => 'application/json',
            'PayPal-Request-Id' => Str::uuid()->toString(),
            'Authorization' => 'Bearer ' . $this->token,
        ];

        // Remove excluded headers
        foreach ($excludeHeaders as $header) {
            unset($baseHeaders[$header]);
        }

        return $baseHeaders;
    }

}