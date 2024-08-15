<?php

namespace App\Core;

require_once __DIR__ . "../Config/config.php";

class Request
{
    protected $data = null;
    private string $url = "/";
    private string $method = "GET";

    public function __construct($data, string $url, string $method = "GET")
    {
        $this->data = $data;
        $this->url = $url;
        $this->method = $method;
    }

    public function getData(): string
    {
        return json_decode($this->data);
    }

    public function getRoute(): string
    {
        return str_replace(BASE_URI, '', $this->url);
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
