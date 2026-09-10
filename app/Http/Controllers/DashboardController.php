<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $orderCount = Order::count();
        $customerCount = Customer::count();

        $lowStockThreshold = 5;

        $lowStockProducts = Product::where('stock', '<=', $lowStockThreshold)
            ->orderBy('stock')
            ->get();

        return view('dashboard', compact(
            'productCount',
            'orderCount',
            'customerCount',
            'lowStockProducts',
            'lowStockThreshold'
        ));
    }
}