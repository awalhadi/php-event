<?php

use App\Controllers\HomeController;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;

// authentication
$router->get('/login', [LoginController::class, 'showLogin'])->name('login.show');

// register
$router->get('/register', [RegisterController::class, 'showRegister'])->name('register.show');



// home page
$router->get('/', [HomeController::class, 'index'])->name('home');

// about page
$router->get('/about', [HomeController::class, 'about'])->name('about');