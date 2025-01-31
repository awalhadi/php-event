<?php
namespace App\Controllers\Auth;
class RegisterController
{
  public function showRegister()
  {
    $title = "Register";
    return view('auth.register');
  }
}