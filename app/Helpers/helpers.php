<?php
use App\Core\View;

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
