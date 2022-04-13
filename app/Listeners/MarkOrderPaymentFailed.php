<?php

namespace App\Listeners;

use App\Events\OrderPaymentFailed;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MarkOrderPaymentFailed
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderPaymentFailed $event)
    {
        $event->order->update([
            'state' => Order::PAYMENT_FAIL
        ]);
    }
}
