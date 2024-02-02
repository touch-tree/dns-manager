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

const ROOT_DIR = __DIR__;

require_once 'autoload.php';
require_once 'functions.php';
require_once 'routes/web.php';

Config::set_config(ROOT_DIR . '/config/app.php');

try {
    if (Route::resolve($_SERVER['REQUEST_URI']) === false) {
        echo view('404')->render();
    }
} catch (ReflectionException $e) {
    echo view('404')->render();
}