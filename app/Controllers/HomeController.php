<?php
namespace App\Controllers;

use App\Core\Request;
// use App\Controllers\Controller;


class HomeController
{
  public function index(Request $request)
  {
    return view('home');
  }

  public function about(Request $request)
  {
    return view('about');
  }
}