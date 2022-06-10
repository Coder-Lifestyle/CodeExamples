<?php

namespace App\Service\API\CDN\CloudFlare;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
    const API_URL = 'https://api.cloudflare.com/client/v4';

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
    private $proxy;

    public function __construct(array $config)
    {
        $this->client = new Client();
        $this->config = $config;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function request(string $method, string $endpoint, array $params = []): array
    {
        $defaultParams = [
            'proxy' => $this->proxy,
            'headers' => [
                'X-Auth-Email' => $this->config['email'],
                'X-Auth-Key' => $this->config['apiKey']
            ]
        ];
        $params = array_merge($defaultParams, $params);

        $res = $this->client->request($method, self::API_URL.$endpoint, $params);
        $json = $res->getBody()->getContents();
        $data = json_decode($json, true);

        if (!$data['success']) {
            throw new Exception('CloudFlare error ' );
        }

        return $data;
    }

    /**
     * @return string|null
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @param string $proxy
     */
    public function setProxy(string $proxy)
    {
        $this->proxy = $proxy;
    }
}
