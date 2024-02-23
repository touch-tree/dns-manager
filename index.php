<?php

/*
|--------------------------------------------------------------------------
| Introduction
|--------------------------------------------------------------------------
|
| This application is made using our personal framework. This framework
| contains every tooling that makes a solid application abiding by PSR
| convention. Have fun
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

if (!config('development_mode', false)) {
    if (!session('cloudflare_enabled')) {
        echo view('404')->render();
        die();
    }
}

Router::dispatch();