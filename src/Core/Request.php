<?php

namespace App\Core;

/** Require app config variables */
require_once __DIR__ . "/../Config/config.php";

/**
 * Stores variables and data passed via HTTP request
 */
class Request
{
    /** Data, passed in request */
    protected $data = null;

    /** Requesting URL */
    private string $url = "/";

    /** Incoming request method */
    private string $method = "GET";

    public function __construct($data, string $url, string $method = "GET")
    {
        $this->data = $data;
        $this->url = $url;
        $this->method = $method;
    }

    /**
     * Get request data
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get request route
     * @return array
     */
    public function getRoute(): string
    {
        return str_replace(BASE_URI, '', $this->url);
    }

    /**
     * Get request method
     * @return array
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}
