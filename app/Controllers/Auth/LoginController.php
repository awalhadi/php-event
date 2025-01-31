<?php
namespace App\Controllers\Auth;

class LoginController
{
  public function showLogin()
  {
    $title = "Login";
    return view('auth.login');
  }
}