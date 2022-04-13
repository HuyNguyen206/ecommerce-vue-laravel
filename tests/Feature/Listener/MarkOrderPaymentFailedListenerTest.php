<?php

namespace Tests\Feature\Listener;

use App\Cart\Cart;
use App\Events\OrderPaymentFailed;
use App\Listeners\MarkOrderPaymentFailed;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MarkOrderPaymentFailedListenerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_mark_order_as_payment_failed()
    {
        $event = new OrderPaymentFailed($order = Order::factory()->create());
        $listener = new MarkOrderPaymentFailed();
        $listener->handle($event);
        $this->assertEquals($order->fresh()->state, Order::PAYMENT_FAIL);
    }
}
