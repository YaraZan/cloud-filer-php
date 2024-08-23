<?php

use App\Controllers\AuthController;
use App\Core\App;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;

require_once 'vendor/autoload.php';

App::registerServices();

$router = new Router();

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$headers = apache_request_headers();

if ($method === 'POST') {
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);
} else {
    $data = $_GET;
}

$request = new Request($data, $uri, $method, $headers);

$router->processRequest($request);
