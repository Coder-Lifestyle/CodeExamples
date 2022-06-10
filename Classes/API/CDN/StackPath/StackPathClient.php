<?php

namespace App\Service\API\CDN\StackPath;

use App\Service\API\CDN\StackPath\Endpoints\Site;
use App\Service\API\CDN\StackPath\Endpoints\Stack;
use App\Service\API\CDN\StackPath\Endpoints\Zone;
use App\Service\API\CDN\StackPath\Endpoints\ZoneDnsRecord;
use App\Service\API\CDN\StackPath\Http\HttpClient;
use App\Service\Domain\RegistrarSettingManager;
use Exception;

class StackPathClient
{
    /**
     * @var Stack
     */
    private $stack;

    /**
     * @var Zone
     */
    private $zone;

    /**
     * @var ZoneDnsRecord
     */
    private $zoneDnsRecord;

    /**
     * @var Site
     */
    private $site;

    /**
     * @param RegistrarSettingManager $settingsManager
     * @throws Exception
     */
    public function __construct(RegistrarSettingManager $settingsManager)
    {
        $stackPathConfig = $settingsManager->getRegistrarSettingsByCode('stack_path_v1');

        $httpClient = new HttpClient([
            'clientId' => $stackPathConfig->getUsername(),
            'clientSecret' => $stackPathConfig->getPassword()
        ]);
        $this->stack = new Stack($httpClient);
        $this->zone = new Zone($httpClient);
        $this->zoneDnsRecord = new ZoneDnsRecord($httpClient);
        $this->site = new Site($httpClient);
    }

    /**
     * @return Stack
     */
    public function stack(): Stack
    {
        return $this->stack;
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
    public function zoneDnsRecord(): ZoneDnsRecord
    {
        return $this->zoneDnsRecord;
    }

    /**
     * @return Site
     */
    public function site(): Site
    {
        return $this->site;
    }
}