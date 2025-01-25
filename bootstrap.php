<?php

use Illuminate\Support\Facades\App;
use App\Models\Model;

require_once __DIR__.'/vendor/autoload.php';

// check if .env file exists
$env_path = __DIR__.'/.env';
if (!file_exists($env_path)) {
  die('.env file not found at: '.$env_path);
}
// load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// include database connection
$pdo = new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
Model::setConnection($pdo);
// include helpers
require_once __DIR__.'/app/Helpers/helpers.php';

