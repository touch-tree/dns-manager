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

use App\Http\Controllers\DashboardController;
use Framework\Routing\Router;

Router::get('/', [DashboardController::class, 'index'])->name('dashboard');
Router::get('/domain/add', [DashboardController::class, 'add'])->name('domain.add');
Router::get('/domain/refresh', [DashboardController::class, 'refresh'])->name('domain.refresh');
Router::get('/domain/details/{id}', [DashboardController::class, 'details'])->name('domain.details');
Router::get('/domain/edit/{id}', [DashboardController::class, 'edit'])->name('domain.edit');
Router::get('/domain/{id}/nameservers/check', [DashboardController::class, 'check_nameservers'])->name('nameservers.check');

Router::post('/domain/edit/{id}', [DashboardController::class, 'update'])->name('domain.update');
Router::post('/domain/create/{id}', [DashboardController::class, 'create'])->name('domain.create');
Router::post('/domain/details/{id}/modal', [DashboardController::class, 'details_modal'])->name('domain.details.modal');