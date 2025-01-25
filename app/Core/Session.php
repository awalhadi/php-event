<?php
namespace App\Core;

class Session {
  public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
      $value = $_SESSION[$key] ?? $default;
      
      // clear flash message after getting
      if ($key === 'flash_message' && isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
      }
      return $value;
    }

    public static function forget($key)
    {
        unset($_SESSION[$key]);
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }
}