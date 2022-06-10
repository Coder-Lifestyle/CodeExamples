<?php

namespace App\Service\API\DNS\PowerDNS;

use App\Service\API\DNS\PowerDNS\Endpoints\Zone;
use App\Service\API\DNS\PowerDNS\Endpoints\ZoneDnsRecord;
use App\Service\API\DNS\PowerDNS\Http\HttpClient;
use App\Service\Domain\RegistrarSettingManager;

class PowerDnsClient
{
    /**
     * @var Zone
     */
    private $zone;

    public function __construct(RegistrarSettingManager $registrarSettingManager)
    {
        $settings = $registrarSettingManager->getRegistrarSettingsByCode('power_dns');
        $client = new HttpClient([
            'api_key' => $settings->getOtherInfo()
        ]);
        $this->zone = new Zone($client);
    }

    /**
     * @return Zone
     */
    public function zone(): Zone
    {
        return $this->zone;
    }
}