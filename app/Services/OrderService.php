<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

use App\Jobs\SendOrderConfirmationJob;


class OrderService
{
    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {

            $customer = Customer::firstOrCreate([
                'name' => $data['customer_name'],
                'email' => $data['customer_email'],
            ]);

            $items = [];
            $subtotal = 0;
            $tax = 0;

            foreach ($data['products'] as $item) {

                $product = Product::where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception(
                        "Product not found: {$item['product_id']}"
                    );
                }

                $quantity = $item['quantity'];

                if ($product->stock < $quantity) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'products' => "Insufficient stock for {$product->name}",
                    ]);
                }

                $unitPrice = $product->price;

                $itemSubtotal = $unitPrice * $quantity;

                $itemTax = $itemSubtotal *
                    ($product->tax_percentage / 100);

                $itemTotal = $itemSubtotal + $itemTax;

                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'tax_percentage' => $product->tax_percentage,
                    'subtotal' => $itemSubtotal,
                    'tax' => $itemTax,
                    'total' => $itemTotal,
                ];

                $subtotal += $itemSubtotal;
                $tax += $itemTax;
            }

            $grandTotal = $subtotal + $tax;

            $order = Order::create([
                'customer_id' => $customer->id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'grand_total' => $grandTotal,
            ]);

            foreach ($items as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_percentage' => $item['tax_percentage'],
                    'subtotal' => $item['subtotal'],
                    'tax' => $item['tax'],
                    'total' => $item['total'],
                ]);

                Product::where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);
            }

            SendOrderConfirmationJob::dispatch($order->id)->afterCommit();
            return $order;
        });
    }
}
