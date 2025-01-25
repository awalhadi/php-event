<?php

use App\Controllers\HomeController;


// home page
$router->get('/', [HomeController::class, 'index'])->name('home');

// about page
$router->get('/about', [HomeController::class, 'about'])->name('about');