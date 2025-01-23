<?php

namespace App\Core;

use Jenssegers\Blade\Blade;

class View
{
  private static $blade;
  private static $view;

  public static function init()
  {
    if (!self::$blade) {
      $views = realpath(__DIR__ . '/../../resources/views');
      $cache = realpath(__DIR__ . '/../../storage/cache');

      if (!$views || !$cache) {
        throw new \Exception('Invalid views or cache directory.');
      }

      self::$blade = new Blade($views, $cache);
    }
  }

  public static function render($view, $data = [])
  {
    self::init();

    echo self::$blade->render($view, $data);
  }
}
