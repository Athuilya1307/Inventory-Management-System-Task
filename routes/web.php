<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;


Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/products', [ProductController::class, 'index'])
    ->name('products.list');

Route::get('/customers', [CustomerController::class, 'index'])
    ->name('customer.list');

Route::get('/orders', [OrderController::class, 'index'])
    ->name('orders.list');

Route::get('/orders/create', [OrderController::class, 'add'])
    ->name('orders.create');

Route::get('/customers/orders/list/{email}', [CustomerController::class, 'customerList'])
    ->name('orders.customerList');

Route::get('/products/low-stock', [ProductController::class, 'lowStock'])
    ->name('products.lowStock');
