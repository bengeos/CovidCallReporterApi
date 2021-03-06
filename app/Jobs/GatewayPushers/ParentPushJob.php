<?php

namespace App\Jobs\GatewayPushers;

use App\Clients\ApiGatewayClient;
use App\Clients\CognitoClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Log\Logger;

abstract class ParentPushJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(CognitoClient $congnito, Logger $logger)
    {
//        $token = $congnito->getAccessToken();
        $token = "26874263847234";

        $client = (new ApiGatewayClient($token))->getClient();

        $response = $client->request($this->method(), $this->endPoint(), ['json' => $this->data]);

        $logger->alert('AWS API Gateway Response', [
            'method' => $this->method(),
            'url' => $this->endPoint(),
            'data' => $this->data,
            'statusCode' => $response->getStatusCode(),
            'body' => $response->getBody()->getContents(),
        ]);
    }

    protected function method(): string
    {
        return 'POST';
    }

    protected abstract function endPoint(): string;
}
