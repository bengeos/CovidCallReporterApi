<?php

namespace App\Jobs\JsiDataSyncs;

use App\Clients\ApiGatewayClient;
use App\Clients\JsiGatewayClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Log\Logger;

abstract class JsiDataSync implements ShouldQueue
{
    use Dispatchable, Queueable;
    private $data;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param Logger $logger
     * @return void
     */
    public function handle(Logger $logger)
    {
        $client = (new JsiGatewayClient())->getClient();
        $response = $client->request($this->method(), $this->endPoint(), ['json' => $this->data]);
        $logger->alert('JSI API Gateway Response', [
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
