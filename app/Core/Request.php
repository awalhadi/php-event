<?php
namespace App\Core;

class Request 
{
  private $method;
  private $uri;
  private $query;
  private $body;
  private $params;

  public function __construct($query = [], $body = [], $params = []) {
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->uri = $_SERVER['REQUEST_URI'];
    $this->query = isset($query) ? $query : $_SERVER['QUERY_STRING'];
    $this->body = isset($body) ? $body : file_get_contents('php://input');
    $this->params = isset($params) ? $params : $_REQUEST;
  }

  // get all request data
  public function all() {
    // return [
    //   'method' => $this->method,
    //   'uri' => $this->uri,
    //   'query' => $this->query,
    //   'body' => $this->body,
    //   'params' => $this->params
    // ];

    return array_merge($this->query, $this->body, $this->params);
  }

  // get input data
  public function input($key, $default = null) {
    $data = $this->all();
    return $data[$key] ?? $default;
  }

  // get query parameters
  public function query($key, $default = null) {
    if($key && isset($this->query[$key])) {
      return $this->query[$key] ?? $default;
    }
    return $this->query;
  }

  // get body parameters
  public function body($key = null, $default = null) {
    if($key && isset($this->body[$key])) {
      return $this->body[$key] ?? $default;
    }
    return $this->body;
  }
  // get route parameters
  public function params($key = null, $default = null) {
    if($key && isset($this->params[$key])) {
      return $this->params[$key] ?? $default;
    }
    return $this->params;
  }

  // get route
  public function route($key = null, $default = null) {
    if($key && isset($this->params[$key])) {
      return $this->params[$key] ?? $default;
    }
    return $this->params;
  }

  public function has($key) {
    $data = $this->all();
    return isset($data[$key]);
  }

  // check hasFile
  public function hasFile($key) {
    $data = $this->all();
    // check if file exists
    return isset($data[$key]) && is_uploaded_file($data[$key]['tmp_name']);
  }


  // validate request
  public function validate(array $rules) {
    $validator = new Validator();
    $data = $this->all();
    $is_valid = $validator->validate($data, $rules);

    if (!$is_valid) {
      Session::set('old', $this->all());
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
      // return $validator->errors();
    }

    return $data;
  }
}