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

Router::get('/', ['App\Http\Controllers\DashboardController', 'index'])->name('dashboard');
Router::get('/domain/add', ['App\Http\Controllers\DashboardController', 'add'])->name('domain.add');
Router::get('/domain/add/form', ['App\Http\Controllers\DashboardController', 'add_modal'])->name('domain.add_modal');
Router::get('/domain/details/{id}', ['App\Http\Controllers\DashboardController', 'details'])->name('domain.details');
Router::get('/domain/edit/{id}', ['App\Http\Controllers\DashboardController', 'edit'])->name('domain.edit');
Router::get('/domain/{id}/nameservers/verify', ['App\Http\Controllers\DashboardController', 'verify_nameservers'])->name('nameservers.verify');

Router::post('/domain/edit/{id}', ['App\Http\Controllers\DashboardController', 'update'])->name('domain.update');
Router::post('/domain/create/{id}', ['App\Http\Controllers\DashboardController', 'create'])->name('domain.create');