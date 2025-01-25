<?php
namespace App\Controllers;

use App\Core\Request;
use App\Controllers\Controller;


class HomeController extends Controller
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