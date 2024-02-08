<?php

/*
|--------------------------------------------------------------------------
| Introduction
|--------------------------------------------------------------------------
|
| A lightweight framework designed for simplicity and ease of use.
| The framework includes a simple routing system,
| Views are used to render HTML content, and the autoloader ensures that
| framework are loaded dynamically as needed.
|
|--------------------------------------------------------------------------
*/

use App\Framework\Base\Config;
use App\Framework\Base\Session;
use App\Framework\Routing\Router;

require_once 'autoload.php';
require_once 'helpers.php';
require_once 'routes/web.php';

Session::start();
Config::resolve(base_path('/config/app.php'));
Router::dispatch();
