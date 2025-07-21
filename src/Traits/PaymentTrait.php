<?php

namespace Innoboxrr\OmniBillingPaypal\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
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
                            'landing_page' => 'GUEST_CHECKOUT',
                            'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                            'user_action' => 'PAY_NOW',
                            'return_url' => $this->getSuccessRedirect($data),
                            'cancel_url' => $this->getCancelRedirect($data),
                        ],
                        'email_address' => $data['email'],
                        'name' => [
                            'given_name' => $data['user_name'],
                        ]
                    ],
                ],
            ]);
        if ($response->failed()) {
            throw new \Exception('Failed to create order');
        }
        return new PaymentResponse($response->json());
    }

    public function verify($transaction) : bool
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->getUrl('/v2/checkout/orders/' . $transaction['id']));

        if ($response->failed()) {
            throw new \Exception('Failed to verify order');
        }
        
        $response = new PaymentResponse($response->json());

        if ($response->getStatus() === 'COMPLETED') {
            return true;
        }

        if ($response->getStatus() === 'APPROVED') {
            $captureResponse = $this->capturePayment($transaction['id']);
            if ($captureResponse->getStatus() === 'COMPLETED') {
                return true;
            }
        }

        return false;
    }

    private function capturePayment($orderId)
    {
        // Inicializa cURL
        $curl = curl_init();

        $headers = [
            "Content-Type: application/json",
            "PayPal-Request-Id: " . Str::uuid()->toString(),
            "Authorization: Bearer " . $this->token,
        ];

        // Configura las opciones de cURL
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->getUrl("/v2/checkout/orders/{$orderId}/capture"),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{}',  // Enviar un cuerpo vacío
            CURLOPT_HTTPHEADER => $headers,  // Utilizar los encabezados generados
        ]);

        // Ejecuta la solicitud cURL
        $response = curl_exec($curl);
        $err = curl_error($curl);

        // Cierra la sesión cURL
        curl_close($curl);

        // Manejo de errores
        if ($err) {
            throw new \Exception('cURL Error: ' . $err);
        } else {
            // Convertir la respuesta JSON a un arreglo
            $response = json_decode($response, true);
            // Retornar la respuesta
            return new PaymentResponse($response);
        }
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