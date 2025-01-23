<?php
namespace App\Core;
use Jenssegers\Blade\Blade;

class View
{
  private $blade;
  private $view;

  public static function init()
  {
    if (!self::$blade) {
        $views = __DIR__ . '/../../resources/views';
        $cache = __DIR__ . '/../../storage/cache';
  
        self::$blade = new Blade($views, $cache);
    }
  }

  public static function render($view, $data = [])
  {
    self::init();
    return self::$blade->render($view, $data);
  }
}