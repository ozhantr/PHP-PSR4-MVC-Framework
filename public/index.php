<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('utf-8');
}

use Core\Router;
use App\Controllers\HomeController;

require __DIR__ . '/../vendor/autoload.php';

new Router();
