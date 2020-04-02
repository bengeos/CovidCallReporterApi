<?php

use Illuminate\Foundation\Inspiring;
use App\Clients\{CognitoClient, ApiGatewayClient};

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('aws:toll-free', function (CognitoClient $cognitoClient) {
    $token = $cognitoClient->getAccessToken();

    $client = (new ApiGatewayClient($token))->getClient();

    $result = $client->get('gateway/toll-free');

    $this->alert($result->getStatusCode());
    dump(json_decode($result->getBody()->getContents()));
})->describe('Display the toll-free call report data');
