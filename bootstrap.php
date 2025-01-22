<?php

require_once __DIR__.'/vendor/autoload.php';

// load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// include helpers
require_once __DIR__.'/app/Helpers/helpers.php';

