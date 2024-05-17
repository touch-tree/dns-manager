<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here you can define all your application routes. The Router class
| provides a convenient way to register your routes and associate them
| with controller actions. Have fun.
|
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\DefaultController;
use Framework\Support\Facades\Router;

Router::get('/', [DefaultController::class, 'dashboard'])->name('dashboard');
Router::get('/domain/add', [DefaultController::class, 'add'])->name('domain.add');
Router::get('/domain/details/{id}', [DefaultController::class, 'details'])->name('domain.details');
Router::get('/domain/edit/{id}', [DefaultController::class, 'edit'])->name('domain.edit');
Router::get('/domain/{id}/nameservers/check', [DefaultController::class, 'check_nameservers'])->name('nameservers.check');

Router::post('/domain/edit/{id}', [DefaultController::class, 'update'])->name('domain.update');
Router::post('/domain/create/{id}', [DefaultController::class, 'create'])->name('domain.create');
Router::post('/domain/details/{id}/modal', [DefaultController::class, 'details_modal'])->name('domain.details.modal');

Router::get('/cache/clear', [DefaultController::class, 'clear_cache'])->name('domain.clear');