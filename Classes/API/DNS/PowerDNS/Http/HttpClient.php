<?php

namespace App\Service\API\DNS\PowerDNS\Http;

use App\Service\API\DNS\PowerDNS\Exception\PowerDnsException;
use App\Service\API\DNS\PowerDNS\Response\ApiResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
    const API_URL = 'http://37.97.234.137:8081/api/v1';

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
                'X-API-Key' => $config['api_key'],
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
     * @throws PowerDnsException
     */
    public function request(string $method, string $url, array $options = []): ApiResponse
    {
        // todo: logs

        try {
            $response = $this->client->request($method, self::API_URL.$url, $options);
        } catch (ClientException $e) {
            throw new PowerDnsException('PowerDNS API error: '.$e->getMessage());
        } catch (GuzzleException $e) {
            throw new PowerDnsException('PowerDNS API error: '.$e->getMessage());
        }
        $apiResponse = new ApiResponse($response->getBody()->getContents(), $response->getStatusCode());

        if (!$apiResponse->isSuccessful()) {
            throw new PowerDnsException('PowerDNS API error: '.$apiResponse->getError());
        }

        return $apiResponse;
    }
}