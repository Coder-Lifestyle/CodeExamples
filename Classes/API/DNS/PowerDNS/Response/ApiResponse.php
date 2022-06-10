<?php

namespace App\Service\API\DNS\PowerDNS\Response;

use App\Service\API\DNS\PowerDNS\Exception\PowerDnsException;

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
     * @var array
     */
    private $errors = [];

    /**
     * ApiResponse constructor.
     *
     * @param string $responseBody
     * @param $statusCode
     * @throws PowerDnsException
     */
    public function __construct($responseBody, $statusCode)
    {
        $this->body = $responseBody;
        $this->statusCode = (int) $statusCode;

        if ($responseBody) {
            $response = \GuzzleHttp\json_decode($responseBody, true);

            if (!$response && JSON_ERROR_NONE !== ($error = json_last_error())) {
                throw new PowerDnsException("Invalid JSON in the API response body. Error: $error");
            }

            $this->body = $response;
        }

        if ($this->offsetExists('error')) {
            $this->error = $this->getOffset('error');
        }

        if ($this->offsetExists('errors')) {
            $this->errors = $this->getOffset('errors');
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
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->getStatusCode() < 400;
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
