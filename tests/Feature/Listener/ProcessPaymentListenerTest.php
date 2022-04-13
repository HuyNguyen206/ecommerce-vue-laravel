<?php

namespace Tests\Feature\Listener;

use App\Events\OrderCreated;
use App\Events\OrderPaid;
use App\Events\OrderPaymentFailed;
use App\Exceptions\PaymentFaildException;
use App\Http\Middleware\Cart\Payment\Gateways\StripeGateway;
use App\Http\Middleware\Cart\Payment\Gateways\StripeGatewayCustomer;
use App\Listeners\ProcessPayment;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ProcessPaymentListenerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_charge_the_chosen_payment_the_correct_amount()
    {
        Event::fake();
        $event = new OrderCreated($order = Order::factory()->create());

        list($gateway, $customer) = $this->mockFlow();
        $customer->shouldReceive('charge')->with($order->paymentMethod, $order->total->amount());
        $listener = new ProcessPayment($gateway);
        $listener->handle($event);
    }

    public function test_it_fire_the_order_paid_event()
    {
        Event::fake();
        $event = new OrderCreated($order = Order::factory()->create());
        list($gateway, $customer) = $this->mockFlow();

        $customer->shouldReceive('charge')->with($order->paymentMethod, $order->total->amount());
        $listener = new ProcessPayment($gateway);
        $listener->handle($event);
        Event::assertDispatched(OrderPaid::class, function ($event) use($order) {
           return $event->order->id === $order->id;
        });
    }

    public function test_it_fire_the_order_failed_event()
    {
        Event::fake();
        $event = new OrderCreated($order = Order::factory()->create());
        list($gateway, $customer) = $this->mockFlow();

        $customer->shouldReceive('charge')->with($order->paymentMethod, $order->total->amount())
            ->andThrow(PaymentFaildException::class);
        $listener = new ProcessPayment($gateway);
        $listener->handle($event);
        Event::assertDispatched(OrderPaymentFailed::class, function ($event) use($order) {
           return $event->order->id === $order->id;
        });
    }

    protected function mockFlow()
    {
        $gateway = \Mockery::mock(StripeGateway::class);
        $gateway->shouldReceive('withUser')
            ->andReturn($gateway)
            ->shouldReceive('createCustomer')
            ->andReturn($customer = \Mockery::mock(StripeGatewayCustomer::class));
        return [$gateway, $customer];
    }
}
