<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;


Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/customers/{email}/orders', [OrderController::class, 'customerOrders']);
Route::get('/products/low-stock', [OrderController::class, 'lowStockProducts']);
Route::get('/products/{productId}', [ProductController::class, 'getProducts']);


