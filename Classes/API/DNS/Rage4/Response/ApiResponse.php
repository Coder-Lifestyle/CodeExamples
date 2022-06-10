<?php

namespace App\Service\API\DNS\Rage4\Response;

use App\Service\API\DNS\Rage4\Exception\Rage4Exception;

class ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var mixed
     */
    protected $body;

    /**
     * @var string
     */
    private $error;

    /**
     * ApiResponse constructor.
     *
     * @param array $responseBody
     * @param $statusCode
     * @throws Rage4Exception
     */
    public function __construct($responseBody, $statusCode)
    {
        $this->body = $responseBody;
        $this->statusCode = (int) $statusCode;

        if (!empty($responseBody)) {
            $response = \GuzzleHttp\json_decode($responseBody, true);

            if (!$response && JSON_ERROR_NONE !== ($error = json_last_error())) {
                throw new Rage4Exception("Invalid JSON in the API response body. Error: $error");
            }

            $this->body = $response;
        }

        if ($this->offsetExists('error')) {
            $this->error = $this->getOffset('error');
        }
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error ?? null;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return !(bool)$this->getError();
    }

    /**
     * @param $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->body[$offset]);
    }

    /**
     * @param $offset
     *
     * @return mixed
     */
    public function getOffset($offset)
    {
        return $this->body[$offset] ?? null;
    }
}
