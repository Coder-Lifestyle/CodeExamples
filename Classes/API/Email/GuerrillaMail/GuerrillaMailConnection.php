<?php

namespace App\Service\API\Email\GuerrillaMail;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use function json_decode;

class GuerrillaMailConnection
{
    public const USER_AGENT = 'GuerrillaMail_Library';

    /**
     * @var ClientInterface
     */
    protected $connection;

    /**
     * @var string
     */
    protected $apiUrl = 'https://api.guerrillamail.com/ajax.php';

    /**
     * @var string
     */
    protected $apiKey = 'eug4RKieoVtl2a7m';

    /**
     * @param $apiKey
     */
    //public function __construct()
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;

        $defaults = [
            'headers' => [
                'User-Agent' => self::USER_AGENT,
                'Accepts' => 'application/json',
            ],
        ];

        $this->connection = new Client(['defaults' => $defaults]);
    }

    public function makeClient($sidToken = null)
    {
        return new GuerrillaMailClient($this, $sidToken);
    }

    /**
     * @param $method
     * @param $action
     *
     * @return mixed
     *
     * @throws GuzzleException
     */
    public function request($method, $action, array $options)
    {
        $requestOptions = $this->buildRequestOptions($action, $options);

        $response = $this->connection->request($method, $this->apiUrl, [
            'headers' => $requestOptions['headers'],
            'cookies' => $requestOptions['cookies'],
            'query' => $requestOptions['query'],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    private function buildRequestOptions($action, array $options)
    {
        $options = array_merge_recursive([
            'f' => $action,
            'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',
            'agent' => self::USER_AGENT,
        ], $options);

        return $this->parseOptions($options);
    }

    private function parseOptions(array $options)
    {
        $cookies = new CookieJar();
        $headers = [];

        if (isset($options['sid_token'])) {
            $cookie = new SetCookie(['Name' => 'PHPSESSID', 'Value' => $options['sid_token'], 'Domain' => 0]);
            $cookies->setCookie($cookie);
            if ($apiToken = $this->getApiToken($options['sid_token'])) {
                $headers['Authorization: ApiToken '] = $apiToken;
            }

            unset($options['sid_token']);
        }

        return [
            'cookies' => $cookies,
            'headers' => $headers,
            'query' => $options,
        ];
    }

    /**
     * @param $sid
     *
     * @return bool|string
     */
    private function getApiToken($sid)
    {
        if (!$this->apiKey) {
            return false;
        }

        $token = hash_hmac('sha256', $sid, $this->apiKey);

        return $token;
    }
}
