<?php

namespace App\Core;

abstract class Response
{
    private $data;
    private array $headers = [];
    private int $statusCode = 200;

    public function setData($data): void
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }

        if (!empty($this->data)) {
            if (is_array($this->data) || is_object($this->data)) {
                echo json_encode($this->data);
            }
            echo $this->data;
        }
    }
}
