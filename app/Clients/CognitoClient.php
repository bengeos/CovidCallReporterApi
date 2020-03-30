<?php

namespace App\Clients;

use GuzzleHttp\Client;
use RuntimeException;

class CognitoClient
{
    public function getAccessToken(): string
    {
        $client = new Client();

        $response = $client->post(config('services.aws.cognito.endpoint'), [
            'body' => 'grant_type=client_credentials',
            'auth' => $this->getCredentials(),
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
        ]);

        if ($response->getStatusCode() !== 200) throw new RuntimeException('Failed to fetch access token from cognito.');

        $data = json_decode($response->getBody()->getContents());

        return $data->access_token;
    }

    private function getCredentials()
    {
        return [
            config('services.aws.cognito.id'),
            config('services.aws.cognito.secret')
        ];
    }
}
