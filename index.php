<?php

/*
|--------------------------------------------------------------------------
| Introduction
|--------------------------------------------------------------------------
|
| This application is made using our personal Framework. This Framework
| contains every tooling that makes a solid application abiding by PSR
| convention. Have fun.
|
|--------------------------------------------------------------------------
*/

use Framework\Http\Kernel;
use Framework\Component\Application;

require_once 'autoload.php';
require_once 'Framework/helpers.php';
require_once 'routes/web.php';

$app = new Application(getcwd());

$app->set_config_path(base_path('config'));

/*
|--------------------------------------------------------------------------
| Application Setup
|--------------------------------------------------------------------------
|
| Here you register your configurations and setting your application's
| Service Providers. These registrations will be used to bootstrap the
| application.
|
|--------------------------------------------------------------------------
*/

$app->set_services([]);
$app->bootstrap();

$app->get(Kernel::class)->handle(request());