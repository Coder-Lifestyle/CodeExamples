<?php

namespace App\Service\API\CDN\StackPath;

use App\Service\API\CDN\StackPath\Exception\StackPathException;
use function count;

class StackPathSettingsManager
{
    /**
     * @var StackPathClient
     */
    private $client;

    /**
     * @var string
     */
    private $stackId;

    /**
     * @var string
     */
    private $accountId;

    /**
     * StackPathSettingsManager constructor.
     * @param StackPathClient $client
     */
    public function __construct(StackPathClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws StackPathException
     */
    private function init()
    {
        $stacksRes = $this->client->stack()->getList();
        $stacks = $stacksRes->getOffset('results');
        if (count($stacks) === 0) {
            throw new StackPathException('Unable to get default stack');
        }

        $stack = $stacks[0];
        $this->stackId = $stack['id'];
        $this->accountId = $stack['accountId'];
    }

    /**
     * @return string
     * @throws StackPathException
     */
    public function getStackId(): string
    {
        if (!$this->stackId) {
            $this->init();
        }

        return $this->stackId;
    }

    /**
     * @return string
     * @throws StackPathException
     */
    public function getAccountId(): string
    {
        if (!$this->accountId) {
            $this->init();
        }

        return $this->accountId;
    }


}