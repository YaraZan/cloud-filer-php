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
$data = $method === 'POST' ? $_POST : $_GET;

$request = new Request($data, $uri, $method);

$router->processRequest($request);
