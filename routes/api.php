<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/customers/{email}/orders', [OrderController::class, 'customerOrders']);
Route::get('/products/low-stock', [OrderController::class, 'lowStockProducts']);
