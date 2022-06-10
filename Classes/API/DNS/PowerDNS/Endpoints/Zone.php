<?php

namespace App\Service\API\DNS\PowerDNS\Endpoints;

use App\Service\API\DNS\PowerDNS\Exception\PowerDnsException;
use App\Service\API\DNS\PowerDNS\Http\HttpClient;
use App\Service\API\DNS\PowerDNS\Response\ApiResponse;

class Zone
{
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
     * @return ApiResponse
     * @throws PowerDnsException
     */
    public function getList(): ApiResponse
    {
        return $this->client->request('GET', '/servers/localhost/zones');
    }

    /**
     * @param array $params
     * @return ApiResponse
     * @throws PowerDnsException
     */
    public function create(array $params): ApiResponse
    {
        return $this->client->request('POST', '/servers/localhost/zones', ['json' => $params]);
    }

    /**
     * @param string $zoneId
     * @return ApiResponse
     * @throws PowerDnsException
     */
    public function get(string $zoneId): ApiResponse
    {
        return $this->client->request('GET', sprintf('/servers/localhost/zones/%s', $zoneId));
    }

    /**
     * Edit zone data
     *
     * @param string $zoneId
     * @param array $params
     * @return ApiResponse
     * @throws PowerDnsException
     */
    public function edit(string $zoneId, array $params): ApiResponse
    {
        return $this->client->request('PUT', sprintf('/servers/localhost/zones/%s', $zoneId), ['json' => $params]);
    }

    /**
     * Edit zone DNS records
     *
     * @param string $zoneId
     * @param array $params
     * @return ApiResponse
     * @throws PowerDnsException
     */
    public function patch(string $zoneId, array $params): ApiResponse
    {
        return $this->client->request('PATCH', sprintf('/servers/localhost/zones/%s', $zoneId), ['json' => $params]);
    }

    /**
     * @param $zoneId
     * @return ApiResponse
     * @throws PowerDnsException
     */
    public function delete($zoneId): ApiResponse
    {
        return $this->client->request('DELETE', sprintf('/servers/localhost/zones/%s', $zoneId));
    }

}