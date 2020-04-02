<?php

namespace App\Clients;

use GuzzleHttp\Client;

class ApiGatewayClient
{
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getClient(): Client
    {
        return new Client([
            'base_uri' => config('services.aws.api_gateway.endpoint'),

            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json'
            ]
        ]);
    }
}
