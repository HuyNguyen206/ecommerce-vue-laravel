<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Events\OrderPaid;
use App\Events\OrderPaymentFailed;
use App\Exceptions\PaymentFaildException;
use App\Http\Middleware\Cart\Payment\Gateway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessPayment implements ShouldQueue
{
    /**
     * @var Gateway
     */
    private $gateway;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Gateway $gateway)
    {
        //
        $this->gateway = $gateway;
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\OrderCreated $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;
        try {
            $this->gateway->withUser($order->user)->createCustomer()
                ->charge($order->paymentMethod, $order->total->amount());
            event(new OrderPaid($order));
        } catch (PaymentFaildException $exception) {
            event(new OrderPaymentFailed($order));
        }
    }
}
