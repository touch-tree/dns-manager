<?php

/*
|--------------------------------------------------------------------------
| Introduction
|--------------------------------------------------------------------------
|
| A lightweight framework designed for simplicity and ease of use.
| The framework includes a simple routing system,
| Views are used to render HTML content, and the autoloader ensures that
| core are loaded dynamically as needed.
|
|--------------------------------------------------------------------------
*/

use App\Core\Route;
use App\Core\Config;

session_start();

require_once 'autoload.php';
require_once 'helpers.php';
require_once 'routes/web.php';

Config::prepare(__DIR__ . '/config/app.php');

Route::resolve(
    $_SERVER['REQUEST_URI']
);
