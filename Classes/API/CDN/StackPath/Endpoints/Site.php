<?php

namespace App\Service\API\CDN\StackPath\Endpoints;

use App\Service\API\CDN\StackPath\Exception\StackPathException;
use App\Service\API\CDN\StackPath\Http\HttpClient;
use App\Service\API\CDN\StackPath\Response\ApiResponse;

class Site
{
    const API_PREFIX = '/cdn/v1';

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
    public function getList($stackId, array $params = []): ApiResponse
    {
        return $this->client->request(
            'GET',
            sprintf(self::API_PREFIX.'/stacks/%s/sites',$stackId),
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
            sprintf(self::API_PREFIX.'/stacks/%s/sites',$stackId),
            ['json' => $params]
        );
    }

    /**
     * @param $stackId
     * @param $siteId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function get($stackId, $siteId): ApiResponse
    {
        return $this->client->request(
            'GET',
            sprintf(self::API_PREFIX.'/stacks/%s/sites/%s',$stackId, $siteId)
        );
    }

    /**
     * @param $stackId
     * @param $siteId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function update($stackId, $siteId, array $params): ApiResponse
    {
        return $this->client->request(
            'POST',
            sprintf(self::API_PREFIX.'/stacks/%s/sites/%s',$stackId, $siteId),
            ['json' => $params]
        );
    }

    /**
     * @param $stackId
     * @param $siteId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function delete($stackId, $siteId): ApiResponse
    {
        return $this->client->request(
            'DELETE',
            sprintf(self::API_PREFIX.'/stacks/%s/sites/%s',$stackId, $siteId)
        );
    }

    /**
     * @param $stackId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function getOrigins($stackId, array $params = []): ApiResponse
    {
        return $this->client->request(
            'GET',
            sprintf(self::API_PREFIX.'/stacks/%s/origins',$stackId),
            ['query' => $params]
        );
    }

    /**
     * @param $stackId
     * @param string $originId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function getOrigin($stackId, string $originId): ApiResponse
    {
        return $this->client->request(
            'GET',
            sprintf(self::API_PREFIX.'/stacks/%s/origins/%s',$stackId, $originId)
        );
    }

    /**
     * @param $stackId
     * @param string $originId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function updateOrigin($stackId, string $originId, array $params): ApiResponse
    {
        return $this->client->request(
            'PATCH',
            sprintf(self::API_PREFIX.'/stacks/%s/origins/%s',$stackId, $originId),
            ['json' => $params]
        );
    }

    /**
     * @param string $domain
     * @return ApiResponse
     * @throws StackPathException
     */
    public function scanOrigin(string $domain): ApiResponse
    {
        return $this->client->request('POST', self::API_PREFIX.'/origins/scan', [
            'json' => ['domain' => $domain]
        ]);
    }

    /**
     * @param $stackId
     * @param string $siteId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function getDnsTargets($stackId, string $siteId): ApiResponse
    {
        return $this->client->request(
            'GET',
            sprintf(self::API_PREFIX.'/stacks/%s/sites/%s/dns/targets',$stackId, $siteId)
        );
    }

    /**
     * @param $stackId
     * @param string $siteId
     * @param array $params
     * @return ApiResponse
     * @throws StackPathException
     */
    public function getSiteScopes($stackId, string $siteId, array $params = []): ApiResponse
    {
        return $this->client->request(
            'GET',
            sprintf(self::API_PREFIX.'/stacks/%s/sites/%s/scopes',$stackId, $siteId),
            ['query' => $params]
        );
    }

    /**
     * @param $stackId
     * @param string $siteId
     * @param string $scopeId
     * @return ApiResponse
     * @throws StackPathException
     */
    public function getSiteScopeOrigins($stackId, string $siteId, string $scopeId): ApiResponse
    {
        return $this->client->request(
            'GET',
            sprintf(self::API_PREFIX.'/stacks/%s/sites/%s/scopes/%s/origins',$stackId, $siteId, $scopeId)
        );
    }
}