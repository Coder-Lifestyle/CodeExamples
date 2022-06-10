<?php

namespace App\Service\API\CDN\StackPath\Endpoints;

use App\Service\API\CDN\StackPath\Exception\StackPathException;
use App\Service\API\CDN\StackPath\Http\HttpClient;
use App\Service\API\CDN\StackPath\Response\ApiResponse;

class ZoneDnsRecord
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
     * @param $zoneId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function getList($stackId, $zoneId, array $params = []): ApiResponse
    {
        return $this->client->request(
            'GET',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s/records',$stackId, $zoneId),
            ['query' => $params]
        );
    }

    /**
     * @param $stackId
     * @param $zoneId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function create($stackId, $zoneId, array $params): ApiResponse
    {
        return $this->client->request(
            'POST',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s/records',$stackId, $zoneId),
            ['json' => $params]
        );
    }

    /**
     * @param $stackId
     * @param $zoneId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function bulkCreateOrUpdate($stackId, $zoneId, array $params): ApiResponse
    {
        return $this->client->request(
            'POST',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s/bulk/records',$stackId, $zoneId),
            ['json' => $params]
        );
    }

    /**
     * @param $stackId
     * @param $zoneId
     * @param $recordId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function get($stackId, $zoneId, $recordId): ApiResponse
    {
        return $this->client->request(
            'GET',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s/records/%s',$stackId, $zoneId, $recordId)
        );
    }

    /**
     * @param $stackId
     * @param $zoneId
     * @param $recordId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function update($stackId, $zoneId, $recordId, array $params): ApiResponse
    {
        return $this->client->request(
            'PUT',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s/records/%s',$stackId, $zoneId, $recordId),
            ['json' => $params]
        );
    }

    /**
     * @param $stackId
     * @param $zoneId
     * @param $recordId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function delete($stackId, $zoneId, $recordId): ApiResponse
    {
        return $this->client->request(
            'DELETE',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s/records/%s',$stackId, $zoneId, $recordId)
        );
    }

    /**
     * @param $stackId
     * @param $zoneId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function bulkDelete($stackId, $zoneId, array $params): ApiResponse
    {
        return $this->client->request(
            'POST',
            sprintf(self::API_PREFIX.'/stacks/%s/zones/%s/bulk/records/delete',$stackId, $zoneId),
            ['json' => $params]
        );
    }
}