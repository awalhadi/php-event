<?php
require_once __DIR__.'/../bootstrap.php';

use App\Core\Router;

// session start
session_start();

$router = new Router();

// include routes
require_once __DIR__.'/routes/web.php';

// run routes
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

// redirect to 404 page
http_response_code(404);
echo "404 Not Found";

