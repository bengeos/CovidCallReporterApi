<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;

class CognitoAuth
{

    private $token_granted = false;


    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */


    public function handle($request, Closure $next)
    {
        if ($this->isAuthenticated()) {
            $response = $next($request);
            $response->header('Content-Type', 'application/json')
                ->header('Authorization', 'Bearer '.$this->getAccessToken()->access_token);
            return $response;
        }
        return redirect('/');

    }

    private function isAuthenticated()
    {
        //check if access toke exist
        if (isset($this->getAccessToken()->access_token)) {
            $this->token_granted = true;
            return $this->token_granted;
        } else return false;
    }

    private function getAccessToken()
    {

        $client_id = config('app.api_gateway_client_id');
        $client_secret = config('app.api_gateway_client_secret');

        //Guzzle Http Client
        $client = new Client();

        //create credentials
        $credentials = base64_encode($client_id . ":" . $client_secret);
        $options = array(
            'headers' => [
                'content-type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . $credentials],
            'body' => 'grant_type=client_credentials',
            "debug" => false,
        );
        //async call to the api gateway
        $promise = $client->postAsync(config('app.api_gateway_auth_uri'), $options)->then(
            function (ResponseInterface $res) {
                $response = json_decode($res->getBody()->getContents());
                return $response;
            }, function (RequestException $e) {
            $response = $e->getMessage();
            return $response;
        }
        );

        return $promise->wait(true);
    }

}
