<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here you can define all your application routes. The Route class
| provides a convenient way to register your routes and associate them
| with controller actions. Have fun.
|
|--------------------------------------------------------------------------
*/

use App\Core\Route;

Route::get('/', ['App\Http\Controllers\DashboardController', 'index'])->name('dashboard');
Route::get('/domain/add', ['App\Http\Controllers\DashboardController', 'add'])->name('domain.add');
Route::get('/domain/add/form', ['App\Http\Controllers\DashboardController', 'add_modal'])->name('domain.add_modal');
Route::get('/domain/details/{id}', ['App\Http\Controllers\DashboardController', 'details'])->name('domain.details');
Route::get('/domain/edit/{id}', ['App\Http\Controllers\DashboardController', 'edit'])->name('domain.edit');
Route::get('/domain/{id}/activation_check', ['App\Http\Controllers\DashboardController', 'activation_check'])->name('activation_check');

Route::post('/domain/edit/{id}', ['App\Http\Controllers\DashboardController', 'update'])->name('domain.update');
Route::post('/domain/create/{id}', ['App\Http\Controllers\DashboardController', 'create'])->name('domain.create');