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
use App\Services\AppService;
use App\Services\CloudflareService;

require_once 'autoload.php';
require_once 'helpers.php';
require_once 'routes/web.php';

Session::start();
Config::resolve(base_path('/config/app.php'));

Container::singleton(App::class, function () {
    return new App(new Container());
});

app()->register(
    [
        CloudflareService::class,
        AppService::class
    ]
);

app(Kernel::class)->handle(request());