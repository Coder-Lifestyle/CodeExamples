<?php

namespace App\Service\API\DNS\Rage4\Endpoints;

use App\Service\API\DNS\Rage4\Exception\Rage4Exception;
use App\Service\API\DNS\Rage4\Http\HttpClient;
use App\Service\API\DNS\Rage4\Rage4Options;
use App\Service\API\DNS\Rage4\Response\ApiResponse;

class ZoneDnsRecord
{
    /**
     * @var HttpClient
     */
    private $client;
    /**
     * @var Rage4Options
     */
    private $options;

    /**
     * @param HttpClient $client
     * @param Rage4Options $options
     */
    public function __construct(HttpClient $client, Rage4Options $options)
    {
        $this->client = $client;
        $this->options = $options;
    }

    /**
     * @param $zoneId
     * @return ApiResponse
     * @throws Rage4Exception
     */
    public function getList($zoneId): ApiResponse
    {
        return $this->client->request('GET', '/getrecords/'.$zoneId);
    }

    /**
     * @param $zoneId
     * @param array $params
     * @return ApiResponse
     * @throws Rage4Exception
     */
    public function create($zoneId, array $params): ApiResponse
    {
        return $this->client->request('GET', '/createrecord/'.$zoneId, ['query' => $params]);
    }

    /**
     * @param $recordId
     * @return ApiResponse
     * @throws Rage4Exception
     */
    public function delete($recordId): ApiResponse
    {
        return $this->client->request('GET', '/deleterecord/'.$recordId);
    }
}