<?php

namespace App\Service\API\DNS\Rage4\Endpoints;

use App\Service\API\DNS\Rage4\Exception\Rage4Exception;
use App\Service\API\DNS\Rage4\Http\HttpClient;
use App\Service\API\DNS\Rage4\Rage4Options;
use App\Service\API\DNS\Rage4\Response\ApiResponse;

class Zone
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
     * @return ApiResponse
     * @throws Rage4Exception
     */
    public function getList(): ApiResponse
    {
        return $this->client->request('GET', '/getdomains');
    }

    /**
     * @param array $params
     * @return ApiResponse
     * @throws Rage4Exception
     */
    public function create(array $params): ApiResponse
    {
        $params = array_merge(
            ['email' => $this->options->getEmail()],
            $params
        );

        return $this->client->request('GET', '/createregulardomain', ['query' => $params]);
    }

    /**
     * @param string $domain
     * @return ApiResponse
     * @throws Rage4Exception
     */
    public function getByDomain(string $domain): ApiResponse
    {
        return $this->client->request('GET', '/getdomainbyname', ['query' => ['name' => $domain]]);
    }

    /**
     * @param $zoneId
     * @return ApiResponse
     * @throws Rage4Exception
     */
    public function delete($zoneId): ApiResponse
    {
        return $this->client->request('GET', '/deletedomain/'.$zoneId);
    }

}