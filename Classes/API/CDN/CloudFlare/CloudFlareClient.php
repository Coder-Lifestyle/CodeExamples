<?php

namespace App\Service\API\CDN\CloudFlare;

use App\Entity\CloudflareAccount;
use App\Service\API\CDN\CloudFlare\Endpoints\DnsRecord;
use App\Service\API\CDN\CloudFlare\Endpoints\Zone;

class CloudFlareClient
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Zone
     */
    private $zone;

    /**
     * @var DnsRecord
     */
    private $dnsRecord;

    private $proxyUrl ;

    private $proxyUser ;

    private $proxyPassword ;


    public function __construct($config)
    {
        $this->httpClient = new HttpClient($config);

        $this->zone = new Zone($this->httpClient);
        $this->dnsRecord = new DnsRecord($this->httpClient);
    }

    public function setAccount(CloudflareAccount $account)
    {
        $config = [
            'email' => $account->getEmail(),
            'apiKey' => $account->getApiKey(),
        ];

        $this->httpClient = new HttpClient($config);

        $this->zone = new Zone($this->httpClient);
        $this->dnsRecord = new DnsRecord($this->httpClient);
    }

    public function httpClient(): HttpClient
    {
        return $this->httpClient;
    }

    public function zone(): Zone
    {
        return $this->zone;
    }

    public function dnsRecord(): DnsRecord
    {
        return $this->dnsRecord;
    }
}
