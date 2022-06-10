<?php

namespace App\Service\API\DNS\Rage4\Http;

use App\Service\API\DNS\Rage4\Exception\Rage4Exception;
use App\Service\API\DNS\Rage4\Response\ApiResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
    const API_URL = 'https://secure.rage4.com/rapi';

    /**
     * @var Client
     */
    private $client;

    /**
     * HttpClient constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $defaults = [
            'headers' => [
                'Accepts' => 'application/json',
            ],
            'auth' => [
                $config['username'],
                $config['password']
            ],
            'verify' => false,
            'exceptions' => false,
        ];

        $this->client = new Client($defaults);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ApiResponse
     * @throws Rage4Exception
     */
    public function request(string $method, string $url, array $options = []): ApiResponse
    {
        // todo: logs

        try {
            $response = $this->client->request($method, self::API_URL.$url, $options);
        } catch (ClientException $e) {
            throw new Rage4Exception('Rage4 API error: '.$e->getMessage());
        } catch (GuzzleException $e) {
            throw new Rage4Exception('Rage4 API error: '.$e->getMessage());
        }
        $apiResponse = new ApiResponse($response->getBody(), $response->getStatusCode());

        if (!$apiResponse->isSuccessful()) {
            throw new Rage4Exception('Rage4 API error: '.$apiResponse->getError());
        }

        return $apiResponse;
    }
}