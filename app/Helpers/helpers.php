<?php
use App\Core\View;
use App\Core\Session;

function redirect($path)
{
    header("Location: $path");
    exit;
}

function csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
}

// debug helper
function dd(...$value)
{
    foreach ($value as $val) {
        echo '<pre>';
        var_dump($val);
        echo '</pre>';
    }
    die;
}

// show view
if (!function_exists('view')) {
  function view($view, $data = [])
  {
      echo View::render($view, $data);
  }
}

if (!function_exists('old')) {
  function old($key, $default = '')
  {
      return Session::get('old')[$key] ?? $default;
  }
}

// errors
if (!function_exists('errors')) {
  function errors($key, $default = '')
  {
    $errors = Session::get('errors');
    return $key ? ($errors[$key] ?? $default) : $errors;
  }
}

// has errors
if (!function_exists('hasErrors')) {
  function hasErrors($key)
  {
    $errors = Session::get('errors');
    return $key ? (isset($errors[$key]) ?? false) : $errors;
  }
}

// session
if (!function_exists('session')) {
  function session($key, $value = null)
  {
    if (is_null($value)) {
      return Session::get($key);
    }
    Session::set($key, $value);
  }
}

// flash_message
if (!function_exists('flash_message')) {
  function flash_message($key, $message)
  {
    $messages = array_merge(session('flash_message', []), [$key => $message]);
    session('flash_message', $messages);
  }
}

// get flash message
if (!function_exists('get_flash_message')) {
  function get_flash_message($key)
  {
    return session('flash_message')[$key] ?? null;
  }
} 


// route
if (!function_exists('route')) {
  function route($name, $params = [])
  {
    global $router;
    return $router->route($name, $params);
  }
}

// redirect route
if (!function_exists('redirect')) {
  function redirect($url, $statusCode = 302) {
    global $router;
    
    // Check if $url is a route name
    if (is_string($url)) {
      try {
        $url = $router->route($url);
      } catch (\Exception $e) {
        // If route not found, use original URL
      }
    }
    
    // Set redirect headers
    header("Location: {$url}", true, $statusCode);
    exit();
  }
}

// make hash
if (!function_exists('hash')) {
  function hash($string) {
    // generate custom password hashing encryption
    $string = 'Natore' . $string . "Hadi";

    return password_hash($string, PASSWORD_DEFAULT);
  }
}

// verify hash
if (!function_exists('verify')) {
  function verify($string, $hash) {
    $pass_string = 'Natore' . $string . "Hadi";
    return password_verify($pass_string, $hash);
  }
}
