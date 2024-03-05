<?php

/*
|--------------------------------------------------------------------------
| Introduction
|--------------------------------------------------------------------------
|
| This application is made using our personal Framework. This Framework
| contains every tooling that makes a solid application abiding by PSR
| convention. Have fun
|
|--------------------------------------------------------------------------
*/

use App\Framework\App;
use App\Framework\Foundation\Config;
use App\Framework\Foundation\Container;
use App\Framework\Foundation\Session;
use App\Framework\Http\Kernel;

require_once 'autoload.php';
require_once 'helpers.php';
require_once 'routes/web.php';

Session::start();
Config::resolve(base_path('/config/app.php'));

// this needs to be a middleware lol
if (!config('development_mode', false)) {
    if (!session('cloudflare_enabled')) {
        echo view('errors.404')->render();
        die();
    }
}

Container::bind(App::class, function () {
    return new App();
});

app()->register(app(Container::class));

app(Kernel::class)->handle(request());