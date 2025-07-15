<?php

namespace Innoboxrr\OmniBillingPaypal\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait ProductTrait 
{
    public function createProduct(array $data)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getUrl('/v1/catalogs/products'), [
                'name' => $data['name'],
                'description' => $data['description'],
                'type' => $data['type'],       // Ej: SERVICE
                'category' => $data['category'] // Ej: SOFTWARE
            ]);

        if ($response->failed()) {
            throw new \Exception('Failed to create PayPal product: ' . json_encode($response->json()));
        }

        return (object) $response->json();
    }

    public function getProduct($productId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->getUrl("/v1/catalogs/products/{$productId}"));

        if ($response->failed()) {
            throw new \Exception('Failed to fetch PayPal product: ' . json_encode($response->json()));
        }

        return (object) $response->json();
    }

    public function updateProduct($productId, array $data)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->patch($this->getUrl("/v1/catalogs/products/{$productId}"), [
                'name' => $data['name'] ?? null,
                'description' => $data['description'] ?? null,
            ]);

        if ($response->failed()) {
            throw new \Exception('Failed to update PayPal product: ' . json_encode($response->json()));
        }

        return true;
    }

    public function deleteProduct($productId)
    {
        // PayPal no permite eliminar productos, solo archivarlos
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getUrl("/v1/catalogs/products/{$productId}/deactivate"));

        if ($response->failed()) {
            throw new \Exception('Failed to deactivate PayPal product: ' . json_encode($response->json()));
        }

        return true;
    }
}
