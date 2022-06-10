<?php

namespace App\Service\API\CDN\CloudFlare\Endpoints;

use App\Service\API\CDN\CloudFlare\HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use function count;

class Zone
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * Zone constructor.
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function create(array $params): array
    {
        $result =  $this->client->request('POST','/zones' , ['json' => $params]);

        return $result['result'];
    }

    /**
     * @param string $id
     * @return array
     * @throws GuzzleException
     */
    public function get(string $id): array
    {
        $result = $this->client->request('GET', sprintf('/zones/%s', $id));

        return $result['result'];
    }

    /**
     * @param $params
     * @return array
     * @throws GuzzleException
     */
    public function getList($params): array
    {
        return $this->client->request('GET', '/zones', ['query' => $params]);
    }

    /**
     * @param string $domain
     * @return array|null
     * @throws GuzzleException
     */
    public function find(string $domain)
    {
        $result = $this->client->request('GET','/zones' , ['query' => ['name' => $domain]]);

        if (count($result['result']) > 0) {
            return $result['result'][0];
        }

        return null;
    }

    /**
     * @param string $id
     * @param array $params
     * @throws GuzzleException
     */
    public function edit(string $id, array $params)
    {
        $this->client->request('PATCH', sprintf('/zones/%s', $id) , ['json' => $params]);
    }

    /**
     * @param string $id
     * @throws GuzzleException
     */
    public function delete(string $id)
    {
        $this->client->request('DELETE', sprintf('/zones/%s', $id));
    }
}