<?php

namespace Tests\Feature\Listener;

use App\Events\OrderPaid;
use App\Listeners\MarkOrderProcessing;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MarkOrderPaymentProcessingListenerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_mark_payment_as_processing()
    {
        $order = Order::factory()->create();
        $event = new OrderPaid($order);
        $listener = new MarkOrderProcessing();
        $listener->handle($event);
        $this->assertEquals($order->fresh()->state, Order::PROCESSING);
    }
}
