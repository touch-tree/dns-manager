<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here you can define all your application routes. The Router class
| provides a convenient way to register your routes and associate them
| with controller actions. Have fun.
|
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\DefaultController;
use Framework\Support\Helpers\Router;

Router::get('/api/domain/sites', [DefaultController::class, 'sites'])->name('domain.sites');