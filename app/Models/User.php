<?php
namespace App\Models;

use App\Middleware\AuthMiddleware;
use App\Models\Model;


class User extends Model
{
  protected $table = 'users';


  // attempt to login
  public static function attempt($credentials)
  {
    $user = static::where('email', '=', $credentials['email'])->first();
    if($user && password_verify($credentials['password'], $user->password)) {
      AuthMiddleware::login($user);
      return true;
    } else {
      return false;
    }
  }

  // Check if user is admin or has specific role
  public function isAdmin() 
  {
    return $this->role === 'admin';
  }
}

