<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $lowStockThreshold = 5;

        return view('products.list', compact(
            'products',
            'lowStockThreshold'
        ));
    }

    public function getProducts($productId)
    {

        $products =  Product::where('id', $productId)->first();

        return response()->json($products);
    }

    public function lowStock()
    {
        return view('products.low-stock');
    }
}
