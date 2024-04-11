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
use Framework\Support\Helpers\Route;

Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/domain/add', [DashboardController::class, 'add'])->name('domain.add');
Route::get('/domain/details/{id}', [DashboardController::class, 'details'])->name('domain.details');
Route::get('/domain/edit/{id}', [DashboardController::class, 'edit'])->name('domain.edit');
Route::get('/domain/{id}/nameservers/check', [DashboardController::class, 'check_nameservers'])->name('nameservers.check');

Route::post('/domain/edit/{id}', [DashboardController::class, 'update'])->name('domain.update');
Route::post('/domain/create/{id}', [DashboardController::class, 'create'])->name('domain.create');
Route::post('/domain/details/{id}/modal', [DashboardController::class, 'details_modal'])->name('domain.details.modal');

Route::get('/cache/clear', [DashboardController::class, 'clear_cache'])->name('domain.clear');