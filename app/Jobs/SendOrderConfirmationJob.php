<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Support\Facades\Log;
use App\Models\Order;

class SendOrderConfirmationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $orderId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::with('customer')->find($this->orderId);

        if (!$order) {
            return;
        }

        Log::info('Order confirmation sent', [
            'order_id' => $order->id,
            'customer_email' => $order->customer->email,
        ]);
    }
}
