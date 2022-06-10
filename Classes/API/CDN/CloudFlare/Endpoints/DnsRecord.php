<?php

namespace App\Service\API\CDN\CloudFlare\Endpoints;

use App\Service\API\CDN\CloudFlare\HttpClient;
use GuzzleHttp\Exception\GuzzleException;

class DnsRecord
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * DnsRecord constructor.
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $zone
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function create(string $zone, array $params): array
    {
        $result =  $this->client->request('POST', sprintf('/zones/%s/dns_records', $zone) , [
            'json' => $params
        ]);

        return $result['result'];
    }

    /**
     * @param string $zone
     * @param $id
     * @return array
     * @throws GuzzleException
     */
    public function get(string $zone, string $id): array
    {
        $result = $this->client->request('GET', sprintf('/zones/%s/dns_records/%s', $zone, $id));

        return $result['result'];
    }

    /**
     * @param string $zone
     * @return array
     * @throws GuzzleException
     */
    public function getList(string $zone): array
    {
        return $this->client->request('GET', sprintf('/zones/%s/dns_records', $zone));
    }

    /**
     * @param string $zone
     * @param string $id
     * @param array $params
     * @throws GuzzleException
     */
    public function edit(string $zone, string $id, array $params)
    {
        $this->client->request('PUT', sprintf('/zones/%s/dns_records/%s', $zone, $id) , [
            'json' => $params
        ]);
    }

    /**
     * @param string $zone
     * @param $id
     * @throws GuzzleException
     */
    public function delete(string $zone, $id)
    {
        $this->client->request('DELETE', sprintf('/zones/%s/dns_records/%s', $zone, $id));
    }
}