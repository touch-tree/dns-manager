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

use App\Framework\Routing\Router;
use App\Http\Controllers\DashboardController;

Router::get('/', [DashboardController::class, 'index'])->name('dashboard');
Router::get('/domain/add', [DashboardController::class, 'add'])->name('domain.add');
Router::get('/domain/details/{id}', [DashboardController::class, 'details'])->name('domain.details');
Router::get('/domain/edit/{id}', [DashboardController::class, 'edit'])->name('domain.edit');
Router::get('/domain/{id}/nameservers/verify', [DashboardController::class, 'verify_nameservers'])->name('nameservers.verify');

Router::post('/domain/edit/{id}', [DashboardController::class, 'update'])->name('domain.update');
Router::post('/domain/create/{id}', [DashboardController::class, 'create'])->name('domain.create');