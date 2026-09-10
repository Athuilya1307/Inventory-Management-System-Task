<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;

use App\Models\Customer;
use App\Models\Product;
class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, OrderService $orderService)
    {
        $order = $orderService->createOrder(
            $request->validated()
        );

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
        ], 201);
    }


    public function customerOrders($email)
    {
        $customer = Customer::where('email', $email)->first();

        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found'
            ], 404);
        }

        $orders = $customer->orders()
            ->with('orderItems.product')
            ->latest()
            ->get();

        return response()->json([
            'customer' => $customer,
            'orders' => $orders,
        ]);
    }

    public function lowStockProducts(Request $request)
    {
        $threshold = $request->input('threshold', 5);

        $products = Product::where('stock', '<=', $threshold)
            ->orderBy('stock')
            ->get();

        return response()->json([
            'threshold' => $threshold,
            'products' => $products,
        ]);
    }
}
