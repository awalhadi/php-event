<?php
namespace App\Controllers;
abstract class Controller
{
  public function view($view, $data = [])
  {
    return view($view, $data);
  }
}