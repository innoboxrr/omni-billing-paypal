<?php

namespace Innoboxrr\OmniBillingPaypal\Responses;

use Innoboxrr\OmniBilling\Contracts\CustomerResponseInterface;
use Innoboxrr\OmniBilling\Common\Responses\BaseCustomerResponse;

class CustomerResponse extends BaseCustomerResponse implements CustomerResponseInterface
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
     * ID de la respuesta
     * 
     * @return string
     */
    public function getId(): string
    {
        return $this->data['id'];
    }

}