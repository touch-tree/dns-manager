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

session_start();

const ROOT_DIR = __DIR__;

require_once 'autoload.php';
require_once 'functions.php';
require_once 'config.php';
require_once 'routes/web.php';

$response = true;

try {
    $response = Route::resolve($_SERVER['REQUEST_URI']);
} catch (ReflectionException $e) {
    echo view('404')->render();
    $response = false;
}

if ($response === false) {
    echo view('404')->render();
}