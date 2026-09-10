<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Jobs\SendOrderConfirmationJob;
use Illuminate\Support\Facades\Queue;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_order(): void
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'code' => 'P004',
            'price' => 1000,
            'tax_percentage' => 18,
            'stock' => 10,
        ]);

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('customers', [
            'email' => 'john@example.com',
        ]);

        $customer = Customer::where('email', 'john@example.com')->first();

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'subtotal' => 2000,
            'tax' => 360,
            'grand_total' => 2360,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 1000,
            'subtotal' => 2000,
            'tax' => 360,
            'total' => 2360,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 8,
        ]);
    }

    public function test_order_fails_when_stock_is_insufficient(): void
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'code' => 'P005',
            'price' => 1000,
            'tax_percentage' => 18,
            'stock' => 2,
        ]);

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                ],
            ],
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('orders', [
            'customer_id' => Customer::where(
                'email',
                'john@example.com'
            )->value('id'),
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 2,
        ]);
    }

    public function test_order_confirmation_job_is_dispatched(): void
    {
        Queue::fake();

        $product = Product::factory()->create([
            'name' => 'Test Product',
            'code' => 'P006',
            'price' => 1000,
            'tax_percentage' => 18,
            'stock' => 10,
        ]);

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertStatus(201);

        Queue::assertPushed(
            SendOrderConfirmationJob::class,
            function ($job) use ($response) {
                return $job->orderId === $response->json('order.id');
            }
        );
    }

    public function test_only_one_order_succeeds_when_last_stock_is_purchased(): void
    {
        $product = Product::factory()->create([
            'name' => 'Last Stock Product',
            'code' => 'P007',
            'price' => 1000,
            'tax_percentage' => 18,
            'stock' => 1,
        ]);

        $payload = [
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ];

        // First request
        $firstResponse = $this->postJson('/api/orders', $payload);

        // Second request
        $secondResponse = $this->postJson('/api/orders', [
            ...$payload,
            'customer_email' => 'jane@example.com',
            'customer_name' => 'Jane Doe',
        ]);

        $this->assertTrue(
            $firstResponse->status() === 201
                && $secondResponse->status() === 422
                ||
                $firstResponse->status() === 422
                && $secondResponse->status() === 201
        );

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 0,
        ]);

        $this->assertDatabaseCount('orders', 1);
    }
}
