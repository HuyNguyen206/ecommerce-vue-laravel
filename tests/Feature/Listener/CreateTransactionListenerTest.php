<?php

namespace Tests\Feature\Listener;

use App\Events\OrderPaid;
use App\Listeners\CreateTransaction;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTransactionListenerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_create_transaction()
    {
     $orderPaidEvent = new OrderPaid($order = Order::factory()->create());
     $createTransactionListener = new CreateTransaction();
     $createTransactionListener->handle($orderPaidEvent);
     $this->assertInstanceOf(Transaction::class, $order->transactions->first());
    }
}
