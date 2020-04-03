<?php


namespace App\Clients;


use GuzzleHttp\Client;

class JsiGatewayClient
{

    /**
     * JsiGatewayClient constructor.
     */
    public function __construct()
    {
    }

    public function getClient(): Client
    {
        return new Client([
            'base_uri' => config('services.jsi.api_gateway.endpoint'),
            'headers' => [
                'Authorization' => 'Bearer ' . 'NOTHING',
                'Content-Type' => 'application/json'
            ]
        ]);
    }
}
