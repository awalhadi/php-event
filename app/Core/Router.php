<?php
namespace App\Core;

class Router {
  private $routes = [];

  public function get($uri, $controller) {
    $this->routes['GET'][$uri] = $controller;
  }

  public function post($uri, $controller) {
    $this->routes['POST'][$uri] = $controller;
  }

  public function dispatch($uri, $method) {
    $uri = parse_url($uri, PHP_URL_PATH);
    if (isset($this->routes[$method][$uri])) {
      [$controller, $action, $params] = $this->routes[$method][$uri];
      $controllerInstance = new $controller;
      call_user_func($controllerInstance, $action, $params);
    }else{
      http_response_code(404);
      echo "404 Not Found";
    }
  }
  public static function route($uri) {
    $uri = trim($uri, '/');
    $uri = explode('/', $uri);
    $controller = $uri[0] ?? 'home';
    $method = $uri[1] ?? 'index';
    $params = array_slice($uri, 2);
    return [$controller, $method, $params];
  }
}