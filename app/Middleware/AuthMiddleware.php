<?php
namespace App\Middleware;

use App\Core\Session;
use App\Models\User;

class AuthMiddleware
{
  public static function handle()
  {
    if (!self::check()) {
      header('Location: /login');
    }
  }

  public static function check()
  {
    return isset($_SESSION['user_id']);
  }
  // get current user
  public static function user()
  {
    $user_id = Session::get('user_id') ?? null;
    if ($user_id) {
      return User::find($user_id);
    }
  }
  public static function id()
  {
    Session::get('user_id') ?? null;
  }

  // login
  public static function login(User $user)
  {
    Session::set('user_id', $user->id);
  }

  // logout
  public static function logout()
  {
    Session::forget('user_id');
  }
}