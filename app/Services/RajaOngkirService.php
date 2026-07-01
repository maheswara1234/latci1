<?php

namespace App\Services;

use Config\Services;

class RajaOngkirService
{
    // Menggunakan type hinting pada properti untuk keamanan memori
    protected $client;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->client = Services::curlrequest([
            'timeout' => 10,
            'http_errors' => false,
        ]);

        // Memberikan fallback string kosong jika .env belum diset
        $this->apiKey = env('RAJAONGKIR_API_KEY', '');
        $this->baseUrl = env('RAJAONGKIR_BASE_URL', '');
    }

    public function getDestination(string $keyword): array
    {
        try {
            $response = $this->client->get(
                $this->baseUrl . 'destination/domestic-destination',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'key' => $this->apiKey,
                    ],
                    'query' => [
                        'search' => $keyword,
                        'limit' => 50,
                    ]
                ]
            );

            $data = json_decode($response->getBody(), true);

            // Validasi apakah hasil decode benar-benar array
            return is_array($data) ? $data : [];

        } catch (\Exception $e) {
            // Tangkap error CURL (misal: timeout) dan kembalikan array kosong
            log_message('error', 'RajaOngkir getDestination Error: ' . $e->getMessage());
            return [];
        }
    }

    public function getCost(string $origin, string $destination, int $weight, string $courier): array
    {
        try {
            $response = $this->client->post(
                $this->baseUrl . 'calculate/domestic-cost',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'key' => $this->apiKey,
                    ],
                    'form_params' => [
                        'origin' => $origin,
                        'destination' => $destination,
                        'weight' => $weight,
                        'courier' => $courier,
                    ]
                ]
            );

            $data = json_decode($response->getBody(), true);

            return is_array($data) ? $data : [];

        } catch (\Exception $e) {
            log_message('error', 'RajaOngkir getCost Error: ' . $e->getMessage());
            return [];
        }
    }
}