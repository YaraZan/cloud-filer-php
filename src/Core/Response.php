<?php

namespace App\Core;

/**
 * Represents server response
 */
class Response
{
    /** Response data */
    private $data;

    /** Response status code */
    private int $statusCode = 200;

    /** Response headers */
    private array $headers = [];

    public function __construct($data, int $statusCode = 200, array $headers = [])
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * Set payload to response
     * 
     * @param mixed $data Response data
     * @return void
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * Get response payload
     * 
     * @return mixed Response data
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Set headers to response
     * 
     * @param array $headers Response headers
     * @return void
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Get response headers
     * 
     * @return array Responce headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get response status code
     * 
     * @return int Responce status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set response status code
     * 
     * @param int $statusCode Responce status code
     * @return void
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Send response
     * 
     * @return void
     */
    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }

        echo json_encode($this->data);
    }
}
