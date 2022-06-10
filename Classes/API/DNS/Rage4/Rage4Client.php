<?php

namespace App\Service\API\DNS\Rage4;

use App\Service\API\DNS\Rage4\Endpoints\Zone;
use App\Service\API\DNS\Rage4\Endpoints\ZoneDnsRecord;
use App\Service\API\DNS\Rage4\Http\HttpClient;
use App\Service\Domain\RegistrarSettingManager;

class Rage4Client
{
    /**
     * @var Zone
     */
    private $zone;

    /**
     * @var ZoneDnsRecord
     */
    private $dnsRecord;

    public function __construct(RegistrarSettingManager $registrarSettingManager)
    {
        $settings = $registrarSettingManager->getRegistrarSettingsByCode('rage4');
        $client = new HttpClient([
            'username' => $settings->getUsername(),
            'password' => $settings->getPassword()
        ]);
        $options = new Rage4Options('info@vindtspecialist.nl');
        $this->zone = new Zone($client, $options);
        $this->dnsRecord = new ZoneDnsRecord($client, $options);
    }

    /**
     * @return Zone
     */
    public function zone(): Zone
    {
        return $this->zone;
    }

    /**
     * @return ZoneDnsRecord
     */
    public function dnsRecord(): ZoneDnsRecord
    {
        return $this->dnsRecord;
    }
}