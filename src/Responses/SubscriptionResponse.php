<?php

namespace Innoboxrr\OmniBillingPaypal\Responses;

use Innoboxrr\OmniBilling\Contracts\SubscriptionResponseInterface;
use Innoboxrr\OmniBilling\Common\Responses\BaseSubscriptionResponse;

class SubscriptionResponse extends BaseSubscriptionResponse implements SubscriptionResponseInterface
{

    public function __construct($data)
    {
        parent::__construct($data);
    }

    /**
     * Datos de la respuesta
     * 
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * URL de redirección de pago
     * 
     * @return string
     */
    public function getRedirectUrl(): string
    {
        foreach ($this->links as $link) {
            if ($link['rel'] === 'payer-action') {
                return $link['href'];  
            }
        }
        return null; 
    }

    /**
     * Estado de la transacción
     * 
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

}