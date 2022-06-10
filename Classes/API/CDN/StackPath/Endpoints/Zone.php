<?php

namespace App\Service\API\CDN\StackPath\Endpoints;

use App\Service\API\CDN\StackPath\Exception\StackPathException;
use App\Service\API\CDN\StackPath\Http\HttpClient;
use App\Service\API\CDN\StackPath\Response\ApiResponse;

class Zone
{
    const API_PREFIX = '/dns/v1';

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
     * @param $stackId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function getList($stackId, array $params): ApiResponse
    {
        return $this->client->request(
            'GET',
            sprintf(self::API_PREFIX.'/stacks/%s/zones',$stackId),
            ['query' => $params]
        );
    }

    /**
     * @param $stackId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function create($stackId, array $params): ApiResponse
    {
        return $this->client->request(
            'POST',
            sprintf(self::API_PREFIX.'/stacks/%s/zones',$stackId),
            ['json' => $params]
        );
    }

    /**
     * @param $stackId
     * @param $zoneId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function get($stackId, $zoneId): ApiResponse
    {
        return $this->client->request(
            'GET',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s',$stackId, $zoneId)
        );
    }

    /**
     * @param $stackId
     * @param $zoneId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function update($stackId, $zoneId, array $params): ApiResponse
    {
        return $this->client->request(
            'PUT',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s',$stackId, $zoneId),
            ['json' => $params]
        );
    }

    /**
     * @param $stackId
     * @param $zoneId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function delete($stackId, $zoneId): ApiResponse
    {
        return $this->client->request(
            'DELETE',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s',$stackId, $zoneId)
        );
    }

    /**
     * @param $stackId
     * @param $zoneId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function enable($stackId, $zoneId): ApiResponse
    {
        return $this->client->request(
            'POST',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s/enable',$stackId, $zoneId)
        );
    }

    /**
     * @param $stackId
     * @param $zoneId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function disable($stackId, $zoneId): ApiResponse
    {
        return $this->client->request(
            'POST',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s/disable',$stackId, $zoneId)
        );
    }

    /**
     * @param $stackId
     * @param $zoneId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function getNameServers($stackId, $zoneId): ApiResponse
    {
        return $this->client->request(
            'GET',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s/discover_nameservers',$stackId, $zoneId)
        );
    }

}