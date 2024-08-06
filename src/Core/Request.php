<?php

namespace App\Core;

abstract class Request
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
        $explodedUrlArr = explode("/", $this->url);
        return "/" . $explodedUrlArr[count($explodedUrlArr) - 1];
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
