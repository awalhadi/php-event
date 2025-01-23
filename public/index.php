<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/../bootstrap.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Core\Router;
use App\Core\CsrfTokenMiddleware;

// session start
session_start();

// csrf token validation
CsrfTokenMiddleware::validateCsrfToken();
$log = new Logger('app');
$log->pushHandler(new StreamHandler(__DIR__ . '/storage/logs/app.log', Logger::DEBUG));


$router = new Router();

// include routes
// require_once __DIR__.'/routes/web.php';
require_once __DIR__ . '/../routes/web.php';

// run routes
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);


