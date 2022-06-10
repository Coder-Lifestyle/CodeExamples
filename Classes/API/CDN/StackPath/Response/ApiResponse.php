<?php

namespace App\Service\API\CDN\StackPath\Response;

use App\Service\API\CDN\StackPath\Exception\StackPathException;

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
    private $errorMessage;

    /**
     * @var array
     */
    private $errors;

    /**
     * ApiResponse constructor.
     *
     * @param string $responseBody
     * @param $statusCode
     *
     * @throws StackPathException
     */
    public function __construct(string $responseBody, $statusCode)
    {
        $this->body = [];
        $this->statusCode = (int) $statusCode;
        $this->errorMessage = '';
        $this->errors = [];

        if (!empty($responseBody)) {
            $response = \GuzzleHttp\json_decode($responseBody, true);

            if (!$response && JSON_ERROR_NONE !== ($error = json_last_error())) {
                throw new StackPathException("Invalid JSON in the API response body. Error: $error");
            }

            $this->body = $response;
        }

        if (!$this->isSuccessful()) {
            $this->errorMessage = $this->getOffset('error');
            $this->errors = $this->getOffset('details');
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
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode < 400;
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
