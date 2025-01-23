<?php
namespace App\Core;

class CsrfTokenMiddleware
{
  public static function validateCsrfToken() 
  {
    // check server post method
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
      $csrf_token = $_POST['csrf_token'] ?? null;
      if(!$csrf_token || !validate_csrf_token($csrf_token)) {
        http_response_code(403);
        echo "CSRF token validation failed.";
        exit;
      }
    }
  }
}