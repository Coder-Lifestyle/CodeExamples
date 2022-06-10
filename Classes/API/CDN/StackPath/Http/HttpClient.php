<?php

namespace App\Service\API\CDN\StackPath\Http;

use App\Service\API\CDN\StackPath\Exception\StackPathException;
use App\Service\API\CDN\StackPath\Response\ApiResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
    const API_URL = 'https://gateway.stackpath.com';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $token;

    /**
     * @todo change to \Datetime
     * @var int
     */
    private $tokenExpiresAt;

    /**
     * HttpClient constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $defaults = [
//            'base_uri' => self::API_URL, // todo: base uri
            'headers' => [
                'Accepts' => 'application/json',
            ],
            'verify' => false,
            'exceptions' => false,
        ];

        $this->client = new Client(['defaults' => $defaults]);
        $this->config = $config;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ApiResponse
     * @throws StackPathException
     */
    public function request(string $method, string $url, array $options = []): ApiResponse
    {
        // todo: logs

        $defaultOptions = [
            'headers' => [
                'authorization' => 'Bearer '.$this->getAuthorizeKey()
            ]
        ];
        $options = array_merge_recursive($defaultOptions, $options);

        try {
            $response = $this->client->request($method, self::API_URL.$url, $options);
        } catch (ClientException $e) {
            throw new StackPathException('StackPath API error: '.$e->getMessage());
        } catch (GuzzleException $e) {
            throw new StackPathException('StackPath API error: '.$e->getMessage());
        }
        $apiResponse = new ApiResponse($response->getBody(), $response->getStatusCode());

        if (!$apiResponse->isSuccessful()) {
            throw new StackPathException('StackPath API error: '.$apiResponse->getErrorMessage());
        }

        return $apiResponse;
    }

    /**
     * @return string
     * @throws StackPathException
     */
    private function getAuthorizeKey()
    {
        if (!$this->token || $this->token && $this->tokenExpiresAt < time()) {
            try {
                $response = $this->client->request('POST', self::API_URL.'/identity/v1/oauth2/token', [
                    'json' => [
                        'client_id' => $this->config['clientId'],
                        'client_secret' => $this->config['clientSecret'],
                        'grant_type' => 'client_credentials'
                    ],
                ]);
            } catch (GuzzleException $e) {
                throw new StackPathException('StackPath API error: '.$e->getMessage());
            }

            $body = json_decode($response->getBody()->getContents(), true);

            if (!isset($body['access_token'])) {
                throw new StackPathException('Not authorized');
            }

            $this->token = $body['access_token'];
            $this->tokenExpiresAt = time()+$body['expires_in']-5;
        }

        return $this->token;
    }
}