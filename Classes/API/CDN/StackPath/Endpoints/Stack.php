<?php

namespace App\Service\API\CDN\StackPath\Endpoints;

use App\Service\API\CDN\StackPath\Exception\StackPathException;
use App\Service\API\CDN\StackPath\Http\HttpClient;
use App\Service\API\CDN\StackPath\Response\ApiResponse;

class Stack
{
    const API_PREFIX = '/stack/v1';

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function getList(array $params = []): ApiResponse
    {
        return $this->client->request('GET', self::API_PREFIX.'/stacks', ['query' => $params]);
    }

    /**
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function create(array $params): ApiResponse
    {
        return $this->client->request('POST', self::API_PREFIX.'/stacks', ['json' => $params]);
    }

    /**
     * @param $stackId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function get($stackId): ApiResponse
    {
        return $this->client->request('GET', self::API_PREFIX.'/stacks/'.$stackId);
    }

    /**
     * @param $stackId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function update($stackId, array $params): ApiResponse
    {
        return $this->client->request('POST', self::API_PREFIX.'/stacks/'.$stackId, ['json' => $params]);
    }

}