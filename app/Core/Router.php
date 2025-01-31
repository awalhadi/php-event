<?php
namespace App\Core;

class Router {
  private $routes = [];
  private $named_routes = [];
  private $current_route = null;
  private $middleware = [];

  // get method
  public function get($uri, $controller) {
    $normalized_uri = $this->normalizeUri($uri);
    $this->routes['GET'][$normalized_uri] = $controller;
    $this->setCurrentRoute($normalized_uri, 'GET');
    return $this;
  }

  // post method
  public function post($uri, $controller) {
    $normalized_uri = $this->normalizeUri($uri);
    $this->routes['POST'][$normalized_uri] = $controller;
    $this->setCurrentRoute($normalized_uri, 'POST');
    return $this;
  }

  // put method
  public function put($uri, $controller) {
    $normalized_uri = $this->normalizeUri($uri);
    $this->routes['PUT'][$normalized_uri] = $controller;
    $this->setCurrentRoute($normalized_uri, 'PUT');
    return $this;
  }

  public function delete($uri, $controller) {
    $normalized_uri = $this->normalizeUri($uri);
    $this->routes['DELETE'][$normalized_uri] = $controller;
    $this->setCurrentRoute($normalized_uri, 'DELETE');
    return $this;
  }

  // name method
  public function name($name) {
    if($this->current_route) {
      $this->named_routes[$name] = $this->current_route;
    }
    return $this;
  }

  // route method
  public function route($name, $params = []) {
    if(!isset($this->named_routes[$name])) {
      throw new \Exception("Route not found: {$name}");
    }
    $route = $this->named_routes[$name]['uri'];

    // replace params
    foreach ($params as $key => $value) {
      $route = str_replace("{$key}", $value, $route);
    }
    return $route;
  }

  // middleware method
  public function middleware($middlewares) {
    // Allow single middleware or array of middlewares
    $middlewareList = is_array($middlewares) ? $middlewares : func_get_args();

    if($this->current_route) {
      $method = $this->current_route['method'];
      $uri = $this->current_route['uri'];
      $this->current_route['middleware'] = $middlewareList;
      $this->middleware[$method][$uri] = $middlewareList;
    }
    return $this;
  }
  
  /**
   * Dispatches the controller and action based on the given URI and request method.
   *
   * @param string $uri The URI to dispatch.
   * @param string $method The request method.
   *
   * @throws \Exception If the given URI and request method does not match any route.
   */
  public function dispatch($uri, $method) {
    $uri = $this->normalizeUri(parse_url($uri, PHP_URL_PATH));
    $route = $this->matchRoute($uri, $method);
    if($route){

      // check middleware
      if(isset($this->middleware[$method][$uri])){
        foreach($this->middleware[$method][$uri] as $middleware){
          if(method_exists($middleware, 'handle')){
            $result = $middleware::handle();
            if($result === false){
              return false;
              die;
            }
          }
        }
      }

      [$controller, $action] = $this->routes[$method][$uri];
      $params = $route['params'];
      $controller_instance = new $controller;
      // $request = new Request($_GET, $_POST, $_REQUEST);
      $request = new Request($_GET, $_POST, $params);

      // check method exists in controller
      if(!method_exists($controller_instance, $action)){
        http_response_code(404);
        die("Method {$action} not found in controller {$controller}");
      }
      call_user_func([$controller_instance, $action], $request);
    }else{
      http_response_code(404);
      echo "404 Not Found";
    }
  }

  // match route
  public function matchRoute($uri, $method) 
  {
    foreach($this->routes[$method] ?? [] as $route => $controller) {
      // pattern 
      $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $route); // replace {name} with (?P<name>[^/]+)
      $pattern = "#^{$pattern}$#"; // add ^ and $
      if(preg_match($pattern, $uri, $matches)) {
        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        return [
          'route' => $route,
          'controller' => $controller,
          'params' => $params
        ];
      }
    }
  }

  // normalize uri
  private function normalizeUri($uri) {
    // return parse_url($uri, PHP_URL_PATH);
    return rtrim($uri, '/') ?: '/';
  }

  // set current route
  public function setCurrentRoute($normalized_uri, $method) {
    $this->current_route = [
      'uri' => $normalized_uri,
      'method' => $method
    ];
  }
}