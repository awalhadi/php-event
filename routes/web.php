<?php

use App\Controllers\HomeController;


// home page
$router->get('/', [HomeController::class, 'index']);