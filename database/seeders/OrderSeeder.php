<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer1 = Customer::where('email', 'john@example.com')->first();
        $customer2 = Customer::where('email', 'jane@example.com')->first();

        $product1 = Product::where('code', 'P001')->first();
        $product2 = Product::where('code', 'P002')->first();
        $product3 = Product::where('code', 'P003')->first();

        // Order 1
        $quantity1 = 2;

        $subtotal1 = $product1->price * $quantity1;
        $tax1 = $subtotal1 * ($product1->tax_percentage / 100);
        $total1 = $subtotal1 + $tax1;

        $order1 = Order::create([
            'customer_id' => $customer1->id,
            'subtotal' => $subtotal1,
            'tax' => $tax1,
            'grand_total' => $total1,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $product1->id,
            'quantity' => $quantity1,
            'unit_price' => $product1->price,
            'tax_percentage' => $product1->tax_percentage,
            'subtotal' => $subtotal1,
            'tax' => $tax1,
            'total' => $total1,
        ]);

        // Order 2 - Multiple products
        $quantity2 = 1;
        $quantity3 = 2;

        $subtotal2 =
            ($product2->price * $quantity2) +
            ($product3->price * $quantity3);

        $tax2 =
            ($product2->price * $quantity2 * ($product2->tax_percentage / 100)) +
            ($product3->price * $quantity3 * ($product3->tax_percentage / 100));

        $total2 = $subtotal2 + $tax2;

        $order2 = Order::create([
            'customer_id' => $customer2->id,
            'subtotal' => $subtotal2,
            'tax' => $tax2,
            'grand_total' => $total2,
        ]);

        // Product 2
        $subtotalProduct2 = $product2->price * $quantity2;
        $taxProduct2 = $subtotalProduct2 * ($product2->tax_percentage / 100);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $product2->id,
            'quantity' => $quantity2,
            'unit_price' => $product2->price,
            'tax_percentage' => $product2->tax_percentage,
            'subtotal' => $subtotalProduct2,
            'tax' => $taxProduct2,
            'total' => $subtotalProduct2 + $taxProduct2,
        ]);

        // Product 3
        $subtotalProduct3 = $product3->price * $quantity3;
        $taxProduct3 = $subtotalProduct3 * ($product3->tax_percentage / 100);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $product3->id,
            'quantity' => $quantity3,
            'unit_price' => $product3->price,
            'tax_percentage' => $product3->tax_percentage,
            'subtotal' => $subtotalProduct3,
            'tax' => $taxProduct3,
            'total' => $subtotalProduct3 + $taxProduct3,
        ]);
    }
}